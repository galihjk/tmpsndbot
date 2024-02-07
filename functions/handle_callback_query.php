<?php
function handle_callback_query($botdata){
    if(!f("handle_botdata_functions")($botdata,[
        "handle_callback_query_kirim",
        "handle_callback_query_profil",
        "handle_callback_query_info",
        "handle_callback_query_home",
        "handle_callback_query_topup",
        "handle_callback_query_vip",
        "handle_callback_query_vipbeli",
        "handle_callback_query_pin",
        "handle_callback_query_admtopup",
        "handle_callback_query_admbroadcast",
    ])){
        // file_put_contents("log/unhandledcallback_query_".date("Y-m-d-H-i").".txt", file_get_contents("php://input"));
        file_put_contents("log/unhandledcallback_query_LAST.txt", file_get_contents("php://input"));
    };
}