<?php
function handle_callback_query_kirim($botdata){
    if(!empty($botdata["data"]) 
    and f("str_is_diawali")($botdata["data"], "kirim_")
    and !empty($botdata["message"]["reply_to_message"])
    ){
        $lastconfirm = f("data_load")("waitingsendconfirm$chat_id",0);
        if(!$lastconfirm){
            f("bot_kirim_perintah")('answerCallbackQuery',[
                'callback_query_id' => $botdata['id'],
                'text' => "GAGAL! \nMohon maaf, telah terjadi kesalahan. Silakan coba lagi.",
                'show_alert' => true,
            ]);
            return true;
        }
        $datakirim = $botdata["message"]["reply_to_message"];
        $jenis = str_replace("kirim_","",$botdata["data"]);
        $explode = explode("_",$jenis);
        $templateKey = $explode[count($explode)-1];
        $jenis = substr($jenis,0,-strlen("_$templateKey"));
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
            'text' => "SIAP!",
            'show_alert' => false,
        ]);
        $chat_id = $botdata["message"]["chat"]["id"];
        $message_id = $botdata["message"]["message_id"];
        $result = f("bot_kirim_perintah")("deleteMessage",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
        ]);
        if(!empty($result['ok'])){
            if($jenis == "text"){
                return f("handle_message_send_text")($datakirim, $templateKey);
            }
            else{
                $explode = explode("_",$jenis);
                if(!empty($explode[1])){
                    $jenis = $explode[0];
                    $fileid = $explode[1];
                    if(empty($jenis) or empty($fileid)){
                        return false;
                    }
                    else{
                        return f("handle_message_send_media")($datakirim, $jenis, $templateKey, $fileid);
                    }
                }
                else{
                    return false;
                }
            }
        }
        f("data_delete")("waitingsendconfirm$chat_id",0);
        return true;
    }
    elseif(!empty($botdata["data"]) 
    and $botdata["data"] == "kirimbatal"
    ){
        $datakirim = $botdata["message"]["reply_to_message"];
        $jenis = str_replace("kirim_","",$botdata["data"]);
        f("bot_kirim_perintah")('answerCallbackQuery',[
            'callback_query_id' => $botdata['id'],
            'text' => "Dibatalkan!",
            'show_alert' => false,
        ]);
        $chat_id = $botdata["message"]["chat"]["id"];
        $message_id = $botdata["message"]["message_id"];
        $result = f("bot_kirim_perintah")("deleteMessage",[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
        ]);
        f("data_delete")("waitingsendconfirm$chat_id",0);
        return true;
    }
    return false;
}