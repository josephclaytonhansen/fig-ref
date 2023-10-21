<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
</head>

<?php
ini_set('memory_limit','10240M');
//get URL param "length"
if (!isset($_GET['length'])) {
    $length = 'short';
} else {
$length = $_GET['length'];
}
if(!isset($_GET['balance'])){
    $balance = 'balanced';}
else{
    $balance = $_GET['balance'];
}
$image_datas = array();
$images_names = array();
$images_30 = array();
$images_60 = array();
$images_300 = array();
$images_600 = array();
$images_900 = array();
$starting_amounts = array('30', '60', '300', '600', '900');

function secondsToTime($seconds) {
    $dtF = new \DateTime('@0');
    $dtT = new \DateTime("@$seconds");
    return $dtF->diff($dtT)->format('%i:%s');
}

function addTrailingZeroToTime($time){
    $time = explode(':', $time);
    if(count($time) == 1){
        return '00:0'.$time[0];
    }
    if(count($time) == 2){
        if(strlen($time[0]) == 1){
            $time[0] = '0'.$time[0];
        }
        if(strlen($time[1]) == 1){
            $time[1] = '0'.$time[1];
        }
        return $time[0].':'.$time[1];
    }
    return '00:00';
}

foreach (new DirectoryIterator('./img') as $file) {
    if($file->isDot()) continue;
    $name = $file->getFilename();
    $images_names[] = $name;
}

if ($length === 'short') {
    if ($balance === 'short') {
        $amounts = [3, 4, 2];
    } else if ($balance === 'balanced') {
        $amounts = [5, 4, 3];
    } else if ($balance === 'long') {
        $amounts = [2, 2, 5];
    }
} else if ($length === 'medium') {
    if ($balance === 'short') {
        $amounts = [7, 5, 3];
    } else if ($balance === 'balanced') {
        $amounts = [5, 6, 3];
    } else if ($balance === 'long') {
        $amounts = [4, 5, 4];
    }
} else if ($length === 'long') {
    if ($balance === 'short') {
        $amounts = [7, 5, 3];
    } else if ($balance === 'balanced') {
        $amounts = [5, 6, 3];
    } else if ($balance === 'long') {
        $amounts = [4, 5, 4];
    }
} else if ($length === 'long (no 30s)') {
    if ($balance === 'short') {
        $amounts = [0, 5, 3];
    } else if ($balance === 'balanced') {
        $amounts = [0, 6, 3];
    } else if ($balance === 'long') {
        $amounts = [0, 5, 4];
    }
} else if ($length === 'medium (no 30s)'){
    if ($balance === 'short') {
        $amounts = [0, 5, 3];
    } else if ($balance === 'balanced') {
        $amounts = [0, 6, 3];
    } else if ($balance === 'long') {
        $amounts = [0, 5, 4];
    }
} else if ($length === 'short (no 30s)'){
    if ($balance === 'short') {
        $amounts = [0, 4, 2];
    } else if ($balance === 'balanced') {
        $amounts = [0, 4, 3];
    } else if ($balance === 'long') {
        $amounts = [0, 2, 5];
    }
} else if ($length === '>1m'){
    if ($balance === 'short') {
        $amounts = [0, 0, 2];
    } else if ($balance === 'balanced') {
        $amounts = [0, 0, 3];
    } else if ($balance === 'long') {
        $amounts = [0, 0, 5];
    }
}

