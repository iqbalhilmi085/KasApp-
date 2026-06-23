<table>
    <thead>
        <tr>
            <th style="background-color: #1e3a5f; color: #ffffff; font-weight: bold; font-size: 12px; text-align: center; padding: 8px;" colspan="4">
                LAPORAN ARUS KAS - {{ strtoupper($namaBulan) }} {{ $year }}
            </th>
        </tr>
        <tr>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #cbd5e1; padding: 6px;">Tanggal</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #cbd5e1; padding: 6px;">Deskripsi</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #cbd5e1; padding: 6px;">Tipe</th>
            <th style="background-color: #f1f5f9; font-weight: bold; border: 1px solid #cbd5e1; padding: 6px; text-align: right;">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $t)
            <tr>
                <td style="border: 1px solid #e2e8f0; padding: 5px;">{{ $t->transaction_date->format('d/m/Y') }}</td>
                <td style="border: 1px solid #e2e8f0; padding: 5px;">{{ $t->description ?? '-' }}</td>
                <td style="border: 1px solid #e2e8f0; padding: 5px;">{{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td style="border: 1px solid #e2e8f0; padding: 5px; text-align: right;">{{ $t->type === 'income' ? '+' : '-' }}Rp {{ number_format($t->amount, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; background-color: #f8fafc;">Total Pemasukan</td>
            <td style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; color: #059669; background-color: #f8fafc;">Rp {{ number_format($totalIncome, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; background-color: #f8fafc;">Total Pengeluaran</td>
            <td style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; color: #dc2626; background-color: #f8fafc;">Rp {{ number_format($totalExpense, 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; background-color: #f1f5f9;">Net Cash Flow</td>
            <td style="border: 1px solid #cbd5e1; padding: 6px; font-weight: bold; text-align: right; color: {{ ($totalIncome - $totalExpense) >= 0 ? '#059669' : '#dc2626' }}; background-color: #f1f5f9;">
                Rp {{ number_format($totalIncome - $totalExpense, 2, ',', '.') }}
            </td>
        </tr>
    </tfoot>
</table>
