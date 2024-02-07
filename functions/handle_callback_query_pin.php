<?php
function handle_callback_query_pin($botdata){
    if(!empty($botdata["data"]) 
    and f("str_is_diawali")($botdata["data"], "pin_")
    and !empty($botdata["message"])
    ){
        $pin_cost = f("get_config")("pin_cost",0);
        if($botdata["data"] == "pin_harga"){
            f("bot_kirim_perintah")('answerCallbackQuery',[
                'callback_query_id' => $botdata['id'],
                'text' => "Biaya 📌PIN saat ini: $pin_cost 🪙 untuk 24 jam, cek @rulespin",
                'show_alert' => true,
            ]);
        }
        else{
            $data_user = f("get_user")($botdata["from"]["id"]);
            $coin = $data_user['coin'] ?? 0;
            if($coin < $pin_cost){
                f("bot_kirim_perintah")('answerCallbackQuery',[
                    'callback_query_id' => $botdata['id'],
                    'text' => "Koin tidak cukup! \nKoin Anda: $coin 🪙 \nBiaya: $pin_cost 🪙",
                    'show_alert' => true,
                ]);
            }
            else{
                f("bot_kirim_perintah")('answerCallbackQuery',[
                    'callback_query_id' => $botdata['id'],
                ]);
                
                $coin -= $pin_cost;
                f("db_q")("update users set coin=$coin where id='".$data_user['id']."'");
                
                $pinmsgid = explode("_",$botdata["data"])[1];
                f("bot_kirim_perintah")("pinChatMessage",[
                    'chat_id'=>f("get_config")("channel"),
                    'message_id'=>$pinmsgid,
                    'disable_notification'=>false,
                ]);

                $chat_id = $botdata["message"]["chat"]["id"];
                $message_id = $botdata["message"]["message_id"];
                $channelurl = f("channel_url")("/$pinmsgid");
                $new_job_unpin = f("jobadd_unpin")($pinmsgid)['time'];
                $pin_until = date("Y-m-d H:i:s", $new_job_unpin);
                
                $textkirim = "<a href='$channelurl'>📌PINNED!</a>\nBiaya: $pin_cost 🪙\nSisa: $coin 🪙\nSampai: $pin_until";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'message_id'=>$message_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                    "disable_web_page_preview"=>false,
                ]);
            }
        }
        return true;
    }
    return false;
}