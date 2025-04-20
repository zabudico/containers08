<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';
require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);
$page = new Page(__DIR__ . '/templates/index.tpl');

// Получение ID страницы из GET-запроса (не рекомендуется для продакшена)
$pageId = isset($_GET['page']) ? $_GET['page'] : 1;
$data = $db->Read("page", $pageId);

if ($data) {
    echo $page->Render($data);
} else {
    echo "Page not found";
}