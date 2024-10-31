<?php
/*
  Plugin Name: Instagram Slider
  Plugin URI: http://myplugin.nexuslinkservices.com/
  Description: Instagram Responsive Images Slider
  Version: 1.0
  Author: Nexuslink Services
  Author URI: http://nexuslinkservices.com/
 */
if (!defined('ABSPATH')) {
    die();
}
require_once('library/instagramslider_shortcode.php');

function register_instagram_table() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "tbl_instagramslider";
    $sql = "CREATE TABLE IF NOT EXISTS  $table_name(
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `tab_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                    `tab_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                    `shortcode_1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                    `shortcode_2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
                    `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `user_profilepicture` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `user_fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `user_website` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `user_bio` text COLLATE utf8_unicode_ci NOT NULL,
                    `instagram_settings_option` int(11) NOT NULL,
                    `instagram_settings_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                    `carousel_setting_1` int(11) NOT NULL,
                    `carousel_setting_2` int(11) NOT NULL,
                    `carousel_setting_3` int(11) NOT NULL,
                    `grid_setting_1` int(11) NOT NULL,
                    `grid_setting_2` int(11) NOT NULL,
                    `created_date` datetime NOT NULL,
                    `status` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                   ) $charset_collate;";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'register_instagram_table');

function register_instagram_untable() {
    global $wpdb;
    $table_name = $wpdb->prefix . "tbl_instagramslider";
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
    delete_option("my_plugin_db_version");
}

register_uninstall_hook(__FILE__, 'register_instagram_untable');

class Settings_instagramslider_Plugin {

    private $instagramslider_general_settings_key = 'instagramslider_general_settings';
    private $advanced_settings_key = 'instagramslider_advanced_settings';
    private $plugin_options_key = 'instagramslider_plugin_options';
    private $plugin_settings_tabs = array();

    function __construct() {
        add_action('init', array(&$this, 'load_settings'));
        add_action('admin_init', array(&$this, 'register_instagramslider_client_id'));
        add_action('admin_init', array(&$this, 'register_advanced_settings'));
        add_action('admin_menu', array(&$this, 'add_admin_menus'));
    }

    function load_settings() {
        $this->general_settings = (array) get_option($this->instagramslider_general_settings_key);
        $this->advanced_settings = (array) get_option($this->advanced_settings_key);
        $this->general_settings = array_merge(array(
            'general_option' => 'General value'
                ), $this->general_settings);

        $this->advanced_settings = array_merge(array(
            'advanced_option' => 'Advanced value'
                ), $this->advanced_settings);
    }

    function register_instagramslider_client_id() {
        $this->plugin_settings_tabs[$this->instagramslider_general_settings_key] = 'Instagram Profile';

        register_setting($this->instagramslider_general_settings_key, $this->instagramslider_general_settings_key);
        add_settings_section('section_general', 'General Plugin Settings', array(&$this, 'section_general_desc'), $this->instagramslider_general_settings_key);
        add_settings_field('general_option', 'A General Option', array(&$this, 'field_general_option'), $this->instagramslider_general_settings_key, 'section_general');
    }

    function register_advanced_settings() {
        $this->plugin_settings_tabs[$this->advanced_settings_key] = 'Custom Settings';

        register_setting($this->advanced_settings_key, $this->advanced_settings_key);
        add_settings_section('section_advanced', 'Advanced Plugin Settings', array(&$this, 'section_advanced_desc'), $this->advanced_settings_key);
        add_settings_field('advanced_option', 'An Advanced Option', array(&$this, 'field_advanced_option'), $this->advanced_settings_key, 'section_advanced');
    }

    function section_general_desc() {
        echo 'Instagram Settings';
    }

    function section_advanced_desc() {
        echo 'Manage Instagram Slider.';
    }

    function field_general_option() {
        ?>
        <input type="text" name="<?php echo $this->instagramslider_general_settings_key; ?>[general_option]" value="<?php echo esc_attr($this->general_settings['general_option']); ?>" /><?php
    }

    function field_advanced_option() {
        ?>
        <input type="text" name="<?php echo $this->advanced_settings_key; ?>[advanced_option]" value="<?php echo esc_attr($this->advanced_settings['advanced_option']); ?>" />
        <?php
    }

    function add_admin_menus() {
        //add_options_page('Instagram Slider', 'Instagram Slider', 'manage_options', $this->plugin_options_key, array(&$this, 'instagramslider_options_page'));
        add_menu_page('Instagram Slider', //page title
                'Instagram Slider', //menu title
                'manage_options', //capabilities
                $this->plugin_options_key, //menu slug
                array(&$this, 'instagramslider_options_page'), //function
                plugins_url('images/instagram-slider.png', __FILE__)
        );
    }

    function instagramslider_options_page() {
        global $wpdb;
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $this->instagramslider_general_settings_key;
        ?>
        <div class="wrap">                      
            <script>
                function addmore() {
                    jQuery.ajax({
                        type: "POST",
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        data: {
                            action: 'nexus_insta_options',
                            data: {addmoreoption: "1"},
                        },
                        success: function (result) {
                            location.reload();
                        },
                    });
                }
                function remove_tab(id) {
                    var conf = confirm('Are you sure to remove this options?');
                    if (conf) {
                        jQuery.ajax({
                            type: "POST",
                            url: '<?php echo admin_url('admin-ajax.php'); ?>',
                            data: {
                                action: 'nexus_insta_options',
                                data: {removetab: "1", id: id},
                            },
                            success: function (result)
                            {
                                location.reload();
                            }
                        });
                    }
                }
            </script>
            <h2><div class="ei_block">
                    <div class="ei_left_block">
                        <div class="ei_hard_block">
                            <?php echo '<img src="' . plugins_url('images/instagramslider.jpg', __FILE__) . '" > '; ?>
                        </div>
                    </div>
                    <div style="float: right">
                        <input class="button-primary ei_top" value="Add More +" type="button" onclick="addmore()">
                    </div>
                    <div style="clear: both;"></div>
                </div>
            </h2>
            <hr>            
            <?php
            $table_name = $wpdb->prefix . "tbl_instagramslider";
            $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
            if ($rowcount == 0) {
                $sql_ins_arr = array(
                    'tab_1' => 'profile_1',
                    'tab_2' => 'setting_1',
                    'shortcode_1' => 'instagramslider_mb_1',
                    'shortcode_2' => 'instagramslider_mb_grid_1',
                    'instagram_settings_option' => 1,
                    'carousel_setting_1' => 5,
                    'carousel_setting_3' => 1,
                    'grid_setting_3' => 5,
                    'grid_setting_2' => 2,
                    'created_date' => current_time('mysql', 1),
                    'status' => 1,
                );
                $wpdb->insert($table_name, $sql_ins_arr);
            }

            $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            $count = 0;
            foreach ($results as $k => $val) {
                $count++;
                ?><div>
                    <h2 class="nav-tab-wrapper">
                        <a class="nav-tab <?php
                        if ($tab != $val['tab_2']) {
                            echo "nav-tab-active";
                        }
                        ?>" href="?page=instagramslider_plugin_options&amp;tab=<?= $val['tab_1'] ?>">Instagram Profile-<?= $count ?></a>
                        <a class="nav-tab <?php
                        if ($tab == $val['tab_2']) {
                            echo "nav-tab-active";
                        }
                        ?>" href="?page=instagramslider_plugin_options&amp;tab=<?= $val['tab_2'] ?>">Custom Settings-<?= $count ?></a>
                           <?php
                           if ($count > 1) {
                               ?>
                            <a href="javascript:;" onclick="remove_tab('<?= $val['id'] ?>')" style="float:right;color: red;text-decoration: none;">&cross; Remove</a>
                            <?php
                        }
                        ?>
                    </h2>
                    <?php
                    if ($tab == $val['tab_2'] && !isset($_GET['access_token'])) {
                        include('library/impostazioni_shortcode_dynamic.php');
                    } else {
                        if ($val['token'] == '' && !isset($_GET['access_token'])) {
                            include('library/autenticazione_dynamic.php');
                        } else {
                            if (isset($_GET['access_token']) && $_GET['access_token'] != '' && $tab == $val['tab_1']) {
                                $user = instagramslider_get_user_info($_GET['access_token']);

                                $instagramslider_user_id = $user['data']['id'];
                                $instagramslider_user_username = instagramslider_replace4byte($user['data']['username']);
                                $instagramslider_user_profile_picture = $user['data']['profile_picture'];
                                $instagramslider_user_fullname = instagramslider_replace4byte($user['data']['full_name']);
                                $instagramslider_user_website = $user['data']['website'];
                                $instagramslider_user_bio = instagramslider_replace4byte($user['data']['bio']);
                                $instagramslider_access_token = $_GET['access_token'];

                                $data_access_up = array(
                                    'user_id' => $instagramslider_user_id,
                                    'user_name' => $instagramslider_user_username,
                                    'instagram_settings_text' => $instagramslider_user_username,
                                    'user_profilepicture' => $instagramslider_user_profile_picture,
                                    'user_fullname' => $instagramslider_user_fullname,
                                    'user_website' => $instagramslider_user_website,
                                    'user_bio' => $instagramslider_user_bio,
                                    'token' => $instagramslider_access_token,
                                );
                                $data_access_where = array('tab_1' => $tab);
                                $wpdb->update($table_name, $data_access_up, $data_access_where);
                            }
                            include('library/profile_auth_dynamic.php');
                        }
                    }
                    ?>
                </div><?php
            }
            ?>
            <input type="hidden" name="h_totalcustomtabs" id="h_totalcustomtabs" value="<?= count($results) ?>" />
        </div>
        <?php
    }

    function instagramslider_plugin_options_tabs() {
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->instagramslider_general_settings_key;

        screen_icon();
        echo '<h2 class="nav-tab-wrapper">';
        foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
        }
        echo '</h2>';
    }

}

add_action('wp_ajax_nexus_insta_options', 'nexus_insta_options');
add_action('wp_ajax_nopriv_nexus_insta_options', 'nexus_insta_options');

function nexus_insta_options() {
    global $wpdb;
    $table_name = $wpdb->prefix . "tbl_instagramslider";
    $data = $_POST['data'];
    if ($data['addmoreoption'] != '') {
        $rowcount = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        $append_count = $rowcount + 1;

        $data_insert = array(
            'tab_1' => 'profile_' . $append_count,
            'tab_2' => 'setting_' . $append_count,
            'shortcode_1' => 'instagramslider_mb_' . $append_count,
            'shortcode_2' => 'instagramslider_mb_grid_' . $append_count,
            'instagram_settings_option' => '1',
            'carousel_setting_1' => '5',
            'carousel_setting_3' => '1',
            'grid_setting_1' => '5',
            'grid_setting_2' => '2',
            'created_date' => current_time('mysql', 1),
            'status' => '1',
        );
        $wpdb->insert($table_name, $data_insert);
    }

    if ($data['updatesettings'] != '') {
        $data_update = array(
            'instagram_settings_option' => $data['setting_option'],
            'instagram_settings_text' => $data['setting_text'],
            'carousel_setting_1' => $data['carousel_setting1'],
            'carousel_setting_2' => $data['carousel_setting2'],
            'carousel_setting_3' => $data['carousel_setting3'],
            'grid_setting_1' => $data['grid_setting1'],
            'grid_setting_2' => $data['grid_setting2'],
        );
        $where = array("id" => $data['id']);
        $wpdb->update($table_name, $data_update, $where);
    }

    if ($data['removetab'] != '') {
        $where_del = array('id' => $data['id']);
        $wpdb->delete($table_name, $where_del);
    }

    if ($data['removeaccount'] != '') {
        $data_update_acc = array(
            'token' => '',
            'user_id' => '',
            'user_name' => '',
            'user_profilepicture' => '',
            'user_fullname' => '',
            'user_website' => '',
            'user_bio' => '',
            'instagram_settings_text' => '',
        );
        $where_acc = array("id" => $data['id']);
        $wpdb->update($table_name, $data_update_acc, $where_acc);
    }
    die();
}

function instagramslider_get_user_info($access_token) {
    $url = 'https://api.instagram.com/v1/users/self/?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $data        
        $data = json_decode($curl_data, true);
        return $data;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_hash($hashtag, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/tags/' . $hashtag . '/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_hash_dynamic($hashtag, $count, $token) {
    $access_token = $token;
    $url = 'https://api.instagram.com/v1/tags/' . $hashtag . '/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_hash_code($hashtag, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/tags/' . $hashtag . '/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        $code = $result['meta']['code'];
        return $code;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_user($user, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_user_dynamic($user, $count, $token) {
    $access_token = $token;
    $url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_user_code($user, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/users/self/media/recent?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        $code = $result['meta']['code'];
        return $code;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_media($user, $media) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/media/' . $media . '?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_likes($user, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        return $result;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_get_likes_code($user, $count) {
    $access_token = get_option('instagramslider_access_token');
    $url = 'https://api.instagram.com/v1/users/self/media/liked?access_token=' . $access_token;
    try {
        $curl_connection = wp_remote_get($url);
        $curl_data = $curl_connection['body'];

        //Data are stored in $result        
        $result = json_decode($curl_data, true);
        $code = $result['meta']['code'];
        return $code;
    } catch (Exception $e) {
        return $e->getMessage();
    }
}

function instagramslider_replace4byte($string) {
    return preg_replace('%(?:
          \xF0[\x90-\xBF][\x80-\xBF]{2}      # planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}      # plane 16
    )%xs', '', $string);
}

// Initialize the plugin
add_action('plugins_loaded', create_function('', '$Settings_instagramslider_Plugin = new Settings_instagramslider_Plugin;'));

function instagramslider_default_option() {
    add_option('instagramslider_client_id', '');
    add_option('instagramslider_client_secret', '');
    add_option('instagramslider_client_code', '');
    add_option('instagramslider_user_instagram', '');
    add_option('instagramslider_user_id', '');
    add_option('instagramslider_user_username', '');
    add_option('instagramslider_user_profile_picture', '');
    add_option('instagramslider_user_fullname', '');
    add_option('instagramslider_user_website', '');
    add_option('instagramslider_user_bio', '');
    add_option('instagramslider_access_token', '');
    add_option('instagramslider_carousel_items_number', 4);
    add_option('instagramslider_carousel_navigation', 'false');
    add_option('instagramslider_grid_rows', '2');
    add_option('instagramslider_grid_cols', '5');
    add_option('instagramslider_hashtag', '');
    add_option('instagramslider_user_or_hashtag', 'user');
}

register_activation_hook(__FILE__, 'instagramslider_default_option');

function instagramslider_register_options_group_auth() {
    register_setting('instagramslider_options_group_auth', 'instagramslider_client_id');
    register_setting('instagramslider_options_group_auth', 'instagramslider_client_secret');
    register_setting('instagramslider_options_group_auth', 'instagramslider_client_code');
    register_setting('instagramslider_options_group_auth', 'instagramslider_user_instagram');
}

add_action('admin_init', 'instagramslider_register_options_group_auth');

function instagramslider_register_options_group() {
    register_setting('instagramslider_options_group', 'instagramslider_client_id');
    register_setting('instagramslider_options_group', 'instagramslider_user_instagram');
    register_setting('instagramslider_options_group', 'instagramslider_user_id');
    register_setting('instagramslider_options_group', 'instagramslider_user_username');
    register_setting('instagramslider_options_group', 'instagramslider_user_profile_picture');
    register_setting('instagramslider_options_group', 'instagramslider_user_fullname');
    register_setting('instagramslider_options_group', 'instagramslider_user_website');
    register_setting('instagramslider_options_group', 'instagramslider_user_bio');
    register_setting('instagramslider_options_group', 'instagramslider_access_token');
}

add_action('admin_init', 'instagramslider_register_options_group');

function instagramslider_register_options_carousel() {
    register_setting('instagramslider_options_carousel_group', 'instagramslider_carousel_items_number');
    register_setting('instagramslider_options_carousel_group', 'instagramslider_carousel_navigation');
    register_setting('instagramslider_options_carousel_group', 'instagramslider_grid_cols');
    register_setting('instagramslider_options_carousel_group', 'instagramslider_grid_rows');
    register_setting('instagramslider_options_carousel_group', 'instagramslider_hashtag');
    register_setting('instagramslider_options_carousel_group', 'instagramslider_user_or_hashtag');
}

add_action('admin_init', 'instagramslider_register_options_carousel');

function instagramslider_aggiungi_script_instafeed_owl() {
    if (!is_admin()) {
        wp_register_script('gridrotator', plugins_url('/js/jquery.gridrotator.js', __FILE__), 'jquery', '');
        wp_register_script('owl', plugins_url('/js/owl.carousel.js', __FILE__), 'jquery', '');
        wp_register_script('swipebox', plugins_url('/js/jquery.swipebox.js', __FILE__), 'jquery', '');
        wp_register_script('modernizr.custom.26633', plugins_url('/js/modernizr.custom.26633.js', __FILE__), 'jquery', '');
        wp_register_script('orientationchange', plugins_url('/js/ios-orientationchange-fix.js', __FILE__), 'jquery', '');

        wp_register_style('owl_style', plugins_url('/css/owl.carousel.css', __FILE__));
        wp_register_style('owl_style_2', plugins_url('/css/owl.theme.css', __FILE__));
        wp_register_style('owl_style_3', plugins_url('/css/owl.transitions.css', __FILE__));
        wp_register_style('swipebox_css', plugins_url('/css/swipebox.css', __FILE__));
        wp_register_style('grid_fallback', plugins_url('/css/grid_fallback.css', __FILE__));
        wp_register_style('grid_style', plugins_url('/css/grid_style.css', __FILE__));

        wp_enqueue_script('jquery'); // include jQuery
        wp_enqueue_script('modernizr.custom.26633');
        wp_enqueue_script('gridrotator');
        wp_localize_script('gridrotator', 'GridRotator', array('pluginsUrl' => plugin_dir_url(__FILE__),));
        wp_enqueue_script('owl');
        wp_enqueue_script('swipebox');
        wp_enqueue_script('orientationchange');
        wp_enqueue_style('owl_style');
        wp_enqueue_style('owl_style_2');
        wp_enqueue_style('owl_style_3');
        wp_enqueue_style('swipebox_css');
        wp_enqueue_style('grid_fallback');
        wp_enqueue_style('grid_style');
    }
}

add_action('wp_enqueue_scripts', 'instagramslider_aggiungi_script_instafeed_owl');

function instagramslider_aggiungi_script_in_admin() {
    wp_register_style('instagramslider_settings', plugins_url('/css/instagramslider_settings.css', __FILE__));
    wp_enqueue_style('instagramslider_settings');
}

add_action('admin_enqueue_scripts', 'instagramslider_aggiungi_script_in_admin');
add_action('admin_head', 'instagramslider_aggiungo_javascript_in_pannello_amministrazione');

function instagramslider_aggiungo_javascript_in_pannello_amministrazione() {
    ?>
    <script type="text/javascript">
        function post_to_url(path, method) {
            method = method || "get";
            var params = new Array();
            var client_id = '41c27511bcf647e19b9da4351c387337';
            var client_secret = 'this_is_secret';
            params['client_id'] = client_id;
            params['redirect_uri'] = 'http://111.93.83.62/put_access_token.php?url_redirect=<?php echo admin_url('options-general.php?page=instagramslider_plugin_options&tab=instagramslider_general_settings'); ?>';
            params['response_type'] = 'code';
            params['scope'] = 'public_content';

            var form = document.createElement("form");
            form.setAttribute("method", method);
            form.setAttribute("action", path);

            for (var key in params) {
                if (params.hasOwnProperty(key)) {
                    var hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", key);
                    hiddenField.setAttribute("value", params[key]);

                    form.appendChild(hiddenField);
                }
            }
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    <?php
}

function instagramslider_funzioni_in_head() {
    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $(".swipebox_grid").swipebox({
                hideBarsDelay: 0
            });
        });
    </script>
    <?php
}

add_action('wp_head', 'instagramslider_funzioni_in_head');

function instagramslider_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=instagramslider_plugin_options">' . __('Settings') . '</a>';
    $widgets_link = '<a href="widgets.php">' . __('Widgets') . '</a>';
    $premium_link = '<a href="http://nexuslinkservices.com/">' . __('Premium Version') . '</a>';
    array_push($links, $settings_link);
    array_push($links, $widgets_link);
    array_push($links, $premium_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'instagramslider_plugin_settings_link');


add_action('wp_ajax_instagramslider_user_option_ajax_callback', 'instagramslider_user_option_ajax_callback');

function instagramslider_user_option_ajax_callback() {
    global $wpdb;
    $client_id = sanitize_text_field($_POST['client_id_value']);
    $client_secret = sanitize_text_field($_POST['client_secret_value']);
    update_option('instagramslider_client_id', $client_id);
    update_option('instagramslider_client_secret', $client_secret);
    die();
}

add_action('admin_footer', 'instagramslider_logout_client_ajax');

function instagramslider_logout_client_ajax() {
    ?>
    <script type="text/javascript" >
        jQuery('#button_logout').click(function () {
            var data = {
                action: 'instagramslider_user_logout_ajax_callback'
            };
            jQuery.post(ajaxurl, data, function (response) {
                location.href = '<?php echo get_admin_url(); ?>options-general.php?page=instagramslider_plugin_options&tab=instagramslider_general_settings';
            });
        });
    </script>
    <?php
}

add_action('wp_ajax_instagramslider_user_logout_ajax_callback', 'instagramslider_user_logout_ajax_callback');

function instagramslider_user_logout_ajax_callback() {
    global $wpdb;

    update_option('instagramslider_user_id', '');
    update_option('instagramslider_user_username', '');
    update_option('instagramslider_user_profile_picture', '');
    update_option('instagramslider_user_fullname', '');
    update_option('instagramslider_user_website', '');
    update_option('instagramslider_user_bio', '');
    update_option('instagramslider_access_token', '');
    update_option('instagramslider_client_id', '');
    update_option('instagramslider_client_secret', '');

    die();
}

function instagramslider_isHttps() {
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        return true;
    }
}

add_action('admin_init', 'instagramslider_ignore_slider');

function instagramslider_ignore_slider() {
    global $current_user;
    $user_id = $current_user->ID;
    /* If user clicks to ignore the notice, add that to their user meta */
    if (isset($_GET['ignore_slider']) && '0' == $_GET['ignore_slider']) {
        add_user_meta($user_id, 'ignore_slider_notice', 'true', true);
    }
}

require_once('library/instagramslider_shortcode_grid.php');
require_once('library/instagramslider_shortcode_widget.php');
require_once('library/instagramslider_shortcode_grid_widget.php');
?>