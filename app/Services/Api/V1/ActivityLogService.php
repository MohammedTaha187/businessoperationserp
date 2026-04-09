<?php

namespace App\Services\Api\V1;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityLogService
{
    /**
     * تسجيل نشاط جديد
     */
    public function log(string $action, string $module, ?int $modelId = null, ?array $oldData = null, ?array $newData = null): void
    {
        $user = Auth::user();

        DB::table('activity_logs')->insert([
            'user_id' => $user ? $user->id : null,
            'company_id' => $user ? $user->company_id : null,
            'action' => $action,
            'module' => $module,
            'model_id' => $modelId,
            'old_data' => $oldData ? json_encode($oldData) : null,
            'new_data' => $newData ? json_encode($newData) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
