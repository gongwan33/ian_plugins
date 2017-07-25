<?php
require_once(SI_PATH.'/admin/views/remote-scrapy-db-manager.php');
require_once(SI_PATH.'/includes/function.php');

global $ajax_debug;



class SI {
	public $update_num = 0;
	public $add_num = 0;
	public $del_num = 0;
	public $import_product_list = array();
	public $bottom_content_added = false;
	public $bottom_content_reviewid = null;

	public function load_plugin() {
		if ( is_admin() ) {
			self::load_admin();
		}

        add_shortcode('wechat_inf', array($this,'wechat_single_import'));
		wp_enqueue_style( 'si-comstyle', SI_URL . '/css/comstyle.css' );
		wp_enqueue_script( 'sort-script', SI_URL . '/js/si-sort.js', array(), null, true );
		wp_localize_script( 'sort-script', 'SI_OBJ',
				array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }

    public function load_admin() {
        self::register_ajax_service();

		$admin = new SI_Admin();
		$admin->load();
	}

    private function register_ajax_service() {
        add_action( 'wp_ajax_imprq', array($this, 'import_data') );
        add_action( 'wp_ajax_nopriv_imprq', array($this, 'import_data') );

        add_action( 'wp_ajax_scrapyrq', array($this, 'run_scrapy') );
        add_action( 'wp_ajax_nopriv_scrapyrq', array($this, 'run_scrapy') );

    }

    public function have_same_post($title) {
        global $wpdb;
        global $ajax_debug;
        $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title='%s' AND (post_type=\"coupon\" OR post_type=\"post\") AND post_status<>\"trash\";", $title);
        $ajax_debug = $sql;
        $ids = $wpdb->get_col($sql); 
        //$ajax_debug[] = $sql;
        return $ids[0]; 
    }

    public function run_scrapy() {
        exec(SI_PATH.'/run_ozdazhe.sh '.SI_PATH.'>/dev/null 2>&1 &');
        //exec(SI_PATH.'/run_ozdazhe.sh '.SI_PATH.' 2>&1', $cmd_print);

        $res_ary = array('res' => $res, 'print' => $cmd_print);
        echo json_encode($res_ary);
        wp_die();
    }

    public function wechat_single_import(){

        $output .= '<div>';
        $output .= '<form action="" method="post" id="wechat-url-form">';
        $output .= '<div><label>URL:</label><input style="margin-left:10px;width:95%" type="text" name="url-txt"/></div>';
        $output .= '<div style="margin-top:10px"><label>Password:</label><input style="margin-left:10px" type="password" name="pwd-txt"/></div>';
        $output .= '<div style="margin-top:10px"><label><input type="radio" name="post-type-rad" value="post" checked/>Post</label></div>';
        $output .= '<div style="margin-top:10px"><label><input type="radio" name="post-type-rad" value="coupon"/>Coupon</label></div>';
        $output .= '<div style="margin-top:10px"><input type="submit" value="Submit"/></div>';
        $output .= '</form>';
        $output .= '</div>';

        if(!empty($_POST['url-txt']) && !empty($_POST['pwd-txt'])) {
            $user = get_user_by( 'login', 'ianwang' );

            if ( $user && wp_check_password( $_POST['pwd-txt'], $user->data->user_pass, $user->ID) ) {
                //chdir(SI_PATH.'/spider_wechat_single');
                exec(SI_PATH.'/run_single_wechat.sh "'.$_POST['url-txt'].'" '.SI_PATH, $cmd_output);
                //exec(SI_PATH.'/run_single_wechat.sh "'.$_POST['url-txt'].'" '.SI_PATH.' 2>&1', $cmd_output);//for error output

                foreach($cmd_output as $out) {
                    $output .= '<div>'.$out.'</div>';
                }

                $scrapyMG = new ScrapyDB('scrapy_wechat_single');
                $oz_rmdb = $scrapyMG->getInstance(); 

                $data = $oz_rmdb->get_row('SELECT * FROM wechat_data'); 
                if(empty($data)) {
                    $output .= '<div>No data.</div>';
                    return $output;
                }

                $post_data = preg_replace("/<span[^>]+?\>[^<]+?阅读原文查看更多精彩[^<]+?<\/span>/i", "", $data->post_data); 
                $post_data = preg_replace("/点击上方/i", "", $post_data); 
                $post_data = preg_replace("/关注“澳洲折扣资讯”/i", "", $post_data); 
                $jumper_url = SI_URL.'/jumper.php?url=';
                $post_data = preg_replace("/(<img[^>]*data-src=\")([^\"]+)(\"[^>]*?\>)/i", "$1".$jumper_url."$2$3", $post_data);
                $post_data = preg_replace("/(<img[^>]*data-src=\")([^\"]+)(\")([^>]*?\>)/i", "$1$2$3".' src="'."$2\" "."$4", $post_data);

                $old_post_id = $this->have_same_post(wp_strip_all_tags($data->title));

                $post_type = $_POST['post-type-rad'];

                if(empty($old_post_id)) {
                    // Create post object
                    $coupon_post = array(
                        'post_title'    => wp_strip_all_tags( $data->title ),
                        'post_content'  => $post_data,
                        'post_status'   => 'draft',
                        'post_author'   => 1,
                        // 'post_category' => array( 8,39 )
                        'post_type'     => $post_type,
                        'filter'        => 'db'

                    );

                    // Insert the post into the database
                    $postid = si_insert_post( $coupon_post );
                    $debug = $postid;
                    //var_dump($postid);

                    $coupon_code = trim($data->coupon_code);
                    if(!empty($postid)) {
                        update_post_meta($postid, 'clpr_coupon_aff_url', $data->dst_url);
                        if(empty($coupon_code)) {
                            update_post_meta($postid, 'coupon_type', "promotion");
                        } else {
                            update_post_meta($postid, 'coupon_type', "coupon-code");
                            update_post_meta($postid, 'clpr_coupon_code', $coupon_code);
                        }

                        $expire_date = trim($data->expire_date);
                        if(!empty($expire_date)) {
                            $time = strtotime($expire_date);
                            $expire_date = date('Y-m-d',$time);
                            update_post_meta($postid, 'clpr_coupon_aff_url', $expire_date);
                        }

                        if(empty($data->img)) {
                            return $output;
                        }

                        $attach_id = $this->generate_featured_image($data->img, $postid);
                        if($_POST['site'] == 'ozdazhe') {
                            $img_urls = wp_get_attachment_image_src($attach_id, 'full');
                            if(!empty($img_urls)) {
                                $post_data = $post_data;
                            } 
                            $kv_edited_post = array(
                                'ID'           => $postid,
                                'post_content' => $post_data,
                                'filter'       => true
                            );
                            wp_update_post( $kv_edited_post);
                        }

                        $stores = $this->list_stores();
                        //$ajax_debug = $stores;
                        foreach($stores as $store) {
                            if($data->store == $store->name) {
                                wp_set_post_terms($postid, array($store->id), 'stores');
                            }
                        }

                        if(!empty($old_post_id)) {
                            wp_trash_post($old_post_id);
                        }

                    }

                }

            }
        }

        $output .= '<div style="height:50px"></div>';
        return $output;
    }

     public function list_stores() {
        global $wpdb;
        global $ajax_debug;
        $sql = "SELECT distinct t.name AS name,t.term_id AS id FROM $wpdb->term_taxonomy tt, $wpdb->terms t WHERE tt.term_id=t.term_id AND tt.taxonomy='stores';";
        $stores = $wpdb->get_results($sql); 
        //$ajax_debug[] = $sql;
        return $stores; 
    }

    public function pippin_get_image_id($filename) {
        global $wpdb;
        global $ajax_debug;
        $sql = $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title='%s';", $filename);
        $attachment = $wpdb->get_col($sql); 
        //$ajax_debug[] = $sql;
        return $attachment[0]; 
    }

    public function get_attachment_id( $filename ) {
        $attachment_id = 0;
        $file = $filename;

        $query_args = array(
            'post_type'   => 'attachment',
            'post_status' => 'inherit',
            'fields'      => 'ids',
            'meta_query'  => array(
                array(
                    'value'   => $file,
                    'compare' => 'LIKE',
                    'key'     => '_wp_attachment_metadata',
                ),
            )
        );

        $query = new WP_Query( $query_args );

        if ( $query->have_posts() ) {

            foreach ( $query->posts as $post_id ) {

                $meta = wp_get_attachment_metadata( $post_id );

                $original_file       = basename( $meta['file'] );
                $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

                if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                    $attachment_id = $post_id;
                    break;
                }

            }

        }

        return $attachment_id;
    }

