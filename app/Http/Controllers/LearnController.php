<?php

namespace App\Http\Controllers;

use App\Models\LearnCourse;
use App\Models\LearnLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function index()
    {
        $courses = LearnCourse::with('lessons')->orderBy('sort_order')->get();
        $completed = $this->userCompletedLessonIds();

        return view('learn.index', compact('courses', 'completed'));
    }

    public function course(LearnCourse $course)
    {
        $lessons = $course->lessons()->withCount('words')->get();
        $completed = $this->userCompletedLessonIds();

        // Une leçon est débloquée si c'est la première ou si la précédente est terminée
        $unlocked = [];
        foreach ($lessons as $i => $lesson) {
            $unlocked[$lesson->id] = $i === 0 || isset($completed[$lessons[$i - 1]->id]);
        }

        return view('learn.course', compact('course', 'lessons', 'unlocked', 'completed'));
    }

    public function play(LearnCourse $course, LearnLesson $lesson)
    {
        abort_unless($lesson->course_id === $course->id, 404);

        // Verrou serveur : première leçon ou précédente terminée
        $completed = $this->userCompletedLessonIds();
        $lessons = $course->lessons()->orderBy('sort_order')->get();
        $index = $lessons->search(fn ($l) => $l->id === $lesson->id);
        abort_if($index > 0 && !isset($completed[$lessons[$index - 1]->id]), 403);

        $words = $lesson->words;
        $isEn = app()->getLocale() === 'en';
        $progress = Auth::check()
            ? \App\Models\LearnProgress::where('user_id', Auth::id())->where('lesson_id', $lesson->id)->first()
            : null;
        $bestScore = $progress?->best_score ?? 0;

        return view('learn.play', compact('course', 'lesson', 'words', 'isEn', 'bestScore'));
    }

    public function complete(Request $request, LearnLesson $lesson)
    {
        $data = $request->validate(['score' => 'required|integer|min:0|max:100']);

        if (!Auth::check()) {
            return response()->json([
                'status' => 'guest',
                'message' => app()->getLocale() === 'en' ? 'Sign in to save your progress.' : 'Connecte-toi pour sauvegarder ta progression.',
            ]);
        }

        $existing = \App\Models\LearnProgress::where('user_id', Auth::id())->where('lesson_id', $lesson->id)->first();
        $score = $data['score'];

        if ($existing) {
            $existing->update([
                'best_score' => max($existing->best_score, $score),
                'completed_at' => now(),
            ]);
        } else {
            \App\Models\LearnProgress::create([
                'user_id' => Auth::id(),
                'lesson_id' => $lesson->id,
                'best_score' => $score,
                'completed_at' => now(),
            ]);
        }

        return response()->json(['status' => 'saved', 'best_score' => max($existing->best_score ?? 0, $score)]);
    }

    private function userCompletedLessonIds(): array
    {
        if (!Auth::check()) return [];

        return \App\Models\LearnProgress::where('user_id', Auth::id())
            ->whereNotNull('completed_at')
            ->pluck('lesson_id')
            ->flip()
            ->all();
    }
}
