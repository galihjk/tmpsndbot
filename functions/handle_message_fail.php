<?php
function handle_message_fail($botdata){
    $chat = $botdata["chat"];
    $chat_id = $chat["id"];
    $textkirim = "GAGAL, harap sesuaikan dengan format pengiriman. \ncek => /start";
    f("bot_kirim_perintah")("sendMessage",[
        "chat_id"=>$chat_id,
        "text"=>$textkirim,
        "parse_mode"=>"HTML",
    ]);
    file_put_contents("log/last_message_fail.txt",print_r($botdata,true));
    return true;
}