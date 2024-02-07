<?php
function pesan_utama($datakirim, $userid){
    $data_user = f("get_user")($userid);
    $free_msg_used = $data_user['free_msg_used'] ?? 0;
    $free_media_used = $data_user['free_media_used'] ?? 0;
    if(empty($data_user['vip_until'])){
        $pesan_max = f("get_config")("pesan_max",0);
        $media_max = f("get_config")("media_max",0);
    }
    else{
        $pesan_max = f("get_config")("pesan_max_vip",0);
        $media_max = f("get_config")("media_max_vip",0);
    }
    $sisa_pesan = $pesan_max - $free_msg_used;
    $sisa_media = $media_max - $free_media_used;

    $textkirim = "<b>Kuota Gratis Harian</b>\n"
    ."Pesan: $sisa_pesan/$pesan_max\n"
    ."Media: $sisa_media/$media_max\n"
    ."\n".f("get_config")("msg_home","");

    if(in_array($userid, f("get_config")("bot_admins",[]))) $textkirim .= "\nAnda adalah /admin bot";

    $datakirim['text'] = $textkirim;
    $datakirim['parse_mode'] = "HTML";
    $datakirim['reply_markup'] = f("gen_inline_keyboard")([
        ['👤 Profil','profil'],
        ['ℹ️ Informasi','info'],
    ]);
    return $datakirim;
}