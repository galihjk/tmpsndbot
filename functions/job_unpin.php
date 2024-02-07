<?php
function job_unpin($current_time){
    $job_unpin_data = f("data_load")("job_unpin",[]);
    $datachanged = false;
    foreach($job_unpin_data as $k=>$job){
        if($job['time'] <= $current_time){
            f("bot_kirim_perintah")("unpinChatMessage",[
                "chat_id"=>f("get_config")("channel"),
                "message_id"=>$job['message_id'],
            ]);
            unset($job_unpin_data[$k]);
            $datachanged = true;
        }
    }
    if($datachanged){
        f("data_save")("job_unpin",array_values($job_unpin_data));
    }
}