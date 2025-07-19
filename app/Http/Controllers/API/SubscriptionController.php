<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\SubscriptionResource;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::where('student_id', auth('student')->id())->with('student', 'course')->get();
        $subscriptions = SubscriptionResource::collection($subscriptions);
        return response()->json([
            'message' => 'Success!',
            'data' => $subscriptions
        ]);
    }

    public function show($id)
    {
        $subscription = Subscription::where('student_id', auth('student')->id())->with('student', 'course')->find($id);
        if (!$subscription) {
            return response()->json([
                'message' => 'Fail!',
                'data' => []
            ], 404);
        }
        $subscription = new SubscriptionResource($subscription);
        return response()->json([
            'message' => 'Success!',
            'data' => $subscription
        ]);
    }

    public function store(Request $request)
    {
        $existingSubscription = Subscription::where('student_id', auth('student')->id())
            ->where('course_id', $request->course_id)
            ->exists();

        if ($existingSubscription) {
            return response()->json([
                'message' => 'You are already subscribed to this course.',
                'data' => []
            ], 400);
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id'
        ]);

        $subscription = Subscription::firstOrCreate([
            'student_id' => auth('student')->id(),
            'course_id' => $request->course_id,
            'status' => 0
        ]);

        return response()->json([
            'message' => 'Success!',
            'data' => new SubscriptionResource($subscription)
        ]);
    }

}
