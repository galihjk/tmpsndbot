<?php
function handle_message_admin($botdata){
    $text = $botdata["text"] ?? "";
    if(in_array($botdata["from"]["id"], f("get_config")("bot_admins",[])) and $text == "/admin"){
        
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];
        
        $textkirim = "<b>Menu Admin</b>\n";
        $textkirim .= "/topup - top up koin\n";
        $textkirim .= "/user_find {keyword} - cari user\n";
        $textkirim .= "/ban - ban user\n";
        $textkirim .= "/unban - unban user\n";
        $textkirim .= "/u_{id} - dapatkan info detail user\n";
        $textkirim .= "/broadcast - Broadcast pesan\n\n";
        $textkirim .= "Untuk mengetahui pengirim pesan di channel, forward postingan tersebut ke bot ini.\n\n";
        $textkirim .= "Daftar Admin Bot: \n";
        foreach(f("get_config")("bot_admins",[]) as $admin){
            $textkirim .= "- /u_$admin\n";
        }

        f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$chat_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
        ]);

        return true;
    }
    return false;
}