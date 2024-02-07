<?php
function handle_messagegroup_comments($botdata){
    if($botdata["chat"]["id"] == f("get_config")("groupdisc")){
        // file_put_contents("log/groupdisc".date("Y-m-d-H-i").".txt", print_r($botdata,true));
        $text = $botdata["text"] ?? "";
        if($text){
            $reply_to_message = $botdata['reply_to_message'];
            if(!empty($reply_to_message["caption_entities"])){
                $entities = $reply_to_message["caption_entities"];
            }
            else{
                $entities = $reply_to_message['entities'] ?? [];
            }
            
            $reply_to_message_id = $reply_to_message['forward_from_message_id'] ?? [];
            $oleh = "";
            // file_put_contents("log/groupdiscuserentities".date("Y-m-d-H-i").".txt", print_r([$reply_to_message, $reply_to_message['entities'], $entities],true));
            foreach($entities as $entity){
                if($entity['type'] == "text_link"){
                    $botuname = f("get_config")("botuname","");
                    // file_put_contents("log/groupdiscuser".date("Y-m-d-H-i").".txt", print_r([$entity['url'],str_replace("https://t.me/$botuname?start=","",$entity['url']),f("str_decrypt")(str_replace("https://t.me/$botuname?start=","",$entity['url']),true)],true));
                    $oleh = f("str_decrypt")(str_replace("https://t.me/$botuname?start=","",$entity['url']),true);
                    break;
                }
            }
            if(!empty($botdata['from']['username']) and strtolower($botdata['from']['username']) == strtolower('GroupAnonymousBot')){
                $komentator = f("get_config")("channel");
            }
            else{
                $komentator = $botdata['from']['first_name'] . (empty($botdata['from']['username'])?'':" (@".$botdata['from']['username'].")");
            }
            code:
            $komentator = str_replace("<","&lt;",$komentator);
            if(empty($reply_to_message_id)) return true;
            $url = f("channel_url")("/$reply_to_message_id?comment=".$botdata['message_id']);
            if($oleh){
                f("bot_kirim_perintah")("sendMessage",[
                    "chat_id"=>$oleh,
                    "text"=>"$komentator memberikan <a href='$url'>komentar</a> untuk mu.",
                    "parse_mode"=>"HTML",
                ]);
            }
            return true;
        }
        // file_put_contents("log/unhandle_messagegroup_comments".date("Y-m-d-H-i").".txt", print_r($botdata,true));
        file_put_contents("log/unhandle_messagegroup_commentsLAST.txt", print_r($botdata,true));
        return true;
    }
    return false;
}