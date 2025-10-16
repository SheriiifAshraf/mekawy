<?php

namespace App\Http\Repositories\StudentAuth;

use Log;
use Str;
use Exception;
use App\Jobs\SendMail;
use App\Models\Student;
use Twilio\Rest\Client;
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
        $deviceId   = $request->header('X-Device-ID') ?? $request->input('device_id');
        $deviceName = $request->header('X-Device-Name') ?? $request->input('device_name');

        if (!$deviceId) {
            return ['status' => false, 'errors' => ['error' => ['Device ID is required']]];
        }

        $model = $this->model->where('phone', $request->phone)->first();
        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.email')]]];
        }

        if (!Hash::check($request->password, $model->password)) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.password')]]];
        }

        if (config('feature.single_device_login')) {
            $hasActiveOnOtherDevice = $model->tokens()
                ->whereNull('expires_at')
                ->where(function ($q) use ($deviceId) {
                    $q->whereNull('device_id')->orWhere('device_id', '!=', $deviceId);
                })
                ->exists();

            if ($hasActiveOnOtherDevice) {
                return [
                    'status' => false,
                    'errors' => ['error' => ['حسابك مسجل دخول على جهاز آخر. سجّل الخروج من الجهاز الأول ثم حاول مرة أخرى.']],
                    'code'   => 'OTHER_DEVICE_ACTIVE',
                ];
            }
        }

        if (config('feature.single_device_login')) {
            $model->tokens()->where('device_id', $deviceId)->delete();
        }

        return [
            'status'      => true,
            'data'        => $model,
            'device_id'   => $deviceId,
            'device_name' => $deviceName,
        ];
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

        $model->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(5)
        ]);

        $this->send_sms($model->phone, "رمز التحقق الخاص بك: $otp");

        return ["status" => true];
    }


    public function pinCodeConfirmation($request)
    {
        $model = $this->model->where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->first();

        if (!$model) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }

        if (!$model->otp_expires_at || now()->greaterThan($model->otp_expires_at)) {
            return ['status' => false, 'errors' => ['error' => [trans('auth.invalid', ['attribute' => 'otp'])]]];
        }

        $model->update([
            'otp' => null,
            'otp_expires_at' => null,
            'token' => Str::random(60),
        ]);

        return ['status' => true, 'data' => $model];
    }

    private function send_sms($to, $message)
    {
        $to = preg_replace('/\s+/', '', $to);
        $to = preg_replace('/^0+/', '', $to);
        if (!str_starts_with($to, '+')) {
            $to = '+20' . $to;
        }

        if (app()->environment('production')) {
            $sid   = env('TWILIO_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $from  = env('TWILIO_FROM');
        } else {
            $sid   = env('TWILIO_TEST_SID');
            $token = env('TWILIO_TEST_TOKEN');
            $from  = '+15005550006';
        }

        $client = new Client($sid, $token);

        try {
            $sms = $client->messages->create($to, [
                'from' => $from,
                'body' => $message
            ]);

            Log::info("✅ SMS sent to {$to} - SID: {$sms->sid}");
            return true;
        } catch (Exception $e) {
            Log::error("❌ SMS send failed to {$to}: " . $e->getMessage());
            return false;
        }
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
