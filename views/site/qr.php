<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

 //include "qrlib.php";
 //QRcode::png('PHP QR Code :)', 'test.png', 'L', 4, 2);
    $path=Yii::getAlias('@webroot');
    include $path.'/phpqrcode/qrlib.php';

    
    $filename = $path.'/qr/test.png';
    $qr='../../qr/test.png';
    //processing form input
    //remember to sanitize user input in real-life solution !!!
    /*$errorCorrectionLevel = 'H';
    if (isset($_REQUEST['level']) && in_array($_REQUEST['level'], array('L','M','Q','H')))
        $errorCorrectionLevel = $_REQUEST['level'];    

    $matrixPointSize = 4;
    if (isset($_REQUEST['size']))
        $matrixPointSize = min(max((int)$_REQUEST['size'], 1), 10);


    if (isset($_REQUEST['data'])) { 
    
        //it's very important!
        if (trim($_REQUEST['data']) == '')
            die('data cannot be empty! <a href="?">back</a>');
            
        // user data
        $filename = $PNG_TEMP_DIR.'test'.md5($_REQUEST['data'].'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';
        QRcode::png($_REQUEST['data'], $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    } else {    
    
        //default data
        echo 'You can provide data in GET parameter: <a href="?data=like_that">like that</a><hr/>';    
        QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
        
    }    
        */
    //display generated file
    $matrixPointSize = min(max((int)10, 1), 10);
    echo $numero=rand(10,100);
    QRcode::png($numero, $filename, 'H', $matrixPointSize, 2);
    echo '<img src="'.$qr.'" style=height: 300px;/><hr/>';  
   
    //QRtools::timeBenchmark();    

?>

