<?php
function handle_message_send_media($botdata, $jenis, $templateKey, $fileid){
    if(empty($jenis) or empty($fileid)){
        return false;
    }
    $caption = $botdata["caption"] ?? "";
    
    $chat_id = $botdata["chat"]["id"];
    $data_user = f("get_user")($botdata["from"]["id"]);

    if(empty($data_user['vip_until'])){
        $media_max = f("get_config")("media_max",0);
    }
    else{
        $media_max = f("get_config")("media_max_vip",0);
    }

    $free_media_used = $data_user['free_media_used'] ?? 0;

    $success_text = "";
    $sent_message_id = "";
    if($free_media_used < $media_max){
        $free_media_used++;
        f("db_q")("update users set free_media_used = $free_media_used where id = '$chat_id'");
        // $channelpost = f("post_media_to_channel")($chat_id,$caption,$jenis,$fileid);
        $channelpost = f("post_to_channel")($botdata, $jenis, $templateKey, $fileid);
        if(!empty($channelpost['result']['message_id'])){
            $sent_message_id = $channelpost['result']['message_id'];
            $success_text = "<b>Berhasil!</b>\nSisa kuota gratis: ".($media_max-$free_media_used);
        }
    }
    else{
        $biaya = f("get_config")("media_cost",0);
        $coin = $data_user['coin'] ?? 0;
        if($coin >= $biaya){
            $coin -= $biaya;
            f("db_q")("update users set coin=$coin where id='".$data_user['id']."'");
            // $channelpost = f("post_media_to_channel")($chat_id,$caption,$jenis,$fileid);
            $channelpost = f("post_to_channel")($botdata, $jenis, $templateKey, $fileid);
            if(!empty($channelpost['result']['message_id'])){
                $sent_message_id = $channelpost['result']['message_id'];
                $success_text = "<b>Berhasil!</b>\nBiaya: $biaya 🪙\nSisa: $coin 🪙";                        
            }
        }
        else{
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>"Gagal, tidak ada jatah gratis dan koin tidak cukup.",
                "parse_mode"=>"HTML",
                "reply_to_message_id"=>$botdata["message"]["message_id"],
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
        return true;
    }
    return false;
}