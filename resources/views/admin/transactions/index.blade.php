@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="space-y-5">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Transaksi</h2>
            <a href="{{ route('transactions.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Transaksi
            </a>
        </div>

        {{-- Filter bar --}}
        <x-card>
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Sampai</label>
                    <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Tipe</label>
                    <select name="type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        <option value="">Semua Tipe</option>
                        <option value="income" {{ ($filters['type'] ?? '') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="expense" {{ ($filters['type'] ?? '') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                    <select name="category_id" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ ($filters['category_id'] ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-500 mb-1">Pencarian</label>
                    <input type="text" name="search" placeholder="Deskripsi / No. Ref..." value="{{ $filters['search'] ?? '' }}" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors">
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">Filter</button>
                    <a href="{{ route('transactions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Reset</a>
                </div>
            </form>
        </x-card>

        {{-- Summary bar --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pemasukan</p>
                <p class="text-xl font-bold text-emerald-600 mt-1 tabular-nums">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pengeluaran</p>
                <p class="text-xl font-bold text-red-600 mt-1 tabular-nums">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Selisih</p>
                <p class="text-xl font-bold text-blue-600 mt-1 tabular-nums">Rp {{ number_format($selisih, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- Tabel --}}
        <x-card :padding="false">
            @if ($transactions->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider w-12">No</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tanggal</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Ref#</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Deskripsi</th>
                                <th class="text-left px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Kategori</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tipe</th>
                                <th class="text-right px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Jumlah</th>
                                <th class="text-center px-4 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($transactions as $t)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3.5 text-gray-400 text-xs">{{ $loop->iteration }}</td>
                                    <td class="px-4 py-3.5 text-gray-700 whitespace-nowrap">{{ $t->transaction_date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3.5 text-gray-500 text-xs font-mono">{{ $t->reference_number ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-gray-900 max-w-[180px] truncate">{{ $t->description ?? '-' }}</td>
                                    <td class="px-4 py-3.5 text-gray-600">{{ $t->category->name }}</td>
                                    <td class="px-4 py-3.5 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded-full {{ $t->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $t->type === 'income' ? 'MASUK' : 'KELUAR' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-right font-semibold whitespace-nowrap tabular-nums {{ $t->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $t->type === 'income' ? '+' : '-' }}{{ $t->formatted_amount }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('transactions.show', $t->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Detail">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('transactions.edit', $t->id) }}" class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                            <button onclick="window.showModalDelete{{ $t->id }}()" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                            <x-modal-confirm id="Delete{{ $t->id }}" title="Hapus Transaksi" message="Yakin ingin menghapus transaksi ini?" formAction="{{ route('transactions.destroy', $t->id) }}" />
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                    </svg>
                    <p class="text-sm text-gray-400">Tidak ada data transaksi</p>
                    @if (array_filter($filters))
                        <p class="text-xs text-gray-400 mt-1">Coba ubah filter pencarian</p>
                    @endif
                </div>
            @endif
        </x-card>

        <x-pagination :paginator="$transactions" />
    </div>
@endSection
