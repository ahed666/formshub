<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Trial is Ending Soon</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>Your trial subscription will end soon at <bold>{{$endDate}}</bold>. Don't miss out on continuing your subscription!</p>
    <p>Please visit our site to choose a plan and continue enjoying our services.</p>
    <a href="{{ route('subscriptions.index') }}">Go to Subscriptions</a>
</body>
</html>
