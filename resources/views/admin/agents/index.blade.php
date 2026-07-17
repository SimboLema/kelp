<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>AGENTS</h1>
    <form method="POST" action="{{ route('admin.agents.create') }}">
        @csrf
        <input type="text" name="name" placeholder="name">
        <input type="text" name="password" placeholder="password">
        <input type="tel" name="phone_number" placeholder="phone number">
        <button type="submit">CREATE AGENT</button>
    </form>
</body>
</html>