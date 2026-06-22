<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CashBalance extends Model
{
    protected $fillable = [
        'balance',
        'last_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
            'last_updated_at' => 'datetime',
        ];
    }

    public static function getCurrentBalance(): float
    {
        $record = self::latest()->first();

        return $record ? (float) $record->balance : 0;
    }

    public static function recalculate(): void
    {
        $income = (float) Transaction::where('type', 'income')->sum('amount');
        $expense = (float) Transaction::where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        self::updateOrCreate(
            ['id' => 1],
            [
                'balance' => $balance,
                'last_updated_at' => now(),
            ]
        );
    }
}
