<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Dashboard - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased selection:bg-blue-600 selection:text-white min-h-screen flex flex-col">

    <header class="w-full bg-gray-950 border-b border-gray-900 h-20 flex justify-between items-center px-6 sticky top-0 z-40">
        <div class="text-lg font-black tracking-tight text-white flex items-center gap-2 uppercase">
            <span class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-sm font-bold text-white normal-case">M</span>
            Mwasi App
        </div>
        
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="text-xs font-mono text-gray-500 hover:text-red-400 transition bg-gray-900/50 border border-gray-800 px-4 py-2 rounded-xl">
                Log Out
            </button>
        </form>
    </header>

    <main class="flex-1 max-w-4xl w-full mx-auto p-6 space-y-6">
        
        <div class="bg-gray-900/40 border border-gray-900 rounded-3xl p-6 backdrop-blur-md flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-semibold mb-1">
                    Welcome Back, {{ Auth::user()->owner_name ?? 'Merchant' }}
                </p>
                <h1 class="text-2xl font-black text-white tracking-tight uppercase">
                    {{ Auth::user()->name ?? 'Your Store' }}
                </h1>
            </div>
            <div>
                @if(Auth::user()->is_active)
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        Account Status: Fully Activated
                    </span>
                @else
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-amber-500/10 border border-amber-500/20 text-amber-400">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                        Account Status: Awaiting Payment Verification
                    </span>
                @endif
            </div>
        </div>

        @if(!Auth::user()->is_active)
            <div class="bg-gray-900/40 border border-gray-900 rounded-3xl p-6 backdrop-blur-md">
                <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Activation Progress</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm font-medium">
                    <div class="flex items-center gap-3 bg-gray-950 p-4 rounded-xl border border-gray-800">
                        <span class="text-emerald-500 text-lg">✅</span>
                        <span class="text-gray-300">Step 1: Registration Completed</span>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-950 p-4 rounded-xl border border-amber-500/30">
                        <span class="inline-block animate-spin text-lg">⏳</span>
                        <span class="text-amber-400 font-semibold">Step 2: Payment Receipt Verification</span>
                    </div>
                    <div class="flex items-center gap-3 bg-gray-950/40 p-4 rounded-xl border border-gray-900 opacity-50">
                        <span class="text-gray-600 text-lg">🔒</span>
                        <span class="text-gray-500">Step 3: Network Activation</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-950/20 to-gray-900/40 border border-blue-900/30 rounded-3xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-600/10 blur-[50px] rounded-full pointer-events-none"></div>
                
                <div class="max-w-2xl">
                    <h2 class="text-xl font-bold text-white mb-3">How to Activate Your Account Instantly</h2>
                    <p class="text-gray-300 text-sm leading-relaxed mb-6">
                        If you haven't paid yet, please complete your package payment via <strong class="text-blue-400">Lipa Namba (Vodacom)</strong>. 
                    </p>
                    <div class="bg-gray-950/80 border border-gray-800 rounded-2xl p-4 inline-block">
                        <p class="text-xs text-gray-400 uppercase font-bold tracking-wider mb-1">Send Proof of Payment To:</p>
                        <p class="text-lg font-mono font-bold text-white flex items-center gap-2">
                            📞 0743 074 006
                        </p>
                        <p class="text-[11px] text-gray-500 mt-1 font-sans">Via WhatsApp message or normal SMS text for instant manual verification.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-3">
            <h3 class="text-xs font-semibold uppercase tracking-wider pl-1 {{ Auth::user()->is_active ? 'text-blue-400' : 'text-gray-500' }}">
                {{ Auth::user()->is_active ? 'Your Management Tools (Unlocked)' : 'Your Store Tools (Locked Until Approved)' }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @if(Auth::user()->is_active)
                    <a href="{{ route('merchant.sellers.index') }}" class="border rounded-2xl p-5 flex items-center gap-3 transition duration-300 bg-blue-950/20 border-blue-900/40 hover:bg-blue-950/40">
                        <span class="text-xl">📦</span>
                        <span class="text-sm font-medium text-gray-200">Shop Seller Registration</span>
                    </a>
                @else
                    <div class="border rounded-2xl p-5 flex items-center gap-3 transition duration-300 bg-gray-900/20 border-gray-900/50 opacity-40 select-none">
                        <span class="text-xl">🔒</span>
                        <span class="text-sm font-medium text-gray-400">Shop Seller Registration</span>
                    </div>
                @endif

                @if(Auth::user()->is_active)
                    <a href="{{ route('merchant.products.index') }}" class="border rounded-2xl p-5 flex items-center gap-3 transition duration-300 bg-blue-950/20 border-blue-900/40 hover:bg-blue-950/40">
                        <span class="text-xl">🛍️</span>
                        <span class="text-sm font-medium text-gray-200">Product / Service Management</span>
                    </a>
                @else
                    <div class="border rounded-2xl p-5 flex items-center gap-3 transition duration-300 bg-gray-900/20 border-gray-900/50 opacity-40 select-none">
                        <span class="text-xl">🔒</span>
                        <span class="text-sm font-medium text-gray-400">Product / Service Management</span>
                    </div>
                @endif

                @if(Auth::user()->is_active)
                    <a href="{{ route('seller.sales.index') }}" class="border rounded-2xl p-5 flex items-center justify-between transition duration-300 bg-blue-950/20 border-blue-900/40 cursor-pointer hover:bg-blue-950/40">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">📊</span>
                            <span class="text-sm font-medium text-gray-200">Sales Management</span>
                        </div>
                    </a>
                @else
                    <div class="border rounded-2xl p-5 flex items-center justify-between transition duration-300 bg-gray-900/20 border-gray-900/50 opacity-40 select-none">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🔒</span>
                            <span class="text-sm font-medium text-gray-400">Sales Management</span>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->is_active)
                    <a href="{{ route('merchant.reports.index') }}" class="border rounded-2xl p-5 flex items-center justify-between transition duration-300 bg-blue-950/20 border-blue-900/40 cursor-pointer hover:bg-blue-950/40">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">📈</span>
                            <span class="text-sm font-medium text-gray-200">Report Management</span>
                        </div>
                    </a>
                @else
                    <div class="border rounded-2xl p-5 flex items-center justify-between transition duration-300 bg-gray-900/20 border-gray-900/50 opacity-40 select-none">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🔒</span>
                            <span class="text-sm font-medium text-gray-400">Report Management</span>
                        </div>
                    </div>
                @endif

                @if(Auth::user()->is_active)
                    <a href="#" class="border rounded-2xl p-5 flex items-center justify-between md:col-span-2 transition duration-300 bg-blue-950/20 border-blue-900/40 cursor-pointer hover:bg-blue-950/40">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🔔</span>
                            <span class="text-sm font-medium text-gray-200">Low Stock & Most Sold Service Alerts</span>
                        </div>
                    </a>
                @else
                    <div class="border rounded-2xl p-5 flex items-center justify-between md:col-span-2 transition duration-300 bg-gray-900/20 border-gray-900/50 opacity-40 select-none">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🔒</span>
                            <span class="text-sm font-medium text-gray-400">Low Stock & Most Sold Service Alerts</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </main>

    <footer class="text-center py-8 border-t border-gray-900 text-[10px] text-gray-600 font-mono">
        &copy; 2026 Mwasi App Engine Core. Account Activation Workspace.
    </footer>

</body>
</html>