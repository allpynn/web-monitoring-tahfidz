<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class DashboardController extends Controller
{
    public function index()
    {
        $guruCount = User::where('role', 'guru')->count();
        $studentCount = Student::count();
        $parentCount = User::where('role', 'orang_tua')->count();

        return view('admin.dashboard', compact(
            'guruCount',
            'studentCount',
            'parentCount'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ], ['file.required' => 'Silakan pilih file CSV terlebih dahulu.']);

        $upload = $request->file('file');
        $successCount = 0;
        $errorMessages = [];
        $isGuruCsv = false;

        try {
            $file = new \SplFileObject($upload->getRealPath());
            $file->setFlags(\SplFileObject::READ_CSV | \SplFileObject::SKIP_EMPTY | \SplFileObject::DROP_NEW_LINE);

            // Deteksi Delimiter
            $firstLine = $file->fgets();
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            $file->setCsvControl($delimiter);
            $file->rewind();

            // Baca Header
            $header = $file->fgetcsv();
            $headerClean = array_map(function ($h) {
                return strtolower(trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h)));
            }, $header ?: []);

            foreach ($headerClean as $h) {
                if (str_contains($h, 'nip')) {
                    $isGuruCsv = true;
                    break;
                }
            }

            $targetName = $isGuruCsv ? 'guru' : 'santri';
            $rowNum = 1;

            DB::beginTransaction();

            while (!$file->eof()) {
                $data = $file->fgetcsv();
                if (!$data || empty(array_filter($data)))
                    continue;

                $rowNum++;

                if ($isGuruCsv) {
                    // Logika Guru: Nama | NIP | No telp | Email | Jenis Kelamin
                    if (count($data) < 5) {
                        $errorMessages[] = "Baris $rowNum: Format kolom guru tidak sesuai (Butuh 5 kolom).";
                        continue;
                    }
                    $data = array_map('trim', $data);
                    $nama = $data[0];
                    $nip = $data[1];
                    $phoneRaw = $data[2];
                    $email = $data[3];
                    $gender = $this->normalizeGender($data[4]);

                    $phone = preg_replace('/[^0-9]/', '', $phoneRaw);
                    // Jika diawali 62..., ubah jadi 0...
                    if (str_starts_with($phone, '62')) {
                        $phone = '0' . substr($phone, 2);
                    }
                    // Jika diawali 8..., tambah 0 di depannya
                    elseif (str_starts_with($phone, '8')) {
                        $phone = '0' . $phone;
                    }

                    if (User::where('email', $email)->orWhere('nip', $nip)->exists()) {
                        $errorMessages[] = "Baris $rowNum ($nama): Akun (Email/NIP) sudah terdaftar.";
                        continue;
                    }

                    User::create([
                        'name' => $nama,
                        'gender' => $gender,
                        'nip' => $nip,
                        'email' => $email,
                        'phone' => $phone,
                        'role' => 'guru',
                        'password' => Hash::make($phone),
                        'email_verified_at' => now(),
                    ]);
                    $successCount++;
                } else {
                    // Logika Santri: Nama Santri | NISN | JenKel Santri | Nama Ortu | Email Ortu | No HP Ortu | JenKel Ortu
                    if (count($data) < 7) {
                        $errorMessages[] = "Baris $rowNum: Kolom santri tidak lengkap (Butuh 7 kolom).";
                        continue;
                    }
                    $data = array_map('trim', $data);
                    $namaS = $data[0];
                    $nis = $data[1];
                    $genderS = $this->normalizeGender($data[2]);
                    $namaO = $data[3];
                    $emailO = $data[4];
                    $phoneORaw = $data[5];
                    $genderO = $this->normalizeGender($data[6]);

                    if (Student::where('nis', $nis)->exists()) {
                        $errorMessages[] = "Baris $rowNum (Santri $namaS): NISN $nis sudah ada.";
                        continue;
                    }

                    $parent = User::where('email', $emailO)->first();
                    if (!$parent) {
                        $phoneO = preg_replace('/[^0-9]/', '', $phoneORaw);
                        // Normalisasi format 08...
                        if (str_starts_with($phoneO, '62'))
                            $phoneO = '0' . substr($phoneO, 2);
                        elseif (str_starts_with($phoneO, '8'))
                            $phoneO = '0' . $phoneO;

                        $parent = User::create([
                            'email' => $emailO,
                            'name' => $namaO,
                            'gender' => $genderO,
                            'phone' => $phoneO,
                            'role' => 'orang_tua',
                            'password' => Hash::make($phoneO ?: 'password'),
                            'email_verified_at' => now(),
                        ]);
                    }

                    Student::create([
                        'nis' => $nis,
                        'name' => $namaS,
                        'gender' => $genderS,
                        'target_juz' => 30
                    ])->parents()->syncWithoutDetaching([$parent->id]);
                    $successCount++;
                }
            }

            DB::commit();

            // --- PILAR ALERT ---
            // 1. HIJAU: Sukses Total
            if ($successCount > 0 && count($errorMessages) === 0) {
                return redirect()->back()->with('success', "Berhasil Menambahkan ($successCount) data $targetName.");
            }
            // 2. KUNING: Berhasil Sebagian
            if ($successCount > 0 && count($errorMessages) > 0) {
                return redirect()->back()->with('import_warning', [
                    'success' => $successCount,
                    'errors' => $errorMessages,
                    'tipe' => $targetName
                ]);
            }
            // 3. MERAH: Gagal Total
            return redirect()->back()->with('error', "Gagal: Tidak ada data baru yang masuk. (" . count($errorMessages) . " baris bermasalah/duplikat).");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses file: ' . $e->getMessage());
        }
    }

    private function normalizeGender($value)
    {
        $value = strtolower(trim($value ?? ''));

        // Pola Laki-laki
        if ($value === 'l' || str_contains($value, 'laki') || str_contains($value, 'pria') || str_contains($value, 'male')) {
            return 'Laki-laki';
        }

        // Pola Perempuan
        if ($value === 'p' || str_contains($value, 'perempuan') || str_contains($value, 'wanita') || str_contains($value, 'female')) {
            return 'Perempuan';
        }

        return 'Laki-laki'; // Default
    }
}

