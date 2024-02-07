<?php
function handle_message($botdata){
    if(!empty($botdata["from"]["first_name"])){
        $botdata["from"]["first_name"] = str_replace("<", "&lt;", $botdata["from"]["first_name"]);
    }
    if(!empty($botdata["from"]["last_name"])){
        $botdata["from"]["last_name"] = str_replace("<", "&lt;", $botdata["from"]["last_name"]);
    }
    if(f("is_private")($botdata)){
        $chat_id = $botdata["chat"]["id"];
        if(f("cek_sudah_subscribe")($chat_id)){
            $banned = false;
            if(!empty($botdata["from"])){
                $userdata = f("get_user")($chat_id);
                if(!empty($userdata["banned"])){
                    f("bot_kirim_perintah")("sendMessage",[
                        "chat_id"=>$chat_id,
                        "text"=>"Your user account ($chat_id) is banned. Please contact administrator.",
                    ]);
                    $banned = true;
                }
                else{
                    f("update_user")($botdata["from"]);
                }
            }
            if(!$banned){
                f("handle_botdata_functions")($botdata,[
                    "handle_message_adm_fwdinfo",
                    "handle_message_start",
                    // "handle_message_send_text",
                    // "handle_message_send_media",
                    "handle_message_resend",
                    "handle_message_admin",
                    "handle_message_adm_topup",
                    "handle_message_adm_user",
                    "handle_message_adm_ban",
                    "handle_message_adm_broadcast",
                    "handle_message_fail",
                ]);
            }
        }
    }
    elseif(!f("handle_botdata_functions")($botdata,[
        "handle_messagegroup_comments",
        "handle_messagegroup_force_subs",
        "handle_messagegroup_others",
    ])){
        // file_put_contents("log/unhandleMsg".date("Y-m-d-H-i").".txt", print_r($botdata,true));
        file_put_contents("log/unhandleMsgLAST.txt", print_r($botdata,true));
    }
}