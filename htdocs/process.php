<?php
$inp =$_POST['message'];
$fil =$_FILES['file'];

switch ($inp){
    case !empty($inp):
        echo $inp;
        break;
    default:
        echo 'nifiga';
}
if (!empty($fil)){
    echo "<pre>";
    var_dump($fil);
    echo "</pre>";
}

