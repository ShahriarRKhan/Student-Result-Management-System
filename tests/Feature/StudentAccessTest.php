<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\CreatesDomainData;
use Tests\TestCase;

class StudentAccessTest extends TestCase
{
    use CreatesDomainData;
    use RefreshDatabase;

    public function test_student_can_only_view_their_own_result_page(): void
    {
        $student = $this->createStudent();
        $otherStudent = $this->createStudent();

        $this->withoutExceptionHandling();
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You can only view your own result.');

        $this->actingAs($student, 'student')
            ->get(route('student.results.show', $otherStudent->student_id));
    }

    public function test_student_profile_update_changes_details_and_hashes_password(): void
    {
        $student = $this->createStudent([
            'email' => 'before@example.com',
            'password' => Hash::make('old-password'),
            'department' => 'EEE',
            'semester' => '2',
        ]);

        $response = $this->actingAs($student, 'student')->put(route('student.profile.update'), [
            'email' => 'after@example.com',
            'department' => 'CSE',
            'semester' => 4,
            'password' => 'new-secret',
            'password_confirmation' => 'new-secret',
        ]);

        $response->assertRedirect(route('student.profile.edit'));
        $response->assertSessionHas('success');

        $student->refresh();

        $this->assertSame('after@example.com', $student->email);
        $this->assertSame('CSE', $student->department);
        $this->assertSame('4', (string) $student->semester);
        $this->assertTrue(Hash::check('new-secret', $student->password));
    }
}
