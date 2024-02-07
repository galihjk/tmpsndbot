<?php
function handle_my_chat_member($botdata){
    if(!f("handle_botdata_functions")($botdata,[
        "handle_userstopbot",
    ])){
        // file_put_contents("log/unhandled_my_chat_member".date("Y-m-d-H-i").".txt", file_get_contents("php://input"));
    };
}