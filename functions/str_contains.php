<?php
if(!function_exists("str_contains")){
    function str_contains($haystack, $needle){
        if((string)$needle === ""){
            return true;
        }
        return (strpos($haystack, $needle) !== false);
    }
}