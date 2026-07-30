<?php

namespace App\Observers;

use App\Models\RiwayatHafalan;
use App\Events\HafalanUpdated;

class RiwayatHafalanObserver
{
    public function created(RiwayatHafalan $hafalan): void
    {
        $hafalan->loadMissing('student');
        $studentName = $hafalan->student->name ?? 'Santri';
        $message = "Setoran hafalan baru untuk $studentName!";

        broadcast(new HafalanUpdated($message, 'created', $studentName))->toOthers();
    }

    public function updated(RiwayatHafalan $hafalan): void
    {
        $hafalan->loadMissing('student');
        $studentName = $hafalan->student->name ?? 'Santri';
        $message = "Data setoran hafalan $studentName diperbarui!";

        broadcast(new HafalanUpdated($message, 'updated', $studentName))->toOthers();
    }

    public function deleted(RiwayatHafalan $hafalan): void
    {
        $hafalan->loadMissing('student');
        $studentName = $hafalan->student->name ?? 'Santri';
        $message = "Data setoran hafalan $studentName dihapus!";

        broadcast(new HafalanUpdated($message, 'deleted', $studentName))->toOthers();
    }
}
