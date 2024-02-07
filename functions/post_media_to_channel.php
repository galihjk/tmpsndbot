<?php
function post_media_to_channel($from, $caption, $type, $fileid){
    if(f("check_word_filter")($caption, $from)){
        $botuname = f("get_config")("botuname","");
        $channel = f("get_config")("channel","");
        $sender_encrypt = f("str_encrypt")("$from",true);
        $caption = $caption."<a href='https://t.me/$botuname?start=$sender_encrypt'> 󠀠 </a>";
        return f("bot_kirim_perintah")("send$type",[
            'chat_id'=>$channel,
            'caption'=>$caption,
            "parse_mode"=>"HTML",
            "disable_web_page_preview"=>true,
            $type => $fileid,
        ]);
    }
    else{
        return false;
    }
}