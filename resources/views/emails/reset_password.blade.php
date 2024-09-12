<!-- resources/views/emails/reset_password.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>IT Ops Today: Reset Password</title>
</head>
<body>
    <p>Hi {{ $name }},</p>

    <p>You are receiving this email because we received a password reset request for your account.</p>

    <p>Please click the link below to reset your password:</p>

    <a href="{{ $resetLink }}">Reset Password</a>

    <p>If you did not request a password reset, no further action is required.</p>

    <p>Thank you!</p>
</body>
</html>
