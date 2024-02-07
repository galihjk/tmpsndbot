<?php
function handle_message_adm_topup($botdata){

    if(in_array($botdata["from"]["id"], f("get_config")("bot_admins",[]))){

        $text = $botdata["text"] ?? "";
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        if($text == "/topup"){
            
            $textkirim = "<b>Proses TOP UP (1/3)</b>\n";
            $textkirim .= "Balas pesan ini dengan <b>ID Pengguna</b>";

            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
                'reply_markup' => [
                    'force_reply'=>true,
                    'input_field_placeholder'=>'ID Pengguna',
                ],
            ]);

            return true;
        }


        if(!empty($botdata['reply_to_message']['text'])
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses TOP UP (1/3)")){
            
            $usertopup = f("get_user")($text);

            if(empty($usertopup["first_name"])){
                $textkirim = "<b>Proses TOP UP (1/3)</b>\n";
                $textkirim .= "❌ GAGAL\nPengguna dengan ID [$text] tidak ditemukan, silakan coba lagi.";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                    'reply_markup' => [
                        'force_reply'=>true,
                        'input_field_placeholder'=>'ID Pengguna',
                    ],
                ]);
            }
            else{
                $nama = $usertopup["first_name"] . (empty($usertopup["first_name"]) ? '' : "(@".$usertopup["username"]." )");
                $textkirim = "<b>Proses TOP UP (2/3)</b>\n";
                $textkirim .= "Balas pesan ini dengan <b>nominal</b> 🪙Koin yang akan ditambahkan untuk pengguna dengan \n";
                $textkirim .= "ID: [$text]\nNama: $nama \n<i>*Balas dengan angka saja</i>";
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>$textkirim,
                    "parse_mode"=>"HTML",
                    'reply_markup' => [
                        'force_reply'=>true,
                        'input_field_placeholder'=>'Jumlah Koin',
                    ],
                ]);
            }
            return true;
        }

        if(!empty($botdata['reply_to_message']['text'])
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses TOP UP (2/3)")
        and !empty($botdata["text"]) and is_numeric($botdata["text"])){

            $explode = explode("[", $botdata['reply_to_message']['text'])[1];
            $usertopupid = explode("]", $explode)[0];
            $usertopup = f("get_user")($usertopupid);
            
            $textkirim = "<b>Proses TOP UP (3/3)</b>\nVerifikasi\n";
            $textkirim .= "ID: ".$usertopupid;
            $textkirim .= "\nNama: ".$usertopup["first_name"] . (empty($usertopup["first_name"]) ? '' : "(@".$usertopup["username"]." )");
            $textkirim .= "\nNominal Koin: ".number_format($text)."🪙";

            f("bot_kirim_perintah")("sendMessage",[
                'chat_id'=>$chat_id,
                'text'=>$textkirim,
                "parse_mode"=>"HTML",
                'reply_markup'=>f("gen_inline_keyboard")([
                    ['✅ Kirim', 'topup_'.$usertopupid.'_'.$text],
                    ['🏠 Batal', 'home'],
                ]),
            ]);

            return true;
        }

    }

    return false;
}