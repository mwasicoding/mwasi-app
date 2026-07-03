<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog - Seller Station</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased min-h-screen p-6 selection:bg-blue-600 selection:text-white">

    <div class="max-w-6xl mx-auto">
        
        <header class="flex items-center justify-between mb-8 pb-5 border-b border-gray-900">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                    <a href="{{ route('seller.dashboard') }}" class="hover:text-blue-400 transition">Terminal</a>
                    <span>&rarr;</span>
                    <span class="text-gray-400">Browse Catalog</span>
                </div>
                <h1 class="text-2xl font-black tracking-tight text-white uppercase">{{ $shop->name ?? 'Store' }} Price & Stock List</h1>
            </div>

            <span class="bg-gray-900/60 border border-gray-800 text-gray-400 text-xs font-bold px-4 py-2.5 rounded-xl">
                🔒 View Only Mode
            </span>
        </header>

        <div class="bg-gray-900/20 border border-gray-900 rounded-2xl overflow-hidden backdrop-blur-md shadow-2xl">
            <div class="p-6 border-b border-gray-900 flex items-center justify-between bg-gray-900/10">
                <h3 class="font-bold text-sm tracking-tight text-gray-300">Available Shop Inventory</h3>
                <span class="bg-gray-800 text-gray-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-gray-800">
                    Items: {{ $products->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-900 bg-gray-900/30 text-gray-400 uppercase text-[10px] tracking-wider font-bold">
                            <th class="py-4 px-6">Item Description</th>
                            <th class="py-4 px-6">Brand</th>
                            <th class="py-4 px-6">Retail Selling Price</th>
                            <th class="py-4 px-6 text-center">Availability Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-950">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-900/10 transition group text-sm">
                                <td class="py-4 px-6 font-bold text-white group-hover:text-blue-400 transition">
                                    {{ $product->name }}
                                </td>

                                <td class="py-4 px-6">
                                    <span class="bg-gray-800 border border-gray-700 text-gray-300 text-[10px] font-semibold uppercase px-2 py-0.5 rounded">
                                        {{ $product->brand ?? 'Generic' }}
                                    </span>
                                </td>
                                
                                <td class="py-4 px-6 text-emerald-400 font-mono font-bold">
                                    {{ number_format($product->selling_price) }} TZS
                                </td>
                                
                                <td class="py-4 px-6 text-center">
                                    @if($product->stock_quantity <= 0)
                                        <span class="inline-flex items-center bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            🚫 Out of Stock
                                        </span>
                                    @elif($product->stock_quantity <= $product->low_stock_threshold)
                                        <span class="inline-flex items-center bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider animate-pulse">
                                            ⚠️ Only {{ $product->stock_quantity }} Left
                                        </span>
                                    @else
                                        <span class="inline-flex items-center bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            ✔ {{ $product->stock_quantity }} In Stock
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 px-6 text-center text-gray-500 text-xs tracking-tight">
                                    <div class="text-2xl mb-2">📦</div>
                                    No products listed in this store yet.
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