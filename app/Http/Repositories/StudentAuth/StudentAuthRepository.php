<?php

namespace App\Http\Repositories\StudentAuth;

use App\Jobs\SendMail;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;

class StudentAuthRepository implements StudentAuthInterface
{
    private $model;

    public function __construct(Student $model)
    {
        $this->model = $model;
    }

    public function login($request)
    {
        $model = $this->model->where('phone', $request->phone)->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.email')]]];
        }
        if (!Hash::check($request->password, $model->password)) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.password')]]];
        }
        return ['status' => true, 'data' => $model];
    }

    public function signup($request)
    {
        $model = $this->model->create([
            'first_name'    => $request->first_name,
            'middle_name'   => $request->middle_name,
            'last_name'     => $request->last_name,
            'phone'         => $request->phone,
            'father_phone'  => $request->father_phone,
            'password'      => $request->password,
            'location_id'   => $request->location_id,
            'education_stage_id' => $request->education_stage_id,
            'grade_id' => $request->grade_id,
        ]);
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('crud.create', ['model' => 'user'])]]];
        }
        return $model;
    }

    public function update($request)
    {
        if (!Auth::user()) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.forbidden')]]];
        }

        $user = Auth::user();

        if ($request->has('first_name')) {
            $user->update(['first_name' => $request->first_name]);
        }

        if ($request->has('middle_name')) {
            $user->update(['middle_name' => $request->middle_name]);
        }

        if ($request->has('last_name')) {
            $user->update(['last_name' => $request->last_name]);
        }

        if ($request->has('phone')) {
            $user->update(['phone' => $request->phone]);
        }

        if ($request->has('father_phone')) {
            $user->update(['father_phone' => $request->father_phone]);
        }

        if ($request->has('education_stage_id')) {
            $user->update(['education_stage_id' => $request->education_stage_id]);
        }

        if ($request->has('grade_id')) {
            $user->update(['grade_id' => $request->grade_id]);
        }

        if ($request->has('password')) {
            $user->update(['password' => $request->password]);
        }

        return ['status' => true];
    }


    public function resetPassword($request)
    {
        $otp = rand(111111, 999999);
        $model = $this->model->where('phone', $request->phone)->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.phone')]]];
        }

        $model->update(['otp' => $otp]);

        send_sms($model->phone, "رمز التحقق الخاص بك: $otp");

        return ["status" => true];
    }


    public function pinCodeConfirmation($request)
    {
        $model = $this->model->where([
            'phone' => $request->phone,
            'otp' => $request->otp,
        ])->first();

        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }

        $updatedAt = $model->updated_at;
        if (now()->diffInMinutes($updatedAt) >= 5) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }

        $model->update([
            'otp' => null,
            'token' => \Str::random(60),
        ]);

        return ['status' => true, 'data' => $model];
    }


    public function confirmPassword($request)
    {
        $model = $this->model->where('token', $request->token)->where('token', '!=', null)->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'token'])]]];
        }
        $model->update(['token' => null, 'password' => $request->password]);
        return ['status' => true];
    }
}
