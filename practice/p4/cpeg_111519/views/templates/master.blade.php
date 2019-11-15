<!doctype html>
<html lang='en'>

<head>

    <title>@yield('title', $app->config('app.name'))</title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link rel='stylesheet' href='/css/cpeg.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


    @yield('head')

</head>

<body>

    <?php
    $menuItem_selected = substr($_SESSION['e2_session_previous'], 1);
    ?>
    <header>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="/">Clown Princess Resort</a>
                </div>
                <ul class="nav navbar-nav">
                    <li
                        class='<?=($menuItem_selected=="blackjack" ? "active" : "")?>'>
                        <a href="/blackjack">Black Jack</a></li>
                </ul>
                <ul class="nav navbar-nav">
                    <li
                        class='<?=($menuItem_selected=="casinowar" ? "active" : "")?>'>
                        <a href="/casinowar">Casino War</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li
                        class='<?=($menuItem_selected=="about" ? "active" : "")?>'>
                        <a href="/about">About</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li
                        class='<?=((($menuItem_selected=="services") or ($menuItem_selected=="playerinfo") or ($menuItem_selected=="register")) ? "active" : "")?>'>
                        <a href="/services">Guest Services</a></li>
                </ul>
            </div>

        </nav>
    </header>

    <main>
        @yield('content') </main>
    <footer>
    </footer>

    @yield('body')

</body>

</html>