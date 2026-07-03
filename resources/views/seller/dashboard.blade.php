<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Terminal - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased min-h-screen p-6 selection:bg-blue-600 selection:text-white">

    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-blue-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-6xl mx-auto relative z-10">
        
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-900/40 border border-gray-900 rounded-2xl p-6 backdrop-blur-md mb-8">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-xl font-black uppercase tracking-tight text-white">{{ $shop->name ?? 'My Shop' }}</h1>
                    <span class="bg-blue-500/10 border border-blue-500/20 text-blue-400 text-[10px] font-bold uppercase tracking-widest px-2 py-0.5 rounded-md">
                        Seller Terminal
                    </span>
                </div>
                <p class="text-xs text-gray-400 mt-1">Logged in as: <span class="text-gray-200 font-medium">{{ $seller->username }}</span></p>
            </div>

            <div>
                <form action="{{ route('seller.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="border border-gray-800 hover:border-red-500/30 hover:bg-red-500/10 text-gray-400 hover:text-red-400 text-xs font-bold px-4 py-2.5 rounded-xl transition duration-150 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/xl" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        Logout Account
                    </button>
                </form>
            </div>
        </header>

        <main class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <a href="{{ route('seller.sales.index') }}" class="bg-gray-900/20 border border-gray-900 rounded-2xl p-6 flex flex-col justify-between hover:border-emerald-500/40 hover:bg-emerald-950/10 transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-400 mb-4 font-bold">🛒</div>
                    <h3 class="text-lg font-bold text-white tracking-tight group-hover:text-emerald-400 transition-colors">Sales Management</h3>
                    <p class="text-xs text-gray-400 mt-1.5 leading-relaxed">Access the high-speed checkout terminal, run custom cash registers, and generate customer receipts instantly.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-900/60 flex items-center justify-between">
                    <span class="text-[11px] text-gray-500 uppercase tracking-wider font-semibold">Counter POS</span>
                    <span class="text-xs font-bold text-emerald-400 group-hover:underline">Open Terminal &rarr;</span>
                </div>
            </a>

            <a href="{{ route('seller.products.index') }}" class="bg-gray-900/20 border border-gray-900 rounded-2xl p-6 flex flex-col justify-between hover:border-blue-500/40 hover:bg-blue-950/10 transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-400 mb-4 font-bold">🏷️</div>
                    <h3 class="text-lg font-bold text-white tracking-tight group-hover:text-blue-400 transition-colors">Product Management</h3>
                    <p class="text-xs text-gray-400 mt-1.5 leading-relaxed">Browse live inventory levels, catalog item variations, and check baseline pricing fields for customers.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-900/60 flex items-center justify-between">
                    <span class="text-[11px] text-gray-500 uppercase tracking-wider font-semibold">Catalog Viewer</span>
                    <span class="text-xs font-bold text-blue-400 group-hover:underline">View Stock &rarr;</span>
                </div>
            </a>

            <a href="{{ route('seller.reports.index') }}" class="bg-gray-900/20 border border-gray-900 rounded-2xl p-6 flex flex-col justify-between hover:border-purple-500/40 hover:bg-purple-950/10 transition-all duration-200 group">
                <div>
                    <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-400 mb-4 font-bold">📊</div>
                    <h3 class="text-lg font-bold text-white tracking-tight group-hover:text-purple-400 transition-colors">Report Management</h3>
                    <p class="text-xs text-gray-400 mt-1.5 leading-relaxed">Review your specific daily transaction histories, shift drawer cash totals, and item sales counts.</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-900/60 flex items-center justify-between">
                    <span class="text-[11px] text-gray-500 uppercase tracking-wider font-semibold">Shift Data</span>
                    <span class="text-xs font-bold text-purple-400 group-hover:underline">My Summary &rarr;</span>
                </div>
            </a>

        </main>
    </div>

</body>
</html>