<!doctype html>
<html lang='en'>

<head>

    <title>@yield('title', $app->config('app.name'))</title>
    <meta charset='utf-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel='stylesheet' href='/css/cpeg.css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>




    @yield('head')

</head>

<body>

    <?php
    $menuItem_selected = substr($_SESSION['e2_session_previous'], 1);
    ?>
    <header>
        <nav class='navbar navbar-expand-md'>
            <a class='navbar-brand' href='/'>Clown Princess Resort</a>

            <ul class='navbar-nav'>
                <li
                    class='nav-item <?=(empty($menuItem_selected) || strstr($menuItem_selected, "blackjack") ? "active" : "")?>'>
                    <a class='nav-link' href='/blackjack'>Play Black Jack</a>
                </li>
                <li
                    class='nav-item <?=(strstr($menuItem_selected, "cnotewar") ? "active" : "")?>'>
                    <a class='nav-link' href='/cnotewar'>Play Cnote War</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li
                    class='nav-item <?=(strstr($menuItem_selected, "about") ? "active" : "")?>'>
                    <a class='nav-link' href='/about'>About</a>
                </li>

                <?php if (array_key_exists('cpeg_patron', $_SESSION)) { ?>
                <li
                    class='nav-item <?=(strstr($menuItem_selected, "patron") ? "active" : "")?>'>
                    <a class='nav-link ' href='/patron'>Players Club</a>
                </li>
                <?php } else {?>
                <li
                    class='nav-item <?=(strstr($menuItem_selected, "register") ? "active" : "")?>'>
                    <a class='nav-link' href='/register'>Register</a>
                </li>
                <?php } ?>
            </ul>

        </nav>
    </header>

    <main>
        @yield('content') </main>
    <footer>
        <div class='container pt-6'>
            This is not a gambling site.
        </div>
    </footer>

    @yield('body')

    <!-- <?php (isset($game) ? dump($game) : "");?>
    <?php dump($_SESSION);?> -->

</body>

</html>