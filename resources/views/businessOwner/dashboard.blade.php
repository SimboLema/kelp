<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Busness Owner Dashboard</h1>

<div class="side-menu">
    <a href="{{ route('businessOwner.dashboard') }}">Dashbord</a>
    <a href="{{ route('business.reviews') }}">Reviews</a>
</div>

<div class="stats-card">
    <h4>Total Reviews: {{ $totalReviews }}</h4>
</div>
</body>
</html>
