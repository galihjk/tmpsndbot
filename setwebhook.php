<?php
include("init.php");
echo "<pre>";
print_r(
    f("bot_kirim_perintah")("getWebhookInfo",[])
);
echo $webhook = "https://".$_SERVER['SERVER_NAME'] .f("get_config")("webhook");
print_r(
    f("bot_kirim_perintah")("setWebhook",[
        'url'=>$webhook,
        'drop_pending_updates'=>true,
    ])
);
echo "</pre>";