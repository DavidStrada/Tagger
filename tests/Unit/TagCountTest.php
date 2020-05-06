<?php

namespace Tests\Unit;

use DavidStrada\Tagger\Tests\Stubs\LessonStub;
use DavidStrada\Tagger\Tests\Stubs\TagStub;
use DavidStrada\Tagger\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class TagCountTest extends TestCase
{
    protected $lesson;

    public function setUp() : void
    {
        parent::setUp();

        $this->lesson = LessonStub::create([
            'title' => 'lesson one'
        ]);

    }

    /** @test */
   public function tag_count_is_incremented_when_a_model_is_tagged()
   {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->lesson->tag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(1, $tag->count);
   }

   /** @test */
   public function tag_count_is_decremented_when_a_model_is_tagged()
   {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
            'count' => 5
        ]);

        $this->lesson->tag(['laravel']);
        $this->lesson->untag(['laravel']);

        $tag = $tag->fresh();

        $this->assertEquals(5, $tag->count);
   }

   /** @test */
   public function tag_count_is_never_less_than_zero()
   {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->lesson->untag($tag);
        $tag = $tag->fresh();

        $this->assertEquals(0,$tag->count);
   }

   /** @test */
   public function model_does_not_get_retag_when_attached_same_tag_twice()
   {
        $tag = TagStub::create([
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $this->lesson->tag($tag);
        $this->lesson->retag($tag);
        $this->lesson->tag($tag);

        $tag = $tag->fresh();

        $this->assertEquals(1,$tag->count);
   }
}
