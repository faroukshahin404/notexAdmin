<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // $table->bigIncrements('id');
            // $table->string('name');
            // $table->string('host')->unique();
            // $table->string('port')->default('80');
            // $table->string('database');
            // $table->string('username');
            // $table->string('password');
            // $table->string('email')->nullable();
            // $table->string('phone')->nullable();
            if (!Schema::hasColumn('tenants', 'type')) {
            $table->enum('type', ['demo', 'paid'])->default('demo');
                $table->decimal('monthly_payment', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('tenants', 'is_installed')) {
                $table->boolean('is_installed')->default(false);
            }
            if (!Schema::hasColumn('tenants', 'installation_date')) {
                $table->date('installation_date')->nullable();
            }
            // $table->date('expired_date')->nullable();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};


