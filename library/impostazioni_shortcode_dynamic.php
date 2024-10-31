<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("input[name$='instagramslider_user_or_hashtag']").click(function () {
            var test = $(this).val();
            if (test == 'user') {
                $('#instagramslider_hashtag').attr('disabled', true);
            } else if (test == 'hashtag') {
                $('#instagramslider_hashtag').attr('disabled', false);
            }
            $("div.desc").hide();
            $("#instagramslider_user_or_hashtag_" + test).show();
        });
    });
    function save_settings(id) {
        var setting_option = jQuery("input[name='instagram_settings_option_<?= $val['id'] ?>']:checked").val();
        if (setting_option == "1") {
            var setting_text = jQuery("#instagramslider_user_<?= $val['id'] ?>").val();
        } else {
            var setting_text = jQuery("#instagramslider_hashtag_<?= $val['id'] ?>").val();
        }
        var carousel_setting1 = jQuery("#instagramslider_carousel_items_number_<?= $val['id'] ?>").val();
        var carousel_setting2 = jQuery("#instagramslider_carousel_navigation_<?= $val['id'] ?>").val();
        var carousel_setting3 = jQuery("#instagramslider_carousel_rows_<?= $val['id'] ?>").val();
        var grid_setting1 = jQuery("#instagramslider_grid_cols_<?= $val['id'] ?>").val();
        var grid_setting2 = jQuery("#instagramslider_grid_rows_<?= $val['id'] ?>").val();

        jQuery.ajax({
            type: "POST",
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'nexus_insta_options',
                data: {updatesettings: "1", id: id, setting_option: setting_option, setting_text: setting_text, carousel_setting1: carousel_setting1, carousel_setting2: carousel_setting2, carousel_setting3: carousel_setting3, grid_setting1: grid_setting1, grid_setting2: grid_setting2},
            },
            success: function (result)
            {
                alert('Your settings saved successfully.');
            }
        });
    }
</script>

<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" style="align:left;">
                <label for="instagramslider_user_or_hashtag" class="enfasi">Instagram Settings:</label>
            </th>
            <td>
                <div class="ei_block" style="float: left;width: 63%;">
                    <div class="ei_settings_float_block">
                        Show Pictures: 
                    </div>
                    <div class="ei_settings_float_block"> 
                        <input type="radio" name="instagram_settings_option_<?= $val['id'] ?>" <?php if ($val['instagram_settings_option'] == '1') echo "checked"; ?> value="1">of Your Profile<br/><br/>
                        <input type="radio" name="instagram_settings_option_<?= $val['id'] ?>" <?php if ($val['instagram_settings_option'] == '2') echo "checked"; ?> value="2">by Hashtag<br />
                    </div>
                    <div class="ei_settings_float_block">
                        <div id="instagramslider_user_or_hashtag_user" class="desc" <?php if ($val['instagram_settings_option'] != '1') echo 'style="display:none;"'; ?> >
                            &nbsp;<input type="text" class="ei_disabled" id="instagramslider_user_<?= $val['id'] ?>" disabled value="<?php echo $val['instagram_settings_text']; ?>" name="instagramslider_user_<?= $val['id'] ?>" />
                        </div>
                        <div id="instagramslider_user_or_hashtag_hashtag" class="desc" <?php if ($val['instagram_settings_option'] != '2') echo 'style="display:none;"'; ?>>
                            #<input type="text" id="instagramslider_hashtag_<?= $val['id'] ?>" required value="<?php echo $val['instagram_settings_text']; ?>" name="instagramslider_hashtag_<?= $val['id'] ?>" />
                            <br />Insert a hashtag without '#'
                        </div>
                    </div> 
                </div>       
                <?php
                if ($val['token'] != '') {
                    ?>
                    <div class="wrap" style="
                         float: right;
                         width: 29%;
                         background: #006db3 none repeat scroll 0 0;
                         padding: 5px 20px;
                         margin-top: 0px;
                         border: 2px solid #215575;color: #ffffff;">
                        <h3 style="color: #ffffff;">Short codes to use:</h3>
                        <b>[<?= $val['shortcode_1'] ?>]</b> -> Carousel View <br />
                        <b>[<?= $val['shortcode_2'] ?>]</b> -> Grid View
                    </div>
                    <?php
                }
                ?>
            </td>

        </tr>
    </tbody>
