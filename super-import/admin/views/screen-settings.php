<?php
require_once(SI_PATH.'/admin/views/remote-scrapy-db-manager.php');

$scrapy_no_data = false;

$scrapyMG = new ScrapyDB('scrapy_ozdazhe');
$oz_rmdb = $scrapyMG->getInstance(); 
$oz_cur_status = $oz_rmdb->get_row('SELECT * FROM spider_status');

if($oz_cur_status->status == "running" || $oz_cur_status->item_num == 0) {
    $scrapy_no_data = true;
} else {
    $scrapy_no_data = false;
}

if($scrapy_no_data == true) {
    echo '<div>No Scrapy Data Available.</div>';
    return;
}

?>

<style>
.tab-row a{
    border: solid 1px gray;
    color: black;
    display: inline-block;
    font-weight: bold;
    height: 30px;
    padding-top: 12px;
    text-align: center;
    text-decoration: none;
    width: 60px;
}

#tab-ozdazhe, #tab-wechat, #tab-dealmoon {
    border: solid 1px gray;
    margin-top: -1px;
    padding: 10px;
}
</style>

<h2 class="si-settings-title">
	<?php _e( 'Super Importer Settings', 'ml-light' ); ?>
</h2>

<div style="margin:20px;">
<input type="button" value="RUN SPIDER" onclick="this.disabled=true;runScrapy();"/>
</div>   

<div class="tab-row">
<?php
    $spider_site = $_GET['spider'];
    if($spider_site == 'wechat') {
?>
<a href="#tab-ozdazhe" id="tab-btn-ozdazhe">ozdazhe</a>
<a href="#tab-dealmoon" id="tab-btn-dealmoon">dealmoon</a>
<a href="#tab-wechat" id="tab-btn-wechat" style="background:#888;">Wechat</a>
<?php
    } else if($spider_site == 'dealmoon') {
?>
<a href="#tab-ozdazhe" id="tab-btn-ozdazhe">ozdazhe</a>
<a href="#tab-dealmoon" id="tab-btn-dealmoon" style="background:#888;">dealmoon</a>
<a href="#tab-wechat" id="tab-btn-wechat">Wechat</a>
<?php
    } else {
?>
<a href="#tab-ozdazhe" id="tab-btn-ozdazhe" style="background:#888;">ozdazhe</a>
<a href="#tab-dealmoon" id="tab-btn-dealmoon">dealmoon</a>
<a href="#tab-wechat" id="tab-btn-wechat">Wechat</a>
<?php
    }
?>
</div>


<?php if($spider_site == 'ozdazhe' || empty($spider_site)) {?>
<div id="tab-ozdazhe">
<?php } else {?>
<div id="tab-ozdazhe" style="display:none">
<?php } ?>
<div class="wrap">

    <h3>Ozdazhe.com</h3>
    <div>Time: <?php echo $oz_cur_status->start_time?></div>

    <div class="remote-database">
        <table>

<?php 
    $cur_page = $_GET['pnum'];
    if(empty($cur_page)) {
        $cur_page = 0;
    } else {
        $cur_page--;
    }
    $perpage_num = 20;
    $start = $perpage_num*$cur_page;
    $sql = $oz_rmdb->prepare('SELECT * FROM ozdazhe_data LIMIT %d, %d', $start, $perpage_num);
    $oz_datas = $oz_rmdb->get_results($sql); 
    $total_num = $oz_rmdb->get_row('SELECT COUNT(*) AS num FROM ozdazhe_data')->num; 
    $page_num = ((($total_num % $perpage_num) == 0)?($total_num/$perpage_num) : (int)($total_num/$perpage_num + 1));

    foreach($oz_datas as $item) {
        $post_data = preg_replace("/<img[^>]+\>/i", "", $item->post_data);
        $post_data = preg_replace("/<tr\>/i", "", $post_data);
        $post_data = preg_replace("/<\/tr\>/i", "", $post_data);
        $post_data = preg_replace("/<td\>/i", "<div>", $post_data);
        $post_data = preg_replace("/<\/td\>/i", "</div>", $post_data);
        $post_data = preg_replace("/[^\>]+$/i", "", $post_data);
?>
        <tr>
            <td style="vertical-align:top;">
                <img src="<?php echo $item->img; ?>" style="max-width:150px;max-height:150px;"/>
            </td>

            <td>
            <div style="font-weight:bold;"><?php echo $item->title; ?></div>
            <div><?php echo $item->store; ?></div>
            <div><?php echo $item->category; ?></div>
            <div><?php echo $post_data; ?></div>
            </td>

            <td>
                <input type="checkbox" site-data="ozdazhe" id-data="<?php echo $item->id;?>" name="ckb-oz"/>
            </td>
        </tr>
<?php } ?>
        </table>
        <div style="margin-top:10px;">
            <label style="display:none">Page: </label>
            <select style="display:none" onchange="location.href=location.href.replace(/&pnum[^&]+/g, '')+'&pnum='+ this.value;">
