<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Shops - Mwasi Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 font-sans text-gray-100 antialiased min-h-screen flex">

    <aside class="w-64 bg-gray-950 border-r border-gray-800 flex flex-col justify-between fixed h-full z-30">
        <div class="p-6">
            <h1 class="text-xl font-black tracking-wide text-white">Mwasi Admin</h1>
            <p class="text-[10px] font-mono text-green-400 flex items-center mt-1">
                <span class="w-2 h-2 rounded-full bg-green-500 mr-2 animate-pulse"></span> Master Control Active
            </p>
            <nav class="mt-8 space-y-1">
                <a href="/admin/dashboard" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition">Dashboard Overview</a>
                <a href="/admin/packages" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition">Shop System Packages</a>
                <a href="{{ route('admin.shops.index') }}" class="block px-4 py-2.5 rounded-lg text-sm font-bold bg-blue-600 text-white shadow">Registered Shops</a>
                <a href="#" class="block px-4 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition">Global System Logs</a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-800 bg-gray-900/50">
            <p class="text-xs text-gray-500 font-mono mb-2">Logged in as: <span class="text-white font-bold">admin_master</span></p>
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 rounded-lg transition tracking-wide text-center">Secure Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-10">
        
        <header class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-extrabold text-white tracking-tight">Registered Merchant Shops</h2>
                <p class="text-sm text-gray-400 mt-1">Review, approve, manually configure timelines, or suspend multi-tenant storefront platforms operating on your core network.</p>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 font-medium py-3 px-4 rounded-xl text-sm">
                🎉 {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
            <table class="w-full border-collapse text-left text-sm text-gray-300">
                <thead class="bg-gray-900 text-xs font-semibold uppercase tracking-wider text-gray-400 border-b border-gray-700">
                    <tr>
                        <th class="px-6 py-4">Shop Details</th>
                        <th class="px-6 py-4">Owner Contact</th>
                        <th class="px-6 py-4">Selected Plan</th>
                        <th class="px-6 py-4">Timeline Tracks</th>
                        <th class="px-6 py-4">Tenant Status</th>
                        <th class="px-6 py-4 text-center">Approval Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($shops as $shop)
                        <tr class="hover:bg-gray-700/30 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-white text-base">{{ $shop->name }}</div>
                                <div class="text-xs font-mono text-blue-400 mt-0.5">slug: /{{ $shop->slug }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-gray-200 font-medium">{{ $shop->owner_name }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $shop->email }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-bold uppercase tracking-wider {{ strtolower($shop->package->type) === 'yearly' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : 'bg-blue-500/10 text-blue-400 border border-blue-500/20' }}">
                                    {{ $shop->package->name }} ({{ $shop->package->type }})
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($shop->activated_at && $shop->expires_at)
                                    <div class="text-xs font-medium text-gray-300">
                                        <span class="text-gray-500">Start:</span> {{ $shop->activated_at->format('Y-m-d') }}
                                    </div>
                                    <div class="text-xs font-medium text-amber-400 mt-0.5">
                                        <span class="text-gray-500">Ends:</span> {{ $shop->expires_at->format('Y-m-d') }}
                                    </div>
                                @else
                                    <span class="text-xs italic text-gray-500">No scheduled timeline</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($shop->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.shops.toggle', $shop->id) }}" method="POST" class="flex flex-col gap-2 items-center justify-center max-w-[220px] mx-auto">
                                    @csrf
                                    @if($shop->is_active)
                                        <button type="submit" class="w-full bg-red-600/20 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold py-2 px-4 rounded-lg border border-red-500/30 transition shadow-sm text-center">
                                            Suspend Shop
                                        </button>
                                    @else
                                        <div class="w-full space-y-1.5 bg-gray-900/50 p-2 rounded-xl border border-gray-700/60 text-[11px]">
                                            <div>
                                                <label class="block text-gray-400 font-semibold mb-0.5 tracking-wide uppercase">Activation Start:</label>
                                                <input type="date" name="activated_at" value="{{ date('Y-m-d') }}" class="w-full bg-gray-950 border border-gray-700 rounded px-1.5 py-1 text-white text-xs focus:outline-none focus:border-blue-500">
                                            </div>
                                            <div>
                                                <label class="block text-gray-400 font-semibold mb-0.5 tracking-wide uppercase">Expiration End:</label>
                                                <input type="date" name="expires_at" value="{{ strtolower($shop->package->type) === 'yearly' ? date('Y-m-d', strtotime('+1 year')) : date('Y-m-d', strtotime('+1 month')) }}" class="w-full bg-gray-950 border border-gray-700 rounded px-1.5 py-1 text-amber-400 text-xs focus:outline-none focus:border-blue-500">
                                            </div>
                                        </div>
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-md transition text-center">
                                            Approve &amp; Activate
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-mono">
                                No merchant shops registered on the network yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>