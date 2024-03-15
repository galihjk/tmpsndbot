<?php
function handle_edited_message($botdata){
    $chat_id = $botdata["chat"]["id"];
    $lastconfirm = f("data_load")("waitingsendconfirm$chat_id",0);
    if(!empty($lastconfirm)){
        f("data_delete")("waitingsendconfirm$chat_id",0);
        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$lastconfirm,
            'text'=>"Pesan telah berubah (diedit), silakan kirim ulang.",
            "parse_mode"=>"HTML",
        ]);
    }
    // f("bot_kirim_perintah")("sendMessage",[
    //     'chat_id'=>$chat_id,
    //     'text'=>print_r(['botdata'=>$botdata,'lastconfirm'=>$lastconfirm],true),
    //     "parse_mode"=>"HTML",
    // ]);
    file_put_contents("log/editedMessageLast.txt", print_r(['botdata'=>$botdata,'lastconfirm'=>$lastconfirm],true));
}