<?php 
        for($i = 1; $i <= $page_num; $i++) {
            if($cur_page == ($i - 1)) {
                $sel_sym = 'selected';
            } else {
                $sel_sym = '';
            }
?>
                <option value="<?php echo $i;?>" <?php echo $sel_sym;?>><?php echo $i;?></option>
            <?php } ?>
            </select>
            <div style="margin-left:100px;display:inline-block">
<?php 
            $now_page = $cur_page + 1;
            $start_page = 1;
            $total_disp_page = 30;
            $end_page = 10;
            if($page_num > $total_disp_page) {
                if($now_page > $total_disp_page/2 && $page_num - $now_page > $total_disp_page/2) {$start_page = $now_page - $total_disp_page/2;}
                else if($page_num - $now_page <= $total_disp_page/2) {$start_page = $page_num  - $total_disp_page;}

                $end_page = ($start_page + $total_disp_page > $page_num)?$page_num:($start_page + $total_disp_page);
            } else {
                $start_page = 1;
                $end_page = $page_num;
            }

            if($start_page != 1) {
                echo '<span>...</span>';
            }

            for($i = $start_page; $i <= $end_page ; $i++) {
                if($i != $now_page) {
?>
            <span class="pagenation" onclick="location.href=location.href.replace(/#.+/g, '').replace(/&pnum[^&]+/g, '')+'&pnum='+'<?php echo $i;?>&spider=ozdazhe';"><?php echo $i;?></span>
<?php 
                } else {
?>
            <span class="pagenation" style="cursor:auto;text-decoration:none;font-size:medium;color:blue"><?php echo $i;?></span>
<?php
                }
            }

            if($page_num > $end_page ) {
                echo '<span>...</span>';
            }
?>
            </div>
            <div style="display:inline-block;float:right;">
                <input name="btn-import" type="button" value="Coupon Import" onclick="sendRq('draft', 'ozdazhe');"/>
                <input name="btn-import" type="button" value="Import directly" onclick="sendRq('publish', 'ozdazhe');" style="display:none"/>
            </div>
        </div>
    </div>
</div>
</div>


<?php
//////////////We Chat

$scrapy_no_data = false;

$scrapyMG = new ScrapyDB('scrapy_wechat');
$oz_rmdb = $scrapyMG->getInstance(); 
$oz_cur_status = $oz_rmdb->get_row('SELECT * FROM spider_status');

if($oz_cur_status->status == "running" || $oz_cur_status->item_num == 0) {
    $scrapy_no_data = true;
} else {
    $scrapy_no_data = false;
}

if($scrapy_no_data == true) {
    echo '<div>No Scrapy Data Available.</div>';
    return;
}

?>

<?php if($spider_site == 'wechat') {?>
<div id="tab-wechat">
<?php  } else { ?>
<div id="tab-wechat" style="display:none">
<?php  } ?>

