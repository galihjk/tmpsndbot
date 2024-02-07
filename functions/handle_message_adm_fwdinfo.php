<?php
function handle_message_adm_fwdinfo($botdata){
    if(!empty($botdata["forward_from_chat"]["username"])
    and $botdata["forward_from_chat"]["username"] = str_replace("@","",f("get_config")("channel"))
    and in_array($botdata["from"]["id"], f("get_config")("bot_admins",[]))
    ){
        
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        $entities = [];
        if(!empty($botdata["caption_entities"])){
            $entities = $botdata["caption_entities"];
        }
        elseif(!empty($botdata["entities"])){
            $entities = $botdata["entities"];
        }
        else{
            return false;
        }

        $oleh = "";
        foreach($entities as $entity){
            if($entity['type'] == "text_link"){
                $botuname = f("get_config")("botuname","");
                $oleh = f("str_decrypt")(str_replace("https://t.me/$botuname?start=","",$entity['url']),true);
                break;
            }
        }
        
        $textkirim = "/u_$oleh";

        f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$chat_id,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
            "reply_to_message_id"=>$botdata["message_id"],
        ]);

        return true;
    }
    return false;
}