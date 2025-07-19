<?php

namespace App\Http\Repositories\StudentAuth;

use App\Jobs\SendMail;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'father_phone' => $request->father_phone,
            'password' => $request->password,
            'location_id' => $request->location_id,
            'email' => $request->email,
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
            $user->update([
                'first_name' => $request->first_name,
            ]);
        }
        if ($request->has('last_name')) {
            $user->update([
                'last_name' => $request->last_name,
            ]);
        }
        if ($request->has('phone')) {
            $user->update([
                'phone' => $request->phone,
            ]);
        }
        if ($request->has('father_phone')) {
            $user->update([
                'father_phone' => $request->father_phone,
            ]);
        }
        if ($request->has('password')) {
            $user->update([
                'password' => $request->password,
            ]);
        }
        if ($request->has('email')) {
            $user->update([
                'email' => $request->email,
            ]);
        }

        return ['status' => true];
    }

    public function resetPassword($request)
    {
        $otp = rand(111111, 999999);
        $model = $this->model->where('email', $request->email)->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.email')]]];
        }
        $model->update(['otp' => $otp]);
        $model->refresh();
        dispatch(new SendMail($model->email, $model->otp));
        return ["status" => true];
    }

    public function pinCodeConfirmation($request)
    {
        $model = $this->model->where([
            'email' => $request->email,
        ])->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.email')]]];
        }
        $model = $this->model->where([
            'otp' => $request->otp,
        ])->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }
        $updatedAt = $model->updated_at;
        $now = now();
        $timeDifferenceInMinutes = $now->diffInMinutes($updatedAt);
        if ($timeDifferenceInMinutes >= 5) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }
        $model->update([
            'otp' => null,
            'token' => \Illuminate\Support\Str::random(60),
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
