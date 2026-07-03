<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Storefront - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 font-sans text-gray-100 antialiased min-h-screen flex items-center justify-center py-12 px-6">

    <div class="max-w-md w-full bg-gray-800 rounded-2xl border border-gray-700 shadow-2xl p-8">
        
        <div class="text-center mb-6">
            <a href="/" class="text-xs uppercase tracking-widest font-mono text-gray-400 hover:text-white transition">← Back to Homepage</a>
            <h2 class="text-3xl font-extrabold text-white tracking-tight mt-4">Create Your Shop</h2>
            <p class="text-sm text-gray-400 mt-1">You are signing up for the <span class="text-blue-400 font-bold capitalize">{{ $package->name }} Plan</span></p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl p-4 text-xs space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('shop.register.submit') }}" method="POST" class="space-y-4">
            @csrf
            
            <input type="hidden" name="package_id" value="{{ $package->id }}">

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Shop / Business Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Almasi Boutique"
                    class="w-full bg-gray-900 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Owner Full Name</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}" required placeholder="e.g. John Doe"
                    class="w-full bg-gray-900 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="e.g. manager@store.com"
                    class="w-full bg-gray-900 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Account Password</label>
                <input type="password" name="password" required placeholder="Minimum 8 characters"
                    class="w-full bg-gray-900 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>

            <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1.5">Confirm Password</label>
                <input type="password" name="password_confirmation" required placeholder="Retype your password"
                    class="w-full bg-gray-900 text-white border border-gray-700 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3.5 rounded-xl shadow-xl transition transform hover:-translate-y-0.5 mt-2 text-sm tracking-wide">
                Finalize Store Setup &rarr;
            </button>
        </form>

    </div>

</body>
</html>