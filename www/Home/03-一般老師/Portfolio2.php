<?php
// check_word_count.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];

    // 檢查檔案是否成功上傳
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => '檔案上傳錯誤']);
        exit;
    }

    // 檢查檔案類型
    $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $wordCount = 0;

    if ($fileExtension == 'docx' || $fileExtension == 'doc') {
        // 這裡需要使用 PHP 解析 Word 檔案來計算字數
        require_once 'vendor/autoload.php'; // PHPWord 路徑

        try {
            $phpWord = \PhpOffice\PhpWord\IOFactory::load($file['tmp_name']);
            $text = '';
            foreach ($phpWord->getSections() as $section) {
                $text .= $section->getText();
            }
            // 計算字數
            $wordCount = str_word_count(strip_tags($text));
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => '無法讀取檔案']);
            exit;
        }
    } elseif ($fileExtension == 'txt') {
        // 如果是純文本檔案，可以直接讀取字數
        $text = file_get_contents($file['tmp_name']);
        $wordCount = str_word_count(strip_tags($text));
    } else {
        echo json_encode(['success' => false, 'message' => '不支援此檔案格式']);
        exit;
    }

    echo json_encode(['success' => true, 'wordCount' => $wordCount]);
} else {
    echo json_encode(['success' => false, 'message' => '未接收到檔案']);
}
?>
