<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  * { margin:0; padding:0; box-sizing:border-box; }
  body { font-family: sans-serif; font-size: 12px; color: #1a1a2e; }
  .header { background: #22d3ee; color: #0c1445; padding: 16px 24px; margin-bottom: 20px; }
  .header h1 { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
  .header p  { font-size: 10px; opacity: 0.75; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
  th { background: #0891b2; color: white; padding: 10px 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
  td { padding: 10px 8px; border-bottom: 1px solid #e5e7eb; }
  tr:nth-child(even) td { background: #ecfeff; }
  .rate-high   { color: #16a34a; font-weight: 700; }
  .rate-medium { color: #d97706; font-weight: 700; }
  .rate-low    { color: #dc2626; font-weight: 700; }
  .footer { text-align:center; padding:12px; color:#9ca3af; font-size:9px; border-top:1px solid #e5e7eb; }
</style>
</head>
<body>

<div class="header">
  <h1>🏨 Laporan Okupansi Hotel — {{ $year }}</h1>
  <p>Nusantara Hospitality Group | Dicetak: {{ now()->format('d M Y H:i') }}</p>
</div>

<table>
  <thead>
    <tr>
      <th>Properti</th><th>Tipe</th><th>Kota</th>
      <th>Total Kamar</th><th>Terisi Hari Ini</th>
      <th>Occupancy Rate</th><th>Revenue YTD</th>
    </tr>
  </thead>
  <tbody>
    @foreach($hotels as $hotel)
    <tr>
      <td><strong>{{ $hotel['nama'] }}</strong></td>
      <td>{{ ucfirst($hotel['tipe']) }}</td>
      <td>{{ $hotel['kota'] }}</td>
      <td style="text-align:center;">{{ $hotel['kapasitas_total'] }}</td>
      <td style="text-align:center;">{{ $hotel['terisi'] }}</td>
      <td>
        <span class="{{ $hotel['rate'] >= 85 ? 'rate-high' : ($hotel['rate'] >= 60 ? 'rate-medium' : 'rate-low') }}">
          {{ $hotel['rate'] }}%
        </span>
      </td>
      <td>Rp {{ number_format($hotel['revenue']/1000000,1) }}M</td>
    </tr>
    @endforeach
  </tbody>
</table>

<div class="footer">
  © {{ now()->year }} Nusantara Hospitality Group — NHG Analytics System
</div>
</body>
</html>