<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahfidz - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; margin-top: 30px; }
        .kop-surat { width: 100%; border-bottom: 3px double #059669; margin-bottom: 30px; padding-bottom: 10px; }
        .kop-logo { width: 80px; text-align: left; }
        .kop-text { text-align: center; }
        .kop-text h2 { margin: 0; color: #059669; font-size: 20px; text-transform: uppercase; }
        .kop-text h1 { margin: 0; color: #064e3b; font-size: 26px; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text p { margin: 2px 0; font-size: 11px; color: #4b5563; }
        .report-title { text-align: center; margin-bottom: 25px; }
        .report-title h3 { margin: 0; text-decoration: underline; font-size: 18px; color: #111827; }
        .info-section { margin-bottom: 30px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px 0; font-size: 13px; }
        .info-table td.label { font-weight: bold; width: 20%; color: #374151; }
        .stats-grid { display: block; margin-bottom: 30px; clear: both; }
        .stats-box { width: 31%; display: inline-block; background: #f0fdf4; border: 1px solid #dcfce7; padding: 12px; border-radius: 8px; text-align: center; margin-right: 1.5%; }
        .stats-box:last-child { margin-right: 0; }
        .stats-box h4 { margin: 0; font-size: 9px; color: #059669; text-transform: uppercase; font-weight: bold; }
        .stats-box p { margin: 5px 0 0; font-size: 18px; font-weight: bold; color: #065f46; }
        table.history { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.history th { background: #059669; color: white; padding: 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
        table.history td { padding: 8px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        .footer { position: fixed; bottom: -20px; width: 100%; text-align: right; font-size: 9px; color: #6b7280; padding-top: 10px; border-top: 1px solid #e5e7eb; }
        .logo-emblem {
            width: 70px;
            height: 70px;
            background: #064e3b;
            border: 4px double #fbbf24;
            border-radius: 50%;
            display: inline-block;
            text-align: center;
            line-height: 62px;
            color: #fbbf24;
            font-size: 28px;
            font-weight: bold;
            font-family: 'Georgia', serif;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                @if(extension_loaded('gd') && isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo" style="width: 80px; height: 80px;">
                @else
                    <div class="logo-emblem">AM</div>
                @endif
            </td>
            <td class="kop-text">
                <h2 style="font-size: 18px; margin: 0; color: #059669;">PONDOK PESANTREN AL-MUJAHIDIN</h2>
                <h1 style="font-size: 24px; margin: 2px 0; color: #064e3b;">TAHFIDZ AL-MUJAHIDIN</h1>
                <p>Jl. Soekarno Hatta KM. 5,7 Balikpapan Utara, Kota Balikpapan, Kalimantan Timur</p>
                <p>Telp: (0542) 745345 | Web: www.almujahidin.id | Email: info@almujahidin.id</p>
            </td>
        </tr>
    </table>

    <div class="report-title">
        <h3>LAPORAN MONITORING TAHFIDZ SANTRI</h3>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td class="label">Nama Santri</td>
                <td>: {{ $student->name }}</td>
                <td class="label">NIS</td>
                <td>: {{ $student->nis }}</td>
            </tr>
            <tr>
                <td class="label">Orang Tua</td>
                <td>: {{ $student->parent->name ?? '-' }}</td>
                <td class="label">Ustadz Pendamping</td>
                <td>: {{ $student->guru->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Target Hafalan</td>
                <td>: {{ $student->target_juz }} Juz</td>
                <td class="label">Target Selesai</td>
                <td>: {{ $student->target_date ? \Carbon\Carbon::parse($student->target_date)->format('d M Y') : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stats-box">
            <h4>Hafalan Terakhir</h4>
            <p>Juz {{ $student->current_juz }}</p>
        </div>
        <div class="stats-box">
            <h4>Progres Target</h4>
            <p>{{ $student->target_progress }}%</p>
        </div>
        <div class="stats-box">
            <h4>Total Setoran</h4>
            <p>{{ $memorizations->where('is_present', true)->count() }}</p>
        </div>
    </div>

    <h3>Riwayat Setoran Terbaru</h3>
    <table class="history">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Materi Hafalan</th>
                <th>Status</th>
                <th>Catatan Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memorizations as $m)
                <tr>
                    <td>{{ $m->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($m->is_present)
                            Juz {{ $m->juz }}: {{ $m->surah }} (Ayat {{ $m->ayat }})
                        @else
                            <span style="color: #ef4444;">Absen (Tidak Setor)</span>
                        @endif
                    </td>
                    <td>
                        @if($m->is_present)
                            <span class="status-badge {{ $m->status === 'Lancar' ? 'status-lancar' : 'status-perbaikan' }}">
                                {{ strtoupper($m->status) }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $m->notes ?: '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }} | Sistem Monitoring Tahfidz Al-Mujahidin
    </div>
</body>
</html>
