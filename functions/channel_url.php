<?php
function channel_url($append = ""){
    $channel = f("get_config")("channel");
    return str_replace("@","https://t.me/",$channel).$append;
}