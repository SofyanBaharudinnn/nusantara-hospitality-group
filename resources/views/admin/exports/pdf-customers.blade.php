<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: sans-serif; font-size: 11px; color: #1a1a2e; }
  .header { background: #ec4899; color: white; padding: 16px 24px; margin-bottom: 20px; }
  .header h1 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
  .header p  { font-size: 10px; opacity: 0.8; }
  table { width: 100%; border-collapse: collapse; }
  th { background: #db2777; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
  td { padding: 7px 6px; border-bottom: 1px solid #fce7f3; font-size: 10px; }
  tr:nth-child(even) td { background: #fdf2f8; }
  .badge { display:inline-block; padding:2px 6px; border-radius:4px; font-size:9px; font-weight:600; }
  .platinum { background:#ede9fe; color:#5b21b6; }
  .gold     { background:#fef9c3; color:#854d0e; }
  .silver   { background:#f1f5f9; color:#475569; }
  .footer   { text-align:center; padding:12px; color:#9ca3af; font-size:9px; border-top:1px solid #fce7f3; margin-top:8px; }
</style>
</head>
<body>

<div class="header">
  <h1>👥 Laporan Data Tamu</h1>
  <p>Nusantara Hospitality Group | Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

<table>
  <thead>
    <tr>
      <th>#</th><th>Nama</th><th>Email</th><th>Segmen</th>
      <th>Negara</th><th>Kunjungan</th><th>Total Spending</th><th>Tier</th>
    </tr>
  </thead>
  <tbody>
    @foreach($customers as $i => $c)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td><strong>{{ $c->nama }}</strong></td>
      <td>{{ $c->email }}</td>
      <td>{{ ucfirst($c->segmen) }}</td>
      <td>{{ $c->negara }}</td>
      <td style="text-align:center;">{{ $c->total_kunjungan }}x</td>
      <td><strong>Rp {{ number_format($c->total_spending/1000000,1) }}Jt</strong></td>
      <td><span class="badge {{ $c->tier }}">{{ ucfirst($c->tier) }}</span></td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="footer">
  © {{ now()->year }} Nusantara Hospitality Group — NHG Analytics System
</div>
</body>
</html>