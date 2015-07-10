<?php
set_time_limit(0);
$baseUrl = "http://127.0.0.1/eas/";

$ctx = stream_context_create(array('http'=>
    array(
        'timeout' => 18000, // 1 200 Seconds = 20 Minutes
    )
));

//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncBuyReportDailyPc", false, $ctx);