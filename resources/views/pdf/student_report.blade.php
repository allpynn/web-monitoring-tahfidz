<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perkembangan Tahfidz</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #065f46; font-size: 24px; }
        .header p { margin: 5px 0 0; color: #6b7280; font-size: 14px; }
        .student-info { margin-bottom: 30px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px 0; font-size: 14px; }
        .student-info .label { font-weight: bold; width: 150px; }
        .section-title { background: #ecfdf5; color: #065f46; padding: 8px 15px; font-weight: bold; margin-bottom: 15px; border-left: 4px solid #10b981; }
        table.history { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.history th { background: #f9fafb; border: 1px solid #e5e7eb; padding: 10px; text-align: left; font-size: 12px; color: #4b5563; }
        table.history td { border: 1px solid #e5e7eb; padding: 10px; font-size: 12px; }
        .status-badge { padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 10px; text-transform: uppercase; }
        .status-lancar { background: #dcfce7; color: #166534; }
        .status-perbaikan { background: #ffedd5; color: #9a3412; }
        .footer { margin-top: 50px; }
        .footer table { width: 100%; }
        .footer .signature { text-align: center; width: 200px; }
        .footer .signature-space { height: 80px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERKEMBANGAN TAHFIDZ</h1>
        <p>TPQ/Rumah Tahfidz Al-Kahfi</p>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nama Santri</td>
                <td>: {{ $student->name }}</td>
                <td class="label">Target Juz</td>
                <td>: {{ $student->target_juz }} Juz</td>
            </tr>
            <tr>
                <td class="label">NIS</td>
                <td>: {{ $student->nis }}</td>
                <td class="label">Pencapaian</td>
                <td>: {{ $current_juz }} Juz</td>
            </tr>
            <tr>
                <td class="label">Orang Tua</td>
                <td>: {{ $student->parent->name }}</td>
                <td class="label">Tanggal Cetak</td>
                <td>: {{ now()->format('d F Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="section-title">RIWAYAT SETORAN TERAKHIR</div>
    <table class="history">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Juz</th>
                <th>Surah</th>
                <th>Ayat</th>
                <th>Status</th>
                <th>Catatan Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($memorizations as $item)
            <tr>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                <td>{{ $item->juz ?? '-' }}</td>
                <td>{{ $item->surah ?? '-' }}</td>
                <td>{{ $item->ayat ?? '-' }}</td>
                <td>
                    @if($item->is_present)
                        <span class="status-badge {{ $item->status === 'Lancar' ? 'status-lancar' : 'status-perbaikan' }}">
                            {{ $item->status }}
                        </span>
                    @else
                        <span class="status-badge" style="background: #fee2e2; color: #991b1b;">Absen</span>
                    @endif
                </td>
                <td>{{ $item->notes ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table>
            <tr>
                <td class="signature">
                    <p>Orang Tua/Wali</p>
                    <div class="signature-space"></div>
                    <p>( {{ $student->parent->name }} )</p>
                </td>
                <td></td>
                <td class="signature">
                    <p>Kepala Tahfidz / Guru</p>
                    <div class="signature-space"></div>
                    <p>( {{ auth()->user()->name }} )</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
