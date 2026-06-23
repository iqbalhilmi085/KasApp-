@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <div class="max-w-2xl mx-auto">
        <x-card title="Detail Transaksi">
            <dl class="space-y-4">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Tipe</dt>
                    <dd>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full {{ $transaction->type === 'income' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                            {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                        </span>
                    </dd>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Jumlah</dt>
                    <dd class="text-sm font-bold {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-red-600' }}">{{ $transaction->formatted_amount }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Tanggal</dt>
                    <dd class="text-sm font-medium text-gray-900">{{ $transaction->transaction_date->format('d F Y') }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Keterangan</dt>
                    <dd class="text-sm text-gray-700 max-w-[300px] text-right">{{ $transaction->description ?? '-' }}</dd>
                </div>

                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Input oleh</dt>
                    <dd class="text-sm text-gray-700">{{ $transaction->user->name }}</dd>
                </div>
                @if ($transaction->attachment)
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="text-sm text-gray-500">Lampiran</dt>
                    <dd>
                        <a href="{{ Storage::url($transaction->attachment) }}" target="_blank" class="text-sm text-blue-600 hover:underline">Lihat Lampiran</a>
                    </dd>
                </div>
                @endif
            </dl>

            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Kembali</a>
                <a href="{{ route('transactions.edit', $transaction->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a]">Edit</a>
            </div>
        </x-card>
    </div>
@endsection
