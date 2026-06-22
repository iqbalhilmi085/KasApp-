<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $query = Transaction::with(['category', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('reference_number', 'like', "%{$search}%");
            });
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $totalIncome = (float) (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (float) (clone $query)->where('type', 'expense')->sum('amount');
        $selisih = $totalIncome - $totalExpense;

        $categories = Category::active()->get();
        $filters = $request->only(['type', 'category_id', 'date_from', 'date_to', 'search']);

        return view('admin.transactions.index', compact(
            'transactions', 'categories', 'filters',
            'totalIncome', 'totalExpense', 'selisih'
        ));
    }

    public function create(): View
    {
        $categories = Category::active()->get()->groupBy('type');

        return view('admin.transactions.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'reference_number' => ['nullable', 'string', 'max:50', 'unique:transactions'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments', 'public');
        }

        $validated['user_id'] = auth()->id();

        $transaction = Transaction::create($validated);

        ActivityLog::record('transaksi.create', $transaction);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show($id): View
    {
        $transaction = Transaction::with(['category', 'user'])->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id): View
    {
        $transaction = Transaction::findOrFail($id);
        $categories = Category::active()->get()->groupBy('type');

        return view('admin.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'category_id' => ['required', 'exists:categories,id'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'reference_number' => [
                'nullable', 'string', 'max:50',
                Rule::unique('transactions')->ignore($transaction->id),
            ],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $oldValues = $transaction->only([
            'type', 'category_id', 'amount', 'description',
            'reference_number', 'transaction_date', 'attachment',
        ]);

        if ($request->hasFile('attachment')) {
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }

            $validated['attachment'] = $request->file('attachment')
                ->store('attachments', 'public');
        }

        $transaction->update($validated);

        ActivityLog::record('transaksi.update', $transaction, $oldValues, $transaction->only([
            'type', 'category_id', 'amount', 'description',
            'reference_number', 'transaction_date', 'attachment',
        ]));

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id): JsonResponse|RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
        }

        $transaction->delete();

        ActivityLog::record('transaksi.delete', $transaction);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
