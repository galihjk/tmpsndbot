<?php
function handle_callback_query_admbroadcast($botdata){
    if(!empty($botdata["data"]) 
    and f("str_is_diawali")($botdata["data"], "broadcast_")
    and !empty($botdata["message"])
    ){
        $explode = explode("_",$botdata["data"]);
        $jml = $explode[1];
        $msgid = $explode[2];
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
            'text' => "Proses broadcast dimulai",
            'show_alert' => true,
        ]);
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];
        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"Proses broadcast sedang berlangsung. Harap tunggu sampai selesai.",
            "parse_mode"=>"HTML",
        ]);
        $users = f("db_q")("select id from users where banned is null or banned=false and bot_active is not null order by bot_active desc limit $jml");
        foreach($users as $user){
            f("bot_kirim_perintah")("copyMessage",[
                'from_chat_id'=>$chat_id,
                'chat_id'=>$user['id'],
                'message_id'=>$msgid,
            ]);
        }
        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"Proses broadcast selesai!",
            "parse_mode"=>"HTML",
        ]);
        /*
        $message_id = $botdata["message"]["message_id"];
        $chat_id = $botdata["message"]["chat"]["id"];

        $explode = explode("_",$botdata["data"]);
        $usertopupid = $explode[1];
        $topupnominal = $explode[2];

        $usertopup = f("get_user")($usertopupid);
        $usercoin = $usertopup['coin'] ?? 0;
        $usercoin += $topupnominal;

        f("db_q")("update users set coin=$usercoin where id='$usertopupid'");

        $textkirim = "<b>TOP UP BERHASIL!</b>\n";
        $textkirim .= "ID: ".$usertopupid;
        $textkirim .= "\nNama: ".$usertopup["first_name"] . (empty($usertopup["first_name"]) ? '' : "(@".$usertopup["username"]." )");
        $textkirim .= "\nNominal Koin: ".number_format($topupnominal)." 🪙";
        $textkirim .= "\nTanggal: ".date("Y-m-d H:i:s");

        f("bot_kirim_perintah")("editMessageText",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
        ]);
        
        f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$usertopupid,
            'text'=>$textkirim."\nSaldo: $usercoin 🪙",
            "parse_mode"=>"HTML",
        ]);
        */
        return true;
    }
    return false;
}