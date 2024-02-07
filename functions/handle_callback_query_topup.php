<?php
function handle_callback_query_topup($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "topup"
    and !empty($botdata["message"])
    ){
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
        ]);
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        $data_user = f("get_user")($botdata["from"]["id"]);
        
        $textkirim = "Silakan hubungi admin dan sampaikan bahwa id anda adalah: <pre>".$botdata["from"]["id"]."</pre>";

        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
            'reply_markup'=>f("gen_inline_keyboard")([
                ['⬅️ Kembali', 'profil'],
                ['🏠 Menu Utama', 'home'],
            ]),
        ]);
        return true;
    }
    return false;
}