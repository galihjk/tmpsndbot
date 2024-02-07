<?php
function handle_message_adm_user($botdata){
    $text = $botdata["text"] ?? "";

    if(in_array($botdata["from"]["id"], f("get_config")("bot_admins",[]))){
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        if($text == "/user_find"){
            $textkirim = "Gunakan format /user_find(spasi){keyword}\ncontoh: <pre>/user_find galih</pre>";
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
            ]);
            return true;
        }
    
        if(f("str_is_diawali")($text,"/user_find ")){
            $find = f("str_dbq")("%".str_replace("/user_find ","",$text)."%");
            $q = "select id, first_name from users where 
            first_name like $find or last_name like $find or username like $find 
            order by first_name
            limit 100";
            $dbdata = f("db_q")($q);
            $textkirim = "Users:\n";
            foreach($dbdata as $item){
                $firstnameshort = explode(" ",$item["first_name"])[0];
                $textkirim .= "/u_".$item["id"]." $firstnameshort\n";
            }
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
            ]);
            return true;
        }

        if(f("str_is_diawali")($text,"/u_")){
            $userid = str_replace("/u_","",$text);
            $userdata = f("get_user")($userid);
            if($userdata){
                $textkirim = "User Data:\n<pre>$userid</pre>\n";
                $textkirim .= print_r($userdata,true);
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                ]);
            }
            else{
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>"pengguna [$userid] tidak ditemukan",
                    "parse_mode"=>"HTML",
                ]);
            }
            return true;
        }

    }


    return false;
}