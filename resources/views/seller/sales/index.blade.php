<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Terminal - Sayansi Tech</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#0b0f19] text-[#f3f4f6] min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest">Terminal &rarr; Sales Desk</p>
                <h1 class="text-2xl font-bold tracking-tight">REAL-TIME TRANSACTION TERMINAL</h1>
            </div>
            <div>
                <button id="typeToggle" onclick="toggleMode()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition shadow-lg shadow-blue-900/20">
                    🔄 Switch to Service Mode
                </button>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-xl p-4 mb-8 shadow-xl">
            <h3 class="text-xs font-bold uppercase tracking-wider text-blue-400 mb-2">Multi-Day Return & Exchange Lookup</h3>
            <p class="text-xs text-gray-400 mb-3">Select the day the purchase was made to view that day's transaction log records:</p>
            <div class="flex gap-2">
                <input type="date" id="receiptQueryInput" class="flex-1 bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                <button onclick="lookupInvoiceTicket()" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition shadow-lg shadow-blue-900/20">
                    Load Day's Ledger
                </button>
            </div>
            
            <div id="receiptSearchResultsTable" class="mt-4 hidden border-t border-gray-800 pt-3 space-y-2">
                <p id="resultsHeaderDate" class="text-[11px] text-gray-400 font-semibold uppercase tracking-wider">Transactions on that Day:</p>
                <div id="searchResultsInjectionTarget" class="space-y-2 text-sm max-h-60 overflow-y-auto pr-1">
                </div>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-xl p-4 mb-8 shadow-xl">
            <form id="salesForm" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                <div id="itemInputContainer" class="md:col-span-1">
                    <label id="inputLabel" class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Select Product</label>
                    
                    <select id="productSelect" onchange="updatePrice()" class="w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500">
                        <option value="">-- Choose Item --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                {{ $product->name }} [{{ $product->brand ?? 'Generic' }}] — ({{ $product->stock_quantity }} Left)
                            </option>
                        @endforeach
                    </select>

                    <input type="text" id="serviceInput" placeholder="e.g., Phone Screen Repair" class="hidden w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500" />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Price Charged (TZS)</label>
                    <input type="number" id="priceInput" placeholder="0.00" class="w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500 font-mono text-emerald-400" />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Quantity</label>
                    <input type="number" id="qtyInput" value="1" min="1" class="w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500 font-mono" />
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Payment Method</label>
                    <select id="paymentMethodSelect" class="w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500">
                        <option value="cash" selected>💵 Cash</option>
                        <option value="lipa_number">📱 Lipa Number</option>
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm py-2 px-4 rounded-lg transition shadow-lg shadow-emerald-900/20">
                        + Log Entry (Enter)
                    </button>
                </div>

            </form>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden shadow-xl mb-8">
            <div class="px-6 py-4 border-b border-gray-800 bg-[#161e2e] flex justify-between items-center">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-gray-300">Today's Ledger Spreadsheet</h2>
                <span class="bg-emerald-950 text-emerald-400 text-xs px-2.5 py-1 rounded-full font-medium border border-emerald-800/50 animate-pulse">
                    ● Live Feed
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-800 text-xs uppercase tracking-wider text-gray-400 bg-[#111827]">
                            <th class="py-3 px-6">Timestamp</th>
                            <th class="py-3 px-6">Type</th>
                            <th class="py-3 px-6">Item Description</th>
                            <th class="py-3 px-6">Payment</th>
                            <th class="py-3 px-6 text-right">Unit Price</th>
                            <th class="py-3 px-6 text-center">Qty</th>
                            <th class="py-3 px-6 text-right">Total Revenue</th>
                        </tr>
                    </thead>
                    <tbody id="spreadsheetBody" class="divide-y divide-gray-800 text-sm">
                        @forelse($sales as $sale)
                            <tr class="hover:bg-[#1f2937]/30 transition font-mono {{ $sale->transaction_type === 'return' ? 'bg-red-950/10 text-red-400' : '' }}">
                                <td class="py-3 px-6 text-gray-500 text-xs">{{ $sale->created_at->format('H:i:s') }}</td>
                                <td class="py-3 px-6">
                                    @if($sale->transaction_type === 'return')
                                        <span class="px-2 py-0.5 rounded text-xs font-sans bg-red-950 text-red-400 border border-red-900">
                                            Return
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-xs font-sans {{ $sale->item_type === 'product' ? 'bg-blue-950 text-blue-400 border border-blue-900' : 'bg-purple-950 text-purple-400 border border-purple-900' }}">
                                            {{ ucfirst($sale->item_type) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 font-sans font-medium {{ $sale->transaction_type === 'return' ? 'text-red-300 italic' : 'text-gray-200' }}">
                                    {{ $sale->item_name }}
                                </td>
                                <td class="py-3 px-6 font-sans text-xs">
                                    @if($sale->payment_method === 'lipa_number')
                                        <span class="text-purple-400 font-medium">📱 Lipa No.</span>
                                    @else
                                        <span class="text-gray-400 font-medium">💵 Cash</span>
                                    @endif
                                </td>
                                <td class="py-3 px-6 text-right {{ $sale->transaction_type === 'return' ? 'text-red-400' : 'text-emerald-400' }}">
                                    {{ number_format($sale->price_charged) }} TZS
                                </td>
                                <td class="py-3 px-6 text-center text-gray-300">{{ $sale->quantity }}</td>
                                <td class="py-3 px-6 text-right font-bold {{ $sale->transaction_type === 'return' ? 'text-red-400' : 'text-emerald-400' }}">
                                    {{ $sale->total_revenue < 0 ? '-' : '' }}{{ number_format(abs($sale->total_revenue)) }} TZS
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="7" class="py-8 text-center text-gray-500 font-sans italic">
                                    No transactions logged yet today. Type above and hit enter to log a sale!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-[#161e2e] border-t border-gray-800 px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex gap-4 text-xs text-gray-400">
                    <div>Product Income: <span id="prodTotal" class="text-blue-400 font-mono font-bold">{{ number_format($sales->where('item_type', 'product')->sum('total_revenue')) }} TZS</span></div>
                    <div class="border-l border-gray-700 h-4"></div>
                    <div>Service Income: <span id="servTotal" class="text-purple-400 font-mono font-bold">{{ number_format($sales->where('item_type', 'service')->sum('total_revenue')) }} TZS</span></div>
                </div>
                <div class="text-sm font-medium text-gray-300">
                    Total Sales Income: <span id="grandTotal" class="text-xl font-black font-mono text-emerald-400 ml-2">{{ number_format($sales->sum('total_revenue')) }} TZS</span>
                </div>
            </div>
        </div>

    </div>

    <script>
        let currentMode = 'product'; 

        function toggleMode() {
            const toggleBtn = document.getElementById('typeToggle');
            const label = document.getElementById('inputLabel');
            const productSelect = document.getElementById('productSelect');
            const serviceInput = document.getElementById('serviceInput');
            const priceInput = document.getElementById('priceInput');

            if (currentMode === 'product') {
                currentMode = 'service';
                toggleBtn.innerText = '🔄 Switch to Product Mode';
                toggleBtn.classList.replace('bg-blue-600', 'bg-purple-600');
                label.innerText = 'Service Description';
                productSelect.classList.add('hidden');
                serviceInput.classList.remove('hidden');
                priceInput.value = ''; 
                serviceInput.focus();
            } else {
                currentMode = 'product';
                toggleBtn.innerText = '🔄 Switch to Service Mode';
                toggleBtn.classList.replace('bg-purple-600', 'bg-blue-600');
                label.innerText = 'Select Product';
                productSelect.className = 'w-full bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500';
                serviceInput.classList.add('hidden');
                updatePrice(); 
            }
        }

        function updatePrice() {
            if (currentMode !== 'product') return;
            const select = document.getElementById('productSelect');
            const priceInput = document.getElementById('priceInput');
            const selectedOption = select.options[select.selectedIndex];
            
            if (selectedOption && selectedOption.dataset.price) {
                priceInput.value = Math.round(selectedOption.dataset.price);
            } else {
                priceInput.value = '';
            }
        }

        document.getElementById('salesForm').addEventListener('submit', function(e) {
            e.preventDefault(); 

            const productSelect = document.getElementById('productSelect');
            const serviceInput = document.getElementById('serviceInput');
            const priceInput = document.getElementById('priceInput');
            const qtyInput = document.getElementById('qtyInput');
            const paymentSelect = document.getElementById('paymentMethodSelect');

            let payload = {
                item_type: currentMode,
                price_charged: priceInput.value,
                quantity: qtyInput.value,
                product_id: currentMode === 'product' ? productSelect.value : null,
                service_name: currentMode === 'service' ? serviceInput.value : null,
                payment_method: paymentSelect.value
            };

            fetch("{{ route('seller.sales.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify(payload)
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Something went wrong');
                return data;
            })
            .then(data => {
                const emptyRow = document.getElementById('emptyRow');
                if (emptyRow) emptyRow.remove();

                const tbody = document.getElementById('spreadsheetBody');
                const badgeColor = data.type === 'product' ? 'bg-blue-950 text-blue-400 border border-blue-900' : 'bg-purple-950 text-purple-400 border border-purple-900';
                
                const paymentBadge = data.payment_method === 'lipa_number' ? '<span class="text-purple-400 font-medium">📱 Lipa No.</span>' : '<span class="text-gray-400 font-medium">💵 Cash</span>';

                const newRow = `
                    <tr class="hover:bg-[#1f2937]/30 transition font-mono animate-fadeIn bg-emerald-950/20">
                        <td class="py-3 px-6 text-gray-500 text-xs">${data.time}</td>
                        <td class="py-3 px-6">
                            <span class="px-2 py-0.5 rounded text-xs font-sans ${badgeColor}">
                                ${data.type.charAt(0).toUpperCase() + data.type.slice(1)}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-gray-200 font-sans font-medium">${data.item_name}</td>
                        <td class="py-3 px-6 font-sans text-xs">${paymentBadge}</td>
                        <td class="py-3 px-6 text-right text-emerald-400">${data.price_charged}</td>
                        <td class="py-3 px-6 text-center text-gray-300">${data.quantity}</td>
                        <td class="py-3 px-6 text-right text-emerald-400 font-bold">${data.total_revenue}</td>
                    </tr>
                `;
                
                tbody.insertAdjacentHTML('afterbegin', newRow);

                document.getElementById('prodTotal').innerText = data.prod_total;
                document.getElementById('servTotal').innerText = data.serv_total;
                document.getElementById('grandTotal').innerText = data.grand_total;

                if(currentMode === 'product') {
                    productSelect.value = '';
                } else {
                    serviceInput.value = '';
                }
                priceInput.value = '';
                qtyInput.value = '1';
                paymentSelect.value = 'cash'; // Reset to cash default choice
            })
            .catch(error => {
                alert('⚠️ Error: ' + error.message);
            });
        });

        function lookupInvoiceTicket() {
            const dateStr = document.getElementById('receiptQueryInput').value;
            if (!dateStr) return alert('Please select a calendar date first.');

            fetch(`/seller/returns/search?query=${dateStr}`)
                .then(res => res.json())
                .then(data => {
                    const resultsBox = document.getElementById('receiptSearchResultsTable');
                    const listTarget = document.getElementById('searchResultsInjectionTarget');
                    const headerText = document.getElementById('resultsHeaderDate');
                    listTarget.innerHTML = '';

                    if (data.success) {
                        resultsBox.classList.remove('hidden');
                        headerText.innerText = `Transactions recorded on [ ${dateStr} ] :`;
                        
                        data.items.forEach(item => {
                            let actionMarkup = '';
                            if (item.is_refundable) {
                                actionMarkup = `
                                    <div class="flex gap-2">
                                        <button onclick="processReturnAdjustment(${item.id}, 'resalable')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1 rounded-lg text-xs transition">Return (Restock)</button>
                                        <button onclick="processReturnAdjustment(${item.id}, 'damaged')" class="bg-red-600 hover:bg-red-700 text-white font-bold px-3 py-1 rounded-lg text-xs transition">Return (Damaged)</button>
                                    </div>
                                `;
                            } else {
                                actionMarkup = `<span class="text-xs text-gray-500 italic font-medium bg-gray-900 px-2 py-1 rounded-md">Already Refunded</span>`;
                            }

                            const payMethodLabel = item.payment_method === 'lipa_number' ? '📱 Lipa No.' : '💵 Cash';

                            listTarget.innerHTML += `
                                <div class="flex justify-between items-center bg-[#1f2937]/50 p-3 border border-gray-800 rounded-lg">
                                    <div>
                                        <p class="text-gray-200 font-bold">${item.item_name} (x${item.quantity})</p>
                                        <p class="text-xs text-gray-400">Logged at: ${item.date} Hours | Payment: ${payMethodLabel} | Total Paid: ${Number(item.total).toLocaleString()} TZS</p>
                                    </div>
                                    <div>${actionMarkup}</div>
                                </div>
                                `;
                        });
                    } else {
                        resultsBox.classList.add('hidden');
                        alert(data.message || 'No transaction logs discovered for this date.');
                    }
                })
                .catch(err => alert('⚠️ Day fetch failed: ' + err.message));
        }

        function processReturnAdjustment(saleId, itemCondition) {
            if (!confirm(`Confirm processing return as [${itemCondition.toUpperCase()}]? This will append a negative balance row to today's spreadsheet ledger log.`)) return;

            fetch('/seller/returns/process', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ sale_id: saleId, condition: itemCondition })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload(); 
                } else {
                    alert(data.message);
                }
            })
            .catch(err => alert('⚠️ Failed to complete transaction return step: ' + err.message));
        }
    </script>
</body>
</html>