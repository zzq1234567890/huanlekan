<?php

ini_set("max_execution_time", "3000"); // 原值过大，可能调整

header('Content-Type: text/plain; charset=UTF-8');

header("Content-Disposition: attachment; filename=faintv.txt");

$Files = ['./体育.txt', './儿童.txt','./国际新闻.txt','./推荐.txt','./新闻.txt','./电影.txt','./综艺.txt'];

$combinedData = [];

foreach ($Files as $file) {

if (!file_exists($file)) {

continue; // 或者处理错误

}

$content = file_get_contents($file);

$decoded = json_decode($content);

if ($decoded === null || !isset($decoded->data) {

continue; // JSON解析失败或数据结构不符

}

if (is_array($decoded->data)) {

$combinedData = array_merge($combinedData, $decoded->data);

}

}

foreach ($combinedData as $item) {

if (!isset($item->_id, $item->name)) {

continue; // 数据项不完整

}

$id = $item->_id;

$name = $item->name;

echo "#EXTINF:-1 tvg-id=\"{$id}\" tvg-name=\"{$name}\" tvg-logo=\"{$id}\" group-title=\"4gtv\",{$name}\r\n";

echo "http://127.0.0.1:8081/huanlekan.php?id={$name}\n";

}

?>
