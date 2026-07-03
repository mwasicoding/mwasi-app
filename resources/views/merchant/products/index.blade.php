<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Master Console</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-950 font-sans text-gray-100 antialiased min-h-screen p-6 selection:bg-blue-600 selection:text-white">

    <div class="max-w-6xl mx-auto">
        
        <header class="flex items-center justify-between mb-8 pb-5 border-b border-gray-900">
            <div>
                <div class="flex items-center gap-2 text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">
                    <a href="{{ route('merchant.dashboard') }}" class="hover:text-blue-400 transition">Dashboard</a>
                    <span>&rarr;</span>
                    <span class="text-gray-400">Inventory Management</span>
                </div>
                <h1 class="text-2xl font-black tracking-tight text-white uppercase">{{ $shop->name ?? 'Master Store' }} Catalog</h1>
            </div>

            <a href="{{ route('merchant.products.create') }}" class="bg-blue-600 hover:bg-blue-500 active:scale-95 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition duration-150 shadow-lg shadow-blue-600/10 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Register New Product
            </a>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-gray-900/20 border border-gray-900 rounded-2xl overflow-hidden backdrop-blur-md shadow-2xl">
            <div class="p-6 border-b border-gray-900 flex items-center justify-between bg-gray-900/10">
                <h3 class="font-bold text-sm tracking-tight text-gray-300">Active Stock Units</h3>
                <span class="bg-gray-800 text-gray-400 text-[11px] font-bold px-2.5 py-1 rounded-full border border-gray-800">
                    Total: {{ $products->count() }}
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-900 bg-gray-900/30 text-gray-400 uppercase text-[10px] tracking-wider font-bold">
                            <th class="py-4 px-6">Product Details</th>
                            <th class="py-4 px-6">Wholesale Cost</th>
                            <th class="py-4 px-6">Retail Selling Price</th>
                            <th class="py-4 px-6 text-center">Net Margin</th>
                            <th class="py-4 px-6 text-center">Stock Level</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-950">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-900/10 transition group text-sm">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-white group-hover:text-blue-400 transition">{{ $product->name }}</div>
                                    <span class="inline-block mt-1 bg-gray-800 border border-gray-700 text-gray-400 text-[9px] font-semibold uppercase px-1.5 py-0.5 rounded">
                                        {{ $product->brand ?? 'Generic' }}
                                    </span>
                                </td>
                                
                                <td class="py-4 px-6 text-gray-300 font-mono">
                                    {{ number_format($product->buying_price) }} TZS
                                </td>
                                
                                <td class="py-4 px-6 text-gray-100 font-mono">
                                    {{ number_format($product->selling_price) }} TZS
                                </td>
                                
                                <td class="py-4 px-6 text-center text-emerald-400 font-mono font-semibold">
                                    +{{ number_format($product->selling_price - $product->buying_price) }} TZS
                                </td>
                                
                                <td class="py-4 px-6 text-center">
                                    @if($product->stock_quantity <= $product->low_stock_threshold)
                                        <span class="inline-flex items-center gap-1.5 bg-amber-500/10 border border-amber-500/20 text-amber-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider animate-pulse">
                                            ⚠️ {{ $product->stock_quantity }} Low Stock
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold px-2 py-0.5 rounded-md uppercase tracking-wider">
                                            {{ $product->stock_quantity }} Available
                                        </span>
                                    @endif
                                </td>
                                
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-4">
                                        
                                        <a href="{{ route('merchant.products.edit', $product->id) }}" class="text-xs font-bold text-blue-400 hover:text-blue-300 transition">
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('merchant.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this product from your inventory catalog? This action cannot be undone.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs font-bold text-red-500/70 hover:text-red-400 transition">
                                                Delete
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 px-6 text-center text-gray-500 text-xs tracking-tight">
                                    <div class="text-2xl mb-2">📦</div>
                                    No products found in your catalog database.<br>
                                    Click <span class="text-blue-400 font-medium">"Register New Product"</span> above to build your inventory shelf data.
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