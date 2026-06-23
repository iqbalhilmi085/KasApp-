<?php

namespace App\Http\Controllers;

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
        $query = Transaction::with(['user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('description', 'like', "%{$search}%");
        }

        $transactions = $query->latest()->paginate(15)->withQueryString();

        $totalIncome = (float) (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (float) (clone $query)->where('type', 'expense')->sum('amount');
        $selisih = $totalIncome - $totalExpense;

        $filters = $request->only(['type', 'date_from', 'date_to', 'search']);

        return view('admin.transactions.index', compact(
            'transactions', 'filters',
            'totalIncome', 'totalExpense', 'selisih'
        ));
    }

    public function create(): View
    {
        return view('admin.transactions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')
                ->store('attachments', 'public');
        }

        $validated['user_id'] = auth()->id();

        $transaction = Transaction::create($validated);

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil ditambahkan.');
    }

    public function show($id): View
    {
        $transaction = Transaction::with(['user'])->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function edit($id): View
    {
        $transaction = Transaction::findOrFail($id);

        return view('admin.transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $transaction = Transaction::findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'in:income,expense'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999'],
            'description' => ['nullable', 'string', 'max:500'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'attachment' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        $oldValues = $transaction->only([
            'type', 'amount', 'description',
            'transaction_date', 'attachment',
        ]);

        if ($request->hasFile('attachment')) {
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }

            $validated['attachment'] = $request->file('attachment')
                ->store('attachments', 'public');
        }

        $transaction->update($validated);

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

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
        }

        return redirect()
            ->route('transactions.index')
            ->with('success', 'Transaksi berhasil dihapus.');
    }
}
