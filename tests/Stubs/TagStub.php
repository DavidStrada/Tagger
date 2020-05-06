<?php

namespace DavidStrada\Tagger\Tests\Stubs;

use DavidStrada\Tagger\Scopes\TagUsedScopesTrait;
use Illuminate\Database\Eloquent\Model;

class TagStub extends Model
{
    use TagUsedScopesTrait;

    protected $connection = "testbench";

    public $table = "tags";
}
