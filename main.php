<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: text/plain; charset=UTF-8');

$Files = ['./sport.txt', './children.txt', './internal.txt', './recommdation.txt', './news.txt', './movie.txt', './entertainment.txt'];

$combinedData = [];

foreach ($Files as $file) {
    try {
        $realPath = realpath($file);
        if (!$realPath || !file_exists($realPath)) {
            throw new Exception("文件不存在: $file (實際路徑: " . ($realPath ?: "無法解析") . ")");
        }

        // 使用 file_get_contents 讀取本機檔案
        $content = file_get_contents($realPath);
        if ($content === false) {
            throw new Exception("無法讀取文件: $file");
        }

        $data = json_decode($content);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON解析错误: " . json_last_error_msg());
        }

        if (!isset($data->data) || !is_array($data->data)) {
            throw new Exception("無效的数据结构: $file");
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

file_put_contents('faintv.txt', $output);
chmod('faintv.txt', 0644);
?>
