<?php
// Add Shortcode
function instagramslider_mb_shortcode($atts) {
    $shortcode_content = '';
    STATIC $i = 1;

    if (get_option('instagramslider_client_id') !== null || get_option('instagramslider_client_id') != '') {
        extract(shortcode_atts(array(
            'n' => '4',
                        ), $atts));
        ?>
        <script>
            jQuery(function () {
                jQuery(document.body)
                        .on('click touchend', '#swipebox-slider .current img', function (e) {
                            jQuery('#swipebox-next').click();
                            return false;
                        })
                        .on('click touchend', '#swipebox-slider .current', function (e) {
                            jQuery('#swipebox-close').trigger('click');
                        });
            });
        </script>
        <script type="text/javascript">
            jQuery(function ($) {
                $(".swipebox").swipebox({
                    hideBarsDelay: 0
                });

            });
            jQuery(document).ready(function () {
                jQuery("#owl-<?php echo $i; ?>").owlCarousel({
                    lazyLoad: true,
                    items: <?php echo get_option('instagramslider_carousel_items_number'); ?>,
                    itemsDesktop: [1199,<?php echo get_option('instagramslider_carousel_items_number'); ?>],
                    itemsDesktopSmall: [980,<?php echo get_option('instagramslider_carousel_items_number'); ?>],
                    itemsTablet: [768,<?php echo get_option('instagramslider_carousel_items_number'); ?>],
                    itemsMobile: [479,<?php echo get_option('instagramslider_carousel_items_number'); ?>],
                    stopOnHover: true,
                    navigation: <?php echo get_option('instagramslider_carousel_navigation'); ?>

                });
                jQuery("#owl-<?php echo $i; ?>").fadeIn();
            });
        </script>
        <?php
        if (get_option('instagramslider_user_or_hashtag') == 'hashtag') {
            $result = instagramslider_get_hash(urlencode(get_option('instagramslider_hashtag')), 20);
            $result = $result['data'];
        } else {
            $result = instagramslider_get_user(urlencode(get_option('instagramslider_user_username')), 20);
            $result = $result['data'];
        }
        $pre_shortcode_content = "<div id=\"owl-" . $i . "\" class=\"owl-example\" style=\"display:none;\">";
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

                $link = "<a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\">";

                if (($entry['type'] == 'video')) {
                    $video_link = str_replace("href=\"" . $entry['images']['standard_resolution']['url'] . "\">", "href=\"{$entry['videos']['standard_resolution']['url']}\">", $link);
                    $shortcode_content .= "<div class=\"box\">" . $video_link . "<img alt=\"{$caption}\" src=\"{$square_thumbnail}\"></a></div>";
                } else {
                    if (get_option('instagramslider_carousel_items_number') != '1') {
                        $shortcode_content .= "<div class=\"box\">" . $link . "<img  src=\"{$square_thumbnail}\"></a></div>";
                    } else {
                        $shortcode_content .= "<div class=\"box\"><a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\"><img style=\"width:100%;\" src=\"{$square_thumbnail}\"></a></div>";
                    }
                }
            }
        }

        $post_shortcode_content = "</div>";
    }
    $i++;

    $shortcode_content = $pre_shortcode_content . $shortcode_content . $post_shortcode_content;

    return $shortcode_content;
}

add_shortcode('instagramslider_mb', 'instagramslider_mb_shortcode');

