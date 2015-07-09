<?php
set_time_limit(0);
$baseUrl = "http://127.0.0.1/eas/";

$ctx = stream_context_create(array('http'=>
    array(
        'timeout' => 18000, // 1 200 Seconds = 20 Minutes
    )
));

//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncAdvertisers", false, $ctx);
//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncCampaign", false, $ctx);
//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncCampaignBudget", false, $ctx);
//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncStrategy", false, $ctx);
//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncCreativeMaterial", false, $ctx);
//檢查未執行排程
$checkCron = file_get_contents($baseUrl . "sync/syncCreativeGroups", false, $ctx);