<?php
function check_word_filter($text, $from){
    $wordfilter = strtolower(f("get_config")("wordfilter",""));
    $wordfilterarr = explode(",",strtolower($wordfilter));
    $text = strtolower($text);
    foreach($wordfilterarr as $item){
        if($item != ""){
            if(f("str_contains")($text,$item)){
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$from,
                    'text'=>"Gagal, pesan tidak boleh mengandung kata '$item'.",
                    "parse_mode"=>"HTML",
                ]);
                return false;
            }
        }
    }
    /*
    $text = strtolower($text);
    $text1 = preg_replace('/[^a-zA-Z]/', " ", $text);
    foreach($wordfilterarr as $item){
        if(f("str_contains")(" $text1 ", " $item ")
        or f("str_contains")(" $text ", " $item ")
        ){
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$from,
                'text'=>"Gagal, pesan tidak boleh mengandung kata '$item'.",
                "parse_mode"=>"HTML",
            ]);
            return false;
        }
    }
    */
    return true;
}