<?php

namespace Tests\Unit;

use DavidStrada\Tagger\Tests\Stubs\LessonStub;
use DavidStrada\Tagger\Tests\Stubs\TagStub;
use DavidStrada\Tagger\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class TagTest extends TestCase
{
    protected $lesson;

    public function setUp() : void
    {
        parent::setUp();

        $this->lesson = LessonStub::create([
            'title' => 'lesson one'
        ]);

        $tags = ['PHP', 'Laravel', 'Unix', 'Mac OSX', 'Postgres', 'Fun Stuff'];

        collect($tags)->each(fn($tag) => TagStub::create([
            'name' => $tag,
            'slug' => Str::slug($tag),
        ]));
    }

    /** @test */
    public function can_tag_a_lesson()
    {
        $this->lesson->tag(['Laravel', 'PHP', 'Fun Stuff']);

        $this->assertCount(3, $this->lesson->tags);
        // check assigned tags are were assigned to the model.
        collect(['Laravel', 'PHP', 'Fun Stuff'])
            ->each(fn($tag) => $this->assertContains($tag, $this->lesson->tags->pluck('name')));
    }

    /** @test */
    public function can_untag_a_lesson()
    {
        $this->lesson->tag(['Laravel', 'PHP', 'Fun Stuff']);
        $this->lesson->untag(['laravel']);

        $this->assertCount(2, $this->lesson->tags);
    }

    /** @test */
    public function can_untag_all_lessons()
    {
        $this->lesson->tag(['Laravel', 'PHP', 'Fun Stuff']);
        $this->lesson->untag();

        $this->lesson->load('tags'); // or ->refresh();
        $this->assertCount(0, $this->lesson->tags);
    }

    /** @test */
    public function can_retag_a_lesson()
    {
        $this->lesson->tag(['Laravel', 'PHP', 'Unix']);
        $this->lesson->retag(['Laravel', 'Postgres', 'Fun Stuff']);
        $this->lesson->refresh(); //re-hydrate the existing model

        $this->assertCount(3, $this->lesson->tags);
    }

    /** @test */
    public function non_existing_tags_are_not_sync_to_lesson()
    {
        $this->lesson->tag(['Laravel', 'PHP', 'Unix', 'not in model', 'mee too', 'fake tag']);

        $this->assertCount(3, $this->lesson->tags);
    }

    /** @test */
    public function inconsistent_cases_are_normalize_and_duplicates_are_excluded()
    {
        $this->lesson->tag(['LaraVel', 'pHP', 'UnIX', 'laravel', 'PhP']);

        $this->assertCount(3, $this->lesson->tags);
    }

}
