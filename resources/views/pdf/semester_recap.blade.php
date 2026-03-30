<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Rekap Semester Tahfidz - {{ $student->name }}</title>
    <style>
        @page { margin: 1cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #000; line-height: 1.3; margin: 0; padding: 0; }
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .header-table { border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 15px; width: 100%; }
        .header-logo { width: 60px; vertical-align: middle; }
        .header-text { vertical-align: middle; padding-left: 15px; }
        .header-text h1 { font-size: 18pt; margin: 0; padding: 0; line-height: 1; }
        
        .document-title { margin: 15px 0; text-align: center; }
        .document-title h2 { font-size: 14pt; margin: 0; text-transform: uppercase; }
        
        .info-table { border-collapse: collapse; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; vertical-align: top; }
        .info-label { width: 120px; }
        
        .recap-table { border-collapse: collapse; width: 100%; font-size: 8.5pt; }
        .recap-table th, .recap-table td { border: 1px solid #000; padding: 6px 4px; }
        .recap-table th { background-color: #f2f2f2; }
        
        .footer { position: fixed; bottom: 0; width: 100%; font-size: 8pt; border-top: 1px solid #000; padding-top: 5px; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="width: 60px;">
                @else
                    <div style="width: 60px; height: 60px; border: 1px solid #000; text-align:center; line-height:60px;">LOGO</div>
                @endif
            </td>
            <td class="header-text">
                <h1 class="bold">Pondok Pesantren Al-Mujahidin</h1>
                <h2 class="bold">REKAPITULASI HAFALAN SEMESTER</h2>
            </td>
        </tr>
    </table>

    <div class="document-title">
        <h2>REKAPITULASI CAPAIAN TAHFIDZ</h2>
        <p class="bold">Semester {{ now()->month > 6 ? 'Ganjil' : 'Genap' }} TA {{ now()->year }}/{{ now()->year + 1 }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Santri</td>
            <td width="15">:</td>
            <td class="bold">{{ $student->name }}</td>
        </tr>
        <tr>
            <td class="info-label">NIS</td>
            <td>:</td>
            <td>{{ $student->nis }}</td>
        </tr>
        <tr>
            <td class="info-label">Ustadz Pengampu</td>
            <td>:</td>
            <td>{{ $student->guru->name ?? '-' }}</td>
        </tr>
    </table>

    <table class="recap-table">
        <thead>
            <tr>
                <th width="30">No</th>
                <th width="80">Bulan</th>
                <th>Total Setoran</th>
                <th>Capaian Juz (Range)</th>
                <th>Status Dominan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $months = $memorizations->groupBy(function($date) {
                    return \Carbon\Carbon::parse($date->created_at)->format('F Y');
                });
            @endphp
            @foreach($months as $month => $logs)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $month }}</td>
                    <td class="text-center">{{ $logs->where('is_present', true)->count() }} Kali</td>
                    <td>
                        @php
                            $juzs = $logs->where('is_present', true)->pluck('juz')->unique()->sort();
                        @endphp
                        {{ $juzs->isEmpty() ? '-' : 'Juz ' . $juzs->first() . ' - ' . $juzs->last() }}
                    </td>
                    <td class="text-center">
                        @php
                            $status = $logs->where('is_present', true)->groupBy('status')->map->count()->sortDesc();
                        @endphp
                        {{ $status->keys()->first() ?? '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bold">
                <td colspan="2" class="text-right">TOTAL AKUMULASI</td>
                <td class="text-center">{{ $memorizations->where('is_present', true)->count() }} Kali</td>
                <td colspan="2">Capaian Akhir: Juz {{ $student->current_juz }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 30px;">
        <table class="w-100">
            <tr>
                <td width="60%"></td>
                <td class="text-center">
                    Balikpapan, {{ now()->translatedFormat('d F Y') }}<br>
                    Ustadz Pengampu,<br><br><br><br>
                    <span class="bold">( {{ $student->guru->name ?? '..........................' }} )</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Rekapitulasi Semester Otomatis - Portal Tahfidz Al-Mujahidin | Hal. 1/1
    </div>
</body>
</html>
