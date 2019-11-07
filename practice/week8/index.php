<?php require 'index-controller.php'; ?>

<!doctype html>
<html lang='en'>

<head>

    <title>Week 8 - RPS with an external package</title>
    <meta charset='utf-8'>

    <style>
        #play {
            background-color: #eee;
            padding: 10px;
            width: 300px;
        }

        #play h2 {
            margin: 0 0 15px 0;
        }


        #play button {
            display: block;
            margin-top: 20px;
        }
    </style>

</head>

<body>
    <h1>Week 8 - RPS with an external package</h1>

    <section id='instructions'>
        <h2>Instructions</h2>
        <p>
            It’s you vs. the computer! First choose a move: <em>rock</em>, <em>paper</em>, <em>scissors</em>, then
            <em>Shoot!</em>
        </p>

        <p>
            The computer will choose its move and the victor will be declared according to the following rules:
        </p>

        <ul>
            <li>Rock beats Scissors
            <li>Scissors beats Paper
            <li>Paper beats Rock
        </ul>

        <p>
            If you and the computer choose the same move, it’s a tie.
        </p>
    </section>

    <section id='play'>
        <h2>Play</h2>
        <form method='POST' action='process.php'>

            <?php foreach ($moves as $move): ?>
            <input type='radio' name='player' value='<?=$move ?>'
                id='<?=$move ?>' <?= ($lastGame and $move == $lastGame['player']) ? 'checked' : '' ?>
            >
            <label for='<?=$move ?>'><?=ucfirst($move) ?></label>
            <?php endforeach ?>

            <button type='submit'>Shoot!</button>
        </form>
    </section>

    <section id='results'>
        <?php if ($lastGame): ?>
        <h2>Results</h2>
        <ul>
            <li>You chose <strong><?=$lastGame['player'] ?></strong>
            </li>
            <li>The computer chose <strong><?=$lastGame['computer'] ?></strong>
            </li>
            <li>
                <?php if ($lastGame['outcome'] == 'lost'): ?>
                You lost :(
                <?php elseif ($lastGame['outcome'] == 'won'): ?>
                You won! :)
                <?php else: ?>
                It was a tie.
                <?php endif ?>
            </li>
        </ul>
        <?php endif ?>
    </section>


    <section id='recentGames'>
        <?php if ($recentGames): ?>
        <h2>Game history</h2>
        <?php foreach ($recentGames as $game): ?>
        <ul>
            <li>Player chose: <?= $game['player'] ?>
            <li>Computer chose: <?= $game['computer'] ?>
            <li>Outcome: <?= $game['outcome'] ?>
            <li>Played at: <?= $game['timestamp'] ?>
        </ul>
        <?php endforeach ?>
        <?php endif ?>
    </section>

</body>

</html>