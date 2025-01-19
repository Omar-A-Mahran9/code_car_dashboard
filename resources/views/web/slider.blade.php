<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>:: STDY Slider Test :: </title>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script>
        window.onload = function() {
    // Your code here
    var $ = jQuery.noConflict();

 

  
};

//var $ = jQuery.noConflict();

</script>
<?php
        // try {
        //      file_get_contents('https://slider.webstdy.com/RevSliderEmbedderheadIncludes.php');
        // } catch (\Throwable $th) {
        //     //throw $th;

        //     echo ' error 001';
        // }
       echo file_get_contents('https://slider.codecar.com.sa/RevSliderEmbedderheadIncludes.php');

    ?>

    <!-- <script src="{{ asset('web') }}/js/global_scripts.js"></script> -->
    
    <?php
        // try {
        //      file_get_contents('https://slider.webstdy.com/RevSliderEmbedderheadIncludes.php');
        // } catch (\Throwable $th) {
        //     //throw $th;

        //     echo ' error 001';
        // }
       // file_get_contents('https://slider.webstdy.com/RevSliderEmbedderheadIncludes.php');

    ?>
     <!-- <script src="{{ asset('web') }}/js/jQuery.js"></script> -->

</head>
<body>
    

<!-- Main Content -->
 
<section class="intro" style="direction: ltr">
    <?php
                echo file_get_contents('https://slider.codecar.com.sa/RevSliderEmbedderputRevSlider.php?slide='.$name);

        // try {
        //     echo file_get_contents('https://slider.webstdy.com/RevSliderEmbedderputRevSlider.php?slide='.$name);
        //     // echo file_get_contents("https://slider.rotanacarshowroom.com/RevSliderEmbedderputRevSlider.php?slide=" . settings()->get('slider_en'));
        // } catch (\Throwable $th) {
        //     echo ' error 002 ';
        // }

    ?>
</section>
<!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->

</body>
</html>