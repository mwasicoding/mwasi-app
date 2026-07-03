<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop System Packages - Mwasi App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 font-sans text-gray-100 flex">

    <aside class="w-64 bg-gray-800 min-h-screen flex flex-col shadow-xl">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-xl font-bold text-white tracking-wider">Mwasi Admin</h1>
            <span class="text-xs text-green-400 font-medium">● Master Control Active</span>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition">
                <span>Dashboard Overview</span>
            </a>
            <a href="/admin/packages" class="flex items-center px-4 py-3 bg-blue-600 text-white rounded-lg font-medium transition">
                <span>Shop System Packages</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 text-gray-400 hover:bg-gray-700 hover:text-white rounded-lg transition">
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
                <h2 class="text-3xl font-bold text-white">SaaS Subscription Packages</h2>
                <p class="text-gray-400 text-sm mt-1">Configured platform billing tiers available for incoming shop registrations.</p>
            </div>
        </header>

        <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-md">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700 text-gray-300 text-sm uppercase tracking-wider">
                        <th class="p-4 font-semibold">Package Name</th>
                        <th class="p-4 font-semibold">Billing Period</th>
                        <th class="p-4 font-semibold">Price</th>
                        <th class="p-4 font-semibold">Features Included</th>
                        <th class="p-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 text-sm text-gray-300">
                    @foreach($packages as $package)
                    <tr class="hover:bg-gray-750 transition">
                        <td class="p-4 font-bold text-white">{{ $package->name }}</td>
                        <td class="p-4 capitalize">
                            <span class="px-2 py-1 rounded text-xs font-semibold {{ $package->billing_period === 'monthly' ? 'bg-blue-900/50 text-blue-300' : 'bg-purple-900/50 text-purple-300' }}">
                                {{ $package->billing_period }}
                            </span>
                        </td>
                        <td class="p-4 font-mono font-semibold text-green-400">${{ number_format($package->price, 2) }}</td>
                        <td class="p-4 text-gray-400 max-w-xs truncate">{{ $package->features }}</td>
                        <td class="p-4">
                            <span class="text-green-400 font-medium">● Active</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>