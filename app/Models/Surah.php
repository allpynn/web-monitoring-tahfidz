<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;

class Surah
{
    protected static $data = null;

    /**
     * Get all surahs from the JSON file.
     */
    public static function all(): Collection
    {
        if (is_null(self::$data)) {
            $jsonPath = resource_path('data/surahs.json');
            if (File::exists($jsonPath)) {
                self::$data = collect(json_decode(File::get($jsonPath), true))
                    ->map(fn ($item) => (object) $item);
            } else {
                self::$data = collect();
            }
        }

        return self::$data;
    }

    /**
     * Helper to replicate Eloquent's orderBy()->get() pattern.
     */
    public static function orderBy(string $column, string $direction = 'asc'): self
    {
        $instance = new self;

        // Since we want to support chaining, we return an object that can handle it or just do it here
        return $instance;
    }

    public function get(): Collection
    {
        return self::all()->sortBy('nomor')->values();
    }

    /**
     * Find a surah by nomor.
     */
    public static function find($nomor)
    {
        return self::all()->firstWhere('nomor', (int) $nomor);
    }
}
