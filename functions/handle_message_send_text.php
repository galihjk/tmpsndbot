<?php
function handle_message_send_text($botdata, $templateKey){
    $text = $botdata["text"] ?? "";
    $chat_id = $botdata["chat"]["id"];
    $data_user = f("get_user")($botdata["from"]["id"]);
    $free_msg_used = $data_user['free_msg_used'] ?? 0;
    if(empty($data_user['vip_until'])){
        $pesan_max = f("get_config")("pesan_max",0);
    }
    else{
        $pesan_max = f("get_config")("pesan_max_vip",0);
    }

    $success_text = "";
    $sent_message_id = "";
    if($free_msg_used < $pesan_max){
        $free_msg_used++;
        f("db_q")("update users set free_msg_used = $free_msg_used where id = '$chat_id'");
        // $channelpost = f("post_text_to_channel")($chat_id,$text);
        $channelpost = f("post_to_channel")($botdata, 'text', $templateKey);
        if(!empty($channelpost['result']['message_id'])){
            $sent_message_id = $channelpost['result']['message_id'];
            $success_text = "<b>Berhasil!</b>\nSisa kuota gratis: ".($pesan_max-$free_msg_used);
        }
    }
    else{
        $biaya = f("get_config")("pesan_cost",0);
        $coin = $data_user['coin'] ?? 0;
        if($coin >= $biaya){
            $coin -= $biaya;
            f("db_q")("update users set coin=$coin where id='".$data_user['id']."'");
            // $channelpost = f("post_text_to_channel")($chat_id,$text);
            $channelpost = f("post_to_channel")($botdata, 'text', $templateKey);
            if(!empty($channelpost['result']['message_id'])){
                $sent_message_id = $channelpost['result']['message_id'];
                $success_text = "<b>Berhasil!</b>\nBiaya: $biaya 🪙\nSisa: $coin 🪙";                        
            }
        }
        else{
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>"Gagal, jatah gratis sudah habis dan koin tidak cukup.",
                "parse_mode"=>"HTML",
                //"reply_to_message_id"=>$botdata["message"]["message_id"],
            ]);
        }
    }
    if(!empty($success_text) and !empty($sent_message_id)){
        $channelurl = f("channel_url")("/$sent_message_id");
        f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$chat_id,
            'text'=>$success_text,
            "parse_mode"=>"HTML",
            'reply_markup'=>f("gen_inline_keyboard")([
                ['🔗 Lihat Pesan', $channelurl,2],
                ["📌 PIN Pesan", 'pin_'.$sent_message_id,1],
                ["Cek Biaya 📌PIN ", 'pin_harga',1],
            ]),
        ]);
    }
    return true;
}