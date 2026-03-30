<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahfidz - {{ $student->name }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #059669; padding-bottom: 20px; }
        .header h1 { color: #059669; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; font-size: 14px; }
        .info-section { margin-bottom: 30px; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 5px 0; font-size: 14px; }
        .info-table td.label { font-weight: bold; width: 30%; color: #666; }
        .stats-grid { display: block; margin-bottom: 30px; }
        .stats-box { width: 30.5%; display: inline-block; background: #f0fdf4; border: 1px solid #dcfce7; padding: 15px; border-radius: 10px; text-align: center; margin-right: 2%; }
        .stats-box:last-child { margin-right: 0; }
        .stats-box h4 { margin: 0; font-size: 10px; color: #059669; text-transform: uppercase; }
        .stats-box p { margin: 5px 0 0; font-size: 20px; font-weight: bold; }
        table.history { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.history th { background: #059669; color: white; padding: 10px; text-align: left; font-size: 12px; text-transform: uppercase; }
        table.history td { padding: 10px; border-bottom: 1px solid #eee; font-size: 11px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; padding: 20px 0; border-top: 1px solid #eee; }
        .status-badge { padding: 2px 8px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .status-lancar { background: #dcfce7; color: #166534; }
        .status-perbaikan { background: #ffedd5; color: #9a3412; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERKEMBANGAN TAHFIDZ</h1>
        <p>Pondok Pesantren Al-Mujahidin Balikpapan</p>
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
        Dicetak secara otomatis oleh Sistem Monitoring Tahfidz Al-Mujahidin pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
