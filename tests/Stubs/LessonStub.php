<?php

namespace DavidStrada\Tagger\Tests\Stubs;

use DavidStrada\Tagger\TaggableTrait;
use Illuminate\Database\Eloquent\Model;

class LessonStub extends Model
{
    use TaggableTrait;

    protected $connection = "testbench";

    public $table = "lessons";
}
