@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        {{-- Section 1: Kartu Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-card>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Saldo Kas Saat Ini</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1 tracking-tight tabular-nums">
                            Rp {{ number_format($totalSaldo, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pemasukan Bulan Ini</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1 tracking-tight tabular-nums">
                            Rp {{ number_format($totalPemasukanBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Pengeluaran Bulan Ini</p>
                        <p class="text-2xl font-bold text-red-600 mt-1 tracking-tight tabular-nums">
                            Rp {{ number_format($totalPengeluaranBulanIni, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.886c.429 0 .812.305.882.712l.856 4.908c.101.582.44 1.1.904 1.464l4.436 3.482c.22.174.48.272.754.272H18.25M15.75 21h-9a.75.75 0 0 1-.75-.75v-1.5a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 .75.75v1.5a.75.75 0 0 1-.75.75ZM9 18.75a.75.75 0 0 0 0 1.5h6a.75.75 0 0 0 0-1.5H9Z" />
                        </svg>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Transaksi</p>
                        <p class="text-2xl font-bold text-indigo-600 mt-1 tracking-tight tabular-nums">
                            {{ $totalTransaksiBulanIni }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Section 2: Grafik --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <div class="lg:col-span-3">
                <x-card title="Arus Kas 6 Bulan Terakhir">
                    <canvas id="lineChart" height="120"></canvas>
                </x-card>
            </div>
            <div class="lg:col-span-2">
                <x-card title="Pengeluaran per Kategori">
                    <p class="text-sm text-gray-400 text-center py-8">Fitur kategori sedang dinonaktifkan</p>
                </x-card>
            </div>
        </div>

        {{-- Section 3: Transaksi Terbaru --}}
        <x-card title="Transaksi Terbaru" :padding="false">
            @if ($transaksiTerbaru->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-6 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tanggal</th>
                                <th class="text-left px-6 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Deskripsi</th>
                                <th class="text-left px-6 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tipe</th>
                                <th class="text-right px-6 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transaksiTerbaru as $t)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-3.5 text-gray-700 whitespace-nowrap">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3.5 text-gray-900 max-w-[200px] truncate">{{ $t->description ?? '-' }}</td>
                                    <td class="px-6 py-3.5">
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full {{ $t->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-semibold whitespace-nowrap tabular-nums {{ $t->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $t->type === 'income' ? '+' : '-' }}{{ $t->formatted_amount }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-[#1e3a5f] hover:text-[#162d4a] transition-colors">
                        Lihat Semua Transaksi
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            @else
                <div class="p-6 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    <p class="text-sm text-gray-400">Belum ada transaksi</p>
                </div>
            @endif
        </x-card>

        {{-- Section 4: Quick Actions --}}
        <div>
            <h3 class="text-base font-semibold text-gray-900 mb-3">Aksi Cepat</h3>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('transactions.create') }}?type=income"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 shadow-sm shadow-emerald-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Pemasukan
                </a>
                <a href="{{ route('transactions.create') }}?type=expense"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm shadow-red-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                    </svg>
                    Tambah Pengeluaran
                </a>
                <a href="{{ route('reports.export-pdf', ['month' => now()->format('m'), 'year' => now()->format('Y')]) }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm transition-colors">
                    <svg class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Laporan Bulan Ini
                </a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = {!! json_encode($chartData) !!};
        const colors = ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#8b5cf6'];

        {{-- Line Chart --}}
        const lineCtx = document.getElementById('lineChart');
        if (lineCtx) {
            new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: chartData.income,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.08)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 2.5,
                        },
                        {
                            label: 'Pengeluaran',
                            data: chartData.expense,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.08)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            borderWidth: 2.5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { family: 'Inter' }
                            }
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Inter' },
                            bodyFont: { family: 'Inter' },
                            callbacks: {
                                label: function(ctx) {
                                    return 'Rp ' + Number(ctx.parsed.y).toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                font: { family: 'Inter' },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter' } }
                        }
                    }
                }
            });
        }


    });
</script>
@endpush
