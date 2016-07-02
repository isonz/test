<?php
require_once _LIBS."Vcode/Vcode.class.php";

$image = Vcode::show();
header("Content-type: image/png");
$image = imagePng($image);
imagedestroy($image);
exit;