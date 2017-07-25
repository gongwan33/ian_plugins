<?php
$host = 'mmbiz.qpic.cn';
$target = $_GET['url'];
//$referer = 'http://mp.weixin.qq.com'; // Referer
$referer = ''; // Referer
$port = 80;

$fp = fsockopen($host, $port, $errno, $errstr, 20);
if (!$fp){
    echo "$errstr($errno)<br />\n";
} 
else{

    $out = "GET ".$target." HTTP/1.1\r\n";
    $out .= "Host: ".$host."\r\n";
    $out .= "Connection: close\r\n";
    $out .= "Cache-Control: max-age=0\r\n";
    $out .= "Upgrade-Insecure-Requests: 1\r\n";
    $out .= "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3071.115 Safari/537.36\r\n";
    $out .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8\r\n";
    $out .= "Accept-Encoding: gzip, deflate\r\n";
    $out .= "Accept-Language: zh-CN,zh;q=0.8,en;q=0.6,de;q=0.4\r\n";
    $out .= "Referer: $referer\r\n";
    $out .= "If-Modified-Since: Thu, 19 Jan 2016 18:59:27 GMT\r\n\r\n";
    fwrite($fp, $out);

    $header_flag = true;

    $read_bytes = 1024;
    while (!feof($fp)){
        $data = fgets($fp, $read_bytes);
        if($header_flag) {
            $data = trim($data);
            if(!empty($data)) {
                header($data);
            } else {
                $header_flag = false;
            }
        } else {
            $read_bytes = 2048;
            echo $data;
        }
    }
    fclose($fp);
}
?>
