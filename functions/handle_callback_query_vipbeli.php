<?php
function handle_callback_query_vipbeli($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "vipbeli"
    and !empty($botdata["message"])
    ){
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        $data_user = f("get_user")($botdata["from"]["id"]);

        $cost_vip = f("get_config")("cost_vip",10000);
        $coin = $data_user['coin'] ?? 0;
        if($coin >= $cost_vip){
            $coin -= $cost_vip;
            $vip_until = f("str_dbtime")(' + 1 month');
            f("db_q")("update users set coin=$coin, vip_until=$vip_until where id='".$data_user['id']."'");
            f("bot_kirim_perintah")('answerCallbackQuery',[
                'callback_query_id' => $botdata['id'],
                'text' => "Selamat! Anda telah menjadi pengguna 🎖PREMIUM!",
                'show_alert' => true,
            ]);
            $textkirim = "Berhasil!\n\n";
            $textkirim .= date("Y-m-d H:i:s")."\n\n";
            $textkirim .= "Biaya: $cost_vip 🪙Koin\n";
            $textkirim .= "Sisa: $coin 🪙Koin\n";
            $textkirim .= "🎖PREMIUM sampai : $vip_until\n";
            $buttons = [
                ['⬅️ Kembali', 'profil'],
                ['🏠 Menu Utama', 'home'],
            ];
            f("bot_kirim_perintah")("editMessageText",[
                'chat_id'=>$chat_id,
                'message_id'=>$message_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
                'reply_markup'=>f("gen_inline_keyboard")($buttons),
            ]);
        }
        else{
            f("bot_kirim_perintah")('answerCallbackQuery',[
                'callback_query_id' => $botdata['id'],
                'text' => "Koin tidak cukup! \nKoin Anda: $coin 🪙 \nBiaya: $cost_vip 🪙",
                'show_alert' => true,
            ]);
        }
        return true;
    }
    return false;
}