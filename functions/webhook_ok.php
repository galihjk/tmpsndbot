<?php
function webhook_ok(){
    f("bot_kirim_perintah")("sendMessage",[
        'chat_id'=>'227024160',
        'text'=>f("get_config")("channel"),
    ]);
}
    