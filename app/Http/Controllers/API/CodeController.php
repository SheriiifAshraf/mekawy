<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\CodeResource;
use App\Models\Code;
use App\Models\Subscription;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function userCodes()
    {
        $codes = auth('student')->user()->codes;
        $codes = CodeResource::collection($codes);
        return response()->json([
            'success' => true,
            'data' => $codes,
        ]);
    }

    public function codeDetails($id)
    {
        $code = auth('student')->user()->codes()->find($id);
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Code not found',
            ], 404);
        }
        $code = new CodeResource($code);
        return response()->json([
            'success' => true,
            'data' => $code,
        ]);
    }

    public function markAsUsed(Request $request)
    {
        $request->validate([
            'code' => 'required|string|exists:codes,code',
        ]);

        $student = auth('student')->user();

        $code = Code::where('code', $request->code)->whereNull('student_id')->first();

        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Code not found or already assigned',
            ], 404);
        }

        $existingSubscription = Subscription::where('student_id', $student->id)
            ->where('course_id', $code->course_id)
            ->exists();

        if ($existingSubscription) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to this course.',
            ], 400);
        }

        $code->student_id = $student->id;
        $code->used_at = now();
        $code->save();

        Subscription::create([
            'student_id' => $student->id,
            'course_id' => $code->course_id,
            'status' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Code assigned to student and marked as used',
            'data' => new CodeResource($code),
        ]);
    }

    public function markAsCanceled($id)
    {
        $code = auth('student')->user()->codes()->find($id);
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Code not found',
            ], 404);
        }

        $code->canceled_at = now();
        $code->save();

        return response()->json([
            'success' => true,
            'message' => 'Code marked as canceled',
            'data' => new CodeResource($code),
        ]);
    }

    public function markAsStarted($id)
    {
        $code = auth('student')->user()->codes()->find($id);
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Code not found',
            ], 404);
        }

        $code->started_at = now();
        $code->save();

        return response()->json([
            'success' => true,
            'message' => 'Code marked as started',
            'data' => new CodeResource($code),
        ]);
    }

    public function markAsFinished($id)
    {
        $code = auth('student')->user()->codes()->find($id);
        if (!$code) {
            return response()->json([
                'success' => false,
                'message' => 'Code not found',
            ], 404);
        }

        $code->finished_at = now();
        $code->save();

        return response()->json([
            'success' => true,
            'message' => 'Code marked as finished',
            'data' => new CodeResource($code),
        ]);
    }
}
