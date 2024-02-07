<?php
function post_text_to_channel($from, $text){
    if(f("check_word_filter")($text, $from)){
        $botuname = f("get_config")("botuname","");
        $channel = f("get_config")("channel","");
        $sender_encrypt = f("str_encrypt")("$from",true);
        $textkirim = $text."<a href='https://t.me/$botuname?start=$sender_encrypt'> 󠀠 </a>";
        // $last_send = f("str_dbtime")();
        // f("db_q")("update users set last_send=$last_send where id='$from'");
        // file_put_contents('log/debug3.txt',"update users set last_send=$last_send where id='$from'");
        return f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$channel,
            'text'=>$textkirim,
            "parse_mode"=>"HTML",
            "disable_web_page_preview"=>true,
        ]);
    }
    else{
        return false;
    }
}