<?php

namespace App\Services\Remote;

use Exception;

class DBProvisioner
{
    public function __construct(protected SSHService $ssh = new SSHService()) {}

    /**
     * Check if a database exists on the server
     */
    public function databaseExists(string $dbName): bool
    {
        $rootUser = escapeshellarg((string) config('aapanel.mysql_root_user', 'root'));
        $rootPass = escapeshellarg((string) config('aapanel.mysql_root_password', ''));
        $query = sprintf("SHOW DATABASES LIKE '%s';", addslashes($dbName));
        $cmd = sprintf(
            "mysql -u%s -p%s -e %s",
            $rootUser,
            $rootPass,
            escapeshellarg($query)
        );

        $result = $this->ssh->run($cmd);
        $output = trim($result['output'] ?? '');

        $lines = array_values(array_filter(explode("\n", $output)));

        // If we have 2+ lines => header + db name
        if (count($lines) >= 2 && trim($lines[1]) === $dbName) {
            return true;
        }

        return false;
    }

    /**
     * Create database and user if DB does not exist, otherwise throw exception
     */
    public function createDatabaseAndUser(string $dbName, string $dbUser, string $dbPass): array
    {
        // First check existence
        if ($this->databaseExists($dbName)) {
            throw new Exception("Database '{$dbName}' already exists!");
        }

        // Sanitize dbName and dbUser (MySQL identifiers)
        $dbNameSafe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $dbName);
        $dbUserSafe = substr(preg_replace('/[^a-zA-Z0-9_]/', '_', $dbUser), 0, 32);
        $dbPassEsc = addslashes($dbPass);
        $userHost = (string) config('aapanel.mysql_user_host', 'localhost');

        $sql = sprintf(
            "CREATE DATABASE `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
            CREATE USER '%s'@'%s' IDENTIFIED BY '%s';
            GRANT ALL PRIVILEGES ON `%s`.* TO '%s'@'%s';
            FLUSH PRIVILEGES;",
            $dbNameSafe,
            $dbUserSafe,
            $userHost,
            $dbPassEsc,
            $dbNameSafe,
            $dbUserSafe,
            $userHost
        );

        // Prepare command: write SQL into temp file, run, then delete
        $sqlFile = "/tmp/db_create_" . uniqid() . ".sql";

        $rootUser = escapeshellarg((string) config('aapanel.mysql_root_user', 'root'));
        $rootPass = escapeshellarg((string) config('aapanel.mysql_root_password', ''));

        $cmd = sprintf(
            "echo %s > %s && mysql -u%s -p%s < %s && rm -f %s",
            escapeshellarg($sql),
            $sqlFile,
            $rootUser,
            $rootPass,
            $sqlFile,
            $sqlFile
        );

        return $this->ssh->run($cmd);
    }
    public function dropDatabaseAndUser(string $dbName, string $dbUser): array
    {
        // Sanitize identifiers
        $dbNameSafe = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $dbName);
        $dbUserSafe = substr(preg_replace('/[^a-zA-Z0-9_]/', '_', $dbUser), 0, 32);
        $userHost   = (string) config('aapanel.mysql_user_host', 'localhost');

        $sql = sprintf(
            "DROP DATABASE IF EXISTS `%s`;
            DROP USER IF EXISTS '%s'@'%s';
            FLUSH PRIVILEGES;",
            $dbNameSafe,
            $dbUserSafe,
            $userHost
        );

        $sqlFile = "/tmp/db_drop_" . uniqid() . ".sql";

        $rootUser = escapeshellarg((string) config('aapanel.mysql_root_user', 'root'));
        $rootPass = escapeshellarg((string) config('aapanel.mysql_root_password', ''));

        $cmd = sprintf(
            "echo %s > %s && mysql -u%s -p%s < %s && rm -f %s",
            escapeshellarg($sql),
            $sqlFile,
            $rootUser,
            $rootPass,
            $sqlFile,
            $sqlFile
        );

        return $this->ssh->run($cmd);
    }
}
