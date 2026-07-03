<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Reports - Sayansi Tech</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { background: white !important; color: black !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .print-card { border: 1px solid #d1d5db !important; background: transparent !important; color: black !important; box-shadow: none !important; }
            .print-text { color: black !important; }
            table { border: 1px solid #cbd5e1 !important; color: black !important; }
            th { background-color: #f1f5f9 !important; color: black !important; border-bottom: 2px solid #cbd5e1 !important; }
            td { border-bottom: 1px solid #e2e8f0 !important; color: black !important; }
        }
    </style>
</head>
<body class="bg-[#0b0f19] text-[#f3f4f6] min-h-screen p-8">

    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-8 border-b border-gray-800 pb-4">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-widest no-print">Analytics &rarr; Central Intelligence</p>
                <h1 class="text-2xl font-bold tracking-tight text-white print-text">REPORT MANAGEMENT PORTAL</h1>
            </div>
            <div class="flex items-center gap-2 no-print">
                <button onclick="exportTableToCSV('compiled_financial_report.csv')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition flex items-center gap-1.5 shadow-lg shadow-emerald-900/20">
                    🟢 Export Excel (CSV)
                </button>
                <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition flex items-center gap-1.5 shadow-lg shadow-blue-900/20">
                    🖨️ Print / Save PDF
                </button>
                <a href="{{ Auth::guard('seller')->check() ? route('seller.dashboard') : route('merchant.dashboard') }}" class="bg-gray-800 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition">
                    &larr; Dashboard
                </a>
            </div>
        </div>

        <div class="bg-[#111827] border border-gray-800 rounded-xl p-4 mb-8 shadow-xl print-card">
            <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-4">
                <div class="no-print">
                    <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Select Timeframe</label>
                    <select name="timeframe" onchange="if(this.value !== 'custom') this.form.submit(); else document.getElementById('customDates').classList.remove('hidden');" class="bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-gray-200 focus:outline-none focus:border-blue-500">
                        <option value="today" {{ $filter === 'today' ? 'selected' : '' }}>📅 Today</option>
                        <option value="yesterday" {{ $filter === 'yesterday' ? 'selected' : '' }}>⏳ Yesterday</option>
                        <option value="week" {{ $filter === 'week' ? 'selected' : '' }}>🗓️ This Week</option>
                        <option value="month" {{ $filter === 'month' ? 'selected' : '' }}>📊 This Month</option>
                        <option value="custom" {{ $filter === 'custom' ? 'selected' : '' }}>⚙️ Custom Range</option>
                    </select>
                </div>

                <div id="customDates" class="{{ $filter === 'custom' ? '' : 'hidden' }} flex gap-4 no-print">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-gray-400 mb-2">End Date</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="bg-[#1f2937] border border-gray-700 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-blue-500">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-4 py-2 rounded-lg transition">
                            Apply
                        </button>
                    </div>
                </div>
                
                <div class="ml-auto text-xs text-gray-400 italic self-center print-text">
                    Reporting Range: {{ $startDate->format('M d, Y H:i') }} to {{ $endDate->format('M d, Y H:i') }}
                </div>
            </form>
        </div>

        <div class="mb-12">
            <h2 class="text-sm font-bold uppercase tracking-widest text-blue-400 mb-4 flex items-center gap-2 print-text">
                📦 TRACK A: PRODUCT INVENTORY FINANCIALS
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">Gross Product Revenue</p>
                    <p class="text-xl font-mono font-bold text-blue-400 print-text">{{ number_format($productGrossSales) }} TZS</p>
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">Product Returns / Losses</p>
                    <p class="text-xl font-mono font-bold text-red-400 print-text">-{{ number_format($productTotalRefunds) }} TZS</p>
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">Net Product Balance</p>
                    <p class="text-xl font-mono font-bold text-emerald-400 print-text">{{ number_format($productNetRevenue) }} TZS</p>
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">🔥 Most Sold Product</p>
                    @if($topProduct)
                        <p class="text-sm font-bold text-white truncate print-text" title="{{ $topProduct->item_name }}">{{ $topProduct->item_name }}</p>
                        <p class="text-[10px] text-gray-500 font-mono mt-1 print-text">Volume: {{ $topProduct->total_qty }} units</p>
                    @else
                        <p class="text-xs text-gray-500 italic print-text">No sales recorded</p>
                    @endif
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl flex flex-col justify-between print-card">
                    <div class="text-[11px] flex justify-between text-gray-400 print-text">
                        <span>💵 Cash:</span> <span class="font-mono text-gray-200 print-text">{{ number_format($productCashIncome) }} TZS</span>
                    </div>
                    <div class="text-[11px] flex justify-between text-gray-400 border-t border-gray-800 pt-1 mt-1 print-text">
                        <span>📱 Lipa No:</span> <span class="font-mono text-purple-400 print-text">{{ number_format($productLipaIncome) }} TZS</span>
                    </div>
                </div>
            </div>

            <div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden shadow-xl print-card">
                <div class="overflow-x-auto">
                    <table id="productTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-800 text-xs uppercase tracking-wider text-gray-400 bg-[#161e2e]">
                                <th class="py-3 px-6">Timestamp</th>
                                <th class="py-3 px-6">Product Description</th>
                                <th class="py-3 px-6">Payment Mode</th>
                                <th class="py-3 px-6 text-right">Unit Price</th>
                                <th class="py-3 px-6 text-center">Qty</th>
                                <th class="py-3 px-6 text-right">Total Net</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-sm font-mono">
                            @forelse($productSalesLogs as $log)
                                <tr class="{{ $log->total_revenue < 0 ? 'bg-red-950/10 text-red-400 print-text' : 'text-gray-300 print-text' }}">
                                    <td class="py-3 px-6 text-gray-500 text-xs print-text">{{ $log->created_at->format('M d, H:i') }}</td>
                                    <td class="py-3 px-6 font-sans font-medium text-gray-200 print-text">{{ $log->item_name }}</td>
                                    <td class="py-3 px-6 text-xs font-sans print-text">
                                        {{ $log->payment_method === 'lipa_number' ? '📱 Lipa No.' : '💵 Cash' }}
                                    </td>
                                    <td class="py-3 px-6 text-right print-text">{{ number_format($log->price_charged) }} TZS</td>
                                    <td class="py-3 px-6 text-center print-text">{{ $log->quantity }}</td>
                                    <td class="py-3 px-6 text-right font-bold print-text">
                                        {{ number_format($log->total_revenue) }} TZS
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500 italic font-sans">No product records logged for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-sm font-bold uppercase tracking-widest text-purple-400 mb-4 flex items-center gap-2 print-text">
                🛠️ TRACK B: SERVICE ACTIVITY FINANCIALS
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">Total Service Volume Generated</p>
                    <p class="text-xl font-mono font-bold text-purple-400 print-text">{{ number_format($serviceGrossRevenue) }} TZS</p>
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1 print-text">🔥 Top Popular Service</p>
                    @if($topService)
                        <p class="text-sm font-bold text-purple-400 truncate print-text" title="{{ $topService->item_name }}">{{ $topService->item_name }}</p>
                        <p class="text-[10px] text-gray-500 font-mono mt-1 print-text">Rendered: {{ $topService->total_count }} times</p>
                    @else
                        <p class="text-xs text-gray-500 italic print-text">No services logs found</p>
                    @endif
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl flex items-center justify-between print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 print-text">💵 Service Cash Flow</p>
                    <p class="text-sm font-mono font-bold text-gray-200 print-text">{{ number_format($serviceCashIncome) }} TZS</p>
                </div>
                <div class="bg-[#111827] border border-gray-800 p-4 rounded-xl shadow-xl flex items-center justify-between print-card">
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400 print-text">📱 Service Lipa Collection</p>
                    <p class="text-sm font-mono font-bold text-purple-400 print-text">{{ number_format($serviceLipaIncome) }} TZS</p>
                </div>
            </div>

            <div class="bg-[#111827] border border-gray-800 rounded-xl overflow-hidden shadow-xl print-card">
                <div class="overflow-x-auto">
                    <table id="serviceTable" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-800 text-xs uppercase tracking-wider text-gray-400 bg-[#161e2e]">
                                <th class="py-3 px-6">Timestamp</th>
                                <th class="py-3 px-6">Service Rendered Description</th>
                                <th class="py-3 px-6">Payment Mode</th>
                                <th class="py-3 px-6 text-right">Price Charged</th>
                                <th class="py-3 px-6 text-center">Count</th>
                                <th class="py-3 px-6 text-right">Subtotal Revenue</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800 text-sm font-mono">
                            @forelse($serviceSalesLogs as $log)
                                <tr class="text-gray-300 print-text">
                                    <td class="py-3 px-6 text-gray-500 text-xs print-text">{{ $log->created_at->format('M d, H:i') }}</td>
                                    <td class="py-3 px-6 font-sans font-medium text-gray-200 print-text">{{ $log->item_name }}</td>
                                    <td class="py-3 px-6 text-xs font-sans print-text">
                                        {{ $log->payment_method === 'lipa_number' ? '📱 Lipa No.' : '💵 Cash' }}
                                    </td>
                                    <td class="py-3 px-6 text-right print-text">{{ number_format($log->price_charged) }} TZS</td>
                                    <td class="py-3 px-6 text-center print-text">{{ $log->quantity }}</td>
                                    <td class="py-3 px-6 text-right text-emerald-400 font-bold print-text">{{ number_format($log->total_revenue) }} TZS</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 text-center text-gray-500 italic font-sans">No manual service workflows tracked for this period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function exportTableToCSV(filename) {
            let csv = [];
            
            csv.push("--- PRODUCT INVENTORY SALES LOGS ---");
            let pRows = document.querySelectorAll("#productTable tr");
            for (let i = 0; i < pRows.length; i++) {
                let row = [], cols = pRows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length; j++) row.push('"' + cols[j].innerText.trim().replace(/"/g, '""') + '"');
                csv.push(row.join(","));
            }
            
            csv.push("\n");
            
            csv.push("--- MANUAL WORKFLOW SERVICE LOGS ---");
            let sRows = document.querySelectorAll("#serviceTable tr");
            for (let i = 0; i < sRows.length; i++) {
                let row = [], cols = sRows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length; j++) row.push('"' + cols[j].innerText.trim().replace(/"/g, '""') + '"');
                csv.push(row.join(","));
            }

            let csvFile = new Blob([csv.join("\n")], { type: "text/csv;charset=utf-8;" });
            let downloadLink = document.createElement("a");
            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
    </script>
</body>
</html>