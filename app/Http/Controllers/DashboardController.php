<?php

namespace App\Http\Controllers;

use App\Models\CashBalance;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSaldo = CashBalance::getCurrentBalance();

        $totalPemasukanBulanIni = (float) Transaction::income()->thisMonth()->sum('amount');
        $totalPengeluaranBulanIni = (float) Transaction::expense()->thisMonth()->sum('amount');
        $totalTransaksiBulanIni = Transaction::thisMonth()->count();

        $chartData = $this->getChartData();

        $transaksiTerbaru = Transaction::latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSaldo',
            'totalPemasukanBulanIni',
            'totalPengeluaranBulanIni',
            'totalTransaksiBulanIni',
            'chartData',
            'transaksiTerbaru'
        ));
    }

    public function refreshBalance(): JsonResponse
    {
        CashBalance::recalculate();

        return response()->json([
            'balance' => CashBalance::getCurrentBalance(),
            'formatted' => 'Rp ' . number_format(CashBalance::getCurrentBalance(), 0, ',', '.'),
        ]);
    }

    private function getChartData(): array
    {
        $labels = [];
        $income = [];
        $expense = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = (int) $date->format('m');
            $year = (int) $date->format('Y');

            $labels[] = $date->translatedFormat('M Y');
            $income[] = (float) Transaction::where('type', 'income')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
            $expense[] = (float) Transaction::where('type', 'expense')
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
        }

        return [
            'labels' => $labels,
            'income' => $income,
            'expense' => $expense,
        ];
    }
}
