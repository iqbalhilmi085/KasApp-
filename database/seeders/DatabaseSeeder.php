<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@kasapp.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        $categories = [
            ['name' => 'Penjualan', 'type' => 'income', 'description' => 'Hasil penjualan produk atau jasa'],
            ['name' => 'Investasi', 'type' => 'income', 'description' => 'Pendapatan dari investasi'],
            ['name' => 'Pembelian', 'type' => 'expense', 'description' => 'Pembelian barang atau perlengkapan'],
            ['name' => 'Gaji', 'type' => 'expense', 'description' => 'Gaji karyawan'],
            ['name' => 'Listrik & Air', 'type' => 'expense', 'description' => 'Tagihan listrik dan air'],
            ['name' => 'Transportasi', 'type' => 'expense', 'description' => 'Biaya transportasi dan perjalanan'],
            ['name' => 'Makanan & Minuman', 'type' => 'expense', 'description' => 'Konsumsi harian'],
            ['name' => 'Lainnya', 'type' => 'expense', 'description' => 'Pengeluaran lainnya'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
