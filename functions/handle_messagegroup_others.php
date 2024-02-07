<?php
function handle_messagegroup_others($botdata){
    $chat_id = $botdata["chat"]["id"];
    if(!empty($chat_id)){
        f("bot_kirim_perintah")("sendMessage",[
            "chat_id"=>$chat_id,
            "text"=>"yuk, ke sini ==> ".f("get_config")("channel"),
        ]);
        f("bot_kirim_perintah")("leaveChat",[
            "chat_id"=>$chat_id,
        ]);
        return true;
    }
    return false;
}