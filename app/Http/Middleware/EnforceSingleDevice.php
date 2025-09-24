<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnforceSingleDevice
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // هيجيب يوزر الجارد الحالي (student)
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $deviceId = $request->header('X-Device-ID') ?? (string)$request->input('device_id');
        if (!$deviceId) {
            return response()->json(['message' => 'X-Device-ID header is required'], 400);
        }

        $token = $user->currentAccessToken(); // Sanctum token
        if ($token && $token->device_id && $token->device_id !== $deviceId) {
            return response()->json([
                'message' => 'تم تسجيل الدخول من جهاز آخر. يُرجى تسجيل الخروج من الجهاز الأول أولاً.'
            ], 403);
        }

        return $next($request);
    }
}