// Add Shortcode
global $wpdb;
$table_name = $wpdb->prefix . "tbl_instagramslider";
$results = $wpdb->get_results("SELECT * FROM $table_name WHERE token!=''", ARRAY_A);
$count = 1;
foreach ($results as $k => $val) {
    $cb = function() use ($val) {
        
        if ($val['carousel_setting_2'] == 1) {
            $navigation = "true";
        } else {
            $navigation = "false";
        }
        ?>
        <script>
            jQuery(function () {
                jQuery(document.body)
                        .on('click touchend', '#swipebox-slider .current img', function (e) {
                            jQuery('#swipebox-next').click();
                            return false;
                        })
                        .on('click touchend', '#swipebox-slider .current', function (e) {
                            jQuery('#swipebox-close').trigger('click');
                        });
            });
        </script>
        <script type="text/javascript">
            jQuery(function ($) {
                $(".swipebox").swipebox({
                    hideBarsDelay: 0
                });

            });
            jQuery(document).ready(function () {
                jQuery("#owl-<?php echo $val['id']; ?>").owlCarousel({
                    lazyLoad: true,
                    items: <?php echo $val['carousel_setting_1']; ?>,
                    itemsDesktop: [1199,<?php echo $val['carousel_setting_1']; ?>],
                    itemsDesktopSmall: [980,<?php echo $val['carousel_setting_1']; ?>],
                    itemsTablet: [768,<?php echo $val['carousel_setting_1']; ?>],
                    itemsMobile: [479,<?php echo $val['carousel_setting_1']; ?>],
                    stopOnHover: true,
                    navigation: <?php echo $navigation; ?>

                });
                jQuery("#owl-<?php echo $val['id']; ?>").fadeIn();
            });
        </script>
        <?php
        if ($val['instagram_settings_option'] == '2') {
            $result = instagramslider_get_hash_dynamic(urlencode($val['instagram_settings_text']), 20, $val['token']);
            $result = $result['data'];
        } else {
            $result = instagramslider_get_user_dynamic(urlencode($val['instagram_settings_text']), 20, $val['token']);
            $result = $result['data'];
        }

        $pre_shortcode_content = "<div id=\"owl-" . $val['id'] . "\" class=\"owl-example\" style=\"display:block;\">";
        if (is_null($result)) {
            foreach ($result as $entry) {
                $entry['images']['thumbnail']['url'] = str_replace('http://', 'https://', $entry['images']['thumbnail']['url']);
                $entry['images']['standard_resolution']['url'] = str_replace('http://', 'https://', $entry['images']['standard_resolution']['url']);
            }
        }
        if (!is_null($result)) {
            $j = 0;
            foreach ($result as $entry) {
                if ($val['carousel_setting_3'] == 2 && $j == 0) {
                    $shortcode_content .= "<div class=\"box\">";
                }

                if (!empty($entry['caption'])) {
                    $caption = $entry['caption']['text'];
                } else {
                    $caption = '';
                }
                $square_thumbnail = str_replace('s150x150/', 's320x320/', $entry['images']['thumbnail']['url']);

                $link = "<a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\">";

                if (($entry['type'] == 'video')) {
                    $video_link = str_replace("href=\"" . $entry['images']['standard_resolution']['url'] . "\">", "href=\"{$entry['videos']['standard_resolution']['url']}\">", $link);
                    $shortcode_content .= "<div class=\"box\">" . $video_link . "<img alt=\"{$caption}\" src=\"{$square_thumbnail}\"></a></div>";
                } else {
                    if ($val['carousel_setting_3'] == 2) {
                        if ($j % 2 == 0 && $j != 0) {
                            $shortcode_content .= "</div><div class=\"box\">";
                        }

                        if ($val['carousel_setting_1'] != '1') {
                            $shortcode_content .= $link . "<img  src=\"{$square_thumbnail}\"></a>";
                        } else {
                            $shortcode_content .= "<a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\"><img style=\"width:100%;\" src=\"{$square_thumbnail}\"></a>";
                        }
                    } else {
                        if ($val['carousel_setting_1'] != '1') {
                            $shortcode_content .= "<div class=\"box\">" . $link . "<img  src=\"{$square_thumbnail}\"></a></div>";
                        } else {
                            $shortcode_content .= "<div class=\"box\"><a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\"><img style=\"width:100%;\" src=\"{$square_thumbnail}\"></a></div>";
                        }
                    }
                }
                $j++;
            }
            if ($val['carousel_setting_3'] == 2) {
                $shortcode_content .= "</div>";
            }
        }
        $post_shortcode_content = "</div>";

        $shortcode_content = $pre_shortcode_content . $shortcode_content . $post_shortcode_content;

        return $shortcode_content;
    };
    add_shortcode($val['shortcode_1'], $cb);
}
?>