<?php

function generateShortCode($length = 10) {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

require_once("system/con_db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptcha_secret = "6LccH0MgAAAAAONt4FD2MK5c13KZ387wDDZm06wQ";
    $recaptcha_response = $_POST["g-recaptcha-response"];

    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_data = array(
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($recaptcha_data)
        )
    );

    $recaptcha_context = stream_context_create($options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_data = json_decode($recaptcha_result, true);

    if (!$recaptcha_data['success']) {
        echo '<p>Please complete the captcha and try again.</p>';
        exit();
    }

    $text = $_POST["notetexts"];
    $code = generateShortCode();

    $query = "SELECT * FROM notes WHERE code = '$code'";
    $result = mysqli_query($conn, $query);

    while (mysqli_num_rows($result) > 0) {
        $code = generateShortCode();
        $query = "SELECT * FROM notes WHERE code = '$code'";
        $result = mysqli_query($conn, $query);
    }

    $sql = "INSERT INTO notes (text, code) 
            VALUES ('$text', '$code')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: /view.php?page=$code");
    } else {
        echo 'Unknown Error';
    }

    mysqli_close($conn);
}
?>
<?php
require_once("system/con_db.php");

$query = "SELECT COUNT(*) as totalNotes FROM notes";
$result = mysqli_query($conn, $query);


