<?php
function handle_message_adm_broadcast($botdata){
    if(in_array($botdata["from"]["id"], f("get_config")("bot_admins",[]))){
        $text = $botdata["text"] ?? "";
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        if($text == "/broadcast"){
            $textkirim = "<b>Proses BROADCAST (1/3)</b>\n";
            $textkirim .= "Mau kirim ke berapa user? User akan dipilih dari yang paling terkini aktivitasnya. Balas dengan angka.";
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
                'reply_markup' => [
                    'force_reply'=>true,
                    'input_field_placeholder'=>'Jumlah Pengguna',
                ],
            ]);
            return true;
        }


        if(!empty($botdata['reply_to_message']['text'])
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses BROADCAST (1/3)")
        and is_numeric($botdata["text"]) and $botdata["text"] > 0){
            $jml = $botdata["text"];
            $textkirim = "<b>Proses BROADCAST (2/3)</b>\n";
            $textkirim .= "Mau kirim pesan apa? User akan dipilih dari yang paling terkini aktivitasnya sebanyak: $jml";
            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
                'reply_markup' => [
                    'force_reply'=>true,
                    'input_field_placeholder'=>'Pesan Broadcast',
                ],
            ]);
            return true;
        }

        
        if(!empty($botdata['reply_to_message']['text'])
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses BROADCAST (2/3)")){
            $jml = explode(": ",$botdata['reply_to_message']['text'])[1];
            $pesan_sample = f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$text,
                "parse_mode"=>"HTML",
            ]);
            $pesan_sample_msgid = $pesan_sample["result"]["message_id"] ?? "";
            if(empty($pesan_sample_msgid)){
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>"GAGAL: ".print_r($pesan_sample,true),
                ]);
            }
            else{
                $textkirim = "<b>Proses BROADCAST (3/3)</b>\n";
                $textkirim .= "Verifikasi\n";
                $textkirim .= "Jml Pengguna: <b>$jml</b>\n";
                $botid = explode(":",f("get_config")("bot_token"))[0];
                $textkirim .= "Pesan: ☝️ di<i>reply</i>";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                    "reply_to_message_id"=>$pesan_sample_msgid,
                    'reply_markup'=>f("gen_inline_keyboard")([
                        ['✅ KIRIM', "broadcast_$jml"."_$pesan_sample_msgid"],
                        ['🏠 Batal', 'home'],
                    ]),
                ]);
            }
            
            return true;
        }
    }
}