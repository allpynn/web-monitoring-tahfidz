<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Tahfidz - {{ $student->name }}</title>
    <style>
        @page { margin: 1.2cm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 9pt; color: #000; line-height: 1.4; margin: 0; padding: 0; }
        
        /* Utility */
        .w-100 { width: 100%; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        /* Header & Title */
        .page-meta { font-size: 7.5pt; color: #444; margin-bottom: 5px; border-bottom: 0px; }
        .header-table { border-bottom: 2.5px solid #000; padding-bottom: 8px; margin-bottom: 12px; width: 100%; border-collapse: collapse; }
        .header-logo { width: 65px; vertical-align: middle; }
        .header-text { vertical-align: middle; padding-left: 12px; }
        .header-text h1 { font-size: 18pt; margin: 0; padding: 0; line-height: 1.1; font-weight: bold; }
        .header-text h2 { font-size: 13pt; margin: 0; padding: 0; margin-top: 3px; font-weight: bold; letter-spacing: 0.5px; }
        
        .document-title { margin: 15px 0 12px 0; text-align: center; width: 100%; }
        .document-title h2 { font-size: 14pt; margin: 0; font-weight: bold; text-decoration: none; }
        .document-title p { margin: 4px 0 0 0; font-size: 10pt; font-weight: bold; }

        /* Student Info Block */
        .info-container { margin-bottom: 15px; width: 100%; position: relative; min-height: 110px; }
        .info-table { border-collapse: collapse; width: 75%; float: left; }
        .info-table td { padding: 3px 0; vertical-align: top; font-size: 9.5pt; }
        .info-label { width: 110px; }
        .info-separator { width: 15px; text-align: center; }
        
        .photo-box { 
            width: 85px; 
            height: 105px; 
            border: 1px solid #333; 
            float: right;
            text-align: center;
            line-height: 105px;
            font-size: 9pt;
            color: #666;
            margin-top: 2px;
            background: #fff;
        }

        /* History Table */
        .history-table { border-collapse: collapse; margin-top: 10px; font-size: 8.5pt; width: 100%; clear: both; }
        .history-table th, .history-table td { border: 1px solid #000; padding: 7px 5px; vertical-align: middle; text-align: left; }
        .history-table th { background-color: #f8f8f8; font-weight: bold; text-align: center; }
        .history-table td.center { text-align: center; }
        
        /* Summary Section */
        .summary-container { margin-top: 15px; font-size: 9pt; width: 100%; }
        .summary-table { border-collapse: collapse; width: 100%; }
        .summary-table td { border: 1px solid #000; padding: 5px 8px; }
        .summary-label { background-color: #f8f8f8; font-weight: bold; width: 180px; }

        /* Footer */
        .page-footer { position: fixed; bottom: -10px; width: 100%; font-size: 7.5pt; border-top: 1px solid #ccc; padding-top: 4px; color: #555; }
        
        /* Status Badges */
        .status-lancar { font-weight: bold; color: #15803d; }
        .status-perbaikan { font-weight: bold; color: #b91c1c; font-style: italic; }
    </style>
</head>
<body>
    <!-- Top Metadata -->
    <table class="w-100 page-meta">
        <tr>
            <td>Dicetak: {{ now()->format('d/m/Y, H:i:s') }}</td>
            <td class="text-right">Portal Akademik Tahfidz Al-Mujahidin</td>
        </tr>
    </table>

    <!-- Header -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                @if(isset($logoBase64) && $logoBase64)
                    <img src="{{ $logoBase64 }}" style="width: 62px; height: 62px;">
                @else
                    <div style="width: 62px; height: 62px; border: 1px solid #000; text-align:center; line-height:62px; font-size:8pt;">LOGO</div>
                @endif
            </td>
            <td class="header-text">
                <h1>Pondok Pesantren Al-Mujahidin</h1>
                <h2>TAHFIDZ AL-MUJAHIDIN BALIKPAPAN</h2>
            </td>
        </tr>
    </table>

    <!-- Title Section -->
    <div class="document-title">
        <h2 class="uppercase">LAPORAN PERKEMBANGAN TAHFIDZ</h2>
        <p>Semester: {{ now()->month > 6 ? 'Ganjil' : 'Genap' }} {{ now()->year }}/{{ now()->year + 1 }}</p>
    </div>

    <!-- Student Info -->
    <div class="info-container">
        <div class="photo-box">PAS PHOTO</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Santri</td>
                <td class="info-separator">:</td>
                <td class="bold">{{ $student->name }}</td>
            </tr>
            <tr>
                <td class="info-label">NIS (Nomor Induk)</td>
                <td class="info-separator">:</td>
                <td>{{ $student->nis }}</td>
            </tr>
            <tr>
                <td class="info-label">Orang Tua / Wali</td>
                <td class="info-separator">:</td>
                <td>{{ $student->parents->pluck('name')->join(', ') ?: '-' }}</td>
            </tr>
            <tr>
                <td class="info-label">Ustadz Pengampu</td>
                <td class="info-separator">:</td>
                <td>{{ $student->guru->name ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Main Data Table -->
    <table class="history-table">
        <thead>
            <tr>
                <th width="30">No.</th>
                <th width="75">Tanggal</th>
                <th>Materi Hafalan (Juz, Surah, Ayat)</th>
                <th width="90">Kualitas</th>
                <th>Catatan Guru Pendamping</th>
            </tr>
        </thead>
        <tbody>
            @forelse($memorizations as $index => $m)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $m->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($m->is_present)
                            <span class="bold">Juz {{ $m->juz }}</span> : {{ $m->surah }} (Ayat {{ $m->ayat }})
                        @else
                            <i style="color: #666;">Absen (Tidak Ada Setoran)</i>
                        @endif
                    </td>
                    <td class="center">
                        @if($m->is_present)
                            <span class="{{ $m->status === 'Lancar' ? 'status-lancar' : 'status-perbaikan' }}">
                                {{ strtoupper($m->status) }}
                            </span>
                        @endif
                    </td>
                    <td>{{ $m->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="center" style="padding: 20px;">Belum ada riwayat hafalan untuk periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Statistics Summary -->
    <div class="summary-container">
        <table class="summary-table">
            <tr>
                <td class="summary-label">Total Setoran Tahfizh</td>
                <td>{{ $memorizations->where('is_present', true)->count() }} Kali</td>
                <td class="summary-label">Target Hafalan</td>
                <td>{{ $student->target_juz }} Juz</td>
            </tr>
            <tr>
                <td class="summary-label">Hafalan Terakhir</td>
                <td>Juz {{ $student->current_juz }}</td>
                <td class="summary-label">Persentase Capaian</td>
                <td class="bold">{{ $student->target_progress }}%</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 30px; font-size: 8.5pt;">
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

    <!-- Page Footer -->
    <div class="page-footer">
        <table class="w-100">
            <tr>
                <td>Laporan ini diunggah secara otomatis melalui Portal Tahfidz Al-Mujahidin</td>
                <td class="text-right">https://portal.almujahidin.id/cetak/{{ $student->nis }} | Hal. 1</td>
            </tr>
        </table>
    </div>
</body>
</html>