</table>
<hr />
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" style="align:left;">
                <label for="instagramslider_carousel_items_numbe" class="enfasi">Carousel settings:</label>
            </th>
            <td><div class="ei_block">
                    <div class="ei_settings_float_block ei_fixed">
                        Images displayed at a time:
                    </div>
                    <div class="ei_settings_float_block">
                        <select name="instagramslider_carousel_items_number_<?= $val['id'] ?>" class="ei_sel" id="instagramslider_carousel_items_number_<?= $val['id'] ?>">
                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php
                                if ($val['carousel_setting_1'] == $i)
                                    echo "selected='selected'";
                                ?>>
                                            <?php echo "&nbsp;" . $i; ?>			
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="ei_block">
                    <div class="ei_settings_float_block ei_fixed">
                        Navigation buttons:
                    </div>
                    <div class="ei_settings_float_block">
                        <select name="instagramslider_carousel_navigation_<?= $val['id'] ?>" class="ei_sel" id="instagramslider_carousel_navigation_<?= $val['id'] ?>">
                            <option value="1" <?php if ($val['carousel_setting_1'] == '1') echo "selected='selected'"; ?>>Yes
                            </option>
                            <option value="2" <?php if ($val['carousel_setting_1'] == '2') echo "selected='selected'"; ?>>No
                            </option>
                        </select>
                    </div>
                </div>
                <div class="ei_block">
                    <div class="ei_settings_float_block ei_fixed">
                        Number of Rows:
                    </div>
                    <div class="ei_settings_float_block">
                        <select name="instagramslider_carousel_rows_<?= $val['id'] ?>" id="instagramslider_carousel_rows_<?= $val['id'] ?>" class="ei_sel">
                            <?php for ($i = 1; $i <= 2; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php
                                if ($val['carousel_setting_3'] == $i)
                                    echo "selected='selected'";
                                ?>>
                                            <?php echo "&nbsp;" . $i; ?>			
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<hr />
<!-- SHORTCODE WALL GRID -->
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" style="align:left;">
                <label for="instagramslider_carousel_grid" class="enfasi">Grid view settings:</label>
            </th>
            <td><div class="ei_block">
                    <div class="ei_settings_float_block ei_fixed">
                        Number of Columns:
                    </div>
                    <div class="ei_settings_float_block">
                        <select name="instagramslider_grid_cols_<?= $val['id'] ?>" id="instagramslider_grid_cols_<?= $val['id'] ?>" class="ei_sel">
                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php
                                if ($val['grid_setting_1'] == $i)
                                    echo "selected='selected'";
                                ?>>
                                            <?php echo "&nbsp;" . $i; ?>			
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="ei_block">
                    <div class="ei_settings_float_block ei_fixed">
                        Number of Rows:
                    </div>
                    <div class="ei_settings_float_block">
                        <select name="instagramslider_grid_rows_<?= $val['id'] ?>" id="instagramslider_grid_rows_<?= $val['id'] ?>" class="ei_sel">
                            <?php for ($i = 1; $i <= 10; $i++) { ?>
                                <option value="<?php echo $i ?>" <?php
                                if ($val['grid_setting_2'] == $i)
                                    echo "selected='selected'";
                                ?>>
                                            <?php echo "&nbsp;" . $i; ?>			
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
<hr/>
<input type="button" class="button-primary" name="button_instagramslider_advanced" value="Save Settings" onclick="save_settings('<?= $val['id'] ?>')"/>