<?php

namespace App\Http\Controllers\API;

use App\Services\ResponseService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ConfirmTokenRequest;
use App\Http\Requests\StudentLoginRequest;
use App\Http\Requests\StudentSignupRequest;
use App\Http\Resources\API\StudentResource;
use App\Http\Resources\API\UpdateProfileResource;
use App\Http\Requests\StudentResetPasswordRequest;
use App\Http\Requests\UpdateStudentProfileRequest;
use App\Http\Resources\API\StudentProfileResource;
use App\Http\Requests\StudentConfirmPasswordRequest;
use App\Http\Repositories\StudentAuth\StudentAuthInterface;

class StudentAuthController extends Controller
{
    public function __construct(private StudentAuthInterface $modelInterface, public ResponseService $ResponseService)
    {
        $this->modelInterface = $modelInterface;
    }
    public function login(StudentLoginRequest $request)
    {
        $auth = $this->modelInterface->login($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, ['error' => [trans('messages.error')]]);
        }
        if (!$auth['status']) {
            return $this->ResponseService->json('Fail!', [], 400, $auth['errors']);
        }
        $token = $auth['data']->createToken('student')->plainTextToken;
        $tokenModel = $auth['data']->tokens()->latest('id')->first();
        if ($tokenModel) {
            $tokenModel->device_id   = $auth['device_id'] ?? ($request->header('X-Device-ID') ?? $request->input('device_id'));
            $tokenModel->device_name = $auth['device_name'] ?? ($request->header('X-Device-Name') ?? $request->input('device_name'));
            $tokenModel->ip          = $request->ip();
            $tokenModel->user_agent  = (string) $request->userAgent();
            $tokenModel->save();
        }
        $Student = new StudentResource($auth['data'], $token);
        return $this->ResponseService->Json("success", $Student, 200);
    }

    public function signup(StudentSignupRequest $request)
    {
        $auth = $this->modelInterface->signup($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, []);
        }
        $token = $auth->createToken('student')->plainTextToken;
        $Student = new StudentResource($auth, $token);
        return $this->ResponseService->Json("success", $Student, 200);
    }

    public function logout()
    {
        if (!auth('student')->user()) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.forbidden')]]];
        }
        auth('student')->user()->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function update(UpdateStudentProfileRequest $request)
    {
        $auth = $this->modelInterface->update($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, ['error' => [trans('messages.error')]]);
        }
        if (!$auth['status']) {
            return $this->ResponseService->json('Fail!', [], 400, $auth['errors']);
        }
        $update = auth('student')->user();
        return $this->ResponseService->Json("success", new UpdateProfileResource($update), 200);
    }

    public function profile()
    {
        if (!auth('student')->user()) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.forbidden')]]];
        }
        $Student = new StudentProfileResource(auth('student')->user());
        return $this->ResponseService->Json("success", $Student, 200);
    }

    public function resetPassword(StudentResetPasswordRequest $request)
    {
        $auth = $this->modelInterface->resetPassword($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, ['error' => [trans('messages.error')]]);
        }
        if (!$auth['status']) {
            return $this->ResponseService->json('Fail!', [], 400, $auth['errors']);
        }
        return $this->ResponseService->Json("success", [], 200);
    }

    public function confirmPinCode(ConfirmTokenRequest $request)
    {
        $auth = $this->modelInterface->pinCodeConfirmation($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, ['error' => [trans('messages.error')]]);
        }
        if (!$auth['status']) {
            return $this->ResponseService->json('Fail!', [], 400, $auth['errors']);
        }
        return $this->ResponseService->Json("success", $auth['data']->token, 200);
    }

    public function confirmPassword(StudentConfirmPasswordRequest $request)
    {
        $auth = $this->modelInterface->confirmPassword($request);
        if (!$auth) {
            return $this->ResponseService->json('Fail!', [], 400, ['error' => [trans('messages.error')]]);
        }
        if (!$auth['status']) {
            return $this->ResponseService->json('Fail!', [], 400, $auth['errors']);
        }
        return $this->ResponseService->Json("success", [], 200);
    }

    public function completed()
    {
        $student = Auth::user();

        $homeworksCount = $student->homeworkAttempts()->where('completed', true)->count();
        $examsCount = $student->examAttempts()->where('completed', true)->count();
        $videosCount = $student->videoViews()->count();

        return response()->json([
            'Completed Homeworks' => $homeworksCount,
            'Completed Exams' => $examsCount,
            'Viewed Videos' => $videosCount,
        ]);
    }
}
