<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentAssignment;
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
        $gurus = User::where('role', 'guru')->orderBy('name')->get();

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $defaultAcademicYear = $defaultStartYear . '/' . ($defaultStartYear + 1);

        $academicYears = StudentAssignment::distinct()
            ->pluck('academic_year')
            ->push($defaultAcademicYear)
            ->unique()
            ->sortByDesc(fn($year) => $year)
            ->values()
            ->toArray();

        return view('admin.dashboard', compact(
            'guruCount',
            'studentCount',
            'parentCount',
            'gurus',
            'academicYears',
            'defaultAcademicYear'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'academic_year' => 'nullable|string',
            'guru_id' => 'nullable|exists:users,id',
        ], ['file.required' => 'Silakan pilih file CSV terlebih dahulu.']);

        $currentMonth = now()->month;
        $currentYear = now()->year;
        $defaultStartYear = ($currentMonth >= 7) ? $currentYear : $currentYear - 1;
        $fallbackAcademicYear = $request->input('academic_year') ?: ($defaultStartYear . '/' . ($defaultStartYear + 1));
        $fallbackGuruId = $request->input('guru_id') ?: null;

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
                    if (str_starts_with($phone, '62')) {
                        $phone = '0' . substr($phone, 2);
                    } elseif (str_starts_with($phone, '8')) {
                        $phone = '0' . $phone;
                    }

                    if (strlen($phone) < 10 || strlen($phone) > 15) {
                        $errorMessages[] = "Baris $rowNum ($nama): Nomor HP '$phoneRaw' tidak valid (harus 10-15 digit angka).";
                        continue;
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

                    $phoneO = preg_replace('/[^0-9]/', '', $phoneORaw);
                    if (str_starts_with($phoneO, '62'))
                        $phoneO = '0' . substr($phoneO, 2);
                    elseif (str_starts_with($phoneO, '8'))
                        $phoneO = '0' . $phoneO;

                    if (strlen($phoneO) < 10 || strlen($phoneO) > 15) {
                        $errorMessages[] = "Baris $rowNum (Santri $namaS): Nomor HP orang tua '$phoneORaw' tidak valid (harus 10-15 digit angka).";
                        continue;
                    }

                    if (Student::where('nis', $nis)->exists()) {
                        $errorMessages[] = "Baris $rowNum (Santri $namaS): NISN $nis sudah ada.";
                        continue;
                    }

                    // Gunakan Guru Pendamping & Tahun Ajaran dari pilihan di Form Modal
                    $rowGuruId = $fallbackGuruId;
                    $rowAcademicYear = $fallbackAcademicYear;

                    $parent = User::where('email', $emailO)->first();
                    if (!$parent) {
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

                    $newStudent = Student::create([
                        'nis' => $nis,
                        'name' => $namaS,
                        'gender' => $genderS,
                        'guru_id' => $rowGuruId,
                        'target_juz' => 30
                    ]);
                    $newStudent->parents()->syncWithoutDetaching([$parent->id]);

                    // Selalu catat penugasan di StudentAssignment berdasarkan Tahun Ajaran & Guru yang dipilih pada form modal
                    StudentAssignment::updateOrCreate(
                        [
                            'student_id' => $newStudent->id,
                            'academic_year' => $rowAcademicYear,
                        ],
                        [
                            'guru_id' => $rowGuruId,
                        ]
                    );

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

