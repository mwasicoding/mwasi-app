<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin Dashboard - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 font-sans text-gray-100 flex">

    <aside class="w-64 bg-gray-800 min-h-screen flex flex-col shadow-xl">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold text-white tracking-wider">Mwasi Admin</h1>
            <span class="text-xs text-green-400 font-medium">● Master Control Active</span>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg font-medium transition">
                <span>Dashboard Overview</span>
            </a>
            <a href="/admin/packages" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <span>Shop System Packages</span>
            </a>
            <a href="{{ route('admin.shops.index') }}" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <span>Registered Shops</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <span>Global System Logs</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-700">
            <div class="text-sm text-gray-400 mb-2">Logged in as: <strong class="text-white">admin_master</strong></div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="block text-center text-xs bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded font-bold transition">
                Secure Logout
            </a>
            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="hidden">
                @csrf
            </form>
        </div>
    </aside>

    <main class="flex-1 p-8">
        <header class="flex justify-between items-center mb-8 pb-4 border-b border-gray-800">
            <div>
                <h2 class="text-3xl font-bold text-white">System Overview</h2>
                <p class="text-gray-400 text-sm mt-1">Real-time statistics for the entire multi-tenant app network.</p>
            </div>
            <div class="bg-gray-800 px-4 py-2 rounded-lg text-sm font-mono text-gray-300 border border-gray-700">
                Server Status: <span class="text-green-400 font-bold">ONLINE</span>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-md">
                <div class="text-gray-400 font-semibold text-sm uppercase tracking-wider mb-2">Total SaaS Packages</div>
                <div class="text-4xl font-extrabold text-blue-500">{{ $totalPackages }}</div>
                <p class="text-xs text-gray-500 mt-2">
                    @if($totalPackages > 0)
                        Plans successfully loaded from database
                    @else
                        No active plans configured yet
                    @endif
                </p>
            </div>
            
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-md">
                <div class="text-gray-400 font-semibold text-sm uppercase tracking-wider mb-2">Active Client Shops</div>
                <div class="text-4xl font-extrabold text-purple-500">{{ $activeShops }}</div>
                <p class="text-xs text-gray-500 mt-2">
                    @if($activeShops > 0)
                        Active merchants running on your network
                    @else
                        Waiting for first shop registration
                    @endif
                </p>
            </div>

            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-md">
                <div class="text-gray-400 font-semibold text-sm uppercase tracking-wider mb-2">Total System Revenue</div>
                <div class="text-4xl font-extrabold text-green-500">${{ number_format($totalSystemRevenue ?? 0, 2) }}</div>
                <p class="text-xs text-gray-500 mt-2">SaaS recurring payments metrics</p>
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl border border-gray-700 shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-700 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-white">Recent Shop Registrations</h3>
                    <p class="text-xs text-gray-400 mt-0.5">The latest commercial storefronts deployed to your application infrastructure.</p>
                </div>
                <a href="{{ route('admin.shops.index') }}" class="text-xs bg-gray-700 hover:bg-gray-600 text-blue-400 font-semibold px-3 py-1.5 rounded transition">
                    View All Shops &rarr;
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-900/50 text-xs font-semibold uppercase tracking-wider text-gray-400 border-b border-gray-700">
                            <th class="py-3 px-6">Shop ID</th>
                            <th class="py-3 px-6">Storefront Name</th>
                            <th class="py-3 px-6">Primary Owner Account</th>
                            <th class="py-3 px-6">Registration Date</th>
                            <th class="py-3 px-6 text-center">Infrastructure Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700 text-sm font-mono text-gray-300">
                        @forelse($recentShops ?? [] as $shop)
                            <tr class="hover:bg-gray-700/30 transition">
                                <td class="py-3.5 px-6 text-gray-500 text-xs">#{{ $shop->id }}</td>
                                <td class="py-3.5 px-6 font-sans font-semibold text-white">{{ $shop->name }}</td>
                                <td class="py-3.5 px-6 font-sans text-gray-400">{{ $shop->email ?? 'N/A' }}</td>
                                <td class="py-3.5 px-6 text-xs text-gray-400">{{ $shop->created_at ? $shop->created_at->format('M d, Y H:i') : 'Unknown' }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="bg-green-950/40 text-green-400 border border-green-900/50 px-2 py-0.5 rounded text-[11px] font-sans font-medium">
                                        ● Operational
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 italic font-sans text-sm">
                                    No shop deployments tracked in the system logs yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>