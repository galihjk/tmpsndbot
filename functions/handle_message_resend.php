<?php
function handle_message_resend($botdata){
    if(empty($botdata["forward_date"])){
        $prefixes = f("get_config")("resend_prefixes",[]);
        $contains = f("get_config")("resend_contains",[]);
        $suffixes = f("get_config")("resend_suffixes",[]);
        $caption = $botdata["caption"] ?? "";
        if(!empty($caption)){
            if(!empty($botdata['animation'])){
                $jenis = "animation";
            }
            elseif(!empty($botdata['audio'])){
                $jenis = "audio";
            }
            elseif(!empty($botdata['document'])){
                $jenis = "document";
            }
            elseif(!empty($botdata['photo'])){
                $jenis = "photo";
            }
            elseif(!empty($botdata['video'])){
                $jenis = "video";
            }
            elseif(!empty($botdata['voice'])){
                $jenis = "voice";
            }
            else{
                return false;
            }
            $fileid = "";
            if(!empty($botdata[$jenis]['file_id'])){
                $fileid = $botdata[$jenis]['file_id'];
            }
            elseif(!empty($botdata[$jenis][0]['file_id'])){
                $fileid = $botdata[$jenis][0]['file_id'];
            }
            else{
                // file_put_contents("log/jenis_not_exists".date("Y-m-d-H-i").".txt", print_r($botdata,true));
                file_put_contents("log/jenis_not_existsLAST.txt", print_r($botdata,true));
            }
            $text = $caption;
        }
        else{
            $text = $botdata["text"] ?? "";
            if(empty($text)){
                return false;
            }
            $jenis = "text";
        }
        if($jenis != "text"){
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>"Fitur ini tidak tersedia untuk saat ini.",
                "parse_mode"=>"HTML",
            ]);
            return true;
        }
        $mathced = "";
        foreach($prefixes as $prefix){
            if(f("str_is_diawali")($text,$prefix)){
                $mathced = $prefix;
                break;
            }
        }
        if(!$mathced){
            foreach($contains as $conval){
                if(f("str_contains")($text,$conval)){
                    $mathced = $conval;
                    break;
                }                
            }
        }
        if(!$mathced){
            foreach($suffixes as $suffix){
                if(f("str_is_diakhiri")($text,$suffix)){
                    $mathced = $suffix;
                    break;
                }                
            }
        }
        if(!empty($mathced)){
            
            $chat_id = $botdata["chat"]["id"];
            $data_user = f("get_user")($botdata["from"]["id"]);

            $pesan_minchar = f("get_config")("pesan_minchar",0);
            $msgcharcount = strlen(str_replace($mathced, "", $text));

            $last_send = $data_user['last_send'] ?? null;
            $delay = f("get_config")("delay",0);
            // file_put_contents("log/debug.txt",print_r([$data_user, $last_send, $delay],true));
            if(!empty($last_send) and abs(time()-strtotime($last_send)) < $delay){
                $textkirim = "Anda baru saja mengirim pesan, silakan tunggu ".($delay - (time()-strtotime($last_send)))." detik lagi.";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                ]);
                return true;
            }

            if($msgcharcount < $pesan_minchar){
                $textkirim = "Jumlah karakter pesan anda tidak boleh kurang dari $pesan_minchar";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                ]);
                return true;
            }
            // file_put_contents("log/debug2.txt",print_r([$msgcharcount, $pesan_minchar, $delay],true));

            if(empty($data_user['vip_until'])){
                $pesan_maxchar = f("get_config")("pesan_maxchar",0);
            }
            else{
                $pesan_maxchar = f("get_config")("pesan_maxchar_vip",0);
            }

            if($msgcharcount > $pesan_maxchar){
                $textkirim = "Jumlah karakter pesan anda tidak boleh lebih dari $pesan_maxchar";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                ]);
                return true;
            }
            $kirimjenis = $jenis;
            if(!empty($fileid)){
                $kirimjenis .= "_$fileid";
            }

            $lastconfirm = f("data_load")("waitingsendconfirm$chat_id",0);
            if(!empty($lastconfirm)){
                f("bot_kirim_perintah")("deleteMessage",[
                    'chat_id'=>$chat_id,
                    'message_id'=>$lastconfirm,
                ]);
            }

            $keyboard = [];
            foreach(f("get_config")("templates") as $k=>$v){
                $keyboard[] = [$v, "kirim_$kirimjenis"."_$k"];
            }
            $keyboard[] = ['❌ BATALKAN', "kirimbatal"];

            $kirimconfirm = f("bot_kirim_perintah")("sendMessage",[
                "chat_id"=>$chat_id,
                "text"=>"Pilih Template",
                "parse_mode"=>"HTML",
                "reply_to_message_id"=>$botdata["message_id"],
                'reply_markup'=>f("gen_inline_keyboard")($keyboard,1),
            ]);
            if(!empty($kirimconfirm["result"]["message_id"])){
                f("data_save")("waitingsendconfirm$chat_id",$kirimconfirm["result"]["message_id"]);
            }

            return true;
            
        }
    }
    return false;
}