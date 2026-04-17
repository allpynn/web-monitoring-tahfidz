<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Surah
{
    protected static ?Collection $data = null;

    public static function all(): Collection
    {
        if (is_null(self::$data)) {
            $jsonPath = resource_path('data/surahs.json');
            self::$data = File::exists($jsonPath)
                ? collect(json_decode(File::get($jsonPath), true))->map(fn ($item) => (object) $item)
                : collect();
        }

        return self::$data;
    }

    public static function orderBy(string $column, string $direction = 'asc'): Collection
    {
        $sorted = self::all()->sortBy($column, SORT_REGULAR, $direction === 'desc');

        return $sorted->values();
    }

    public static function find($nomor)
    {
        return self::all()->firstWhere('nomor', (int) $nomor);
    }
}