    public function generate_featured_image( $image_url, $post_id){
        global $ajax_debug;

        $upload_dir = wp_upload_dir();

        $filename = basename($image_url);
        $filename = preg_replace('/(.*)fmt=(.*)/i','import'.$post_id.'.${2}',$filename);
        //$ajax_debug = $filename;

        if(wp_mkdir_p($upload_dir['path']))     $file = $upload_dir['path'] . '/' . $filename;
        else                                    $file = $upload_dir['basedir'] . '/' . $filename;

        $image_data = file_get_contents($image_url);
        if($image_data === FALSE) {
            return;
        }

        $attach_id = $this->pippin_get_image_id($filename);

        if(!$attach_id) {
            file_put_contents($file, $image_data);

            $wp_filetype = wp_check_filetype($filename, null );
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
        } 
        //$ajax_debug[] = $attach_id;

        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
        $res1= wp_update_attachment_metadata( $attach_id, $attach_data );

        $res2= set_post_thumbnail( $post_id, $attach_id );
        return $attach_id;
    }

    public function import_data() {
        global $ajax_debug;

        $res = false;
        $flag = $_POST['flag'];
        $ckb_list = $_POST['list'];
        foreach($ckb_list as $ckb) {
            $clause .= ('id='.$ckb.' OR ');
        }

        $clause = substr($clause, 0, -4);

        $scrapyMG = new ScrapyDB('scrapy_'.$_POST['site']);
        $oz_rmdb = $scrapyMG->getInstance(); 

        $sql = $oz_rmdb->prepare('SELECT * FROM '.$_POST['site'].'_data WHERE '.$clause, $start, $perpage_num);
        $oz_datas = $oz_rmdb->get_results($sql); 
 
        foreach($oz_datas as $data) {
            if($_POST['site'] != 'wechat') {
                $post_data = preg_replace("/<img[^>]+\>/i", "", $data->post_data);
            } else {
                $post_data = preg_replace("/<span[^>]+?\>[^<]+?阅读原文查看更多精彩[^<]+?<\/span>/i", "", $data->post_data); 
                $post_data = preg_replace("/点击上方/i", "", $post_data); 
                $post_data = preg_replace("/关注“澳洲折扣资讯”/i", "", $post_data); 
            }

            $old_post_id = $this->have_same_post(wp_strip_all_tags($data->title));
            //$ajax_debug = $old_post_id;
            if($flag == 'post') {
                $flag = 'draft';
                $post_type = 'post';
            } else { 
                $post_type = 'coupon';
            }

            if(empty($old_post_id)) {
                // Create post object
                $coupon_post = array(
                    'post_title'    => wp_strip_all_tags( $data->title ),
                    'post_content'  => $post_data,
                    'post_status'   => $flag,
                    'post_author'   => 1,
                    // 'post_category' => array( 8,39 )
                    'post_type'     => $post_type

                );

                // Insert the post into the database
                $postid = wp_insert_post( $coupon_post );
                $debug = $postid;

                $coupon_code = trim($data->coupon_code);
                if(!empty($postid)) {
                    update_post_meta($postid, 'clpr_coupon_aff_url', $data->dst_url);
                    if(empty($coupon_code)) {
                        update_post_meta($postid, 'coupon_type', "promotion");
                    } else {
                        update_post_meta($postid, 'coupon_type', "coupon-code");
                        update_post_meta($postid, 'clpr_coupon_code', $coupon_code);
                    }

                    $expire_date = trim($data->expire_date);
                    if(!empty($expire_date)) {
                        $time = strtotime($expire_date);
                        $expire_date = date('Y-m-d',$time);
                        update_post_meta($postid, 'clpr_coupon_aff_url', $expire_date);
                    }

                    $attach_id = $this->generate_featured_image($data->img, $postid);
                    if($_POST['site'] == 'ozdazhe') {
                        $img_urls = wp_get_attachment_image_src($attach_id, 'full');
                        if(!empty($img_urls)) {
                            $post_data = $post_data.'<div style="display:block;width:100%;height:auto"><img src="'.$img_urls[0].'"/></div>';
                        } 
                        $kv_edited_post = array(
                            'ID'           => $postid,
                            'post_content' => $post_data
                        );
                        wp_update_post( $kv_edited_post);
                    }

                    $stores = $this->list_stores();
                    //$ajax_debug = $stores;
                    foreach($stores as $store) {
                        if($data->store == $store->name) {
                            wp_set_post_terms($postid, array($store->id), 'stores');
                        }
                    }

                    if(!empty($old_post_id)) {
                        wp_trash_post($old_post_id);
                    }

                }
            }
        

        }
       
        $res_ary = array('res' => $res, $oz_datas, $sql, $debug, 'ajaxdebug'=>$ajax_debug);
        echo json_encode($res_ary);
        wp_die();
    }

}