<div class="wrap">
    <h3>WeChat</h3>
    <div>Time: <?php echo $oz_cur_status->start_time?></div>

    <div class="remote-database">
        <table>

<?php 
    $cur_page = $_GET['pnum'];
    if(empty($cur_page)) {
        $cur_page = 0;
    } else {
        $cur_page--;
    }
    $perpage_num = 20;
    $start = $perpage_num*$cur_page;
    $sql = $oz_rmdb->prepare('SELECT * FROM wechat_data LIMIT %d, %d', $start, $perpage_num);
    $oz_datas = $oz_rmdb->get_results($sql); 
    $total_num = $oz_rmdb->get_row('SELECT COUNT(*) AS num FROM wechat_data')->num; 
    $page_num = ((($total_num % $perpage_num) == 0)?($total_num/$perpage_num) : (int)($total_num/$perpage_num + 1));

    $index = 0;
    foreach($oz_datas as $item) {
        if($item->store != 'wechat') {
            $post_data = preg_replace("/<img[^>]+\>/i", "", $item->post_data);
        } else {
            $post_data = preg_replace("/<span[^>]+?\>[^<]+?阅读原文查看更多精彩[^<]+?<\/span>/i", "", $item->post_data); 
            $post_data = preg_replace("/点击上方/i", "", $post_data); 
            $post_data = preg_replace("/关注“澳洲折扣资讯”/i", "", $post_data); 
        }

        $index++;
?>
        <tr>
            <td style="vertical-align:top;">
                <img src="<?php echo $item->img; ?>" style="max-width:150px;max-height:150px;"/>
            </td>

            <td>
            <div style="font-weight:bold;"><?php echo $item->title; ?></div>
            <div><?php echo $item->store; ?></div>
            <div><?php echo $item->category; ?></div>
            <div onclick="var dataBlk = document.getElementById('<?php echo $index?>-data'); if(dataBlk.style.display == 'block') {dataBlk.style.display='none';}else{dataBlk.style.display='block';}" style="cursor:pointer;color:red">Click to show content</div>
            <div id="<?php echo $index;?>-data" style="display:none"><?php echo $post_data?></div>
            </td>

            <td>
                <input type="checkbox" site-data="wechat" id-data="<?php echo $item->id;?>" name="ckb-oz"/>
            </td>
        </tr>
<?php } ?>
        </table>
        <div style="margin-top:10px;">
            <label style="display:none">Page: </label>
            <select style="display:none" onchange="location.href=location.href.replace(/&pnum[^&]+/g, '')+'&pnum='+ this.value;">
<?php 
        for($i = 1; $i <= $page_num; $i++) {
            if($cur_page == ($i - 1)) {
                $sel_sym = 'selected';
            } else {
                $sel_sym = '';
            }
?>
                <option value="<?php echo $i;?>" <?php echo $sel_sym;?>><?php echo $i;?></option>
            <?php } ?>
            </select>
            <div style="margin-left:100px;display:inline-block">
<?php 
            $now_page = $cur_page + 1;
            $start_page = 1;
            $total_disp_page = 30;
            $end_page = 10;
            if($page_num > $total_disp_page) {
                if($now_page > $total_disp_page/2 && $page_num - $now_page > $total_disp_page/2) {$start_page = $now_page - $total_disp_page/2;}
                else if($page_num - $now_page <= $total_disp_page/2) {$start_page = $page_num  - $total_disp_page;}

                $end_page = ($start_page + $total_disp_page > $page_num)?$page_num:($start_page + $total_disp_page);
            } else {
                $start_page = 1;
                $end_page = $page_num;
            }

            if($start_page != 1) {
                echo '<span>...</span>';
            }

            for($i = $start_page; $i <= $end_page ; $i++) {
                if($i != $now_page) {
?>
            <span class="pagenation" onclick="location.href=location.href.replace(/#.+/g, '').replace(/&pnum[^&]+/g, '')+'&pnum='+'<?php echo $i;?>&spider=wechat';"><?php echo $i;?></span>
<?php 
                } else {
?>
            <span class="pagenation" style="cursor:auto;text-decoration:none;font-size:medium;color:blue"><?php echo $i;?></span>
<?php
                }
            }

            if($page_num > $end_page ) {
                echo '<span>...</span>';
            }
