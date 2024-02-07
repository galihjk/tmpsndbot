<?php
function handle_message_start($botdata){
    $text = $botdata["text"] ?? "";
    if(f("str_is_diawali")($text,"/start")){
        
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        $datakirim = f("pesan_utama")(
            ["chat_id"=>$chat_id],
            $botdata["from"]["id"]
        );
        f("bot_kirim_perintah")("sendMessage",$datakirim);

        return true;
    }
    return false;
}