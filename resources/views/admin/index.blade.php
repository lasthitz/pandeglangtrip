<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h1>ADMIN PANEL (T3 Dummy)</h1>
    <p>Halo, {{ auth()->user()->name }} | role: {{ auth()->user()->role }}</p>
    <p>URL ini hanya boleh diakses admin.</p>
</body>
</html>
