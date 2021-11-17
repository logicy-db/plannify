<!doctype html>
<html lang="en" class="login-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Log in to Plannnify</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="body">
    <h1 class="header">
        Plannify
    </h1>
    <div class="wrapper">
        <h2 class="text">Log in to your account</h2>
        <form class="form" action="#" method="POST">
            <div>
                <div>
                    <label for="email-address">Email address</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required placeholder="Email address">
                </div>
                <div>
                    <label for="password">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Password">
                </div>
            </div>
            <div>
                <button type="submit">Log in</button>
            </div>
        </form>
        <br/>
        <div>
            Forgot your password?<br/>
            <b>Click here!</b>
        </div>
    </div>
</body>
</html>
