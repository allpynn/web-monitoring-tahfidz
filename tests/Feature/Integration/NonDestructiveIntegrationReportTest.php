<?php

namespace Tests\Feature\Integration;

use App\Models\Pesan;
use App\Models\RiwayatHafalan;
use App\Models\Student;
use App\Models\StudentAssignment;
use App\Models\StudentTarget;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Helpers\IntegrationPdfReporter;
use Tests\TestCase;

/**
 * Pengujian integrasi terstruktur untuk laporan, tanpa menghapus isi database.
 *
 * Catatan: setiap test menggunakan DatabaseTransactions agar perubahan test tidak permanen.
 */
class NonDestructiveIntegrationReportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    /** @test */
    public function pengujian_integrasi_autentikasi_dan_otorisasi(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);
        $orangTua = User::factory()->create(['role' => 'orang_tua']);

        $loginAdmin = $this->post('/login', ['email' => $admin->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($admin);
        $loginAdmin->assertRedirect(route('admin.dashboard'));
        auth()->logout();

        $loginGuru = $this->post('/login', ['email' => $guru->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($guru);
        $loginGuru->assertRedirect(route('guru.dashboard'));
        auth()->logout();

        $loginOrtu = $this->post('/login', ['email' => $orangTua->email, 'password' => 'password']);
        $this->assertAuthenticatedAs($orangTua);
        $loginOrtu->assertRedirect(route('parent.dashboard'));
        auth()->logout();

        $this->actingAs($orangTua)
            ->get(route('guru.hafalan.index'))
            ->assertForbidden();

        $this->actingAs($guru)
            ->get(route('admin.students.index'))
            ->assertForbidden();

        IntegrationPdfReporter::record('Pengujian Integrasi Autentikasi dan Otorisasi', [
            ['komponen' => 'Auth Controller', 'metode' => 'Role-based login', 'skenario' => 'Login dan redirect sesuai peran (admin, guru, orang_tua)', 'hasil' => 'PASSED'],
            ['komponen' => 'Middleware & Guard', 'metode' => 'Authorization enforcement', 'skenario' => 'Akses modul terlarang ditolak berdasarkan peran', 'hasil' => 'PASSED'],
        ]);
    }

    /** @test */
    public function pengujian_integrasi_pengelolaan_data_santri(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $guru = User::factory()->create(['role' => 'guru']);

        $response = $this->actingAs($admin)
            ->post(route('admin.students.store'), [
                'name'           => 'Santri Integration Test',
                'gender'         => 'Laki-laki',
                'nis'            => '2025012345',
                'guru_id'        => $guru->id,
                'target_juz'     => [10],
                'target_date'    => [now()->addMonths(3)->format('Y-m-d')],
                'parent_names'   => ['Bapak Integration'],
                'parent_phones'  => ['081234500001'],
                'parent_genders' => ['Laki-laki'],
                'parent_emails'  => ['integration.parent@test.com'],
            ]);

        $response->assertRedirect(route('admin.students.index'));
        $student = Student::where('nis', '2025012345')->first();
        $this->assertNotNull($student);
        $this->assertDatabaseHas('student_assignments', ['student_id' => $student->id, 'guru_id' => $guru->id]);
        $this->assertDatabaseHas('student_targets', ['student_id' => $student->id, 'target_juz' => 10]);

        IntegrationPdfReporter::record('Pengujian Integrasi Pengelolaan Data Santri', [
            ['komponen' => 'Student Controller', 'metode' => 'Student creation', 'skenario' => 'Admin mendaftarkan santri baru dan membuat relasi target serta assignment', 'hasil' => 'PASSED'],
            ['komponen' => 'Student Target', 'metode' => 'Target persistence', 'skenario' => 'Target hafalan tersimpan bersamaan dengan santri baru', 'hasil' => 'PASSED'],
        ]);
    }

    /** @test */
    public function pengujian_integrasi_pengelolaan_hafalan(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $student = Student::factory()->create(['guru_id' => $guru->id]);
        StudentAssignment::create(['student_id' => $student->id, 'guru_id' => $guru->id, 'academic_year' => now()->year . '/' . (now()->year + 1)]);

        $response = $this->actingAs($guru)
            ->post(route('guru.hafalan.store'), [
                'student_id'  => $student->id,
                'is_present'  => true,
                'juz'         => 1,
                'surah'       => 'Al-Fatihah',
                'ayat_dari'   => 1,
                'ayat_sampai' => 7,
                'status'      => 'Lancar',
                'tanggal'     => now()->format('Y-m-d'),
            ]);

        $response->assertRedirect(route('guru.hafalan.index'));
        $this->assertDatabaseHas('riwayat_hafalan', ['student_id' => $student->id, 'surah' => 'Al-Fatihah', 'ayat' => '1-7', 'status' => 'Lancar']);

        $invalid = $this->actingAs($guru)
            ->post(route('guru.hafalan.store'), [
                'student_id'  => $student->id,
                'is_present'  => true,
                'juz'         => 1,
                'surah'       => 'Al-Fatihah',
                'ayat_dari'   => 1,
                'ayat_sampai' => 999,
                'status'      => 'Lancar',
                'tanggal'     => now()->format('Y-m-d'),
            ]);

        $invalid->assertSessionHasErrors('ayat_sampai');

        IntegrationPdfReporter::record('Pengujian Integrasi Pengelolaan Hafalan', [
            ['komponen' => 'Hafalan Controller', 'metode' => 'Hafalan input', 'skenario' => 'Guru menyimpan setoran hafalan dan sistem menyimpan riwayat benar', 'hasil' => 'PASSED'],
            ['komponen' => 'Hafalan Validator', 'metode' => 'Range ayat validation', 'skenario' => 'Sistem menolak ayat_sampai yang melebihi jumlah ayat surah', 'hasil' => 'PASSED'],
        ]);
    }

    /** @test */
    public function pengujian_integrasi_komunikasi_real_time(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $parent = User::factory()->create(['role' => 'orang_tua']);
        $student = Student::factory()->create(['guru_id' => $guru->id]);
        $student->parents()->attach($parent->id);

        $response = $this->actingAs($parent)
            ->post(route('parent.messages.send', ['student' => $student->id]), [
                'message' => 'Cek perkembangan hafalan saya',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pesan', ['sender_id' => $parent->id, 'receiver_id' => $guru->id, 'student_id' => $student->id]);

        $pesan = Pesan::where('sender_id', $parent->id)->where('student_id', $student->id)->first();
        $reply = $this->actingAs($guru)
            ->post(route('guru.messages.reply', ['pesan' => $pesan->id]), ['message' => 'Teruskan latihan harian.']);

        $reply->assertRedirect();
        $this->assertDatabaseHas('pesan', ['sender_id' => $guru->id, 'receiver_id' => $parent->id, 'student_id' => $student->id]);

        IntegrationPdfReporter::record('Pengujian Integrasi Komunikasi Real-Time', [
            ['komponen' => 'Pesan Controller', 'metode' => 'Parent to Guru message', 'skenario' => 'Orang tua mengirim pesan ke guru dan pesan tersimpan ke tabel pesan', 'hasil' => 'PASSED'],
            ['komponen' => 'Pesan Controller', 'metode' => 'Guru reply flow', 'skenario' => 'Guru membalas pesan dan sistem menyimpan balasan secara benar', 'hasil' => 'PASSED'],
        ]);
    }

    /** @test */
    public function pengujian_integrasi_monitoring_hafalan(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $student = Student::factory()->create(['guru_id' => $guru->id]);
        StudentAssignment::create(['student_id' => $student->id, 'guru_id' => $guru->id, 'academic_year' => now()->year . '/' . (now()->year + 1)]);
        StudentTarget::factory()->create(['student_id' => $student->id, 'target_juz' => 30, 'target_date' => now()->addMonths(6)->format('Y-m-d')]);

        RiwayatHafalan::factory()->create(['student_id' => $student->id, 'guru_id' => $guru->id, 'surah' => 'Al-Fatihah', 'ayat' => '1-7', 'is_present' => true, 'status' => 'Lancar', 'tanggal' => now()->subDays(1)->format('Y-m-d')]);
        RiwayatHafalan::factory()->create(['student_id' => $student->id, 'guru_id' => $guru->id, 'surah' => 'An-Nas', 'ayat' => '1-6', 'is_present' => true, 'status' => 'Perlu Perbaikan', 'tanggal' => now()->subDays(2)->format('Y-m-d')]);

        $service = new \App\Services\RiwayatHafalanService();
        $analytics = $service->getAnalytics($student->fresh());
        $prediction = $service->getPrediction($student->fresh());

        $this->assertArrayHasKey('quality', $analytics);
        $this->assertEquals(1, $analytics['quality']['lancar']);
        $this->assertEquals(1, $analytics['quality']['perbaikan']);
        $this->assertArrayHasKey('attendance', $analytics);
        $this->assertIsString($prediction);
        $this->assertNotEmpty($prediction);

        IntegrationPdfReporter::record('Pengujian Integrasi Monitoring Hafalan', [
            ['komponen' => 'Analytics Service', 'metode' => 'Quality aggregation', 'skenario' => 'Sistem menghitung perbandingan Lancar vs Perlu Perbaikan', 'hasil' => 'PASSED'],
            ['komponen' => 'Prediction Service', 'metode' => 'Estimate generation', 'skenario' => 'Sistem menghasilkan prediksi kelulusan target berdasarkan progress hafalan', 'hasil' => 'PASSED'],
        ]);
    }

    /** @test */
    public function pengujian_integrasi_pembuatan_laporan(): void
    {
        $guru = User::factory()->create(['role' => 'guru']);
        $student = Student::factory()->create(['guru_id' => $guru->id]);
        StudentAssignment::create(['student_id' => $student->id, 'guru_id' => $guru->id, 'academic_year' => now()->year . '/' . (now()->year + 1)]);
        RiwayatHafalan::factory()->create(['student_id' => $student->id, 'guru_id' => $guru->id, 'surah' => 'Al-Fatihah', 'status' => 'Lancar']);

        $studentPdf = $this->actingAs($guru)
            ->get(route('guru.hafalan.export', ['student' => $student->id]));
        $studentPdf->assertOk();
        $studentPdf->assertHeader('content-type', 'application/pdf');

        IntegrationPdfReporter::record('Pengujian Integrasi Pembuatan Laporan', [
            ['komponen' => 'Report Generator', 'metode' => 'Student report PDF', 'skenario' => 'Sistem menggenerate laporan PDF santri berdasarkan data hafalan', 'hasil' => 'PASSED'],
        ]);

        $out = storage_path('app/integration_testing.pdf');
        IntegrationPdfReporter::generatePdfFromResults($out);
        $this->assertFileExists($out);
    }
}
