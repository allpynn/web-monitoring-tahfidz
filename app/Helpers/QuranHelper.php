<?php

namespace App\Helpers;

class QuranHelper
{
    /**
     * Pemetaan Juz ke daftar Surah (ID atau Nama)
     * Format: Juz => [SurahStart, SurahEnd] atau list surah
     * Untuk kesederhanaan, kita gunakan pemetaan juz ke daftar nama surah utama.
     */
    public static function getSurahsInJuz($juz)
    {
        $mapping = [
            1  => ['Al-Fatihah', 'Al-Baqarah'],
            // ... Secara ringkas untuk demo Juz 30 & 29 yang sering digunakan
            29 => ['Al-Mulk', 'Al-Qalam', 'Al-Haqqah', 'Al-Ma\'arij', 'Nuh', 'Al-Jinn', 'Al-Muzzammil', 'Al-Muddatstsir', 'Al-Qiyamah', 'Al-Insan', 'Al-Mursalat'],
            30 => [
                'An-Naba', 'An-Nazi\'at', 'Abasa', 'At-Takwir', 'Al-Infitar', 'Al-Mutaffifin', 'Al-Inshiqaq', 'Al-Buruj', 'At-Tariq', 'Al-A\'la', 'Al-Ghashiyah', 'Al-Fajr', 'Al-Balad', 'Ash-Shams', 'Al-Layl', 'Ad-Duha', 'Ash-Sharh', 'At-Tin', 'Al-Alaq', 'Al-Qadr', 'Al-Bayyinah', 'Az-Zalzalah', 'Al-Adiyat', 'Al-Qari\'ah', 'At-Takathur', 'Al-Asr', 'Al-Humazah', 'Al-Fil', 'Quraish', 'Al-Ma\'un', 'Al-Kawsar', 'Al-Kafirun', 'An-Nasr', 'Al-Masad', 'Al-Ikhlas', 'Al-Falaq', 'An-Nas'
            ],
        ];

        return $mapping[$juz] ?? [];
    }
}
