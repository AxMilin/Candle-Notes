<?php
// Include your database connection file here
require_once("system/con_db.php");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch post details based on the provided post ID
$postID = $_GET["page"];

// Prepare the SQL statement with a placeholder
$sql = "SELECT * FROM notes WHERE code = ?";
$stmt = mysqli_prepare($conn, $sql);

// Check if the prepare statement is successful
if (!$stmt) {
    die("Prepare failed: " . mysqli_error($conn));
}

// Bind the parameter to the statement
mysqli_stmt_bind_param($stmt, "s", $postID);

// Execute the statement
mysqli_stmt_execute($stmt);

// Get the result
$result = mysqli_stmt_get_result($stmt);

// Check for errors
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    echo "Post not found.";
    mysqli_close($conn);
    exit();
}

$post = mysqli_fetch_assoc($result);
$text = $post["text"];
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
            transform: scale(0.5);
            margin-top: 175px;
            }
            .ule ul li a{
            text-decoration:none;
            color:#000;
            background:#ffc;
            display:block;
            height:10em;
            width:10em;
            padding:1em;
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

            .ule ul li{
            margin:1em;
            }

            .help-button {
            height: 40px;
            border: solid 3px #CCCCCC;
            background: #333;
            width: 100px;
            line-height: 32px;
            -webkit-transform: rotate(-90deg);
            font-weight: 600;
            color: white;
            transform: rotate(-90deg);
            -ms-transform: rotate(-90deg);
            -moz-transform: rotate(-90deg);
            text-align: center;
            font-size: 17px;
            position: fixed;
            right: -40px;
            top: 45%;
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
        <div class="ule" id="note">
            <ul>
                <li>
                    <a href="#">
                    <h2>Read A Note</h2>
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#noteleaving" style="margin-top:80px;">Read The Note</button>
                    </a>
                </li>
            </ul>
        </div>
        <div class="candle" id="as" style="opacity: 1;">
            <div class="blinking-glow" id="vis" style="visibility: hidden; opacity: 1;"></div>
            <button class="thread" onclick="showMask();" id="sa" style="opacity: 1;"></button>
            <div class="glow" id="vis1" style="visibility: hidden; opacity: 1;"></div>
            <div class="flame" id="vis2" style="visibility: hidden; opacity: 1;"></div>

        </div>
    </div>
    
    <button class="help-button" data-bs-toggle="modal" data-bs-target="#helpp">Help?</button>

    
    <div class="credit">
    <p>Made By <a href="https://linktr.ee/Ax_Milin" class="fw-bold">Ax_Milin</a><br>Can't Create Notes? Try Check Our <a href="https://status.candle-note.site" class="fw-bold">Status</a><br>We Currently Has <?php echo $totalNotes; ?> Notes Created</p>
    </div>

    <div class="modal fade" id="noteleaving" tabindex="-1" aria-labelledby="noteleaving" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="noteleaving">A Note Says:</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h4><?php echo $text; ?></h4>
        </div>
        <div class="modal-footer">
            <button class="btn btn-warning" onclick="copyText()">Share This Note</button> 
            <a class="btn btn-primary" href="/" >Leave Your Own Note</a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>
    
    
    <div class="modal fade" id="helpp" tabindex="-1" aria-labelledby="helpp" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="noteleaving">How To Use</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <h6>Click at the Thread To Melt the Candle and Read The Note.</h4>
        </div>
        <div class="modal-footer">
            <a class="btn btn-primary" href="/" >Leave Your Own Note</a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div>

    <script> 
        function copyText() { 
            navigator.clipboard.writeText 
                ("https://www.candle-note.site/view.php?page=<?php echo $postID;?>"); 
        } 
    </script> 

    <script>

function showMask() {
    var vis = document.getElementById('vis');
    var vis1 = document.getElementById('vis1');
    var vis2 = document.getElementById('vis2');
    var ass = document.getElementById('as');
    var sa = document.getElementById('sa');

    vis.style.visibility = 'visible';
    vis1.style.visibility = 'visible';
    vis2.style.visibility = 'visible';

    for (var i = 1; i <= 10; i++) {
        setTimeout(function (opacity) {
            return function () {
                vis.style.opacity = opacity;
                vis1.style.opacity = opacity;
                vis2.style.opacity = opacity;
                sa.style.opacity = opacity;
                ass.style.opacity = opacity;
            };
        }(0.9 - i * 0.1), i * 1000); 
    }
    setTimeout(function() {
        as.remove();
    }, 11000); 
}
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>