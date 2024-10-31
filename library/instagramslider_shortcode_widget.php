<?php

// Add Shortcode
function instagramslider_mb_shortcode_widget($atts) {
    if (get_option('instagramslider_client_id') || get_option('instagramslider_client_id') != '') {
        extract(shortcode_atts(array(
            'n' => '4',
            'id' => 'owl',
            'n_y_n' => 'false',
            'u_or_h' => 'user'
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
                jQuery("#owl-<?php echo "{$id}"; ?>").owlCarousel({
                    items: <?php echo "{$n}"; ?>,
                    navigation: <?php echo "{$n_y_n}"; ?>,
                });
                jQuery("#owl-<?php echo "{$id}"; ?>").fadeIn('slow');
            });
        </script>
        <?php
        if ("{$u_or_h}" == 'hashtag') {
            $result = instagramslider_get_hash(urlencode(get_option('instagramslider_hashtag')), 20);
            $result = $result['data'];
        } else {
            $result = instagramslider_get_user(urlencode(get_option('instagramslider_user_username')), 20);
            $result = $result['data'];
        }

        if (instagramslider_isHttps() && !is_null($result)) {
            foreach ($result as $entry) {
                $entry['images']['thumbnail']['url'] = str_replace('http://', 'https://', $entry['images']['thumbnail']['url']);
                $entry['images']['standard_resolution']['url'] = str_replace('http://', 'https://', $entry['images']['standard_resolution']['url']);
            }
        }
        ?>
        <div id="owl-<?php echo "{$id}"; ?>" class="owl-example">
            <?php
            if (!is_null($result)) {
                foreach ($result as $entry) {
                    if (!empty($entry['caption'])) {
                        $caption = $entry['caption']['text'];
                    } else {
                        $caption = '';
                    }
                    if (get_option('instagramslider_carousel_items_number') != '1') {
                        echo "<div class=\"box\"><a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\"><img  src=\"{$entry['images']['thumbnail']['url']}\"></a></div>";
                    } else {
                        echo "<div class=\"box\"><a title=\"{$caption}\" rel=\"gallery_swypebox\" class=\"swipebox\" href=\"{$entry['images']['standard_resolution']['url']}\"><img style=\"width:100%;\" src=\"{$entry['images']['standard_resolution']['url']}\"></a></div>";
                    }
                }
            }
            ?>
        </div>
        <?php
    }
}

add_shortcode('instagramslider_mb_widget', 'instagramslider_mb_shortcode_widget');
?>
