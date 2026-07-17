<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    @if ($errors->any())
    <div class="alert alert-danger">
        {{ $errors->first() }}
    </div>
    @endif
    <form method="POST" action="{{ route('business.login.submit') }}">
        @csrf
        <input type="email" name="email" id="email" placeholder="EMAIL">
        <input type="password" name="password" id="password" placeholder="PASSWORD">
        <button type="submit">LOGIN</button>
    </form>
</body>
</html>
