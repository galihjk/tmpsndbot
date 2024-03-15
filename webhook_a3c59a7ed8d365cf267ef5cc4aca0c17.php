<?php
include("init.php");
try {
    $jenis_update = [
        "message",
        "edited_message",
        "callback_query",
        "my_chat_member",
    ];

    f("handle_update_sesuai_jenis")($jenis_update);

    f("db_disconnet")();
}
catch (Exception $e) {
    foreach(f("get_config")("bot_admins",[]) as $chatidadmin){
        f("bot_kirim_perintah")("sendMessage",[
            'chat_id'=>$chatidadmin,
            'text'=>$e->getMessage(),
        ]);
    };
    file_put_contents("log/Last Error PHP.txt",print_r($e,true));
}