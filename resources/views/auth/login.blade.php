<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <style></style>
    @yield('styles')
</head>
<body>
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4">
    <div class="w-full max-w-md space-y-6">
        <div class="text-center">
            <h1 class="text-2xl font-semibold text-gray-900">Admin Login</h1>
        </div>
        <div class="bg-white shadow rounded-lg p-6">
            <form method="post" action="{{ route('login.attempt') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" type="email" name="email" value="{{ old('email') }}" autofocus>
                    @error('email')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                    <input class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" type="password" name="password">
                    @error('password')<div class="mt-1 text-sm text-red-600">{{ $message }}</div>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-700"><input class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded" type="checkbox" name="remember" value="1"> Remember me</label>
                </div>
                <button type="submit" class="w-full text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>


