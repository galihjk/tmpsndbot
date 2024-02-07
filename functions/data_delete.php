<?php
function data_delete($name){
 $filename="data/$name.json";
 if(file_exists($filename)) return unlink($filename);
 return false;
}