<p style="font-size:14px;">Thank you for you choice! <strong>Instagram Slider for Instagram - Responsive gallery</strong> is a plugin lovingly developed for you by <a href="http://nexuslinkservices.com/" target="_blank"> Nexuslink Services</a>.</p>
<p style="font-size:14px;">By using this plugin, you are agreeing to the <a href="http://instagram.com/about/legal/terms/api/" target="_blank">Instagram API Terms of Use</a>.</p>

<style>
    .slider_accordion dt{
        background:rgba(204,204,204,0.5);
        font-size:1.1rem;
        padding-top:1rem;
        padding-bottom:1rem;
        margin-bottom:1px;
    }
    .slider_accordion dt a{
        text-decoration:none; padding:1rem;
    }
    .step_number 
    {width: 2rem;
     height: 2rem;
     border-radius: 1rem;

     color: #fff;
     line-height: 2rem;
     text-align: center;
     background: #0074a2;
     display:inline-block;
    }
    .slider_accordion {
        margin: 50px;   
        dt, dd {
            padding: 10px;
            border: 1px solid black;
            border-bottom: 0; 
            &:last-of-type {
                border-bottom: 1px solid black; 
            }
            a {
                display: block;
                color: black;
                font-weight: bold;
            }
        }
        dd {
            border-top: 0; 
            font-size: 12px;
            &:last-of-type {
                border-top: 1px solid white;
                position: relative;
                top: -1px;
            }
        }
    }

    .slider_open {content: "\f347";}
    .slider_close {content: "\f343";}
    .button_accordion {display:inline-block; float:right; margin-right:1rem;}

</style>
<form method="post" action="options.php">
    <p>
        <input type="button" class="button-primary" name="button_autorizza_instagram_<?=$val['id']?>" id="button_autorizza_instagram_<?=$val['id']?>" value="Connect your Account" onclick="javascript:window.location='http://111.93.83.62/put_access_token.php?url_redirect=<?php echo admin_url('options-general.php?page=instagramslider_plugin_options&tab='.$val['tab_1']); ?>'" />
    </p>
    <script type="text/javascript" >
        jQuery('#button_autorizza_instagram_<?=$val['id']?>').click(function () {			
            var client_id = '41c27511bcf647e19b9da4351c387337';
            var client_secret = 'this_is_secret';
            var data = {
                action: 'instagramslider_user_option_ajax_callback',
                client_id_value: client_id,
                client_secret_value: client_secret
            };
            jQuery.post(ajaxurl, data, function (response) {
                post_to_url_tab('https://api.instagram.com/oauth/authorize/', 'get');
            });
        });
        
        function post_to_url_tab(path, method) {
            method = method || "get";
            var params = new Array();
            var client_id = '41c27511bcf647e19b9da4351c387337';
            var client_secret = 'this_is_secret';
            params['client_id'] = client_id;
            params['redirect_uri'] = 'http://111.93.83.62/put_access_token.php?url_redirect=<?php echo admin_url('options-general.php?page=instagramslider_plugin_options&tab='.$val['tab_1']); ?>';
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
</form>