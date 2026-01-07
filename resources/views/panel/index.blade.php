<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelola Panel</title>
</head>
<body>
    <h1>PENGELOLA PANEL (T3 Dummy)</h1>
    <p>Halo, {{ auth()->user()->name }} | role: {{ auth()->user()->role }}</p>
    <p>URL ini hanya boleh diakses pengelola.</p>
</body>
</html>
