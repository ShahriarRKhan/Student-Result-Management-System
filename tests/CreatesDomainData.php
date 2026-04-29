<?php

namespace Tests;

use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

trait CreatesDomainData
{
    protected function createTeacher(array $attributes = []): Teacher
    {
        static $sequence = 1;
        $number = $sequence++;

        return Teacher::create(array_merge([
            'name' => 'Teacher '.$number,
            'email' => 'teacher'.$number.'@example.com',
            'password' => Hash::make('password'),
            'employee_id' => 'EMP-'.$number,
            'department' => 'CSE',
            'subject' => 'Programming',
        ], $attributes));
    }

    protected function createStudent(array $attributes = []): Student
    {
        static $sequence = 1;
        $number = $sequence++;

        return Student::create(array_merge([
            'student_id' => 'STD-'.$number,
            'email' => 'student'.$number.'@example.com',
            'password' => Hash::make('password'),
            'department' => 'CSE',
            'semester' => '3',
        ], $attributes));
    }

    protected function createSubject(array $attributes = []): Subject
    {
        static $sequence = 1;
        $number = $sequence++;

        return Subject::create(array_merge([
            'subject_code' => 'CSE-'.$number,
            'subject_name' => 'Subject '.$number,
            'credit' => 3,
        ], $attributes));
    }

    protected function createResult(array $attributes = []): Result
    {
        $teacher = $attributes['teacher'] ?? $this->createTeacher();
        $student = $attributes['student'] ?? $this->createStudent();
        $subject = $attributes['subject'] ?? $this->createSubject();

        unset($attributes['teacher'], $attributes['student'], $attributes['subject']);

        return Result::create(array_merge([
            'teacher_id' => $teacher->id,
            'student_id' => $student->student_id,
            'subject_id' => $subject->subject_code,
            'marks' => 75,
            'grade' => 'A',
            'is_published' => false,
            'published_at' => null,
        ], $attributes));
    }
}
