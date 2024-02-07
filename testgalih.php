<?php

include("init.php");
echo "<pre>";
print_r(f("bot_kirim_perintah")("sendphoto",[
        "photo"=>"AgACAgUAAxkBAAIDcmO7zuoQKRC_g8faToK46yn8Wa5fAALxtTEbn9zZVVdRRq_uEFiFAQADAgADcwADLQQ",
        "chat_id"=>"227024160",
        "caption"=>"hai <a href='https://t.me/galihjkdev'>gal</a> hehe",
        "parse_mode"=>"HTML",
    ]));