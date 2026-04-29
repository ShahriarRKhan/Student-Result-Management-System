<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    protected $resultService;

    public function __construct(ResultService $resultService)
    {
        $this->resultService = $resultService;
    }

    protected function clearOtherSessionGuard(Request $request): void
    {
        if (Auth::guard('teacher')->check()) {
            Auth::guard('teacher')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    public function index()
    {
        $student = Auth::guard('student')->user();
        $results = $this->resultService->getPublishedResultsForStudent($student->student_id);

        $totalCredit = $results->sum(static fn ($result) => (int) ($result->subject->credit ?? 0));
        $weightedPoint = $results->sum(function ($result) {
            $credit = (int) ($result->subject->credit ?? 0);
            return $this->resultService->gradePoint((string) $result->grade) * $credit;
        });
        $cgpa = $totalCredit > 0 ? round($weightedPoint / $totalCredit, 2) : 0;

        return view('students.index', [
            'title' => 'Your Profile',
            'student' => $student,
            'results' => $results,
            'cgpa' => $cgpa,
            'totalCredit' => $totalCredit,
        ]);
    }

    public function show(?string $student_id = null)
    {
        $loggedInStudent = Auth::guard('student')->user();
        $student_id = $student_id ?? $loggedInStudent->student_id;

        if ($loggedInStudent->student_id !== $student_id) {
            abort(403, 'You can only view your own result.');
        }

        $results = $this->resultService->getPublishedResultsForStudent($student_id);

        return view('students.results.show', [
            'title' => 'Result Details',
            'student' => $loggedInStudent,
            'results' => $results,
        ]);
    }

    public function edit()
    {
        return view('students.profile.edit', [
            'title' => 'Edit Profile',
            'student' => Auth::guard('student')->user(),
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\Student $student */
        $student = Auth::guard('student')->user();

        $validated = $request->validate([
            'email' => 'required|email|unique:students,email,' . $student->id,
            'department' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $student->update($validated);

        return redirect()
            ->route('student.profile.edit')
            ->with('success', 'Profile updated successfully');
    }

    public function register(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|unique:students,student_id',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|min:6',
            'department' => 'required|string|max:255',
            'semester' => 'required|integer|min:1|max:8',
        ]);

        $this->clearOtherSessionGuard($request);

        $student = Student::create([
            'student_id' => $request->student_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'department' => $request->department,
            'semester' => $request->semester,
        ]);

        Auth::guard('student')->login($student); // Assuming separate guard
        $request->session()->regenerate();

        return redirect()->route('student.index')->with('success', 'Registration successful');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $this->clearOtherSessionGuard($request);

        if (Auth::guard('student')->attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return redirect()->route('student.index')->with('success', 'Login successful');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('student.login')->with('success', 'Logged out successfully');
    }
}
