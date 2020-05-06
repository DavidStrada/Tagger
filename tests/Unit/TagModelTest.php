<?php

namespace Tests\Unit;

use DavidStrada\Tagger\Tests\Stubs\LessonStub;
use DavidStrada\Tagger\Tests\Stubs\TagStub;
use DavidStrada\Tagger\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class TagModelTest extends TestCase
{
    use RefreshDatabase;

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
    public function can_tag_lesson()
    {
        $this->lesson->tag(TagStub::where('slug', 'php')->first());

        $this->assertCount(1, $this->lesson->tags);
        $this->assertContains('php', $this->lesson->tags->pluck('slug'));
    }

    /** @test */
    public function can_tag_a_lesson_using_a_tag_model()
    {
        $tags = TagStub::find([1,2,3]); //['PHP', 'Laravel', 'Unix']
        $this->lesson->tag($tags);

        $this->assertCount(3, $this->lesson->tags);
        // check assigned tags are were assigned to the model.
        collect(['Laravel', 'PHP', 'Unix'])
            ->each(fn($tag) => $this->assertContains($tag, $this->lesson->tags->pluck('name')));
    }

    /** @test */
    public function can_untag_a_lesson()
    {
        $tags = TagStub::find([1,2,3]); //['PHP', 'Laravel', 'Unix']
        $this->lesson->tag($tags);

        $this->lesson->untag($tags->first()); //untag PHP

        $this->assertCount(2, $this->lesson->tags);
        // check assigned tags are were assigned to the model.
        collect(['Laravel', 'Unix'])
            ->each(fn($tag) => $this->assertContains($tag, $this->lesson->tags->pluck('name')));

    }

    /** @test */
    public function can_untag_all_lessons()
    {
        $tags = TagStub::find([1,2,3]); //['PHP', 'Laravel', 'Unix']
        $this->lesson->tag($tags);

        $this->lesson->untag();
        $this->lesson->refresh();

        $this->assertCount(0, $this->lesson->tags);
    }

    /** @test */
    public function can_retag_a_lesson()
    {
        $tags = TagStub::find([1,2,3]); //['PHP', 'Laravel', 'Unix']
        $retag = TagStub::find([4,5,1]); //['Mac OSX', 'Postgres', 'PHP']

        $this->lesson->tag($tags);
        $this->lesson->retag($retag);
        $this->lesson->refresh(); //re-hydrate the existing model

        $this->assertCount(3, $this->lesson->tags);

         // check assigned tags are were assigned to the model.
        collect(['Mac OSX', 'Postgres', 'PHP'])
            ->each(fn($tag) => $this->assertContains($tag, $this->lesson->tags->pluck('name')));
    }

    /** @test */
    public function non_models_are_filterd_out_of_a_collection()
    {
        $tags = TagStub::find([1,2,3]); //['PHP', 'Laravel', 'Unix']
        $tags->push('not-a-tag-model');

        $this->lesson->tag($tags);

        $this->assertCount(3, $this->lesson->tags);
    }
}
