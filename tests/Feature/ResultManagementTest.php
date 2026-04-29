<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesDomainData;
use Tests\TestCase;

class ResultManagementTest extends TestCase
{
    use CreatesDomainData;
    use RefreshDatabase;

    public function test_teacher_can_store_marks_for_multiple_subjects(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $math = $this->createSubject(['subject_code' => 'MAT-101']);
        $physics = $this->createSubject(['subject_code' => 'PHY-101']);

        $response = $this->actingAs($teacher, 'teacher')->post(route('teacher.results.store'), [
            'student_id' => $student->student_id,
            'marks' => [
                $math->subject_code => 82,
                $physics->subject_code => 64,
            ],
            'publish' => 1,
        ]);

        $response->assertRedirect(route('teacher.results.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseCount('results', 2);
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'MAT-101',
            'grade' => 'A+',
            'is_published' => 1,
        ]);
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'PHY-101',
            'grade' => 'A-',
            'is_published' => 1,
        ]);
    }

    public function test_teacher_cannot_store_results_when_all_marks_are_blank(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $subject = $this->createSubject(['subject_code' => 'CSE-220']);

        $response = $this->from(route('teacher.results.index'))
            ->actingAs($teacher, 'teacher')
            ->post(route('teacher.results.store'), [
                'student_id' => $student->student_id,
                'marks' => [
                    $subject->subject_code => '',
                ],
            ]);

        $response->assertRedirect(route('teacher.results.index'));
        $response->assertSessionHasErrors('marks');
        $this->assertDatabaseCount('results', 0);
    }

    public function test_teacher_can_publish_results_for_a_student(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();

        $this->createResult([
            'teacher' => $teacher,
            'student' => $student,
            'subject' => $this->createSubject(['subject_code' => 'CSE-110']),
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->patch(route('teacher.results.publish', $student->student_id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'CSE-110',
            'is_published' => 1,
        ]);
    }
}
