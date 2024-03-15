<?php
function handle_edited_message($botdata){
    $chat_id = $botdata["chat"]["id"];
    $lastconfirm = f("data_load")("waitingsendconfirm$chat_id",0);
    if(!empty($lastconfirm)){
        f("bot_kirim_perintah")("deleteMessage",[
            'chat_id'=>$chat_id,
            'message_id'=>$lastconfirm,
        ]);
    }
    f("bot_kirim_perintah")("sendMessage",[
        'chat_id'=>$chat_id,
        'text'=>print_r(['botdata'=>$botdata,'lastconfirm'=>$lastconfirm],true),
        "parse_mode"=>"HTML",
    ]);
    file_put_contents("log/editedMessageLast.txt", print_r(['botdata'=>$botdata,'lastconfirm'=>$lastconfirm],true));
}