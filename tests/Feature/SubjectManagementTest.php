<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\CreatesDomainData;
use Tests\TestCase;

class SubjectManagementTest extends TestCase
{
    use CreatesDomainData;
    use RefreshDatabase;

    public function test_updating_subject_code_relinks_existing_results(): void
    {
        $teacher = $this->createTeacher();
        $student = $this->createStudent();
        $subject = $this->createSubject([
            'subject_code' => 'CSE-101',
            'subject_name' => 'Intro to CSE',
        ]);

        $this->createResult([
            'teacher' => $teacher,
            'student' => $student,
            'subject' => $subject,
            'subject_id' => 'CSE-101',
        ]);

        $response = $this->actingAs($teacher, 'teacher')->put(route('teacher.subjects.update', $subject), [
            'subject_code' => 'CSE-102',
            'subject_name' => 'Intro to CSE Updated',
            'credit' => 4,
        ]);

        $response->assertRedirect(route('teacher.subjects.index'));
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'subject_code' => 'CSE-102',
            'subject_name' => 'Intro to CSE Updated',
            'credit' => 4,
        ]);
        $this->assertDatabaseHas('results', [
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => 'CSE-102',
        ]);
    }

    public function test_subject_with_results_cannot_be_deleted(): void
    {
        $teacher = $this->createTeacher();
        $subject = $this->createSubject(['subject_code' => 'EEE-101']);

        $this->createResult([
            'teacher' => $teacher,
            'subject' => $subject,
        ]);

        $response = $this->actingAs($teacher, 'teacher')
            ->delete(route('teacher.subjects.destroy', $subject));

        $response->assertRedirect(route('teacher.subjects.index'));
        $response->assertSessionHasErrors('subject');
        $this->assertDatabaseHas('subjects', [
            'id' => $subject->id,
            'subject_code' => 'EEE-101',
        ]);
    }
}
