<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; padding: 20px;">

    <div
        style="background-color: #fff; max-width: 600px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Verification Code</h2>

        <p>Hello,</p>

        <p>We received a request to access your account. Use the verification code below to complete the login process.
            This code is valid for a short period of time.</p>

        <table style="background-color: #f0f0f0; width: 100%; padding: 10px; border-radius: 5px;">
            <tr>
                <td style="text-align: center; font-size: 24px; font-weight: bold; padding: 15px;">{{ $otp }}
                </td>
            </tr>
        </table>

        {{-- <p style="margin-top: 20px;">
            <a href="{{ url('/login') }}"
                style="display: inline-block; background-color: #007bff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Verify
                Now</a>
        </p> --}}

        <p>If you did not request this, please ignore this email or contact support if you have any questions.</p>

        <p>Thanks,<br>
            {{ config('app.name') }}</p>

        {{-- <p style="font-size: 14px; margin-top: 20px;">If you're having trouble clicking the "Verify Now" button, copy
            and paste the URL below into your web browser:</p>
        <p style="font-size: 14px; margin-top: 5px;"><a href="{{ url('/login') }}"
                style="color: #007bff; text-decoration: none;">{{ url('/login') }}</a></p> --}}
    </div>

</body>

</html>
