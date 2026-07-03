<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Product - Master Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased min-h-screen p-6 selection:bg-blue-600 selection:text-white">

    <div class="max-w-2xl mx-auto">
        
        <header class="mb-8 pb-5 border-b border-gray-900">
            <div class="flex items-center gap-2 text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                <a href="{{ route('merchant.dashboard') }}" class="hover:text-blue-400 transition">Dashboard</a>
                <span>&rarr;</span>
                <a href="{{ route('merchant.products.index') }}" class="hover:text-blue-400 transition">Inventory</a>
                <span>&rarr;</span>
                <span class="text-gray-400">New Product</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white uppercase">Register New Stock Item</h1>
        </header>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-xs space-y-1">
                <p class="font-bold uppercase tracking-wide">Please correct the following errors:</p>
                <ul class="list-disc list-inside opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-900/20 border border-gray-900 rounded-2xl p-6 backdrop-blur-md shadow-2xl">
            <form action="{{ route('merchant.products.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Product / Service Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="e.g., iPhone 15 Pro Max or Consulting Fee" required
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                </div>

                <div>
                    <label for="brand" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Brand / Brand Name <span class="text-gray-500">(Optional)</span></label>
                    <input type="text" id="brand" name="brand" value="{{ old('brand') }}" placeholder="e.g., Apple Inc."
                        class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="wholesale_cost" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Wholesale Cost (TZS) <span class="text-red-500">*</span></label>
                        <input type="number" id="wholesale_cost" name="wholesale_cost" value="{{ old('wholesale_cost') }}" min="0" placeholder="0" required
                            class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="selling_price" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Retail Selling Price (TZS) <span class="text-red-500">*</span></label>
                        <input type="number" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" min="0" placeholder="0" required
                            class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="stock_quantity" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Initial Opening Stock <span class="text-red-500">*</span></label>
                        <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" min="0" required
                            class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                    </div>
                    <div>
                        <label for="low_stock_threshold" class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Low Stock Threshold Limit <span class="text-red-500">*</span></label>
                        <input type="number" id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', 5) }}" min="0" required
                            class="w-full bg-gray-950 border border-gray-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder-gray-600 focus:outline-none focus:border-blue-500 transition">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-900 flex items-center justify-end gap-3">
                    <a href="{{ route('merchant.products.index') }}" class="text-xs font-bold text-gray-400 hover:text-white transition px-4 py-3">
                        Cancel
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 active:scale-95 text-white text-xs font-bold px-6 py-3 rounded-xl transition duration-150 shadow-lg shadow-blue-600/10">
                        Save System Item
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>