$row = mysqli_fetch_assoc($result);
$totalNotes = $row['totalNotes'];

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="https://kit-pro.fontawesome.com/releases/v6.2.0/css/pro.min.css" rel="stylesheet">
        <meta charset="UTF-8">
        <title>Candle Note</title>
        <meta name="google-adsense-account" content="ca-pub-5724120681963585">
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Mitr:wght@500&family=Noto+Sans+Thai&display=swap" rel="stylesheet">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-5724120681963585"crossorigin="anonymous"></script>
        <style>
            *{
                font-family: 'Noto Sans Thai', sans-serif;
            }

            body {
            background : black;
            }

            .holder {
            margin: 12rem auto 0;
            width: 150px;
            height: 400px;
            position: relative;
            }

            .holder *, .holder *:before, .holder *:after {
            position: absolute;
            content: "";
            }

            .candle {
            bottom: 0;
            width: 150px;
            height: 300px;
            border-radius: 150px / 40px;
            -webkit-box-shadow: inset 20px -30px 50px 0 rgba(0, 0, 0, 0.4), inset -20px 0 50px 0 rgba(0, 0, 0, 0.4);
            box-shadow: inset 20px -30px 50px 0 rgba(0, 0, 0, 0.4), inset -20px 0 50px 0 rgba(0, 0, 0, 0.4);
            background: #190f02;
            background: -webkit-gradient(linear, left top, left bottom, from(#e48825), color-stop(#e78e0e), color-stop(#833c03), color-stop(50%, #4c1a03), to(#1c0900));
            background: linear-gradient(#e48825, #e78e0e, #833c03, #4c1a03 50%, #1c0900);
            }

            .candle:before {
            width: 100%;
            height: 40px;
            border-radius: 50%;
            border: 2px solid #d47401;
            background: #b86409;
            background: radial-gradient(#ffef80, #b86409 60%);
            background: radial-gradient(#eaa121, #8e4901 45%, #b86409 80%);
            }

            .candle:after {
            width: 34px;
            height: 10px;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            border-radius: 50%;
            top: 14px;
            -webkit-box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.5);
            background: radial-gradient(rgba(0, 0, 0, 0.6), transparent 45%);
            }

            .thread {
            width: 6px;
            height: 36px;
            top: -17px;
            left: 50%;
            z-index: 1;
            border-radius: 40% 40% 0 0;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            background: #121212;
            background: -webkit-gradient(linear, left top, left bottom, from(#d6994a), color-stop(#4b232c), color-stop(#121212), color-stop(black), color-stop(90%, #e8bb31));
            background: linear-gradient(#d6994a, #4b232c, #121212, black, #e8bb31 90%);
            }

            .flame {
            width: 24px;
            height: 120px;
            left: 50%;
            -webkit-transform-origin: 50% 100%;
            -ms-transform-origin: 50% 100%;
            transform-origin: 50% 100%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            bottom: 100%;
            border-radius: 50% 50% 20% 20%;
            background: rgba(255, 255, 255, 1);
            background: -webkit-gradient(linear, left top, left bottom, color-stop(80%, white), to(transparent));
            background: linear-gradient(white 80%, transparent);
            -webkit-animation: moveFlame 6s linear infinite, enlargeFlame 5s linear infinite;
            animation: moveFlame 6s linear infinite, enlargeFlame 5s linear infinite;
            }

            .flame:before {
            width: 100%;
            height: 100%;
            border-radius: 50% 50% 20% 20%;
            -webkit-box-shadow: 0 0 15px 0 rgba(247, 93, 0, .4), 0 -6px 4px 0 rgba(247, 128, 0, .7);
            box-shadow: 0 0 15px 0 rgba(247, 93, 0, .4), 0 -6px 4px 0 rgba(247, 128, 0, .7);
            }

            @-webkit-keyframes moveFlame {
            0%, 100% {
                -webkit-transform: translateX(-50%) rotate(-2deg);
                transform: translateX(-50%) rotate(-2deg);
            }

            50% {
                -webkit-transform: translateX(-50%) rotate(2deg);
                transform: translateX(-50%) rotate(2deg);
            }
            }

            @keyframes moveFlame {
            0%, 100% {
                -webkit-transform: translateX(-50%) rotate(-2deg);
                transform: translateX(-50%) rotate(-2deg);
            }

            50% {
                -webkit-transform: translateX(-50%) rotate(2deg);
                transform: translateX(-50%) rotate(2deg);
            }
            }

            @-webkit-keyframes enlargeFlame {
            0%, 100% {
                height: 120px;
            }

            50% {
                height: 140px;
            }
            }

            @keyframes enlargeFlame {
            0%, 100% {
                height: 120px;
            }

            50% {
                height: 140px;
            }
            }

            .glow {
            width: 26px;
            height: 60px;
            border-radius: 50% 50% 35% 35%;
            left: 50%;
            top: -48px;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            background: rgba(0, 133, 255, .7);
            -webkit-box-shadow: 0 -40px 30px 0 #dc8a0c, 0 40px 50px 0 #dc8a0c, inset 3px 0 2px 0 rgba(0, 133, 255, .6), inset -3px 0 2px 0 rgba(0, 133, 255, .6);
            box-shadow: 0 -40px 30px 0 #dc8a0c, 0 40px 50px 0 #dc8a0c, inset 3px 0 2px 0 rgba(0, 133, 255, .6), inset -3px 0 2px 0 rgba(0, 133, 255, .6);
            }

            .glow:before {
            width: 70%;
            height: 60%;
            left: 50%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            bottom: 0;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.35);
            }

            .blinking-glow {
            width: 100px;
            height: 180px;
            left: 50%;
            top: -55%;
            -webkit-transform: translateX(-50%);
            -ms-transform: translateX(-50%);
            transform: translateX(-50%);
            border-radius: 50%;
            background: #ff6000;
            -webkit-filter: blur(50px);
            -moz-filter: blur(60px);
            -o-filter: blur(60px);
            -ms-filter: blur(60px);
            filter: blur(60px);
            -webkit-animation: blinkIt .1s infinite;
            animation: blinkIt .1s infinite;
            }

            @-webkit-keyframes blinkIt {
            50% {
                opacity: .8;
            }
            }

            @keyframes blinkIt {
            50% {
                opacity: .8;
            }
            }


            .ule h2 {
            font-weight: bold;
            font-size: 2rem;
            }
            .ule ul,li{
            list-style:none;
            }
            .ule ul{
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 70px;
            }
            .ule ul li a{
            text-decoration:none;
            color:#000;
            background:#ffc;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
            position:relative;
            box-shadow: 5px 5px 7px rgba(33,33,33,.7);
            transform: rotate(-6deg);
            transition: transform .15s linear;
            }

            .ule ul li:nth-child(even) a{
            transform:rotate(4deg);
            position:relative;
            top:5px;
            background:#cfc;
            }
            .ule ul li:nth-child(3n) a{
            transform:rotate(-3deg);
            position:relative;
            top:-5px;
            background:#ccf;
            }
            .ule ul li:nth-child(5n) a{
            transform:rotate(5deg);
            position:relative;
            top:-10px;
            }

            .ule ul li a:hover,ul li a:focus{
            box-shadow:10px 10px 7px rgba(0,0,0,.7);
            transform: scale(1.25);
            position:relative;
            z-index:5;
            }

            .ule ul li{
            margin:1em;
            }

            .credit {
            position: fixed;
            bottom: 0;
            right: 0;
            padding: 10px;
            color: #fff;
            }
        </style>
    </head>
<body class="container">
    <div class="holder">
        <div class="candle">
            <div class="blinking-glow"></div>
            <div class="thread"></div>
            <div class="glow"></div>
            <div class="flame"></div>
            <div class="ule">
                <ul>
                    <li>
                        <a href="#">
                        <h2>Leave Me A Note</h2>
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#noteleaving" style="margin-top: 80px;">Leave A Note</button>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="credit">
        <p>Made By <a href="https://linktr.ee/Ax_Milin" class="fw-bold">Ax_Milin</a><br>Can't Create Notes? Try Check Our <a href="https://status.candle-note.site" class="fw-bold">Status</a><br>We Currently Has <?php echo $totalNotes; ?> Notes Created</p>
    </div>

    <div class="modal fade" id="noteleaving" tabindex="-1" aria-labelledby="noteleaving" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="noteleaving">Leave A Note</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="/" method="post">
                <input type="text" id="notetexts" class="form-control" name="notetexts" required>
                
                <center>
                    <div class="form-group" style="padding: 10px">
                        <div class="g-recaptcha" data-sitekey="6LccH0MgAAAAABeAstRJbfZ4cK53H6Mh1APEEdxM"></div>
                    </div>
                </center>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>