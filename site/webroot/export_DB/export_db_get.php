<?php
$host = 'http://en.sanyatour.com/work/fun/export_db.php?sign=';
$encode_key = 'en@sanya.com';
$encode_token = '274f6c49b3e31a0c1F3870BE274F6C49B3E31A0C6728957F';

set_time_limit(0);
$encode_str = "$encode_token|".time();
$encode_str = encode($encode_str, $encode_key);

$url = $host.$encode_str;
echo $url;

//$filename = 'sanya.sql';
//header('Content-Type: application/octet-stream');
//header('Content-Disposition: attachment; filename="'.$filename.'"');
//$data = curlGet($url);
//echo $data;


function encode($tex, $key = "test123")
{
    $chrArr = array(
        'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
        'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        '0','1','2','3','4','5','6','7','8','9'
    );
    $key_b = $chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
    $rand_key = $key_b.$key;
    $rand_key=md5(md5($rand_key).$key_b);
    $key_p  = substr($rand_key, 0, 10);

    $texlen=strlen($tex);
    $reslutstr="";
    for($i=0;$i<$texlen;$i++){
        $reslutstr.=$tex{$i}^$rand_key{$i%32};
    }
    $reslutstr=trim($key_b.base64_encode($key_p.$reslutstr),"==");
    $reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
    return $reslutstr;
}

function curlGet($url)
{
    if(!$url) return false;
    $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;

    //$header[] = "Content-type: text/xml";
    $header[] = false;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch,CURLOPT_TIMEOUT,5);
    if ($ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    }
    $content = curl_exec($ch);
    curl_close($ch);

    return $content;
}

?>
