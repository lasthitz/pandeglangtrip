<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Account</title>
</head>
<body>
    <h1>USER ACCOUNT (T3 Dummy)</h1>
    <p>Halo, {{ auth()->user()->name }} | role: {{ auth()->user()->role }}</p>
    <p>URL ini hanya boleh diakses user/wisatawan.</p>
</body>
</html>
