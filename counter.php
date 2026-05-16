<?php
/**
 * 站点访问计数接口
 */
$file = __DIR__ . '/visit_count.txt';

// 初始化文件
if (!file_exists($file)) {
    file_put_contents($file, '0', LOCK_EX);
}

// 使用文件锁防止并发冲突
$fp = fopen($file, 'c+');
$count = 0;

if ($fp && flock($fp, LOCK_EX)) {
    $count = (int) stream_get_contents($fp);
    $count++;
    rewind($fp);
    ftruncate($fp, 0);
    fwrite($fp, (string) $count);
    fflush($fp);
    flock($fp, LOCK_UN);
    fclose($fp);
}

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
