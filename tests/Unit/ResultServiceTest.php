<?php

namespace Tests\Unit;

use App\Services\ResultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesDomainData;
use Tests\TestCase;

class ResultServiceTest extends TestCase
{
    use CreatesDomainData;
    use RefreshDatabase;

    public function test_it_calculates_grade_boundaries_and_grade_points(): void
    {
        $service = app(ResultService::class);

        $this->assertSame('A+', $service->calculateGrade(80));
        $this->assertSame('A', $service->calculateGrade(70));
        $this->assertSame('A-', $service->calculateGrade(60));
        $this->assertSame('B', $service->calculateGrade(50));
        $this->assertSame('C', $service->calculateGrade(40));
        $this->assertSame('F', $service->calculateGrade(39.99));

        $this->assertSame(4.0, $service->gradePoint('A+'));
        $this->assertSame(3.75, $service->gradePoint('A'));
        $this->assertSame(3.5, $service->gradePoint('A-'));
        $this->assertSame(3.0, $service->gradePoint('B'));
        $this->assertSame(2.0, $service->gradePoint('C'));
        $this->assertSame(0.0, $service->gradePoint('F'));
    }

    public function test_it_upserts_marks_and_sets_publish_metadata(): void
    {
        $service = app(ResultService::class);
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $subject = $this->createSubject(['subject_code' => 'CSE-101']);

        $service->upsertStudentSubjectMarks($teacher->id, $student->student_id, [
            $subject->subject_code => 88,
        ], true);

        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => $subject->subject_code,
            'marks' => 88,
            'grade' => 'A+',
            'is_published' => 1,
        ]);

        $result = \App\Models\Result::query()
            ->where('teacher_id', $teacher->id)
            ->where('student_id', $student->student_id)
            ->where('subject_id', $subject->subject_code)
            ->first();

        $this->assertNotNull($result);
        $this->assertNotNull($result->published_at);
    }

    public function test_it_publishes_all_results_for_a_student_under_one_teacher(): void
    {
        $service = app(ResultService::class);
        $teacher = $this->createTeacher();
        $otherTeacher = $this->createTeacher();
        $student = $this->createStudent();

        $this->createResult([
            'teacher' => $teacher,
            'student' => $student,
            'subject' => $this->createSubject(['subject_code' => 'PHY-101']),
        ]);
        $this->createResult([
            'teacher' => $teacher,
            'student' => $student,
            'subject' => $this->createSubject(['subject_code' => 'MAT-101']),
        ]);
        $foreignResult = $this->createResult([
            'teacher' => $otherTeacher,
            'student' => $student,
            'subject' => $this->createSubject(['subject_code' => 'ENG-101']),
        ]);

        $updatedCount = $service->publishStudentResults($teacher->id, $student->student_id);

        $this->assertSame(2, $updatedCount);
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'PHY-101',
            'is_published' => 1,
        ]);
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'MAT-101',
            'is_published' => 1,
        ]);
        $this->assertFalse((bool) $foreignResult->fresh()->is_published);
    }
}
