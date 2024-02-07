<?php
function handle_callback_query_home($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "home"
    and !empty($botdata["message"])
    ){
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
        ]);
        $chat_id = $botdata["message"]["chat"]["id"];
        $message_id = $botdata["message"]["message_id"];
        $datakirim = f("pesan_utama")(
            ["chat_id"=>$chat_id, 'message_id'=>$message_id],
            $botdata["from"]["id"]
        );

        f("bot_kirim_perintah")("editMessageText",$datakirim);
        return true;
    }
    return false;
}