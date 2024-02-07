<?php
function handle_callback_query_profil($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "profil"
    and !empty($botdata["message"])
    ){
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
        ]);
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        $data_user = f("get_user")($botdata["from"]["id"]);
        
        $textkirim = "<b>Detail Akun</b>\n";
        $textkirim .= "ID Anda: <pre>".$data_user['id']."</pre>\n\n";
        $textkirim .= "🎖PREMIUM:\n".(empty($data_user['vip_until']) ? '❌ Tidak': '✅YA (Sampai '.$data_user['vip_until'].')')."\n\n";
        $textkirim .= "Koin: ".number_format($data_user['coin']??'0')."🪙\n\n";
        $textkirim .= "<b>Kuota Gratis Harian dan Biaya</b>\n";

        $free_msg_used = $data_user['free_msg_used'] ?? 0;
        $free_media_used = $data_user['free_media_used'] ?? 0;
        if(empty($data_user['vip_until'])){
            $pesan_max = f("get_config")("pesan_max",0);
            $media_max = f("get_config")("media_max",0);
        }
        else{
            $pesan_max = f("get_config")("pesan_max_vip",0);
            $media_max = f("get_config")("media_max_vip",0);
        }
        $sisa_pesan = $pesan_max - $free_msg_used;
        $sisa_media = $media_max - $free_media_used;

        $pesan_cost = f("get_config")("pesan_cost",20);
        $media_cost = f("get_config")("media_cost",100);

        $textkirim .= "Pesan: $sisa_pesan/$pesan_max ( $pesan_cost Koin )\n";
        $textkirim .= "Media: $sisa_media/$media_max ( $media_cost Koin )\n";
        $textkirim .= "<i>Jika batas kuota harian habis maka akan membutuhkan biaya koin menfess tiap pengiriman</i>";

        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
            'reply_markup'=>f("gen_inline_keyboard")([
                ['🎖 Pengguna PREMIUM', 'vip'],
                ['💰 Top Up', 'topup'],
                ['⬅️ Kembali', 'home'],
            ]),
        ]);
        return true;
    }
    return false;
}