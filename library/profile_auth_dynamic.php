<?php
$table_name = $wpdb->prefix . "tbl_instagramslider";
$getdata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id=%d",$val['id']), ARRAY_A);
?>
<p>
<div id="enjoy_user_profile">
    <img class="enjoy_user_profile" src="<?= $getdata['user_profilepicture'] ?>" style="float: left;">     
    <div class="wrap" style="
         float: left;
         width: 25%;
         background: #006db3 none repeat scroll 0 0;
         padding: 20px;
         margin-left: 10px;margin-top: 0px;
         border: 2px solid #215575;color: #ffffff;">
        <h3 style="color: #ffffff;">Short codes to use:</h3>
        <b>[<?= $getdata['shortcode_1'] ?>]</b> -> Carousel View <br />
        <b>[<?= $getdata['shortcode_2'] ?>]</b> -> Grid View
    </div>
    <div style="clear: both;"></div>
</div>

<div id="enjoy_user_block" >
    <h3><?= $getdata['user_name'] ?></h3>
    <p><i><?= $getdata['user_bio'] ?></i></p>
    <hr/>    
</div>
<div>
    <input type="button" value="Logout from this Account" class="button-primary ei_top" id="button_logout" onclick="remove_account('<?= $getdata['id'] ?>')" />
</div>
<script>
    function remove_account(id) {
        var conf = confirm('Are you sure to logout from this account?');
        if (conf) {
            jQuery.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'nexus_insta_options',
                    data: {removeaccount: "1", id: id},
                },
                success: function (result)
                {
                    //location.reload();
                }
            });
        }
    }
</script>
</p>