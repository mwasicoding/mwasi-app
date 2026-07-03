<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Mwasi App - Easy Shop Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased selection:bg-blue-600 selection:text-white">

    @if(session('success'))
        <div class="bg-green-600 text-white text-center py-3.5 px-4 font-bold tracking-wide shadow-xl text-sm fixed top-0 w-full z-50">
            🎉 {{ session('success') }}
        </div>
    @endif

    <header class="fixed top-0 w-full bg-gray-950/80 backdrop-blur-md border-b border-gray-900 z-40">
        <div class="max-w-7xl mx-auto px-6 h-20 flex justify-between items-center">
            <a href="#" class="text-xl font-black tracking-tight text-white flex items-center gap-2 uppercase">
                <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-sm font-bold text-white normal-case">M</span>
                Mwasi App
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-gray-400">
                <a href="#features" class="hover:text-white transition">What You Get</a>
                <a href="#pricing" class="hover:text-white transition">Prices</a>
                <a href="#contact" class="hover:text-white transition">Get Help</a>
            </nav>

            <div class="flex items-center gap-4">
                <a href="/login" class="text-sm font-semibold text-gray-300 hover:text-white px-3 py-2 transition">
                    Shop Owner Login
                </a>
                <a href="#pricing" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition shadow-lg">
                    Start Now
                </a>
                <a href="/admin/login" class="text-xs text-gray-500 hover:text-gray-300 transition font-mono border-l border-gray-800 pl-4 hidden sm:inline-block">
                    Admin Gate
                </a>
            </div>
        </div>
    </header>

    <section class="relative pt-44 pb-24 px-6 max-w-5xl mx-auto text-center">
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[250px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none"></div>

        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-medium bg-gray-900 border border-gray-800 text-blue-400 mb-6">
            📢 The Easiest Way To Manage Your Business
        </span>
        
        <h1 class="text-4xl sm:text-6xl font-black text-white tracking-tight leading-[1.15] mb-6">
            Open Your Online Shop System <br>
            <span class="bg-gradient-to-r from-blue-400 to-indigo-400 bg-clip-text text-transparent">In Only One Minute</span>
        </h1>
        
        <p class="text-gray-400 text-lg sm:text-xl max-w-2xl mx-auto mb-10 font-normal leading-relaxed">
            Mwasi App helps any business owner track daily sales, check available items in stock, and view profits easily using a phone or computer from anywhere.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#pricing" class="w-full sm:w-auto bg-white text-gray-950 font-bold px-8 py-4 rounded-xl transition shadow-xl text-center">
                See Our Prices
            </a>
            <a href="#features" class="w-full sm:w-auto bg-gray-900/50 text-gray-300 font-semibold px-8 py-4 rounded-xl border border-gray-800 transition text-center">
                Read More
            </a>
        </div>
    </section>

    <hr class="border-gray-900 max-w-7xl mx-auto">

    <section id="features" class="py-24 px-6 max-w-7xl mx-auto scroll-mt-20">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">What You Can Do With This System</h2>
            <p class="text-gray-400 mt-3 max-w-xl mx-auto">We use very easy steps and simple terms so you can run your shop without problems.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gray-900/40 border border-gray-900 rounded-2xl p-6 hover:border-gray-800 transition">
                <div class="w-10 h-10 bg-blue-500/10 rounded-xl flex items-center justify-center text-xl text-blue-400 mb-5">💳</div>
                <h3 class="text-lg font-bold text-white mb-2">Track Sales</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Write down daily customer payments, print easy receipts, and know exactly how much money came in.</p>
            </div>
            <div class="bg-gray-900/40 border border-gray-900 rounded-2xl p-6 hover:border-gray-800 transition">
                <div class="w-10 h-10 bg-purple-500/10 rounded-xl flex items-center justify-center text-xl text-purple-400 mb-5">📦</div>
                <h3 class="text-lg font-bold text-white mb-2">Check Your Stock</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Get a warning when items are running low, and see which products sell the most inside your shop.</p>
            </div>
            <div class="bg-gray-900/40 border border-gray-900 rounded-2xl p-6 hover:border-gray-800 transition">
                <div class="w-10 h-10 bg-emerald-500/10 rounded-xl flex items-center justify-center text-xl text-emerald-400 mb-5">👥</div>
                <h3 class="text-lg font-bold text-white mb-2">Worker Accounts</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Add your sales assistants or workers to the system, and control what they are allowed to see or change.</p>
            </div>
            <div class="bg-gray-900/40 border border-gray-900 rounded-2xl p-6 hover:border-gray-800 transition">
                <div class="w-10 h-10 bg-amber-500/10 rounded-xl flex items-center justify-center text-xl text-amber-400 mb-5">📊</div>
                <h3 class="text-lg font-bold text-white mb-2">Daily Reports</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Get short, clean summaries of your daily profits, business path, and expenses without hard math.</p>
            </div>
        </div>
    </section>

    <section id="pricing" class="bg-gray-900/30 border-t border-gray-900 py-24 px-6 scroll-mt-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-extrabold text-white tracking-tight sm:text-4xl">Choose Your Plan</h2>
                <p class="text-gray-400 mt-3 max-w-xl mx-auto">Pick a payment plan that fits the size of your business budget today.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @forelse($packages as $package)
                    <div class="bg-gray-950 border border-gray-800 rounded-3xl p-8 flex flex-col justify-between relative hover:border-blue-500/50 transition duration-300">
                        @if(strtolower($package->type) === 'yearly')
                            <span class="absolute -top-3 right-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-[10px] uppercase font-black px-3 py-1 rounded-full tracking-wider shadow">Best Value</span>
                        @endif
                        <div>
                            <h3 class="text-xl font-bold text-white capitalize">{{ $package->name }} Plan</h3>
                            <p class="text-xs text-gray-500 uppercase tracking-widest font-mono mt-1">Paid {{ strtolower($package->type) === 'yearly' ? 'Every Year' : 'Every Month' }}</p>
                            
                            <div class="my-6 flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white">${{ number_format($package->price, 2) }}</span>
                                <span class="text-xs text-gray-400 font-mono">/{{ strtolower($package->type) === 'yearly' ? 'yr' : 'mo' }}</span>
                            </div>

                            <ul class="space-y-3.5 border-t border-gray-900 pt-6 text-sm text-gray-300 mb-8">
                                <li class="flex items-center gap-2.5">
                                    <span class="text-blue-500 text-xs">✓</span> Safe and private shop data storage
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-blue-500 text-xs">✓</span> Full access to daily sales tools
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-blue-500 text-xs">✓</span> Custom internet link for your shop name
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <span class="text-blue-500 text-xs">✓</span> Instant alerts when items end
                                </li>
                            </ul>
                        </div>

                        <a href="{{ route('shop.register', $package->id) }}" class="w-full block text-center font-bold text-sm py-4 px-4 rounded-xl transition duration-200 {{ strtolower($package->type) === 'yearly' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white hover:opacity-90' : 'bg-gray-900 hover:bg-blue-600 text-gray-200 hover:text-white border border-gray-800 hover:border-blue-500' }}">
                            Register Your Shop Here
                        </a>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 bg-gray-900/20 rounded-2xl p-12 border border-dashed border-gray-800 text-center">
                        <p class="text-gray-400 font-medium">No shop system plans are available right now.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="contact" class="max-w-7xl mx-auto px-6 py-20 border-t border-gray-900">
        <div class="bg-gradient-to-br from-gray-900 to-gray-950 rounded-3xl p-8 md:p-12 border border-gray-900 max-w-4xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8 shadow-2xl">
            <div>
                <h2 class="text-2xl font-bold text-white mb-2">Need Any Help?</h2>
                <p class="text-gray-400 text-sm max-w-md">Talk to us directly for setup help, sending payment proof, or asking any questions about the app.</p>
            </div>
            <div class="w-full md:w-auto space-y-4 font-mono text-sm bg-gray-950/60 p-6 rounded-2xl border border-gray-900 min-w-[280px]">
                <div class="flex items-center space-x-3">
                    <span class="text-base">📧</span>
                    <a href="mailto:mwasicoding@gmail.com" class="text-blue-400 hover:underline">mwasicoding@gmail.com</a>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="text-base">📞</span>
                    <a href="tel:+255743074006" class="text-purple-400 hover:underline">+255 743 074 006</a>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center py-12 border-t border-gray-900 text-xs text-gray-600 font-mono bg-gray-950">
        &copy; 2026 Mwasi App Engine Core. All Rights Reserved.
    </footer>

</body>
</html>