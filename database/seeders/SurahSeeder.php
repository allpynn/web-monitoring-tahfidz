<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Surah;

class SurahSeeder extends Seeder
{
    public function run(): void
    {
        $surahs = array (
  0 => 
  array (
    'nomor' => 1,
    'nama_arab' => 'الفاتحة',
    'nama_latin' => 'Al-Fatihah',
    'nama_indonesia' => 'Pembukaan',
    'jumlah_ayat' => 7,
    'juz_awal' => 1,
    'tempat_turun' => 'Makkiyah',
  ),
  1 => 
  array (
    'nomor' => 2,
    'nama_arab' => 'البقرة',
    'nama_latin' => 'Al-Baqarah',
    'nama_indonesia' => 'Sapi',
    'jumlah_ayat' => 286,
    'juz_awal' => 1,
    'tempat_turun' => 'Madaniyah',
  ),
  2 => 
  array (
    'nomor' => 3,
    'nama_arab' => 'اٰل عمران',
    'nama_latin' => 'Ali \'Imran',
    'nama_indonesia' => 'Keluarga Imran',
    'jumlah_ayat' => 200,
    'juz_awal' => 3,
    'tempat_turun' => 'Madaniyah',
  ),
  3 => 
  array (
    'nomor' => 4,
    'nama_arab' => 'النساۤء',
    'nama_latin' => 'An-Nisa\'',
    'nama_indonesia' => 'Wanita',
    'jumlah_ayat' => 176,
    'juz_awal' => 4,
    'tempat_turun' => 'Madaniyah',
  ),
  4 => 
  array (
    'nomor' => 5,
    'nama_arab' => 'الماۤئدة',
    'nama_latin' => 'Al-Ma\'idah',
    'nama_indonesia' => 'Hidangan',
    'jumlah_ayat' => 120,
    'juz_awal' => 6,
    'tempat_turun' => 'Madaniyah',
  ),
  5 => 
  array (
    'nomor' => 6,
    'nama_arab' => 'الانعام',
    'nama_latin' => 'Al-An\'am',
    'nama_indonesia' => 'Binatang Ternak',
    'jumlah_ayat' => 165,
    'juz_awal' => 7,
    'tempat_turun' => 'Makkiyah',
  ),
  6 => 
  array (
    'nomor' => 7,
    'nama_arab' => 'الاعراف',
    'nama_latin' => 'Al-A\'raf',
    'nama_indonesia' => 'Tempat Tertinggi',
    'jumlah_ayat' => 206,
    'juz_awal' => 8,
    'tempat_turun' => 'Makkiyah',
  ),
  7 => 
  array (
    'nomor' => 8,
    'nama_arab' => 'الانفال',
    'nama_latin' => 'Al-Anfal',
    'nama_indonesia' => 'Rampasan Perang',
    'jumlah_ayat' => 75,
    'juz_awal' => 9,
    'tempat_turun' => 'Madaniyah',
  ),
  8 => 
  array (
    'nomor' => 9,
    'nama_arab' => 'التوبة',
    'nama_latin' => 'At-Taubah',
    'nama_indonesia' => 'Pengampunan',
    'jumlah_ayat' => 129,
    'juz_awal' => 10,
    'tempat_turun' => 'Madaniyah',
  ),
  9 => 
  array (
    'nomor' => 10,
    'nama_arab' => 'يونس',
    'nama_latin' => 'Yunus',
    'nama_indonesia' => 'Yunus',
    'jumlah_ayat' => 109,
    'juz_awal' => 11,
    'tempat_turun' => 'Makkiyah',
  ),
  10 => 
  array (
    'nomor' => 11,
    'nama_arab' => 'هود',
    'nama_latin' => 'Hud',
    'nama_indonesia' => 'Hud',
    'jumlah_ayat' => 123,
    'juz_awal' => 11,
    'tempat_turun' => 'Makkiyah',
  ),
  11 => 
  array (
    'nomor' => 12,
    'nama_arab' => 'يوسف',
    'nama_latin' => 'Yusuf',
    'nama_indonesia' => 'Yusuf',
    'jumlah_ayat' => 111,
    'juz_awal' => 12,
    'tempat_turun' => 'Makkiyah',
  ),
  12 => 
  array (
    'nomor' => 13,
    'nama_arab' => 'الرّعد',
    'nama_latin' => 'Ar-Ra\'d',
    'nama_indonesia' => 'Guruh',
    'jumlah_ayat' => 43,
    'juz_awal' => 13,
    'tempat_turun' => 'Madaniyah',
  ),
  13 => 
  array (
    'nomor' => 14,
    'nama_arab' => 'ابرٰهيم',
    'nama_latin' => 'Ibrahim',
    'nama_indonesia' => 'Ibrahim',
    'jumlah_ayat' => 52,
    'juz_awal' => 13,
    'tempat_turun' => 'Makkiyah',
  ),
  14 => 
  array (
    'nomor' => 15,
    'nama_arab' => 'الحجر',
    'nama_latin' => 'Al-Hijr',
    'nama_indonesia' => 'Hijr',
    'jumlah_ayat' => 99,
    'juz_awal' => 14,
    'tempat_turun' => 'Makkiyah',
  ),
  15 => 
  array (
    'nomor' => 16,
    'nama_arab' => 'النحل',
    'nama_latin' => 'An-Nahl',
    'nama_indonesia' => 'Lebah',
    'jumlah_ayat' => 128,
    'juz_awal' => 14,
    'tempat_turun' => 'Makkiyah',
  ),
  16 => 
  array (
    'nomor' => 17,
    'nama_arab' => 'الاسراۤء',
    'nama_latin' => 'Al-Isra\'',
    'nama_indonesia' => 'Memperjalankan Malam Hari',
    'jumlah_ayat' => 111,
    'juz_awal' => 15,
    'tempat_turun' => 'Makkiyah',
  ),
  17 => 
  array (
    'nomor' => 18,
    'nama_arab' => 'الكهف',
    'nama_latin' => 'Al-Kahf',
    'nama_indonesia' => 'Goa',
    'jumlah_ayat' => 110,
    'juz_awal' => 15,
    'tempat_turun' => 'Makkiyah',
  ),
  18 => 
  array (
    'nomor' => 19,
    'nama_arab' => 'مريم',
    'nama_latin' => 'Maryam',
    'nama_indonesia' => 'Maryam',
    'jumlah_ayat' => 98,
    'juz_awal' => 16,
    'tempat_turun' => 'Makkiyah',
  ),
  19 => 
  array (
    'nomor' => 20,
    'nama_arab' => 'طٰهٰ',
    'nama_latin' => 'Taha',
    'nama_indonesia' => 'Taha',
    'jumlah_ayat' => 135,
    'juz_awal' => 16,
    'tempat_turun' => 'Makkiyah',
  ),
  20 => 
  array (
    'nomor' => 21,
    'nama_arab' => 'الانبياۤء',
    'nama_latin' => 'Al-Anbiya\'',
    'nama_indonesia' => 'Para Nabi',
    'jumlah_ayat' => 112,
    'juz_awal' => 17,
    'tempat_turun' => 'Makkiyah',
  ),
  21 => 
  array (
    'nomor' => 22,
    'nama_arab' => 'الحج',
    'nama_latin' => 'Al-Hajj',
    'nama_indonesia' => 'Haji',
    'jumlah_ayat' => 78,
    'juz_awal' => 17,
    'tempat_turun' => 'Madaniyah',
  ),
  22 => 
  array (
    'nomor' => 23,
    'nama_arab' => 'المؤمنون',
    'nama_latin' => 'Al-Mu\'minun',
    'nama_indonesia' => 'Orang-Orang Mukmin',
    'jumlah_ayat' => 118,
    'juz_awal' => 18,
    'tempat_turun' => 'Makkiyah',
  ),
  23 => 
  array (
    'nomor' => 24,
    'nama_arab' => 'النّور',
    'nama_latin' => 'An-Nur',
    'nama_indonesia' => 'Cahaya',
    'jumlah_ayat' => 64,
    'juz_awal' => 18,
    'tempat_turun' => 'Madaniyah',
  ),
  24 => 
  array (
    'nomor' => 25,
    'nama_arab' => 'الفرقان',
    'nama_latin' => 'Al-Furqan',
    'nama_indonesia' => 'Pembeda',
    'jumlah_ayat' => 77,
    'juz_awal' => 18,
    'tempat_turun' => 'Makkiyah',
  ),
  25 => 
  array (
    'nomor' => 26,
    'nama_arab' => 'الشعراۤء',
    'nama_latin' => 'Asy-Syu\'ara\'',
    'nama_indonesia' => 'Para Penyair',
    'jumlah_ayat' => 227,
    'juz_awal' => 19,
    'tempat_turun' => 'Makkiyah',
  ),
  26 => 
  array (
    'nomor' => 27,
    'nama_arab' => 'النمل',
    'nama_latin' => 'An-Naml',
    'nama_indonesia' => 'Semut-semut',
    'jumlah_ayat' => 93,
    'juz_awal' => 19,
    'tempat_turun' => 'Makkiyah',
  ),
  27 => 
  array (
    'nomor' => 28,
    'nama_arab' => 'القصص',
    'nama_latin' => 'Al-Qasas',
    'nama_indonesia' => 'Kisah-Kisah',
    'jumlah_ayat' => 88,
    'juz_awal' => 20,
    'tempat_turun' => 'Makkiyah',
  ),
  28 => 
  array (
    'nomor' => 29,
    'nama_arab' => 'العنكبوت',
    'nama_latin' => 'Al-\'Ankabut',
    'nama_indonesia' => 'Laba-Laba',
    'jumlah_ayat' => 69,
    'juz_awal' => 20,
    'tempat_turun' => 'Makkiyah',
  ),
  29 => 
  array (
    'nomor' => 30,
    'nama_arab' => 'الرّوم',
    'nama_latin' => 'Ar-Rum',
    'nama_indonesia' => 'Romawi',
    'jumlah_ayat' => 60,
    'juz_awal' => 21,
    'tempat_turun' => 'Makkiyah',
  ),
  30 => 
  array (
    'nomor' => 31,
    'nama_arab' => 'لقمٰن',
    'nama_latin' => 'Luqman',
    'nama_indonesia' => 'Luqman',
    'jumlah_ayat' => 34,
    'juz_awal' => 21,
    'tempat_turun' => 'Makkiyah',
  ),
  31 => 
  array (
    'nomor' => 32,
    'nama_arab' => 'السّجدة',
    'nama_latin' => 'As-Sajdah',
    'nama_indonesia' => 'Sajdah',
    'jumlah_ayat' => 30,
    'juz_awal' => 21,
    'tempat_turun' => 'Makkiyah',
  ),
  32 => 
  array (
    'nomor' => 33,
    'nama_arab' => 'الاحزاب',
    'nama_latin' => 'Al-Ahzab',
    'nama_indonesia' => 'Golongan Yang Bersekutu',
    'jumlah_ayat' => 73,
    'juz_awal' => 21,
    'tempat_turun' => 'Madaniyah',
  ),
  33 => 
  array (
    'nomor' => 34,
    'nama_arab' => 'سبأ',
    'nama_latin' => 'Saba\'',
    'nama_indonesia' => 'Saba\'',
    'jumlah_ayat' => 54,
    'juz_awal' => 22,
    'tempat_turun' => 'Makkiyah',
  ),
  34 => 
  array (
    'nomor' => 35,
    'nama_arab' => 'فاطر',
    'nama_latin' => 'Fatir',
    'nama_indonesia' => 'Maha Pencipta',
    'jumlah_ayat' => 45,
    'juz_awal' => 22,
    'tempat_turun' => 'Makkiyah',
  ),
  35 => 
  array (
    'nomor' => 36,
    'nama_arab' => 'يٰسۤ',
    'nama_latin' => 'Yasin',
    'nama_indonesia' => 'Yasin',
    'jumlah_ayat' => 83,
    'juz_awal' => 22,
    'tempat_turun' => 'Makkiyah',
  ),
  36 => 
  array (
    'nomor' => 37,
    'nama_arab' => 'الصّٰۤفّٰت',
    'nama_latin' => 'As-Saffat',
    'nama_indonesia' => 'Barisan-Barisan',
    'jumlah_ayat' => 182,
    'juz_awal' => 23,
    'tempat_turun' => 'Makkiyah',
  ),
  37 => 
  array (
    'nomor' => 38,
    'nama_arab' => 'ص',
    'nama_latin' => 'Sad',
    'nama_indonesia' => 'Sad',
    'jumlah_ayat' => 88,
    'juz_awal' => 23,
    'tempat_turun' => 'Makkiyah',
  ),
  38 => 
  array (
    'nomor' => 39,
    'nama_arab' => 'الزمر',
    'nama_latin' => 'Az-Zumar',
    'nama_indonesia' => 'Rombongan',
    'jumlah_ayat' => 75,
    'juz_awal' => 23,
    'tempat_turun' => 'Makkiyah',
  ),
  39 => 
  array (
    'nomor' => 40,
    'nama_arab' => 'غافر',
    'nama_latin' => 'Ghafir',
    'nama_indonesia' => 'Maha Pengampun',
    'jumlah_ayat' => 85,
    'juz_awal' => 24,
    'tempat_turun' => 'Makkiyah',
  ),
  40 => 
  array (
    'nomor' => 41,
    'nama_arab' => 'فصّلت',
    'nama_latin' => 'Fussilat',
    'nama_indonesia' => 'Yang Dijelaskan',
    'jumlah_ayat' => 54,
    'juz_awal' => 24,
    'tempat_turun' => 'Makkiyah',
  ),
  41 => 
  array (
    'nomor' => 42,
    'nama_arab' => 'الشورى',
    'nama_latin' => 'Asy-Syura',
    'nama_indonesia' => 'Musyawarah',
    'jumlah_ayat' => 53,
    'juz_awal' => 25,
    'tempat_turun' => 'Makkiyah',
  ),
  42 => 
  array (
    'nomor' => 43,
    'nama_arab' => 'الزخرف',
    'nama_latin' => 'Az-Zukhruf',
    'nama_indonesia' => 'Perhiasan',
    'jumlah_ayat' => 89,
    'juz_awal' => 25,
    'tempat_turun' => 'Makkiyah',
  ),
  43 => 
  array (
    'nomor' => 44,
    'nama_arab' => 'الدخان',
    'nama_latin' => 'Ad-Dukhan',
    'nama_indonesia' => 'Kabut',
    'jumlah_ayat' => 59,
    'juz_awal' => 25,
    'tempat_turun' => 'Makkiyah',
  ),
  44 => 
  array (
    'nomor' => 45,
    'nama_arab' => 'الجاثية',
    'nama_latin' => 'Al-Jasiyah',
    'nama_indonesia' => 'Berlutut',
    'jumlah_ayat' => 37,
    'juz_awal' => 25,
    'tempat_turun' => 'Makkiyah',
  ),
  45 => 
  array (
    'nomor' => 46,
    'nama_arab' => 'الاحقاف',
    'nama_latin' => 'Al-Ahqaf',
    'nama_indonesia' => 'Bukit Pasir',
    'jumlah_ayat' => 35,
    'juz_awal' => 26,
    'tempat_turun' => 'Makkiyah',
  ),
  46 => 
  array (
    'nomor' => 47,
    'nama_arab' => 'محمّد',
    'nama_latin' => 'Muhammad',
    'nama_indonesia' => 'Muhammad',
    'jumlah_ayat' => 38,
    'juz_awal' => 26,
    'tempat_turun' => 'Madaniyah',
  ),
  47 => 
  array (
    'nomor' => 48,
    'nama_arab' => 'الفتح',
    'nama_latin' => 'Al-Fath',
    'nama_indonesia' => 'Kemenangan',
    'jumlah_ayat' => 29,
    'juz_awal' => 26,
    'tempat_turun' => 'Madaniyah',
  ),
  48 => 
  array (
    'nomor' => 49,
    'nama_arab' => 'الحجرٰت',
    'nama_latin' => 'Al-Hujurat',
    'nama_indonesia' => 'Kamar-Kamar',
    'jumlah_ayat' => 18,
    'juz_awal' => 26,
    'tempat_turun' => 'Madaniyah',
  ),
  49 => 
  array (
    'nomor' => 50,
    'nama_arab' => 'ق',
    'nama_latin' => 'Qaf',
    'nama_indonesia' => 'Qaf',
    'jumlah_ayat' => 45,
    'juz_awal' => 26,
    'tempat_turun' => 'Makkiyah',
  ),
  50 => 
  array (
    'nomor' => 51,
    'nama_arab' => 'الذّٰريٰت',
    'nama_latin' => 'Az-Zariyat',
    'nama_indonesia' => 'Angin yang Menerbangkan',
    'jumlah_ayat' => 60,
    'juz_awal' => 26,
    'tempat_turun' => 'Makkiyah',
  ),
  51 => 
  array (
    'nomor' => 52,
    'nama_arab' => 'الطور',
    'nama_latin' => 'At-Tur',
    'nama_indonesia' => 'Bukit Tursina',
    'jumlah_ayat' => 49,
    'juz_awal' => 27,
    'tempat_turun' => 'Makkiyah',
  ),
  52 => 
  array (
    'nomor' => 53,
    'nama_arab' => 'النجم',
    'nama_latin' => 'An-Najm',
    'nama_indonesia' => 'Bintang',
    'jumlah_ayat' => 62,
    'juz_awal' => 27,
    'tempat_turun' => 'Makkiyah',
  ),
  53 => 
  array (
    'nomor' => 54,
    'nama_arab' => 'القمر',
    'nama_latin' => 'Al-Qamar',
    'nama_indonesia' => 'Bulan',
    'jumlah_ayat' => 55,
    'juz_awal' => 27,
    'tempat_turun' => 'Makkiyah',
  ),
  54 => 
  array (
    'nomor' => 55,
    'nama_arab' => 'الرحمن',
    'nama_latin' => 'Ar-Rahman',
    'nama_indonesia' => 'Maha Pengasih',
    'jumlah_ayat' => 78,
    'juz_awal' => 27,
    'tempat_turun' => 'Madaniyah',
  ),
  55 => 
  array (
    'nomor' => 56,
    'nama_arab' => 'الواقعة',
    'nama_latin' => 'Al-Waqi\'ah',
    'nama_indonesia' => 'Hari Kiamat',
    'jumlah_ayat' => 96,
    'juz_awal' => 27,
    'tempat_turun' => 'Makkiyah',
  ),
  56 => 
  array (
    'nomor' => 57,
    'nama_arab' => 'الحديد',
    'nama_latin' => 'Al-Hadid',
    'nama_indonesia' => 'Besi',
    'jumlah_ayat' => 29,
    'juz_awal' => 27,
    'tempat_turun' => 'Madaniyah',
  ),
  57 => 
  array (
    'nomor' => 58,
    'nama_arab' => 'المجادلة',
    'nama_latin' => 'Al-Mujadilah',
    'nama_indonesia' => 'Gugatan',
    'jumlah_ayat' => 22,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  58 => 
  array (
    'nomor' => 59,
    'nama_arab' => 'الحشر',
    'nama_latin' => 'Al-Hasyr',
    'nama_indonesia' => 'Pengusiran',
    'jumlah_ayat' => 24,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  59 => 
  array (
    'nomor' => 60,
    'nama_arab' => 'الممتحنة',
    'nama_latin' => 'Al-Mumtahanah',
    'nama_indonesia' => 'Wanita Yang Diuji',
    'jumlah_ayat' => 13,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  60 => 
  array (
    'nomor' => 61,
    'nama_arab' => 'الصّفّ',
    'nama_latin' => 'As-Saff',
    'nama_indonesia' => 'Barisan',
    'jumlah_ayat' => 14,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  61 => 
  array (
    'nomor' => 62,
    'nama_arab' => 'الجمعة',
    'nama_latin' => 'Al-Jumu\'ah',
    'nama_indonesia' => 'Jumat',
    'jumlah_ayat' => 11,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  62 => 
  array (
    'nomor' => 63,
    'nama_arab' => 'المنٰفقون',
    'nama_latin' => 'Al-Munafiqun',
    'nama_indonesia' => 'Orang-Orang Munafik',
    'jumlah_ayat' => 11,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  63 => 
  array (
    'nomor' => 64,
    'nama_arab' => 'التغابن',
    'nama_latin' => 'At-Tagabun',
    'nama_indonesia' => 'Pengungkapan Kesalahan',
    'jumlah_ayat' => 18,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  64 => 
  array (
    'nomor' => 65,
    'nama_arab' => 'الطلاق',
    'nama_latin' => 'At-Talaq',
    'nama_indonesia' => 'Talak',
    'jumlah_ayat' => 12,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  65 => 
  array (
    'nomor' => 66,
    'nama_arab' => 'التحريم',
    'nama_latin' => 'At-Tahrim',
    'nama_indonesia' => 'Pengharaman',
    'jumlah_ayat' => 12,
    'juz_awal' => 28,
    'tempat_turun' => 'Madaniyah',
  ),
  66 => 
  array (
    'nomor' => 67,
    'nama_arab' => 'الملك',
    'nama_latin' => 'Al-Mulk',
    'nama_indonesia' => 'Kerajaan',
    'jumlah_ayat' => 30,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  67 => 
  array (
    'nomor' => 68,
    'nama_arab' => 'القلم',
    'nama_latin' => 'Al-Qalam',
    'nama_indonesia' => 'Pena',
    'jumlah_ayat' => 52,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  68 => 
  array (
    'nomor' => 69,
    'nama_arab' => 'الحاۤقّة',
    'nama_latin' => 'Al-Haqqah',
    'nama_indonesia' => 'Hari Kiamat',
    'jumlah_ayat' => 52,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  69 => 
  array (
    'nomor' => 70,
    'nama_arab' => 'المعارج',
    'nama_latin' => 'Al-Ma\'arij',
    'nama_indonesia' => 'Tempat Naik',
    'jumlah_ayat' => 44,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  70 => 
  array (
    'nomor' => 71,
    'nama_arab' => 'نوح',
    'nama_latin' => 'Nuh',
    'nama_indonesia' => 'Nuh',
    'jumlah_ayat' => 28,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  71 => 
  array (
    'nomor' => 72,
    'nama_arab' => 'الجن',
    'nama_latin' => 'Al-Jinn',
    'nama_indonesia' => 'Jin',
    'jumlah_ayat' => 28,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  72 => 
  array (
    'nomor' => 73,
    'nama_arab' => 'المزّمّل',
    'nama_latin' => 'Al-Muzzammil',
    'nama_indonesia' => 'Orang Yang Berselimut',
    'jumlah_ayat' => 20,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  73 => 
  array (
    'nomor' => 74,
    'nama_arab' => 'المدّثّر',
    'nama_latin' => 'Al-Muddassir',
    'nama_indonesia' => 'Orang Yang Berkemul',
    'jumlah_ayat' => 56,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  74 => 
  array (
    'nomor' => 75,
    'nama_arab' => 'القيٰمة',
    'nama_latin' => 'Al-Qiyamah',
    'nama_indonesia' => 'Hari Kiamat',
    'jumlah_ayat' => 40,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  75 => 
  array (
    'nomor' => 76,
    'nama_arab' => 'الانسان',
    'nama_latin' => 'Al-Insan',
    'nama_indonesia' => 'Manusia',
    'jumlah_ayat' => 31,
    'juz_awal' => 29,
    'tempat_turun' => 'Madaniyah',
  ),
  76 => 
  array (
    'nomor' => 77,
    'nama_arab' => 'المرسلٰت',
    'nama_latin' => 'Al-Mursalat',
    'nama_indonesia' => 'Malaikat Yang Diutus',
    'jumlah_ayat' => 50,
    'juz_awal' => 29,
    'tempat_turun' => 'Makkiyah',
  ),
  77 => 
  array (
    'nomor' => 78,
    'nama_arab' => 'النبأ',
    'nama_latin' => 'An-Naba\'',
    'nama_indonesia' => 'Berita Besar',
    'jumlah_ayat' => 40,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  78 => 
  array (
    'nomor' => 79,
    'nama_arab' => 'النّٰزعٰت',
    'nama_latin' => 'An-Nazi\'at',
    'nama_indonesia' => 'Malaikat Yang Mencabut',
    'jumlah_ayat' => 46,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  79 => 
  array (
    'nomor' => 80,
    'nama_arab' => 'عبس',
    'nama_latin' => '\'Abasa',
    'nama_indonesia' => 'Bermuka Masam',
    'jumlah_ayat' => 42,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  80 => 
  array (
    'nomor' => 81,
    'nama_arab' => 'التكوير',
    'nama_latin' => 'At-Takwir',
    'nama_indonesia' => 'Penggulungan',
    'jumlah_ayat' => 29,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  81 => 
  array (
    'nomor' => 82,
    'nama_arab' => 'الانفطار',
    'nama_latin' => 'Al-Infitar',
    'nama_indonesia' => 'Terbelah',
    'jumlah_ayat' => 19,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  82 => 
  array (
    'nomor' => 83,
    'nama_arab' => 'المطفّفين',
    'nama_latin' => 'Al-Mutaffifin',
    'nama_indonesia' => 'Orang-Orang Curang',
    'jumlah_ayat' => 36,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  83 => 
  array (
    'nomor' => 84,
    'nama_arab' => 'الانشقاق',
    'nama_latin' => 'Al-Insyiqaq',
    'nama_indonesia' => 'Terbelah',
    'jumlah_ayat' => 25,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  84 => 
  array (
    'nomor' => 85,
    'nama_arab' => 'البروج',
    'nama_latin' => 'Al-Buruj',
    'nama_indonesia' => 'Gugusan Bintang',
    'jumlah_ayat' => 22,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  85 => 
  array (
    'nomor' => 86,
    'nama_arab' => 'الطارق',
    'nama_latin' => 'At-Tariq',
    'nama_indonesia' => 'Yang Datang Di Malam Hari',
    'jumlah_ayat' => 17,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  86 => 
  array (
    'nomor' => 87,
    'nama_arab' => 'الاعلى',
    'nama_latin' => 'Al-A\'la',
    'nama_indonesia' => 'Maha Tinggi',
    'jumlah_ayat' => 19,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  87 => 
  array (
    'nomor' => 88,
    'nama_arab' => 'الغاشية',
    'nama_latin' => 'Al-Gasyiyah',
    'nama_indonesia' => 'Hari Kiamat',
    'jumlah_ayat' => 26,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  88 => 
  array (
    'nomor' => 89,
    'nama_arab' => 'الفجر',
    'nama_latin' => 'Al-Fajr',
    'nama_indonesia' => 'Fajar',
    'jumlah_ayat' => 30,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  89 => 
  array (
    'nomor' => 90,
    'nama_arab' => 'البلد',
    'nama_latin' => 'Al-Balad',
    'nama_indonesia' => 'Negeri',
    'jumlah_ayat' => 20,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  90 => 
  array (
    'nomor' => 91,
    'nama_arab' => 'الشمس',
    'nama_latin' => 'Asy-Syams',
    'nama_indonesia' => 'Matahari',
    'jumlah_ayat' => 15,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  91 => 
  array (
    'nomor' => 92,
    'nama_arab' => 'الّيل',
    'nama_latin' => 'Al-Lail',
    'nama_indonesia' => 'Malam',
    'jumlah_ayat' => 21,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  92 => 
  array (
    'nomor' => 93,
    'nama_arab' => 'الضحى',
    'nama_latin' => 'Ad-Duha',
    'nama_indonesia' => 'Duha',
    'jumlah_ayat' => 11,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  93 => 
  array (
    'nomor' => 94,
    'nama_arab' => 'الشرح',
    'nama_latin' => 'Al-Insyirah',
    'nama_indonesia' => 'Lapang',
    'jumlah_ayat' => 8,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  94 => 
  array (
    'nomor' => 95,
    'nama_arab' => 'التين',
    'nama_latin' => 'At-Tin',
    'nama_indonesia' => 'Buah Tin',
    'jumlah_ayat' => 8,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  95 => 
  array (
    'nomor' => 96,
    'nama_arab' => 'العلق',
    'nama_latin' => 'Al-\'Alaq',
    'nama_indonesia' => 'Segumpal Darah',
    'jumlah_ayat' => 19,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  96 => 
  array (
    'nomor' => 97,
    'nama_arab' => 'القدر',
    'nama_latin' => 'Al-Qadr',
    'nama_indonesia' => 'Kemuliaan',
    'jumlah_ayat' => 5,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  97 => 
  array (
    'nomor' => 98,
    'nama_arab' => 'البيّنة',
    'nama_latin' => 'Al-Bayyinah',
    'nama_indonesia' => 'Bukti Nyata',
    'jumlah_ayat' => 8,
    'juz_awal' => 30,
    'tempat_turun' => 'Madaniyah',
  ),
  98 => 
  array (
    'nomor' => 99,
    'nama_arab' => 'الزلزلة',
    'nama_latin' => 'Az-Zalzalah',
    'nama_indonesia' => 'Guncangan',
    'jumlah_ayat' => 8,
    'juz_awal' => 30,
    'tempat_turun' => 'Madaniyah',
  ),
  99 => 
  array (
    'nomor' => 100,
    'nama_arab' => 'العٰديٰت',
    'nama_latin' => 'Al-\'Adiyat',
    'nama_indonesia' => 'Kuda Yang Berlari Kencang',
    'jumlah_ayat' => 11,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  100 => 
  array (
    'nomor' => 101,
    'nama_arab' => 'القارعة',
    'nama_latin' => 'Al-Qari\'ah',
    'nama_indonesia' => 'Hari Kiamat',
    'jumlah_ayat' => 11,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  101 => 
  array (
    'nomor' => 102,
    'nama_arab' => 'التكاثر',
    'nama_latin' => 'At-Takasur',
    'nama_indonesia' => 'Bermegah-Megahan',
    'jumlah_ayat' => 8,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  102 => 
  array (
    'nomor' => 103,
    'nama_arab' => 'العصر',
    'nama_latin' => 'Al-\'Asr',
    'nama_indonesia' => 'Masa',
    'jumlah_ayat' => 3,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  103 => 
  array (
    'nomor' => 104,
    'nama_arab' => 'الهمزة',
    'nama_latin' => 'Al-Humazah',
    'nama_indonesia' => 'Pengumpat',
    'jumlah_ayat' => 9,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  104 => 
  array (
    'nomor' => 105,
    'nama_arab' => 'الفيل',
    'nama_latin' => 'Al-Fil',
    'nama_indonesia' => 'Gajah',
    'jumlah_ayat' => 5,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  105 => 
  array (
    'nomor' => 106,
    'nama_arab' => 'قريش',
    'nama_latin' => 'Quraisy',
    'nama_indonesia' => 'Quraisy',
    'jumlah_ayat' => 4,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  106 => 
  array (
    'nomor' => 107,
    'nama_arab' => 'الماعون',
    'nama_latin' => 'Al-Ma\'un',
    'nama_indonesia' => 'Barang Yang Berguna',
    'jumlah_ayat' => 7,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  107 => 
  array (
    'nomor' => 108,
    'nama_arab' => 'الكوثر',
    'nama_latin' => 'Al-Kausar',
    'nama_indonesia' => 'Pemberian Yang Banyak',
    'jumlah_ayat' => 3,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  108 => 
  array (
    'nomor' => 109,
    'nama_arab' => 'الكٰفرون',
    'nama_latin' => 'Al-Kafirun',
    'nama_indonesia' => 'Orang-Orang kafir',
    'jumlah_ayat' => 6,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  109 => 
  array (
    'nomor' => 110,
    'nama_arab' => 'النصر',
    'nama_latin' => 'An-Nasr',
    'nama_indonesia' => 'Pertolongan',
    'jumlah_ayat' => 3,
    'juz_awal' => 30,
    'tempat_turun' => 'Madaniyah',
  ),
  110 => 
  array (
    'nomor' => 111,
    'nama_arab' => 'اللهب',
    'nama_latin' => 'Al-Lahab',
    'nama_indonesia' => 'Api Yang Bergejolak',
    'jumlah_ayat' => 5,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  111 => 
  array (
    'nomor' => 112,
    'nama_arab' => 'الاخلاص',
    'nama_latin' => 'Al-Ikhlas',
    'nama_indonesia' => 'Ikhlas',
    'jumlah_ayat' => 4,
    'juz_awal' => 30,
    'tempat_turun' => 'Makkiyah',
  ),
  112 => 
  array (
    'nomor' => 113,
    'nama_arab' => 'الفلق',
    'nama_latin' => 'Al-Falaq',
    'nama_indonesia' => 'Subuh',
    'jumlah_ayat' => 5,
    'juz_awal' => 30,
    'tempat_turun' => 'Madaniyah',
  ),
  113 => 
  array (
    'nomor' => 114,
    'nama_arab' => 'الناس',
    'nama_latin' => 'An-Nas',
    'nama_indonesia' => 'Manusia',
    'jumlah_ayat' => 6,
    'juz_awal' => 30,
    'tempat_turun' => 'Madaniyah',
  ),
);

        foreach ($surahs as $surah) {
            Surah::updateOrCreate(['nomor' => $surah['nomor']], $surah);
        }
    }
}
