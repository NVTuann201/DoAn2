<?php
    function createLog($user_id, $type, $ip, $content, $noi_dung){
        \App\UserLog::create([
            'event_time' => \Carbon\Carbon::now(),
            'user_id' => $user_id,
            'action' => $type,
            'ip_address' => $ip,
            'content' => $content,
            'noi_dung' => $noi_dung,
        ]);
    }
