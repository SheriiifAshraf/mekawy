<?php

namespace App\Http\Repositories\StudentAuth;

interface StudentAuthInterface
{
    public function login($request);
    public function signup($request);

    public function update($request);

    public function resetPassword($request);

    public function pinCodeConfirmation($request);

    public function confirmPassword($request);
}
