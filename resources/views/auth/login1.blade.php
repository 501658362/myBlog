<html>
<head>
    <link href="/css/app.css" rel="stylesheet" />
    <script src="/js/app.js"></script>
</head>
<body>


<form method="POST" action="/auth/login">
    {!! csrf_field() !!}

    <div>
        Email
        <input type="email" class="form-control" name="email" value="{{ old('email') }}">
    </div>

    <div>
        Password
        <input type="password" name="password" id="password">
    </div>

    <div>
        <input type="checkbox" name="remember"> Remember Me
    </div>

    <div>
        <button type="submit">Login</button>
    </div>
    @if (count($errors) > 0)
        <div class="tools-alert tools-alert-red">
            <strong>错误</strong>你填写数据有问题！请重新填写！<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>

    @endif

</form>
</body>
</html>