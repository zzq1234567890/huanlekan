<?php
ini_set("max_execution_time", 300);
header('Content-Type: text/plain; charset=UTF-8');
header("Content-Disposition: attachment; filename=faintv.txt");

$Files = [
    './体育.txt',
    './儿童.txt',
    './国际新闻.txt',
    './推荐.txt',
    './新闻.txt',
    './电影.txt',
    './综艺.txt'
];

$combinedData = [];

foreach ($Files as $filePath) {
    // 检查文件是否存在
    if (!file_exists($filePath) {
        trigger_error("File not found: {$filePath}", E_USER_WARNING);
        continue;
    }

    // 读取文件内容
    $fileContent = file_get_contents($filePath);
    if ($fileContent === false) {
        trigger_error("Failed to read file: {$filePath}", E_USER_WARNING);
        continue;
    }

    // 解析JSON数据
    $parsedData = json_decode($fileContent);
    if ($parsedData === null || json_last_error() !== JSON_ERROR_NONE) {
        trigger_error("Invalid JSON in file: {$filePath}", E_USER_WARNING);
        continue;
    }

    // 验证数据结构
    if (!isset($parsedData->data) || !is_array($parsedData->data)) {
        trigger_error("Invalid data structure in file: {$filePath}", E_USER_WARNING);
        continue;
    }

    // 合并数据
    $combinedData = array_merge($combinedData, $parsedData->data);
}

// 生成M3U播放列表
foreach ($combinedData as $item) {
    // 验证必要字段
    if (!isset(
        $item->_id,
        $item->name,
        $item->image->src
    )) {
        continue;
    }

    // 安全过滤字段
    $id = filter_var($item->_id, FILTER_SANITIZE_STRING);
    $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
    $logo = filter_var($item->image->src, FILTER_VALIDATE_URL);

    // 生成EXTINF行
    echo sprintf(
        "#EXTINF:-1 tvg-id=\"%s\" tvg-name=\"%s\" tvg-logo=\"%s\" group-title=\"4gtv\",%s\n",
        $id,
        $id,  // 根据需求可替换为$name
        $logo ?: $id,  // 如果logo无效则使用id
        $name
    );

    // 生成播放地址
    echo "http://127.0.0.1:8081/huanlekan.php?id=" . urlencode($item->name) . "\n\n";
}
?>
