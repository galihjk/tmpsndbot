<?php
function cek_sudah_subscribe($userid){
    $channel = f("get_config")("channel");
    $force_subs = f("get_config")("force_subs",[]);
    $user = f("get_user")($userid);
    if(
        // jika bukan admin
        !in_array($userid,f("get_config")("bot_admins",[]))

        // tidak dipakai:
        // empty($user['bot_active']) or
        // (!empty($user['bot_active']) and date("YmdH") != date("YmdH",strtotime($user['bot_active'])))
    ){
        $harusjoin = [];
        foreach($force_subs as $forcesubid){
            $getChatMember = f("bot_kirim_perintah")("getChatMember",[
                'chat_id'=>$forcesubid,
                'user_id'=>$userid,
            ]);
            if(empty($getChatMember["result"]["status"])){
                foreach(f("get_config")("bot_admins",[]) as $chatidadmin){
                    f("bot_kirim_perintah")("sendMessage",[
                        'chat_id'=>$chatidadmin,
                        'text'=>"Tolong masukkan saya ke $forcesubid untuk bisa mengecek apakah $userid sudah join/subscribe atau belum.",
                    ]);
                };
                file_put_contents("log/Last Error empty status1.txt",print_r([$getChatMember, $userid, $user],true));
                die("Error empty status");
            }
            if(in_array($getChatMember["result"]["status"],["restricted","left","kicked"])){
                $chatinfo = f("bot_kirim_perintah")("getChat",[
                    "chat_id"=>$forcesubid,
                ]);
                if(!empty($chatinfo['result']['username'])){
                    $harusjoin[] = "@" . $chatinfo['result']['username'];
                }
                elseif(!empty($chatinfo['result']['title'])){
                    $harusjoin[] = $chatinfo['result']['title'];
                }
                else{
                    f("bot_kirim_perintah")("sendMessage",[
                        "chat_id"=>$userid,
                        "text"=>"Maaf, error! \n".print_r($chatinfo,true),
                    ]);
                    return false;
                }
            }
        }
        // file_put_contents("harus join $userid.txt",print_r([$getChatMember, $userid, $user,$harusjoin],true));
        if(empty($harusjoin)){
            return true;
        }
        else{
            $textkirim = "Join ke sini dulu yaa:\n";
            foreach($harusjoin as $item){
                $textkirim .= "- $item\n";
            }
            $textkirim .= "\nAbis itu ke sini lagi.. :D \n/start";
            f("bot_kirim_perintah")("sendMessage",[
                "chat_id"=>$userid,
                "text"=>$textkirim,
            ]);
            return false;
        }
    }
    return true;
}