<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Login - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased flex min-h-screen items-center justify-center p-6 selection:bg-blue-600 selection:text-white">

    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-blue-500/5 blur-[100px] rounded-full pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-8">
            <a href="/" class="inline-flex text-xl font-black tracking-tight text-white items-center gap-2 uppercase mb-3">
                <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-sm font-bold text-white normal-case">M</span>
                Mwasi App
            </a>
            <h2 class="text-2xl font-bold text-white tracking-tight">Welcome Back</h2>
            <p class="text-sm text-gray-400 mt-1">Enter your credentials to access your workspace terminal</p>
        </div>

        <div class="bg-gray-900/40 border border-gray-900 rounded-3xl p-8 backdrop-blur-md shadow-2xl">
            
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-xs text-red-400 font-medium leading-relaxed">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="login_input" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Business Email or Username</label>
                    <input type="text" name="login_input" id="login_input" value="{{ old('login_input') }}" required autofocus
                        placeholder="Owner email or seller username" 
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition duration-150">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <label for="password" class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Password</label>
                    </div>
                    <input type="password" name="password" id="password" required
                        placeholder="••••••••" 
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3.5 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition duration-150">
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-800 bg-gray-950 text-blue-600 focus:ring-0 focus:ring-offset-0">
                        <span class="text-xs text-gray-400">Remember my session</span>
                    </label>
                </div>

                <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-4 px-4 rounded-xl transition duration-200 shadow-lg shadow-blue-600/10 mt-2 block">
                    Log In to My Workspace
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-500 font-medium mt-6">
            Don't have a shop setup yet? 
            <a href="/#pricing" class="text-blue-400 hover:underline">Choose a plan here</a>
        </p>
    </div>

</body>
</html>