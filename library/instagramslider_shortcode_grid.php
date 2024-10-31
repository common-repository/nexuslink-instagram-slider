<?php
// Add Shortcode
function instagramslider_mb_shortcode_grid() {
    $shortcode_content = '';
    STATIC $i = 1;
    if (get_option('instagramslider_client_id') !== null || get_option('instagramslider_client_id') != '') {

        if (get_option('instagramslider_user_or_hashtag') == 'hashtag') {
            $result = instagramslider_get_hash(urlencode(get_option('instagramslider_hashtag')), 30);
            $result = $result['data'];
        } else {
            $result = instagramslider_get_user(urlencode(get_option('instagramslider_user_username')), 30);
            $result = $result['data'];
        }

        $pre_shortcode_content = "<div id=\"grid-" . $i . "\" class=\"ri-grid ri-grid-size-2 ri-shadow\" style=\"display:none;\"><ul>";

        if (instagramslider_isHttps() && !is_null($result)) {
            foreach ($result as $entry) {
                $entry['images']['thumbnail']['url'] = str_replace('http://', 'https://', $entry['images']['thumbnail']['url']);
                $entry['images']['standard_resolution']['url'] = str_replace('http://', 'https://', $entry['images']['standard_resolution']['url']);
            }
        }

        if (!is_null($result)) {
            foreach ($result as $entry) {
                if (!empty($entry['caption'])) {
                    $caption = $entry['caption']['text'];
                } else {
                    $caption = '';
                }
                $square_thumbnail = str_replace('s150x150/', 's320x320/', $entry['images']['thumbnail']['url']);

                //For standard resolution and default format
                //$shortcode_content .=  "<li>".$link."<img  src=\"{$entry['images']['standard_resolution']['url']}\">".$link_close."</li>";

                $square_thumbnail = str_replace('s150x150/', 's320x320/', $entry['images']['thumbnail']['url']);

                $link = "<a title=\"{$caption}\" class=\"swipebox_grid\" data-video=\"no\" href=\"{$entry['images']['standard_resolution']['url']}\"><img  src=\"{$square_thumbnail}\">";

                //For square pictures or videos
                if (($entry['type'] == 'video')) {
                    $video_link = str_replace("href=\"" . $entry['images']['standard_resolution']['url'] . "\">", "href=\"{$entry['videos']['standard_resolution']['url']}\">", $link);
                    $video_link = str_replace("data-video=\"no\"", "data-video=\"yes\"", $video_link);
                    $shortcode_content .= "<li>" . $video_link . "<img src=\"{$square_thumbnail}\"></a></li>";
                } else {
                    $shortcode_content .= "<li>" . $link . "</a></li>";
                }
            }
        }

        $post_shortcode_content = "</ul></div>";
        ?>
        <script type="text/javascript">

            jQuery(function () {
                jQuery('#grid-<?php echo $i; ?>').gridrotator({
                    rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                    columns: <?php echo get_option('instagramslider_grid_cols'); ?>,
                    animType: 'fadeInOut',
                    onhover : false,
                    interval: 7000,
                    preventClick: false,
                    w1400: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    },
                    w1024: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    },
                    w768: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    },
                    w480: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    },
                    w320: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    },
                    w240: {
                        rows: <?php echo get_option('instagramslider_grid_rows'); ?>,
                        columns: <?php echo get_option('instagramslider_grid_cols'); ?>
                    }
                });

                jQuery('#grid-<?php echo $i; ?>').fadeIn('1000');
            });
        </script>
        <?php
    }
    $i++;
    $shortcode_content = $pre_shortcode_content . $shortcode_content . $post_shortcode_content;
    return $shortcode_content;
}
add_shortcode('instagramslider_mb_grid', 'instagramslider_mb_shortcode_grid');

global $wpdb;
$table_name = $wpdb->prefix . "tbl_instagramslider";
$results = $wpdb->get_results("SELECT * FROM $table_name WHERE token!=''", ARRAY_A);
$count = 1;
foreach ($results as $k => $val) {
    $cb = function() use ($val) {
        $shortcode_content = '';
        
        if ($val['instagram_settings_option'] == '2') {
            $result = instagramslider_get_hash_dynamic(urlencode($val['instagram_settings_text']), 30, $val['token']);
            $result = $result['data'];
        } else {
            $result = instagramslider_get_user_dynamic(urlencode($val['instagram_settings_text']), 30, $val['token']);
            $result = $result['data'];
        }

        $pre_shortcode_content = "<div id=\"grid-" . $val['id'] . "\" class=\"ri-grid ri-grid-size-2 ri-shadow\" style=\"display:none;\"><ul>";

        if (instagramslider_isHttps() && !is_null($result)) {
            foreach ($result as $entry) {
                $entry['images']['thumbnail']['url'] = str_replace('http://', 'https://', $entry['images']['thumbnail']['url']);
                $entry['images']['standard_resolution']['url'] = str_replace('http://', 'https://', $entry['images']['standard_resolution']['url']);
            }
        }

        if (!is_null($result)) {
            foreach ($result as $entry) {
                if (!empty($entry['caption'])) {
                    $caption = $entry['caption']['text'];
                } else {
                    $caption = '';
                }
                $square_thumbnail = str_replace('s150x150/', 's320x320/', $entry['images']['thumbnail']['url']);

                //For standard resolution and default format
                //$shortcode_content .=  "<li>".$link."<img  src=\"{$entry['images']['standard_resolution']['url']}\">".$link_close."</li>";

                $square_thumbnail = str_replace('s150x150/', 's320x320/', $entry['images']['thumbnail']['url']);

                $link = "<a title=\"{$caption}\" class=\"swipebox_grid\" data-video=\"no\" href=\"{$entry['images']['standard_resolution']['url']}\"><img  src=\"{$square_thumbnail}\">";

                //For square pictures or videos
                if (($entry['type'] == 'video')) {
                    $video_link = str_replace("href=\"" . $entry['images']['standard_resolution']['url'] . "\">", "href=\"{$entry['videos']['standard_resolution']['url']}\">", $link);
                    $video_link = str_replace("data-video=\"no\"", "data-video=\"yes\"", $video_link);
                    $shortcode_content .= "<li>" . $video_link . "<img src=\"{$square_thumbnail}\"></a></li>";
                } else {
                    $shortcode_content .= "<li>" . $link . "</a></li>";
                }
            }
        }

        $post_shortcode_content = "</ul></div>";
        ?>
        <script type="text/javascript">

            jQuery(function () {
                jQuery('#grid-<?php echo $val['id']; ?>').gridrotator({
                    rows: <?php echo $val['grid_setting_2']; ?>,
                    columns: <?php echo $val['grid_setting_1']; ?>,
                    animType: 'fadeInOut',
                    onhover : false,
                    interval: 7000,
                    preventClick: false,
                    w1400: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    },
                    w1024: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    },
                    w768: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    },
                    w480: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    },
                    w320: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    },
                    w240: {
                        rows: <?php echo $val['grid_setting_2']; ?>,
                        columns: <?php echo $val['grid_setting_1']; ?>
                    }
                });

                jQuery('#grid-<?php echo $val['id']; ?>').fadeIn('1000');
            });
        </script>
        <?php
        
        $shortcode_content = $pre_shortcode_content . $shortcode_content . $post_shortcode_content;
        return $shortcode_content;
    };

    add_shortcode($val['shortcode_2'], $cb);
}
?>