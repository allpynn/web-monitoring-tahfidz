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
            'file' => 'required|mimes:csv,txt'
        ]);

        $file = $request->file('file');
        
        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            // Deteksi delimiter (koma atau titik koma) dengan membaca baris pertama
            $firstLine = fgets($handle);
            $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
            rewind($handle); // Kembali ke awal file
            
            $header = fgetcsv($handle, 1000, $delimiter);
            
            // Auto detect CSV type based on headers
            $headerLower = array_map('strtolower', array_map('trim', $header ?? []));
            $isGuruCsv = in_array('nip', $headerLower);

            $successCount = 0;
            $errorMessages = [];
            $rowNum = 1; // baris 1 adalah header
            
            DB::beginTransaction();
            try {
                while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                    $rowNum++;
                    
                    // Lewati baris kosong
                    if(empty(array_filter($data))) continue;

                    if ($isGuruCsv) {
                        // Skema Guru: Nama | NIP | No telp | Email
                        if (count($data) < 4) {
                            $errorMessages[] = "Baris $rowNum: Format kolom guru tidak lengkap (membutuhkan 4 kolom: Nama, NIP, No Telp, Email).";
                            continue;
                        }

                        $nama = trim($data[0] ?? '');
                        $nip = trim($data[1] ?? ''); 
                        $phone = ltrim(trim($data[2] ?? ''), '0');
                        $email = trim($data[3] ?? '');

                        if (!$email) {
                            $errorMessages[] = "Baris $rowNum ($nama): Gagal. Email wajib diisi untuk Guru.";
                            continue;
                        }

                        if (User::where('email', $email)->exists()) {
                            $errorMessages[] = "Baris $rowNum ($nama): Gagal. Email '$email' sudah terekam di sistem.";
                            continue;
                        }

                        User::create([
                            'name' => $nama,
                            'email' => $email,
                            'phone' => $phone,
                            'role' => 'guru',
                            'password' => Hash::make($phone), // Password = Nomor HP
                            'email_verified_at' => now(),
                        ]);
                        $successCount++;
                    } else {
                        // Skema Santri: Nama Santri | NIS | Nama Orang Tua | Email Orang Tua | (opsional: No Telp)
                        if (count($data) < 4) {
                            $errorMessages[] = "Baris $rowNum: Format kolom santri tidak lengkap (membutuhkan minimal 4 kolom).";
                            continue;
                        }

                        $namaSantri = trim($data[0] ?? '');
                        $nis = trim($data[1] ?? '');
                        $namaOrangTua = trim($data[2] ?? '');
                        $emailOrangTua = trim($data[3] ?? '');
                        $phoneOrangTua = ltrim(trim($data[4] ?? '08' . rand(1000,9999)), '0');

                        // Validasi NISN (10 angka)
                        if (strlen($nis) !== 10 || !is_numeric($nis)) {
                            $errorMessages[] = "Baris $rowNum ($namaSantri): NISN '$nis' gagal ditambahkan (harus persis 10 angka).";
                            continue;
                        }

                        // Pengecekan Duplikasi NISN
                        if (Student::where('nis', $nis)->exists()) {
                            $errorMessages[] = "Baris $rowNum ($namaSantri): NISN '$nis' gagal ditambahkan karena sudah terdaftar di sistem.";
                            continue;
                        }
                        
                        // Create or find Parent
                        $parent = User::firstOrCreate(
                            ['email' => $emailOrangTua],
                            [
                                'name' => $namaOrangTua,
                                'phone' => $phoneOrangTua,
                                'password' => Hash::make($phoneOrangTua), // Password = Nomor HP
                                'role' => 'orang_tua',
                                'email_verified_at' => now(),
                            ]
                        );

                        // Create Student
                        $student = Student::create([
                            'nis' => $nis,
                            'name' => $namaSantri,
                            'target_juz' => 30 // default fallback
                        ]);

                        // Sync pivot table without detaching others
                        $student->parents()->syncWithoutDetaching([$parent->id]);
                        $successCount++;
                    }
                }
                
                DB::commit();

                $targetName = $isGuruCsv ? 'guru' : 'santri';

                // Kembalikan feedback import
                if (count($errorMessages) > 0) {
                    return redirect()->back()->with('import_warning', [
                        'success' => $successCount,
                        'errors' => $errorMessages,
                        'tipe' => $targetName
                    ]);
                }

                return redirect()->back()->with('success', "$successCount data $targetName berhasil diimport secara massal.");
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memproses import: ' . $e->getMessage());
            } finally {
                fclose($handle);
            }
        }
        
        return redirect()->back()->with('error', 'Gagal membaca file.');
    }
}
