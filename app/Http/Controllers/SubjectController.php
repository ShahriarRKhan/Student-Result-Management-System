<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::query()
            ->withCount('results')
            ->orderBy('subject_code')
            ->get();

        return view('teacher.subjects.index', [
            'title' => 'Subject Management',
            'subjects' => $subjects,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_code' => ['required', 'string', 'max:50', 'unique:subjects,subject_code'],
            'subject_name' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Subject::create($validated);

        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject created successfully.');
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'subject_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('subjects', 'subject_code')->ignore($subject->id),
            ],
            'subject_name' => ['required', 'string', 'max:255'],
            'credit' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        DB::transaction(function () use ($subject, $validated) {
            $oldCode = $subject->subject_code;
            $newCode = $validated['subject_code'];

            $subject->update($validated);

            if ($oldCode !== $newCode) {
                Result::query()
                    ->where('subject_id', $oldCode)
                    ->update(['subject_id' => $newCode]);
            }
        });

        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->results()->exists()) {
            return redirect()->route('teacher.subjects.index')
                ->withErrors(['subject' => 'Cannot delete a subject that already has saved results.']);
        }

        $subject->delete();

        return redirect()->route('teacher.subjects.index')
            ->with('success', 'Subject deleted successfully.');
    }
}
