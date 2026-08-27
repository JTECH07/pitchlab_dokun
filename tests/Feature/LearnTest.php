<?php

namespace Tests\Feature;

use App\Models\LearnCourse;
use App\Models\LearnLesson;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LearnTest extends TestCase
{
    use RefreshDatabase;

    private function makeCourseWithLesson(): array
    {
        $course = LearnCourse::create([
            'slug' => 'artisanat-benin', 'title_fr' => 'Artisanat du Bénin', 'title_en' => 'Benin Craft',
            'desc_fr' => 'Découverte', 'desc_en' => 'Discovery', 'sort_order' => 1,
        ]);
        $lesson = LearnLesson::create([
            'course_id' => $course->id, 'slug' => 'lecon-1', 'title_fr' => 'Leçon 1', 'title_en' => 'Lesson 1',
            'sort_order' => 1,
        ]);
        return [$course, $lesson];
    }

    public function test_learn_requires_auth(): void
    {
        $this->get(route('learn.index'))->assertRedirect(route('login'));
    }

    public function test_learn_index_requires_verified(): void
    {
        $user = User::factory()->unverified()->create();
        $this->actingAs($user)->get(route('learn.index'))->assertRedirect(route('verification.notice'));
    }

    public function test_guest_complete_does_not_save(): void
    {
        [$course, $lesson] = $this->makeCourseWithLesson();

        $this->post(route('learn.complete', $lesson), ['score' => 80])
            ->assertRedirect(route('login'));

        $this->assertDatabaseMissing('learn_progress', ['lesson_id' => $lesson->id]);
    }

    public function test_authenticated_complete_saves_progress(): void
    {
        [$course, $lesson] = $this->makeCourseWithLesson();
        $user = User::factory()->create(['role' => 'tourist']);

        $response = $this->actingAs($user)->post(route('learn.complete', $lesson), ['score' => 85])
            ->assertJson(['status' => 'saved', 'best_score' => 85]);

        $this->assertDatabaseHas('learn_progress', ['user_id' => $user->id, 'lesson_id' => $lesson->id, 'best_score' => 85]);
        $this->assertDatabaseHas('loyalty_events', ['user_id' => $user->id, 'code' => 'lesson_completed']);
    }

    public function test_perfect_score_awards_quiz_bonus(): void
    {
        [$course, $lesson] = $this->makeCourseWithLesson();
        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($user)->post(route('learn.complete', $lesson), ['score' => 100])
            ->assertJson(['status' => 'saved']);

        $this->assertDatabaseHas('loyalty_events', ['user_id' => $user->id, 'code' => 'lesson_completed']);
        $this->assertDatabaseHas('loyalty_events', ['user_id' => $user->id, 'code' => 'perfect_quiz']);
    }

    public function test_best_score_is_max(): void
    {
        [$course, $lesson] = $this->makeCourseWithLesson();
        $user = User::factory()->create(['role' => 'tourist']);

        $this->actingAs($user)->post(route('learn.complete', $lesson), ['score' => 100]);
        $this->actingAs($user)->post(route('learn.complete', $lesson), ['score' => 60]);

        $this->assertDatabaseHas('learn_progress', [
            'user_id' => $user->id, 'lesson_id' => $lesson->id, 'best_score' => 100,
        ]);
    }
}
