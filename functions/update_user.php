<?php
function update_user($userdata){
    $userdataq = f("str_dbq")($userdata,true);
    $id = $userdataq["id"];
    $first_name = $userdataq["first_name"] ?? "''";
    $last_name = $userdataq["last_name"] ?? "''";
    $username = $userdataq["username"] ?? "''";
    $bot_active = f("str_dbtime")();
    $user_exist = f("get_user")($userdata['id']);
    if(empty($user_exist)){
        f("db_q")("INSERT INTO users 
        (id, first_name, last_name, username, bot_active) VALUES 
        ($id, $first_name, $last_name, $username, $bot_active)");
    }
    else{
        $last_bot_active = $user_exist['bot_active'];
        $free_msg_used = $user_exist['free_msg_used'] ?? 0;
        $free_media_used = $user_exist['free_media_used'] ?? 0;
        $need_refresh = false;
        if(!empty($last_bot_active) and date("Y-m-d") != date("Y-m-d", strtotime($last_bot_active))){
            $free_msg_used = 0;
            $free_media_used = 0;
            $need_refresh = true;
        }
        $vip_until = $user_exist['vip_until'] ?? "null";
        if($vip_until != "null"){
            if(time() > strtotime($vip_until)){
                $vip_until = "null";
                $need_refresh = true;
            }
            else{
                $vip_until = "'".$user_exist['vip_until']."'";
            };
        }
        f("db_q")("update users set
        first_name=$first_name, 
        last_name=$last_name,
        username=$username,
        free_msg_used=$free_msg_used,
        free_media_used=$free_media_used,
        vip_until=$vip_until,
        bot_active=$bot_active
        where id=$id");
        if($need_refresh) unset($GLOBALS['global_user_got'][$userdata['id']]);
    }
    /*
        $q = "INSERT INTO users 
            (id, first_name, last_name, username, free_msg_used, bot_active) VALUES 
            ($id, $first_name, $last_name, $username, $max_free_msg, $bot_active)
            ON DUPLICATE KEY UPDATE 
            first_name=$first_name, last_name=$last_name, username=$username, bot_active=$bot_active;
        ";
        f("db_q")($q);
    */
}