<?php
function jobadd_unpin($message_id){
    $job_unpin_data = f("data_load")("job_unpin",[]);
    $new_job_unpin = [
        'time'=>time()+24*60*60, //24 hours
        'message_id'=>$message_id,
    ];
    $job_unpin_data[] = $new_job_unpin;
    f("data_save")("job_unpin",$job_unpin_data);
    return $new_job_unpin;
}