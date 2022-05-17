<?php

namespace Wikichua\Bliss\Traits;

use Illuminate\Support\Facades\File;

if (File::exists(base_path('vendor/jenssegers/mongodb'))) {
    trait HybridRelations
    {
        use \Jenssegers\Mongodb\Eloquent\HybridRelations;

    }
} else {
    trait HybridRelations
    {

    }
}


