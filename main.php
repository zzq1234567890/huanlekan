<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=UTF-8');

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

foreach ($Files as $file) {
    try {
        if (!file_exists($file)) {
            throw new Exception("文件不存在: $file");
        }

        $content = file_get_contents($file);
        if ($content === false) {
            throw new Exception("无法读取文件: $file");
        }

        $data = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON解析错误: " . json_last_error_msg());
        }

        if (!isset($data->data) || !is_array($data->data)) {
            throw new Exception("无效的数据结构: $file");
        }

        $combinedData = array_merge($combinedData, $data->data);
    } catch (Exception $e) {
        file_put_contents('php://stderr', "[ERROR] " . $e->getMessage() . PHP_EOL);
    }
}

$output = "";
foreach ($combinedData as $item) {
    if (!isset($item->_id, $item->name, $item->image->src)) {
        continue;
    }

    $id = htmlspecialchars($item->_id, ENT_QUOTES, 'UTF-8');
    $name = htmlspecialchars($item->name, ENT_QUOTES, 'UTF-8');
    $logo = filter_var($item->image->src, FILTER_VALIDATE_URL);

    $output .= sprintf(
        "#EXTINF:-1 tvg-id=\"%s\" tvg-name=\"%s\" tvg-logo=\"%s\" group-title=\"4gtv\",%s\n",
        $id,
        $id,
        $logo ?: $id,
        $name
    );

    $output .= "http://127.0.0.1:8081/huanlekan.php?id=" . urlencode($item->name) . "\n\n";
}

// 写入文件并设置权限
file_put_contents('faintv.txt', $output);
chmod('faintv.txt', 0644);
