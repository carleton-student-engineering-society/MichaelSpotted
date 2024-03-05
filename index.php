<?php

date_default_timezone_set('EST');

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['location'])) {
        $error = "No location specified";
    } else {
        $loc = htmlspecialchars($_POST['location'], ENT_QUOTES);
        $date = date('Y/m/d H:i:s');
        $orig = file_get_contents("michael.txt");
        $spl = explode("<br>", $orig, 5);
        $out = "";
        foreach($spl as $s){
            if(str_contains($s, "<br>")){
                break;
            }
            $out .= "<br>" . $s;
        }
        $file = fopen("michael.txt", "w");
        $txt = "\"". $loc . "\" at " . $date; 
        fwrite($file, $txt . $out);
        fclose($file);
    }
}

?>

<style>
body {
    background-image: url('Michael.jpg');
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: 100% 100%;
}
h1 {
    color: white;
    font-size: 40px;
}
h2 {
    color: white;
    font-size: 30px;
}
.middle {
    top: 10%;
    position: absolute;
    width: 100%;
    text-align: center;
}

.bottom {
    top: 70%;
    position: absolute;
    width: 100%;
    text-align: center;
}
</style>

<div class="middle">
    <h1>Have you seen this man???</h1>
</div>

<div class="bottom">
    <?php
        if(isset($error)) {
            echo "<p style=\"text-color: red;\">" . $error . "</p>";
        }
    ?>
    <form method="POST">
        <input type="text" name="location">
        <input type="submit" value="Submit">
    </form>
    <h2>
    <?php
        include "michael.txt";
    ?>
    </h2>
</div>

