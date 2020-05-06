<?php

namespace DavidStrada\Tagger\Models;

use DavidStrada\Tagger\Scopes\TagUsedScopesTrait;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use TagUsedScopesTrait;
}
