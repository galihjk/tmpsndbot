<?php
function handle_callback_query_vip($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "vip"
    and !empty($botdata["message"])
    ){
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
        ]);
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        $data_user = f("get_user")($botdata["from"]["id"]);

        $cost_vip = f("get_config")("cost_vip",10000);
        $pesan_max = f("get_config")("pesan_max",0);
        $pesan_max_vip = f("get_config")("pesan_max_vip",0);
        $media_max = f("get_config")("media_max",0);
        $media_max_vip = f("get_config")("media_max_vip",0);
        $pesan_maxchar = f("get_config")("pesan_maxchar",0);
        $pesan_maxchar_vip = f("get_config")("pesan_maxchar_vip",0);
        
        $textkirim = "<b>Fitur 🎖PREMIUM:</b>\n\n";
        $textkirim .= "<b>Kuota Gratis Harian</b>\n";
        $textkirim .= "Pesan: <s>$pesan_max</s> ➡️ <b>$pesan_max_vip</b> ✅\n";
        $textkirim .= "Media: <s>$media_max</s> ➡️ <b>$media_max_vip</b>  ✅\n\n";
        $textkirim .= "Maksimal karakter pesan: <s>$pesan_maxchar</s> ➡️ <b>$pesan_maxchar_vip</b> ✅\n\n";
        $textkirim .= "<b>Biaya untuk 1 bulan</b>: $cost_vip 🪙Koin\n";

        $buttons = [];
        if(empty($data_user['vip_until'])){
            $buttons[] = ['✅ Beli 🎖PREMIUM', 'vipbeli'];
        }
        else{
            $textkirim .= "\n✅ ANDA ADALAH PENGGUNA 🎖PREMIUM HINGGA: \n".$data_user['vip_until'];
        }
        $buttons[] = ['⬅️ Kembali', 'profil'];
        $buttons[] = ['🏠 Menu Utama', 'home'];

        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
            'reply_markup'=>f("gen_inline_keyboard")($buttons),
        ]);
        return true;
    }
    return false;
}