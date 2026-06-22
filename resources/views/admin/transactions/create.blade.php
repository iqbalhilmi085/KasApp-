@extends('layouts.app')

@section('title', 'Tambah Transaksi')

@section('content')
    <div class="max-w-2xl mx-auto">
        <x-card title="Tambah Transaksi">
            <form
                method="POST"
                action="{{ route('transactions.store') }}"
                enctype="multipart/form-data"
                class="space-y-5"
                x-data="transactionForm()"
            >
                @csrf

                {{-- Tipe --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi</label>
                    <div class="flex gap-2">
                        <template x-for="opt in ['income', 'expense']" :key="opt">
                            <label
                                class="flex-1 flex items-center justify-center gap-2 px-4 py-3 rounded-lg border-2 cursor-pointer transition-all duration-200 font-semibold text-sm"
                                :class="{
                                    'bg-emerald-50 border-emerald-500 text-emerald-700': type === 'income' && opt === 'income',
                                    'bg-red-50 border-red-500 text-red-700': type === 'expense' && opt === 'expense',
                                    'bg-white border-gray-200 text-gray-500 hover:border-gray-300': type !== opt
                                }"
                            >
                                <input type="radio" name="type" :value="opt" x-model="type" class="sr-only" required>
                                <svg x-show="opt === 'income'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                <svg x-show="opt === 'expense'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" />
                                </svg>
                                <span x-text="opt === 'income' ? 'Pemasukan' : 'Pengeluaran'"></span>
                            </label>
                        </template>
                    </div>
                    @error('type') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Kategori --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" id="category_id" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('category_id') border-red-500 @enderror" required>
                        <option value="">Pilih kategori</option>
                        <template x-for="cat in filteredCategories" :key="cat.id">
                            <option :value="cat.id" :selected="'{{ old('category_id') }}' == cat.id" x-text="cat.name"></option>
                        </template>
                    </select>
                    @error('category_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="text-gray-400 font-semibold text-sm">Rp</span>
                        </div>
                        <input
                            type="text"
                            id="amount"
                            name="amount_display"
                            x-model="amountDisplay"
                            @@input="formatAmount($event)"
                            placeholder="0"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('amount') border-red-500 @enderror"
                            required
                        >
                        <input type="hidden" name="amount" x-model="amount">
                    </div>
                    @error('amount') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Tanggal --}}
                <div>
                    <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Transaksi</label>
                    <input type="date" id="transaction_date" name="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('transaction_date') border-red-500 @enderror" required>
                    @error('transaction_date') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- No Referensi --}}
                <div>
                    <label for="reference_number" class="block text-sm font-medium text-gray-700 mb-1">Nomor Referensi <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" id="reference_number" name="reference_number" value="{{ old('reference_number') }}" placeholder="INV/2024/001" class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('reference_number') border-red-500 @enderror">
                    @error('reference_number') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea id="description" name="description" rows="3" placeholder="Keterangan transaksi..." class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#1e3a5f]/20 focus:border-[#1e3a5f] outline-none transition-colors @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Lampiran --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bukti / Attachment <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                            <svg class="w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                            </svg>
                            Pilih File
                            <input type="file" name="attachment" accept=".jpg,.jpeg,.png,.pdf" @@change="previewFile($event)" class="hidden">
                        </label>
                        <span class="text-xs text-gray-400">Maks 2MB. JPG, PNG, PDF</span>
                    </div>
                    <div x-show="attachmentPreview" class="mt-3" x-cloak>
                        <template x-if="attachmentPreview?.type?.startsWith('image/')">
                            <img :src="attachmentPreview.url" class="max-h-32 rounded-lg border border-gray-200 shadow-sm">
                        </template>
                        <template x-if="attachmentPreview && !attachmentPreview.type?.startsWith('image/')">
                            <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg border border-gray-200 text-sm text-gray-600">
                                <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                <span x-text="attachmentPreview.name"></span>
                            </div>
                        </template>
                    </div>
                    @error('attachment') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('transactions.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</a>
                    <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-[#1e3a5f] rounded-lg hover:bg-[#162d4a] transition-colors">Simpan Transaksi</button>
                </div>
            </form>
        </x-card>
    </div>
@endsection

@push('scripts')
<script>
    function transactionForm() {
        return {
            type: '{{ old('type') }}' || 'income',
            amountDisplay: '',
            amount: '',
            attachmentPreview: null,
            categories: {!! json_encode($categories->map(function ($group, $type) {
                return $group->map(function ($cat) {
                    return ['id' => $cat->id, 'name' => $cat->name, 'type' => $cat->type];
                });
            })->flatten(1)->values()) !!},

            get filteredCategories() {
                return this.categories.filter(c => c.type === this.type);
            },

            formatAmount(e) {
                let val = e.target.value.replace(/[^\d]/g, '');
                if (val === '') { this.amountDisplay = ''; this.amount = ''; return; }
                this.amount = val;
                this.amountDisplay = new Intl.NumberFormat('id-ID').format(parseInt(val, 10));
            },

            previewFile(e) {
                const file = e.target.files[0];
                if (!file) { this.attachmentPreview = null; return; }
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (ev) => {
                        this.attachmentPreview = { type: file.type, url: ev.target.result, name: file.name };
                    };
                    reader.readAsDataURL(file);
                } else {
                    this.attachmentPreview = { type: file.type, url: null, name: file.name };
                }
            }
        };
    }
</script>
@endpush
