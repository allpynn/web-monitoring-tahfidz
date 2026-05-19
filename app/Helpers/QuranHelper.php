<?php

namespace App\Helpers;

use App\Models\Surah;

class QuranHelper
{
    /**
     * Daftar pemetaan Juz ke Surah yang ada di dalamnya.
     * Digunakan untuk verifikasi hafalan per Juz.
     */
    private static $juzMapping = [
        1 => ['Al-Fatihah', 'Al-Baqarah'],
        2 => ['Al-Baqarah'],
        3 => ['Al-Baqarah', 'Ali \'Imran'],
        4 => ['Ali \'Imran', 'An-Nisa\''],
        5 => ['An-Nisa\''],
        6 => ['An-Nisa\'', 'Al-Ma\'idah'],
        7 => ['Al-Ma\'idah', 'Al-An\'am'],
        8 => ['Al-An\'am', 'Al-A\'raf'],
        9 => ['Al-A\'raf', 'Al-Anfal'],
        10 => ['Al-Anfal', 'At-Taubah'],
        11 => ['At-Taubah', 'Yunus', 'Hud'],
        12 => ['Hud', 'Yusuf'],
        13 => ['Yusuf', 'Ar-Ra\'d', 'Ibrahim'],
        14 => ['Al-Hijr', 'An-Nahl'],
        15 => ['Al-Isra\'', 'Al-Kahf'],
        16 => ['Al-Kahf', 'Maryam', 'Taha'],
        17 => ['Al-Anbiya\'', 'Al-Hajj'],
        18 => ['Al-Mu\'minun', 'An-Nur', 'Al-Furqan'],
        19 => ['Al-Furqan', 'Asy-Syu\'ara\'', 'An-Naml'],
        20 => ['An-Naml', 'Al-Qasas', 'Al-\'Ankabut'],
        21 => ['Al-\'Ankabut', 'Ar-Rum', 'Luqman', 'As-Sajdah', 'Al-Ahzab'],
        22 => ['Al-Ahzab', 'Saba\'', 'Fatir', 'Yasin'],
        23 => ['Yasin', 'As-Saffat', 'Sad', 'Az-Zumar'],
        24 => ['Az-Zumar', 'Gafir', 'Fussilat'],
        25 => ['Asy-Syura', 'Az-Zukhruf', 'Ad-Dukhan', 'Al-Jasiyah'],
        26 => ['Al-Ahqaf', 'Muhammad', 'Al-Fath', 'Al-Hujurat', 'Qaf', 'Az-Zariyat'],
        27 => ['At-Tur', 'An-Najm', 'Al-Qamar', 'Ar-Rahman', 'Al-Waqi\'ah', 'Al-Hadid'],
        28 => ['Al-Mujadalah', 'Al-Hasyr', 'Al-Mumtahanah', 'As-Saff', 'Al-Jumu\'ah', 'Al-Munafiqun', 'At-Tagabun', 'At-Talaq', 'At-Tahrim'],
        29 => ['Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij', 'Nuh', 'Al-Jinn', 'Al-Muzzammil', 'Al-Muddassir', 'Al-Qiyamah', 'Al-Insan', 'Al-Mursalat'],
        30 => [
            'An-Naba\'',
            'An-Nazi\'at',
            '\'Abasa',
            'At-Takwir',
            'Al-Infitar',
            'Al-Mutaffifin',
            'Al-Insyiqaq',
            'Al-Buruj',
            'At-Tariq',
            'Al-A\'la',
            'Al-Ghasyiyah',
            'Al-Fajr',
            'Al-Balad',
            'Asy-Syams',
            'Al-Lail',
            'Ad-Duha',
            'Asy-Syarh',
            'At-Tin',
            'Al-\'Alaq',
            'Al-Qadr',
            'Al-Bayyinah',
            'Az-Zalzalah',
            'Al-\'Adiyat',
            'Al-Qari\'ah',
            'At-Takasur',
            'Al-\'Asr',
            'Al-Humazah',
            'Al-Fil',
            'Quraisy',
            'Al-Ma\'un',
            'Al-Kausar',
            'Al-Kafirun',
            'An-Nasr',
            'Al-Masad',
            'Al-Ikhlas',
            'Al-Falaq',
            'An-Nas'
        ],
    ];

    /**
     * Mengambil daftar nama surah dalam Juz tertentu.
     */
    public static function getSurahsInJuz(int $juz): array
    {
        return self::$juzMapping[$juz] ?? [];
    }

    /**
     * Mengambil list juz yang tersedia (1-30).
     */
    public static function getAllJuzNumbers(): array
    {
        return range(1, 30);
    }
}
