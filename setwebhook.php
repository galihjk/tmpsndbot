<?php
include("init.php");
echo "<pre>";
print_r(
    ['before'=>f("bot_kirim_perintah")("getWebhookInfo",[])]
);
echo $webhook = "https://".$_SERVER['SERVER_NAME'] .f("get_config")("webhook");
print_r(
    f("bot_kirim_perintah")("setWebhook",[
        'url'=>$webhook,
        'drop_pending_updates'=>true,
        'allowed_updates'=>json_encode(include("jenis_update.php")),
    ])
);
print_r(
    ['after'=>f("bot_kirim_perintah")("getWebhookInfo",[])]
);
f("webhook_ok")();
echo "</pre>";