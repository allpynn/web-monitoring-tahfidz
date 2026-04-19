<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Dokumentasi Akses Pengguna - Tahfidz Al-Mujahidin</title>
    <style>
        @page { margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            font-size: 10pt; 
            color: #334155; 
            line-height: 1.5; 
            margin: 0; 
            padding: 0; 
        }
        
        .header { 
            border-bottom: 3px solid #10b981; 
            padding-bottom: 20px; 
            margin-bottom: 30px; 
        }
        
        .logo-container { float: left; width: 70px; }
        .header-text { margin-left: 85px; }
        .header-text h1 { 
            font-size: 20pt; 
            margin: 0; 
            color: #0d9488; 
            font-weight: bold; 
        }
        .header-text p { margin: 5px 0 0; color: #64748b; font-size: 11pt; }

        .section-title { 
            background: #f1f5f9; 
            padding: 10px 15px; 
            border-left: 5px solid #10b981; 
            margin: 25px 0 15px; 
            font-size: 14pt; 
            font-weight: bold; 
            color: #0f172a;
            text-transform: uppercase;
        }

        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        
        th { 
            background-color: #f8fafc; 
            color: #475569; 
            font-weight: bold; 
            text-align: left; 
            padding: 12px 10px; 
            border-bottom: 2px solid #e2e8f0;
            font-size: 9pt;
            text-transform: uppercase;
        }
        
        td { 
            padding: 12px 10px; 
            border-bottom: 1px solid #f1f5f9; 
            vertical-align: middle; 
        }

        .role-badge {
            background: #ecfdf5;
            color: #059669;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 8pt;
            font-weight: bold;
        }

        .footer { 
            position: fixed; 
            bottom: 0; 
            width: 100%; 
            font-size: 8pt; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 10px; 
            color: #94a3b8; 
            text-align: center;
        }

        .alert {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 9pt;
        }

        .bold { font-weight: bold; color: #0f172a; }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            @if(isset($logoBase64) && $logoBase64)
                <img src="{{ $logoBase64 }}" style="width: 70px; height: 70px;">
            @else
                <div style="width: 70px; height: 70px; border: 2px solid #10b981; border-radius: 12px; text-align:center; line-height:70px; color:#10b981; font-weight:bold;">LOGO</div>
            @endif
        </div>
        <div class="header-text">
            <h1>Tahfidz Al-Mujahidin</h1>
            <p>Sistem Monitoring Hafalan Santri - Dokumentasi Akses</p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="alert">
        <strong>PEMBERITAHUAN KEAMANAN:</strong> Dokumen ini berisi kredensial akses default untuk sistem. Harap simpan dokumen ini di tempat yang aman dan segera ubah kata sandi default setelah login pertama kali di lingkungan produksi.
    </div>

    @php
        $roles = [
            'admin' => 'Administrator',
            'guru' => 'Guru (Ustadz)',
            'orang_tua' => 'Wali Santri (Orang Tua)'
        ];
    @endphp

    @foreach($roles as $role => $label)
        @php $roleUsers = $users->where('role', $role); @endphp
        
        @if($roleUsers->count() > 0)
            <div class="section-title">{{ $label }}</div>
            <table>
                <thead>
                    <tr>
                        <th width="30%">Nama</th>
                        <th width="30%">Email / Username</th>
                        <th width="20%">No. Telepon</th>
                        <th width="20%">Password</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roleUsers as $user)
                        <tr>
                            <td class="bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone ?? '-' }}</td>
                            <td><code style="background: #f1f5f9; padding: 2px 5px; border-radius: 3px;">password</code></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

    <div class="footer">
        Dokumen ini dibuat otomatis oleh Sistem Monitoring Tahfidz Al-Mujahidin pada {{ now()->translatedFormat('d F Y, H:i') }}.
    </div>
</body>
</html>
