<?php
echo 'test';
$r = exec('/var/www/html/qdeal.com/wp-content/plugins/super-import//run_single_wechat.sh "https://mp.weixin.qq.com/s?__biz=MzAxODIyMzA2Ng==&mid=502742338&idx=2&sn=91ba293ac2ee0415a2b064e2f4c25a5e&pass_ticket=R%2FIPRkivqQKyk3MZZjiAdfD0AkzNQt3dfpdrKJwfzXdY%2FmyaOu%2BWPXurCJPppsF5" /var/www/html/qdeal.com/wp-content/plugins/super-import/ 2>&1', $re);
echo $r;
var_dump($re);
//exec('scrapy -h 2>&1', $re);
//var_dump($re);
?>
