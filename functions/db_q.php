<?php
function db_q($q){
    $mysqli = f("db_connect")();
    $data = [];
    if ($result = $mysqli -> query($q)) {
        if(is_object($result) and method_exists($result,"fetch_array")){
            while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                $data[] = $row;
            }
            $result -> free_result();            
        }
        else{
            $data = $result;
        }
    }
    else{
        file_put_contents("log/ERROR DBQ ".date("YmdHis").".txt","$q\n\n".$mysqli->error);
    }
    return $data;
}