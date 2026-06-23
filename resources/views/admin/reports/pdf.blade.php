<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas - {{ $namaBulan }} {{ $year }}</title>
    <style>
        @page { margin: 20mm 15mm 25mm; }
        * { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        body { color: #1e293b; line-height: 1.5; }

        .header { text-align: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 3px solid #1e3a5f; }
        .header h1 { font-size: 16pt; font-weight: 700; color: #1e3a5f; margin: 0 0 3px; text-transform: uppercase; letter-spacing: 1px; }
        .header h2 { font-size: 13pt; font-weight: 600; color: #334155; margin: 5px 0; }
        .header p { font-size: 9pt; color: #64748b; margin: 2px 0; }

        .info { margin-bottom: 15px; font-size: 8pt; color: #64748b; }
        .info table { width: 100%; }
        .info td { padding: 1px 0; }

        table.data { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data th { background: #1e3a5f; color: white; font-weight: 600; font-size: 8pt; padding: 7px 8px; text-align: left; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; }
        table.data tr:nth-child(even) { background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-green { color: #059669; font-weight: 600; }
        .text-red { color: #dc2626; font-weight: 600; }
        .text-bold { font-weight: 700; }

        .summary { margin-top: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 4px 10px; font-size: 10pt; }
        .summary .label { text-align: right; font-weight: 600; color: #475569; width: 70%; }
        .summary .value { text-align: right; font-weight: 700; width: 30%; }
        .summary .total td { padding-top: 8px; border-top: 2px solid #1e3a5f; font-size: 11pt; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 7pt; color: #94a3b8; padding: 10px 0; border-top: 1px solid #e2e8f0; }
        .footer .page-number:before { content: "Halaman " counter(page); }
    </style>
</head>
<body>
    <div class="header">
        <h1>KasApp</h1>
        <h2>Laporan Arus Kas</h2>
        <p>Periode {{ $namaBulan }} {{ $year }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td style="width:50%">Dicetak oleh: {{ auth()->user()->name ?? 'System' }}</td>
                <td style="width:50%; text-align:right;">Tanggal cetak: {{ now()->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width:10%">Tanggal</th>
                <th style="width:25%">Deskripsi</th>
                <th style="width:12%" class="text-center">Tipe</th>
                <th style="width:25%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions as $t)
                <tr>
                    <td>{{ $t->transaction_date->format('d/m/Y') }}</td>
                    <td>{{ $t->description ?? '-' }}</td>
                    <td class="text-center {{ $t->type === 'income' ? 'text-green' : 'text-red' }}">{{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                    <td class="text-right {{ $t->type === 'income' ? 'text-green' : 'text-red' }}">
                        {{ $t->type === 'income' ? '+' : '-' }}Rp {{ number_format($t->amount, 2, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="padding:20px; color:#94a3b8;">Tidak ada transaksi periode ini</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td class="label">Total Pemasukan</td>
                <td class="value text-green">Rp {{ number_format($totalIncome, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="label">Total Pengeluaran</td>
                <td class="value text-red">Rp {{ number_format($totalExpense, 2, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td class="label">Net Cash Flow</td>
                <td class="value {{ ($totalIncome - $totalExpense) >= 0 ? 'text-green' : 'text-red' }}">
                    Rp {{ number_format($totalIncome - $totalExpense, 2, ',', '.') }}
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <span class="page-number"></span>
    </div>
</body>
</html>
