<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\Category;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $query = Transaction::with('category')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $totalIncome = (float) Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = (float) Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('type', 'expense')
            ->sum('amount');

        $netCashFlow = $totalIncome - $totalExpense;

        $transactions = $query->latest()->paginate(20)->withQueryString();

        $categorySummary = Transaction::with('category')
            ->selectRaw('category_id, type, COUNT(*) as count, SUM(amount) as total')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->groupBy('category_id', 'type')
            ->orderBy('type')
            ->orderBy('total', 'desc')
            ->get();

        $categories = Category::active()->get();
        $filters = $request->only(['month', 'year', 'type', 'category_id']);
        $namaBulan = Carbon::create()->month((int) $month)->locale('id')->translatedFormat('F');

        $view = $request->routeIs('reports.public.*') ? 'reports.index' : 'admin.reports.index';

        return view($view, compact(
            'transactions',
            'totalIncome',
            'totalExpense',
            'netCashFlow',
            'categorySummary',
            'categories',
            'filters',
            'month',
            'year',
            'namaBulan'
        ));
    }

    public function exportPdf(Request $request, $bulan = null, $tahun = null)
    {
        $month = $bulan ?? $request->input('month', now()->format('m'));
        $year = $tahun ?? $request->input('year', now()->format('Y'));

        $data = $this->getReportData($request, $month, $year);

        $view = $request->routeIs('reports.public.*') ? 'reports.pdf' : 'admin.reports.pdf';

        $pdf = Pdf::loadView($view, $data);

        $namaBulan = Carbon::create()->month((int) $month)->locale('id')->translatedFormat('F');

        return $pdf->download("laporan-kas-{$namaBulan}-{$year}.pdf");
    }

    public function exportExcel(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $data = $this->getReportData($request, $month, $year);

        $namaBulan = Carbon::create()->month((int) $month)->locale('id')->translatedFormat('F');

        return Excel::download(
            new ReportExport($data),
            "laporan-kas-{$namaBulan}-{$year}.xlsx"
        );
    }

    private function getReportData(Request $request, string $month, string $year): array
    {
        $query = Transaction::with('category', 'user')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $totalIncome = (float) Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('type', 'income')
            ->sum('amount');

        $totalExpense = (float) Transaction::whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->where('type', 'expense')
            ->sum('amount');

        $transactions = $query->latest()->get();

        $categorySummary = Transaction::with('category')
            ->selectRaw('category_id, type, COUNT(*) as count, SUM(amount) as total')
            ->whereMonth('transaction_date', $month)
            ->whereYear('transaction_date', $year)
            ->groupBy('category_id', 'type')
            ->orderBy('type')
            ->orderBy('total', 'desc')
            ->get();

        $namaBulan = Carbon::create()->month((int) $month)->locale('id')->translatedFormat('F');

        return compact('transactions', 'totalIncome', 'totalExpense', 'month', 'year', 'categorySummary', 'namaBulan');
    }
}
