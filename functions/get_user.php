<?php
$GLOBALS['global_user_got'] = [];

function get_user($id, $refresh = false){
    global $global_user_got;
    if(!empty($global_user_got[$id]) and !$refresh){
        return $global_user_got[$id];
    }
    $data = f("db_select_one")("select * from users where id = ".f("str_dbq")($id,true));
    $global_user_got[$id] = $data;
    return $data;
}