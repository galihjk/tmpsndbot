<?php
function handle_message_adm_ban($botdata){
    if(in_array($botdata["from"]["id"], f("get_config")("bot_admins",[]))){
        $text = $botdata["text"] ?? "";
        $chat = $botdata["chat"];
        $chat_id = $chat["id"];

        if($text == "/ban"){
            $textkirim = "<b>Proses BAN User</b>\n";
            $textkirim .= "Balas pesan ini dengan <b>ID Pengguna</b> yang akan di<i>banned</i>";
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
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses BAN User")){
            
            $userban = f("get_user")($text);

            if(empty($userban)){
                $textkirim = "<b>Proses BAN User</b>\n";
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
                f("db_q")("update users set banned=1 where id='".$userban['id']."'");
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>"Berhasil! /u_".$userban['id'] . " telah di<i>banned</i>",
                    "parse_mode"=>"HTML",
                ]);
            }
            return true;
        }

        if($text == "/unban"){
            $textkirim = "<b>Proses UNBAN User</b>\n";
            $textkirim .= "Balas pesan ini dengan <b>ID Pengguna</b> yang akan diUNBAN";
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
        and f("str_contains")($botdata['reply_to_message']['text'], "Proses UNBAN User")){
            
            $userban = f("get_user")($text);

            if(empty($userban)){
                $textkirim = "<b>Proses UNBAN User</b>\n";
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
                f("db_q")("update users set banned=0 where id='".$userban['id']."'");
                f("bot_kirim_perintah")("sendMessage",[
                    'chat_id'=>$chat_id,
                    'text'=>"Berhasil! /u_".$userban['id'] . " telah diUNBAN",
                    "parse_mode"=>"HTML",
                ]);
            }
            return true;
        }
    }
}