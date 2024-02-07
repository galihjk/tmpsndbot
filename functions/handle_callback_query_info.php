<?php
function handle_callback_query_info($botdata){
    if(!empty($botdata["data"]) 
    and $botdata["data"] == "info"
    and !empty($botdata["message"])
    ){
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
        ]);
        $pesan_max = f("get_config")("pesan_max",0);
        $pesan_max_vip = f("get_config")("pesan_max_vip",0);
        $media_max = f("get_config")("media_max",0);
        $media_max_vip = f("get_config")("media_max_vip",0);
        $pesan_cost = f("get_config")("pesan_cost",0);
        $media_cost = f("get_config")("media_cost",0);

        $pesan_minchar = f("get_config")("pesan_minchar",0);
        $pesan_maxchar = f("get_config")("pesan_maxchar",0);
        $pesan_maxchar_vip = f("get_config")("pesan_maxchar_vip",0);

        $pin_cost = f("get_config")("pin_cost",0);
        $cost_vip = f("get_config")("cost_vip",0);

        $delay = f("get_config")("delay",0);
        
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        $textkirim = "";
        $textkirim .= "<b>Kuota Gratis Harian</b>\n";
        $textkirim .= "Pesan: $pesan_max ($pesan_max_vip untuk 🎖PREMIUM)\n";
        $textkirim .= "Media: $media_max ($media_max_vip untuk 🎖PREMIUM)\n";
        $textkirim .= "Jika batas kuota harian habis maka akan membutuhkan biaya 🪙Koin tiap pengiriman.\n\n";
        $textkirim .= "<b>Biaya</b>\n";
        $textkirim .= "Pesan: $pesan_cost 🪙Koin\n";
        $textkirim .= "Media: $media_cost 🪙Koin\n";
        $textkirim .= "📌PIN: $pin_cost 🪙Koin\n\n";
        $textkirim .= "Minimal Karakter: $pesan_minchar\n";
        $textkirim .= "Maksimal Karakter: $pesan_maxchar ($pesan_maxchar_vip untuk 🎖PREMIUM)\n";
        $textkirim .= "Jeda pengiriman: $delay detik\n\n";
        $textkirim .= "Biaya 🎖PREMIUM 1 bulan: $cost_vip 🪙\n\n";
        $textkirim .= "Format pengiriman pesan:\n======\n";
        $blablabla = "{pesan}";
        foreach (f("get_config")("resend_prefixes",[]) as $prefix){
            $textkirim .= "<pre>$prefix$blablabla</pre>\n======\n";
        }
        foreach (f("get_config")("resend_contains",[]) as $conval){
            $textkirim .= "<pre>$blablabla$conval$blablabla</pre>\n======\n";
        }
        foreach (f("get_config")("resend_suffixes",[]) as $suffix){
            $textkirim .= "<pre>$blablabla$suffix</pre>\n======\n";
        }
        $textkirim .= "\n<i>Aturan mungkin bisa berubah sewaktu-waktu</i>";
        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$textkirim,//."\n\n".f("str_decrypt")("felix"),
            "parse_mode"=>"HTML",
            "disable_web_page_preview"=>true,
            'reply_markup'=>f("gen_inline_keyboard")([
                ['⬅️ Kembali', 'home']
            ]),
        ]);
        return true;
    }
    return false;
}