?>
            </div>
            <div style="display:inline-block;float:right">
                <input name="btn-import" type="button" value="Coupon Import" onclick="sendRq('draft', 'wechat');"/>
                <input name="btn-import" type="button" value="Post Import" onclick="sendRq('post', 'wechat');"/>
            </div>
        </div>
    </div>
</div>
</div>

<?php
//dealmoon
$scrapy_no_data = false;

$scrapyMG = new ScrapyDB('scrapy_dealmoon');
$oz_rmdb = $scrapyMG->getInstance(); 
$oz_cur_status = $oz_rmdb->get_row('SELECT * FROM spider_status');

if($oz_cur_status->status == "running" || $oz_cur_status->item_num == 0) {
    $scrapy_no_data = true;
} else {
    $scrapy_no_data = false;
}

if($scrapy_no_data == true) {
    echo '<div>No Scrapy Data Available.</div>';
    return;
}

?>
<?php if($spider_site == 'dealmoon') {?>
<div id="tab-dealmoon">
<?php  } else { ?>
<div id="tab-dealmoon" style="display:none">
<?php  } ?>


<div class="wrap">

    <h3>dealmoon.com.au</h3>
    <div>Time: <?php echo $oz_cur_status->start_time?></div>

    <div class="remote-database">
        <table>

<?php 
    $cur_page = $_GET['pnum'];
    if(empty($cur_page)) {
        $cur_page = 0;
    } else {
        $cur_page--;
    }
    $perpage_num = 20;
    $start = $perpage_num*$cur_page;
    $sql = $oz_rmdb->prepare('SELECT * FROM dealmoon_data LIMIT %d, %d', $start, $perpage_num);
    $oz_datas = $oz_rmdb->get_results($sql); 
    $total_num = $oz_rmdb->get_row('SELECT COUNT(*) AS num FROM dealmoon_data')->num; 
    $page_num = ((($total_num % $perpage_num) == 0)?($total_num/$perpage_num) : (int)($total_num/$perpage_num + 1));

    $index = 0;
    foreach($oz_datas as $item) {
        if($item->store != 'wechat') {
            $post_data = preg_replace("/<img[^>]+\>/i", "", $item->post_data);
	    $post_data = preg_replace("/<tr\>/i", "", $post_data);
	    $post_data = preg_replace("/<\/tr\>/i", "", $post_data);
	    $post_data = preg_replace("/<td\>/i", "<div>", $post_data);
	    $post_data = preg_replace("/<\/td\>/i", "</div>", $post_data);
	    $post_data = preg_replace("/[^\>]+$/i", "", $post_data);
	} else {
            $post_data = preg_replace("/<span[^>]+?\>[^<]+?阅读原文查看更多精彩[^<]+?<\/span>/i", "", $item->post_data); 
            $post_data = preg_replace("/点击上方/i", "", $post_data); 
            $post_data = preg_replace("/关注“澳洲折扣资讯”/i", "", $post_data); 
        }

        $index++;
?>
        <tr>
            <td style="vertical-align:top;">
                <img src="<?php echo $item->img; ?>" style="max-width:150px;max-height:150px;"/>
            </td>

            <td>
            <div style="font-weight:bold;"><?php echo $item->title; ?></div>
            <div><?php echo $item->store; ?></div>
            <div><?php echo $item->category; ?></div>
            <div onclick="var dataBlk = document.getElementById('dlm-<?php echo $index?>-data'); if(dataBlk.style.display == 'block') {dataBlk.style.display='none';}else{dataBlk.style.display='block';}" style="cursor:pointer;color:red">Click to show content</div>
            <div id="dlm-<?php echo $index;?>-data" style="display:none"><iframe seamless srcdoc='<?php echo $post_data?>'></iframe></div>
            </td>

            <td>
                <input type="checkbox" site-data="dealmoon" id-data="<?php echo $item->id;?>" name="ckb-oz"/>
            </td>
        </tr>
<?php } ?>
        </table>
        <div style="margin-top:10px;">
            <label style="display:none">Page: </label>
            <select style="display:none" onchange="location.href=location.href.replace(/&pnum[^&]+/g, '')+'&pnum='+ this.value;">
