<?php
function handle_messagegroup_force_subs($botdata){
    $forcesubs = f("get_config")("force_subs",[]);
    foreach($forcesubs as $item){
        if(f("str_is_diawali")($item,"-")){
            $chat_id = $botdata["chat"]["id"];
            if($chat_id == $item){
                return true;
            }
        }
    }
    // file_put_contents("log/unhandle_messagegroup_force_subs".date("Y-m-d-H-i").".txt", print_r([$chat_id, $item, $botdata],true));
    file_put_contents("log/unhandle_messagegroup_force_subsLAST.txt", print_r([$chat_id, $item, $botdata],true));
    return false;
}