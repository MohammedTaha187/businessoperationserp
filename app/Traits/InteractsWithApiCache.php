<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait InteractsWithApiCache
{
    protected function forgetApiCache(array $keys): void
    {
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    protected function forgetPublicContentCache(): void
    {
        $this->forgetApiCache([
            'api:v1:home',
        ]);
    }
}