<?php 
        for($i = 1; $i <= $page_num; $i++) {
            if($cur_page == ($i - 1)) {
                $sel_sym = 'selected';
            } else {
                $sel_sym = '';
            }
?>
                <option value="<?php echo $i;?>" <?php echo $sel_sym;?>><?php echo $i;?></option>
            <?php } ?>
            </select>
            <div style="margin-left:100px;display:inline-block">
<?php 
            $now_page = $cur_page + 1;
            $start_page = 1;
            $total_disp_page = 30;
            $end_page = 10;
            if($page_num > $total_disp_page) {
                if($now_page > $total_disp_page/2 && $page_num - $now_page > $total_disp_page/2) {$start_page = $now_page - $total_disp_page/2;}
                else if($page_num - $now_page <= $total_disp_page/2) {$start_page = $page_num  - $total_disp_page;}

                $end_page = ($start_page + $total_disp_page > $page_num)?$page_num:($start_page + $total_disp_page);
            } else {
                $start_page = 1;
                $end_page = $page_num;
            }

            if($start_page != 1) {
                echo '<span>...</span>';
            }

            for($i = $start_page; $i <= $end_page ; $i++) {
                if($i != $now_page) {
?>
            <span class="pagenation" onclick="location.href=location.href.replace(/#.+/g, '').replace(/&pnum[^&]+/g, '')+'&pnum='+'<?php echo $i;?>&spider=dealmoon;"><?php echo $i;?></span>
<?php 
                } else {
?>
            <span class="pagenation" style="cursor:auto;text-decoration:none;font-size:medium;color:blue"><?php echo $i;?></span>
<?php
                }
            }

            if($page_num > $end_page ) {
                echo '<span>...</span>';
            }
?>
            </div>
            <div style="display:inline-block;float:right;">
                <input name="btn-import" type="button" value="Coupon Import" onclick="sendRq('draft', 'dealmoon');"/>
                <input name="btn-import" type="button" value="Import directly" onclick="sendRq('publish', 'dealmoon');" style="display:none"/>
            </div>
        </div>
    </div>
</div>
</div>


<script>
(function() {
    var btnOzdazhe = document.getElementById('tab-btn-ozdazhe');
    var btnWechat = document.getElementById('tab-btn-wechat')
    var btnDealmoon = document.getElementById('tab-btn-dealmoon')

    var tabOzdazhe = document.getElementById('tab-ozdazhe');
    var tabWechat = document.getElementById('tab-wechat');
    var tabDealmoon = document.getElementById('tab-dealmoon');

    btnOzdazhe.onclick = function () {
        btnOzdazhe.style.background = "#888";
        btnWechat.style.background = "transparent";
        btnDealmoon.style.background = "transparent";
        tabOzdazhe.style.display = "block";
        tabWechat.style.display = "none";
        tabDealmoon.style.display = "none";
    };

    btnWechat.onclick = function () {
        btnOzdazhe.style.background = "transparent";
        btnWechat.style.background = "#888";
        btnDealmoon.style.background = "transparent";
        tabOzdazhe.style.display = "none";
        tabDealmoon.style.display = "none";
        tabWechat.style.display = "block";
    };

    btnDealmoon.onclick = function () {
        btnOzdazhe.style.background = "transparent";
        btnWechat.style.background = "transparent";
        btnDealmoon.style.background = "#888";
        tabOzdazhe.style.display = "none";
        tabWechat.style.display = "none";
        tabDealmoon.style.display = "block";
    };

})();
</script>


