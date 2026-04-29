<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use App\Services\ResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    protected $resultService;

    public function __construct(ResultService $resultService)
    {
        $this->resultService = $resultService;
    }

    public function index()
    {
        $teacherId = Auth::guard('teacher')->id();

        $resultsByStudent = $this->resultService->getTeacherResultsByStudent($teacherId);
        $students = Student::query()->orderBy('student_id')->get(['student_id', 'department', 'semester']);
        $subjects = Subject::query()->orderBy('subject_code')->get(['subject_code', 'subject_name', 'credit']);

        return view('teacher.results.index', [
            'title' => 'Result List',
            'resultsByStudent' => $resultsByStudent,
            'students' => $students,
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|string|exists:students,student_id',
            'marks' => 'required|array|min:1',
            'marks.*' => 'nullable|numeric|min:0|max:100',
            'publish' => 'nullable|boolean',
        ]);

        $marks = collect($request->input('marks', []))
            ->filter(static fn ($value) => $value !== null && $value !== '')
            ->all();

        if (empty($marks)) {
            return back()->withErrors([
                'marks' => 'Please enter marks for at least one subject.',
            ])->withInput();
        }

        $subjectCodes = Subject::query()
            ->whereIn('subject_code', array_keys($marks))
            ->pluck('subject_code')
            ->all();

        if (count($subjectCodes) !== count($marks)) {
            return back()->withErrors([
                'marks' => 'One or more selected subjects are invalid.',
            ])->withInput();
        }

        $this->resultService->upsertStudentSubjectMarks(
            Auth::guard('teacher')->id(),
            (string) $request->input('student_id'),
            $marks,
            (bool) $request->boolean('publish')
        );

        return redirect()->route('teacher.results.index')
            ->with('success', 'Marks saved successfully for selected student.');
    }

    public function show(string $student_id)
    {
        $teacherId = Auth::guard('teacher')->id();
        $subjects = Subject::query()->orderBy('subject_code')->get(['subject_code', 'subject_name', 'credit']);
        $results = $this->resultService->getTeacherStudentResults($teacherId, $student_id)->keyBy('subject_id');

        abort_if($results->isEmpty(), 404);

        $student = Student::query()->where('student_id', $student_id)->firstOrFail();

        return view('teacher.results.show', [
            'title' => 'Result Details',
            'student' => $student,
            'subjects' => $subjects,
            'results' => $results,
        ]);
    }

    public function update(Request $request, int $result)
    {
        $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
            'publish' => 'nullable|boolean',
        ]);

        $teacherResult = Result::query()
            ->where('id', $result)
            ->where('teacher_id', Auth::guard('teacher')->id())
            ->firstOrFail();

        $this->resultService->updateSingle(
            $teacherResult,
            (float) $request->input('marks'),
            (bool) $request->boolean('publish')
        );

        return back()->with('success', 'Result updated successfully.');
    }

    public function destroy(int $result)
    {
        $teacherResult = Result::query()
            ->where('id', $result)
            ->where('teacher_id', Auth::guard('teacher')->id())
            ->firstOrFail();

        $studentId = $teacherResult->student_id;
        $this->resultService->delete($teacherResult);

        return redirect()->route('teacher.results.show', $studentId)
            ->with('success', 'Result deleted successfully.');
    }

    public function publish(string $student_id)
    {
        $updated = $this->resultService->publishStudentResults(
            Auth::guard('teacher')->id(),
            $student_id
        );

        if ($updated === 0) {
            return back()->withErrors([
                'publish' => 'No result found for this student to publish.',
            ]);
        }

        return back()->with('success', 'All results for this student have been published.');
    }
}
