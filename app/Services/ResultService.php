<?php

namespace App\Services;

use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ResultService
{
    public function getTeacherResultsByStudent(int $teacherId): Collection
    {
        return Result::query()
            ->with(['student', 'subject'])
            ->where('teacher_id', $teacherId)
            ->orderBy('student_id')
            ->orderBy('subject_id')
            ->get()
            ->groupBy('student_id');
    }

    public function getTeacherStudentResults(int $teacherId, string $studentId): Collection
    {
        return Result::query()
            ->with(['student', 'subject'])
            ->where('teacher_id', $teacherId)
            ->where('student_id', $studentId)
            ->orderBy('subject_id')
            ->get();
    }

    public function getPublishedResultsForStudent(string $studentId): Collection
    {
        return Result::query()
            ->with(['teacher', 'subject'])
            ->where('student_id', $studentId)
            ->where('is_published', true)
            ->orderBy('subject_id')
            ->get();
    }

    public function upsertStudentSubjectMarks(
        int $teacherId,
        string $studentId,
        array $subjectMarks,
        bool $publish = false
    ): void
    {
        foreach ($subjectMarks as $subjectId => $marks) {
            if ($marks === null || $marks === '') {
                continue;
            }

            $marks = (float) $marks;

            Result::query()->updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                ],
                [
                    'marks' => $marks,
                    'grade' => $this->calculateGrade($marks),
                    'is_published' => $publish,
                    'published_at' => $publish ? Carbon::now() : null,
                ]
            );
        }
    }

    public function updateSingle(Result $result, float $marks, bool $publish): Result
    {
        $result->update([
            'marks' => $marks,
            'grade' => $this->calculateGrade($marks),
            'is_published' => $publish,
            'published_at' => $publish ? Carbon::now() : null,
        ]);

        return $result;
    }

    public function publishStudentResults(int $teacherId, string $studentId): int
    {
        return Result::query()
            ->where('teacher_id', $teacherId)
            ->where('student_id', $studentId)
            ->update([
                'is_published' => true,
                'published_at' => Carbon::now(),
            ]);
    }

    public function delete(Result $result): void
    {
        $result->delete();
    }

    public function calculateGrade(float $marks): string
    {
        if ($marks >= 80) {
            return 'A+';
        }
        if ($marks >= 70) {
            return 'A';
        }
        if ($marks >= 60) {
            return 'A-';
        }
        if ($marks >= 50) {
            return 'B';
        }
        if ($marks >= 40) {
            return 'C';
        }

        return 'F';
    }

    public function gradePoint(string $grade): float
    {
        return match ($grade) {
            'A+' => 4.00,
            'A' => 3.75,
            'A-' => 3.50,
            'B' => 3.00,
            'C' => 2.00,
            default => 0.00,
        };
    }
}
