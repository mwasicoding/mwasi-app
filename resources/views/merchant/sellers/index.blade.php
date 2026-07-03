<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management - Merchant Control Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 font-sans text-gray-100 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto p-6 lg:p-10">
        
        <header class="flex justify-between items-center mb-10 border-b border-gray-800 pb-6">
            <div>
                <a href="{{ route('merchant.dashboard') }}" class="text-xs font-bold text-blue-400 hover:underline uppercase tracking-wider">← Back to Main Dashboard</a>
                <h1 class="text-3xl font-extrabold text-white tracking-tight mt-2">Shop Seller Registration</h1>
                <p class="text-sm text-gray-400 mt-1">Manage, deploy, register, or instantly reset access parameters for your floor sales attendants.</p>
            </div>
            <div class="bg-gray-800 px-4 py-2 rounded-xl border border-gray-700 text-right">
                <span class="text-xs text-gray-400 block font-medium">Logged in Store:</span>
                <span class="text-sm font-bold text-green-400">{{ Auth::user()->name }}</span>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-8 bg-green-500/10 border border-green-500/20 text-green-400 font-medium py-3 px-4 rounded-xl text-sm">
                🎉 {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-400 font-medium py-3 px-4 rounded-xl text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <div class="bg-gray-800 rounded-2xl border border-gray-700 p-6 shadow-xl sticky top-6">
                <h2 class="text-xl font-bold text-white mb-1">Register New Staff</h2>
                <p class="text-xs text-gray-400 mb-6">Create credentials for a new cashier or sales associate account below.</p>

                <form action="{{ route('merchant.sellers.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Full Employee Name:</label>
                        <input type="text" name="name" required placeholder="e.g. Jane Doe" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Login Username (Unique System ID):</label>
                        <input type="text" name="username" required placeholder="e.g. janedoe_mwasi" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm font-mono focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Phone Number (Optional):</label>
                        <input type="text" name="phone" placeholder="e.g. +255 712 345 678" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Temporary Initial Password:</label>
                        <input type="password" name="password" required placeholder="Minimum 4 characters" class="w-full bg-gray-950 border border-gray-700 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-blue-500 transition">
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-sm transition shadow-lg tracking-wide mt-2">
                        Add Attendant to System
                    </button>
                </form>
            </div>

            <div class="lg:grid-cols-1 lg:col-span-2 bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-gray-700 bg-gray-900/40">
                    <h2 class="text-xl font-bold text-white">Registered Shop Sellers</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Live roster tracking active credentials tied strictly to your corporate shop instance boundary.</p>
                </div>

                <table class="w-full border-collapse text-left text-sm text-gray-300">
                    <thead class="bg-gray-900 text-xs font-semibold uppercase tracking-wider text-gray-400 border-b border-gray-700">
                        <tr>
                            <th class="px-6 py-4">Sellers Profiles</th>
                            <th class="px-6 py-4">Gate Username / Phone</th>
                            <th class="px-6 py-4">Status Flag</th>
                            <th class="px-6 py-4 text-center">Security Corrections Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @forelse($sellers as $seller)
                            <tr class="hover:bg-gray-700/20 transition">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-white text-base">{{ $seller->name }}</div>
                                    <div class="text-[10px] font-mono text-gray-500 mt-0.5">Assigned: {{ $seller->created_at->format('Y-m-d H:i') }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-mono text-blue-400 text-sm font-semibold">{{ $seller->username }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $seller->phone ?? 'No phone added' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('merchant.sellers.toggle', $seller->id) }}" method="POST">
                                        @csrf
                                        @if($seller->is_active)
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20 hover:bg-amber-500/10 hover:text-amber-400 hover:border-amber-500/20 transition">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-green-500"></span> Live / Active
                                            </button>
                                        @else
                                            <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-green-500/10 hover:text-green-400 hover:border-green-500/20 transition">
                                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Locked / Suspended
                                            </button>
                                        @endif
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('merchant.sellers.reset', $seller->id) }}" method="POST" class="flex gap-2 items-center justify-center max-w-sm mx-auto">
                                        @csrf
                                        <input type="password" name="password" required placeholder="New Password" class="w-32 bg-gray-950 border border-gray-700 rounded px-2 py-1 text-xs text-white focus:outline-none focus:border-blue-500">
                                        <button type="submit" class="bg-gray-700 hover:bg-blue-600 hover:text-white text-gray-300 font-bold py-1 px-2.5 rounded text-xs transition border border-gray-600/50">
                                            Reset
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-500 font-mono">
                                    No shop attendants registered under your multi-tenant workspace yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</body>
</html>