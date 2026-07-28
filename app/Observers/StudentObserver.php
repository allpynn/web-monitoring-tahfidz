<?php

namespace App\Observers;

use App\Models\Student;
use App\Events\StudentUpdated;

class StudentObserver
{
    public function created(Student $student): void
    {
        broadcast(new StudentUpdated('created', $student->name, $student->nis))->toOthers();
    }

    public function updated(Student $student): void
    {
        broadcast(new StudentUpdated('updated', $student->name, $student->nis))->toOthers();
    }

    public function deleted(Student $student): void
    {
        broadcast(new StudentUpdated('deleted', $student->name, $student->nis))->toOthers();
    }
}
