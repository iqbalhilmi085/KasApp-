@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="space-y-5">
        <h2 class="text-lg font-semibold text-gray-900">Laporan Keuangan</h2>

        {{-- Filter --}}
        <x-card>
            <form method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Bulan</label>
                    <select name="month" class="w-44 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ ($filters['month'] ?? now()->format('m')) == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('id')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tahun</label>
                    <select name="year" class="w-28 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        @foreach (range(now()->year, now()->year - 4, -1) as $y)
                            <option value="{{ $y }}" {{ ($filters['year'] ?? now()->format('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tipe Transaksi</label>
                    <select name="type" class="w-40 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        <option value="">Semua Tipe</option>
                        <option value="income" {{ ($filters['type'] ?? '') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ ($filters['type'] ?? '') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                    <select name="category_id" class="w-44 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">Tampilkan</button>
                    <a href="{{ route('reports.export-pdf', request()->query()) }}" class="px-5 py-2 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Export PDF</a>
                    <a href="{{ route('reports.export-excel', request()->query()) }}" class="px-5 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors">Export Excel</a>
                </div>
            </form>
        </x-card>

        {{-- Periode info --}}
        <p class="text-sm text-gray-500">
            Periode: <span class="font-semibold text-gray-700">{{ $namaBulan }} {{ $year }}</span>
        </p>

        {{-- Summary cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pemasukan</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1 tabular-nums">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengeluaran</p>
                <p class="text-2xl font-bold text-red-600 mt-1 tabular-nums">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Net Cash Flow</p>
                <p class="text-2xl font-bold mt-1 tabular-nums {{ $netCashFlow >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                    Rp {{ number_format($netCashFlow, 0, ',', '.') }}
                </p>
            </div>
        </div>

        {{-- Ringkasan per kategori --}}
        <x-card title="Ringkasan per Kategori" :padding="false">
            @if ($categorySummary->isNotEmpty())
                @php
                    $incomeGroup = $categorySummary->where('type', 'income');
                    $expenseGroup = $categorySummary->where('type', 'expense');
                    $grandTotal = $categorySummary->sum('total');
                @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Kategori</th>
                                <th class="text-center px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tipe</th>
                                <th class="text-right px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Jumlah Transaksi</th>
                                <th class="text-right px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Total Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            {{-- Pemasukan --}}
                            @if ($incomeGroup->isNotEmpty())
                                <tr class="bg-emerald-50/50">
                                    <td colspan="4" class="px-5 py-2.5 text-xs font-bold text-emerald-700 uppercase tracking-wider">Pemasukan</td>
                                </tr>
                                @foreach ($incomeGroup as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3 text-gray-900">{{ $item->category->name }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">MASUK</span>
                                        </td>
                                        <td class="px-5 py-3 text-right text-gray-600 tabular-nums">{{ $item->count }}</td>
                                        <td class="px-5 py-3 text-right font-semibold text-emerald-600 tabular-nums">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-emerald-100/30 font-semibold">
                                    <td colspan="2" class="px-5 py-2.5 text-right text-emerald-800 text-xs uppercase">Subtotal Pemasukan</td>
                                    <td class="px-5 py-2.5 text-right text-emerald-800 tabular-nums">{{ $incomeGroup->sum('count') }}</td>
                                    <td class="px-5 py-2.5 text-right text-emerald-800 tabular-nums">Rp {{ number_format($incomeGroup->sum('total'), 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            {{-- Pengeluaran --}}
                            @if ($expenseGroup->isNotEmpty())
                                <tr class="bg-red-50/50">
                                    <td colspan="4" class="px-5 py-2.5 text-xs font-bold text-red-700 uppercase tracking-wider">Pengeluaran</td>
                                </tr>
                                @foreach ($expenseGroup as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-3 text-gray-900">{{ $item->category->name }}</td>
                                        <td class="px-5 py-3 text-center">
                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700">KELUAR</span>
                                        </td>
                                        <td class="px-5 py-3 text-right text-gray-600 tabular-nums">{{ $item->count }}</td>
                                        <td class="px-5 py-3 text-right font-semibold text-red-600 tabular-nums">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                                <tr class="bg-red-100/30 font-semibold">
                                    <td colspan="2" class="px-5 py-2.5 text-right text-red-800 text-xs uppercase">Subtotal Pengeluaran</td>
                                    <td class="px-5 py-2.5 text-right text-red-800 tabular-nums">{{ $expenseGroup->sum('count') }}</td>
                                    <td class="px-5 py-2.5 text-right text-red-800 tabular-nums">Rp {{ number_format($expenseGroup->sum('total'), 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            {{-- Grand total --}}
                            <tr class="bg-gray-100 font-bold">
                                <td colspan="2" class="px-5 py-3 text-right text-gray-900 text-xs uppercase">Grand Total</td>
                                <td class="px-5 py-3 text-right text-gray-900 tabular-nums">{{ $categorySummary->sum('count') }}</td>
                                <td class="px-5 py-3 text-right text-gray-900 tabular-nums">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-sm text-gray-400">Tidak ada data transaksi untuk periode ini</div>
            @endif
        </x-card>

        {{-- Detail transaksi --}}
        <x-card title="Detail Transaksi" :padding="false">
            @if ($transactions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tanggal</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Ref#</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Deskripsi</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Kategori</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tipe</th>
                                <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transactions as $t)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-700 whitespace-nowrap">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $t->reference_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-900 max-w-[200px] truncate">{{ $t->description ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $t->category->name }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $t->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $t->type === 'income' ? 'MASUK' : 'KELUAR' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold whitespace-nowrap tabular-nums {{ $t->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $t->type === 'income' ? '+' : '-' }}{{ $t->formatted_amount }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                                <td colspan="4" class="px-4 py-3 text-xs text-gray-600 uppercase tracking-wider">Total</td>
                                <td class="px-4 py-3 text-center text-xs text-gray-600">{{ $transactions->total() }} transaksi</td>
                                <td class="px-4 py-3 text-right text-gray-900 tabular-nums">Rp {{ number_format($transactions->sum('amount'), 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="p-8 text-center text-sm text-gray-400">Tidak ada transaksi untuk periode ini</div>
            @endif
        </x-card>

        <x-pagination :paginator="$transactions" />
    </div>
@endSection
