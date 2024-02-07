# menfessbot

untuk clone, buat file config.php dengan format berikut:
<?php
return [
    'bot_token'=>bot_token,
    'channel'=>'@channel',
    'group'=>'-xxxxxx',
    'groupdisc'=>'-xxxxxxxxxx',
    'botuname'=>{bot user name tanpa @},
    'webhook'=>'/url_path_to/webhook_a3c59a7ed8d365cf269ef5cc4aca0c17.php',
    'bot_admins'=>[
        '???',
        '???'
    ],
    
    'db_database'=>'???',
    'db_user'=>'???',
    'db_password'=>'???',
    
    'pesan_max'=>10,
    'media_max'=>1,
    
    'pesan_cost'=>15,
    'media_cost'=>99,
    
    'cost_vip'=>999,
    
    'resend_prefixes'=>[
        "#menfess\n\n",
        "#random\n\n",
    ],

    'msg_home'=>"Kirim pesan kalian disini maka akan otomatis ter post di channel @xxxxxx \n"
        ."\n"
        ."❌ Spam\n"
        ."❌ Porn\n"
        ."\n"
        ."👇Format:👇\n"
        ."(#menfess Atau #random)(↵)\n"
        ."(↵)\n"
        ."(Pesan)\n"
        ."\n"
        ."Contoh (bisa dicopy):\n===============\n"
        ."<pre>#random\n\nBlablabla...</pre>\n===============",


];
];