if ($amounts[0] > 0){
$random_keys = array_rand($images_names, $amounts[0]);
$random_images = array();
foreach ($random_keys as $key) {
    $random_images[] = $images_names[$key];
}
foreach ($random_images as $image) {
    $images_30[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/'.$image));
}
}
if ($amounts[1] > 0){
$random_keys = array_rand($images_names, $amounts[1]);
$random_images = array();
foreach ($random_keys as $key) {
    $random_images[] = $images_names[$key];
}
foreach ($random_images as $image) {
    $images_60[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/'.$image));
}
}
$random_keys = array_rand($images_names, $amounts[2]);
$random_images = array();
foreach ($random_keys as $key) {
    $random_images[] = $images_names[$key];
}
foreach ($random_images as $image) {
    $images_300[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/'.$image));
}

if($length === 'medium' || $length === 'long' || $length === '>1m'){
$images_600[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/'.$images_names[array_rand($images_names, 1)]));}
if ($length === 'medium (no 30s)' || $length === 'long (no 30s)') {
    $images_600[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/' . $images_names[array_rand($images_names, 1)]));
}
if($length === 'long' || '>1m'){
$images_900[] = 'data:image/' . pathinfo($name, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents('./img/'.$images_names[array_rand($images_names, 1)]));
}
?>

<body>
    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
    <script type="text/javascript">

        function changeBalanceURLParam(balance){
            var url = window.location.href;
            var url_split = url.split('?');
            var url_params = url_split[1].split('&');
            var new_url = url_split[0] + '?';
            var new_url_params = [];
            for(var i = 0; i < url_params.length; i++){
                if(url_params[i].includes('balance')){
                    new_url_params.push('balance='+balance);
                }else{
                    new_url_params.push(url_params[i]);
                }
            }
            new_url = new_url + new_url_params.join('&');
            window.location.href = new_url;
        }

        $(document).ready(function(){
            var audioElement = document.createElement('audio');
            audioElement.setAttribute('src', 'https://soundbible.com/mp3/Ship_Bell-Mike_Koenig-1911209136.mp3');
            
            audioElement.addEventListener('ended', function() {
                this.play();
            }, false);
            $('.card').click(function(){
                $('.card').removeClass('card-clicked');
                $('.card').removeClass('card-hidden');
                $(this).addClass('card-clicked');
                $('.card').not(this).addClass('card-hidden');
            });
            $('.start').click(function(){
                $(this).css('display', 'none');
                var id = $(this).attr('id');
                var id = id.split('-');
                var i = id[1];
                var ii = id[2];
                let parent = $(this).parent();
                var amount = parent.find('.amount');
                var amount_text = amount.text();
                var amount_split = amount_text.split(':');
                var amount_minutes = parseInt(amount_split[0]);
                var amount_seconds = parseInt(amount_split[1]);
                var amount_total = amount_minutes * 60 + amount_seconds;
                var starting_amount = amount_total;
                var interval = setInterval(function(){
                    amount_total = amount_total - 1;
                    amount_minutes = Math.floor(amount_total / 60);
                    amount_seconds = amount_total - amount_minutes * 60;
                    amount.text(('0' + amount_minutes).slice(-2) + ':' + ('0' + amount_seconds).slice(-2));


                    let degree_dark = 360 - Math.floor(amount_total / (starting_amount) * 360);
                    let degree_light = 360 - degree_dark;
                    degree_dark = degree_dark.toString() + 'deg';
                    degree_light = degree_light.toString() + 'deg';

                    let gradient = 'conic-gradient(#eee '+degree_dark+', #444 '+degree_dark + " "+  degree_light+')';
                    console.log(gradient);

                    parent.find('.timer').css('background', gradient);
                    if(amount_total <= 0){
                        setTimeout(function(){
                            audioElement.pause();
                        }, 2000);
                        clearInterval(interval);
                        $('.card').removeClass('card-clicked');
                        $('.card').removeClass('card-hidden');
                        parent.removeClass('card-clicked');
                        parent.addClass('card-hidden');
                    }
                }, 1000);
            });
        });
    </script>
    <div class = "button-row">
        <button onclick="window.location.href = 'index.php?length=short';">Short</button>
        <button onclick="window.location.href = 'index.php?length=medium';">Medium</button>
        <button onclick="window.location.href = 'index.php?length=long';">Long</button>
    </div>
    <div class = "card-row">
    <?php
    $i = 0;
    $ii = 0;
    foreach(array($images_30, $images_60, $images_300, $images_600, $images_900) as $c){
        
    foreach ($c as $data) {
        echo '
        <div class = "card" id = "'. $i.'-'.$ii.'">
            <div class = "rect-img-container">
                <img class = "rect-img" src="'.$data.'">
            </div>
            <div class = "card-content">
                <div class = "timer">
                    <div class = "cutout">
                        <div class = "amount">'.addTrailingZeroToTime(secondsToTime($starting_amounts[$i])).'</div>
                    </div>
                </div>
                <button class = "start" id = "start-'.$i.'-'.$ii.'">Start</button>
            </div>
        </div>
        ';
        $ii = $ii + 1;
    }
    $i = $i + 1;
}
    ?>
        
    </div>
</body>
</html>
