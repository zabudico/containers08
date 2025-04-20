<?php

require_once __DIR__ . '/testframework.php';
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modules/database.php';
require_once __DIR__ . '/../modules/page.php';

$testFramework = new TestFramework();

// Тест 1: Проверка подключения к базе данных
function testDbConnection()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    return assertExpression($db !== null, 'Database connection successful', 'Database connection failed');
}

// Тест 2: Проверка метода Count
function testDbCount()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $count = $db->Count("page");
    return assertExpression($count == 3, 'Count method works correctly', 'Count method failed');
}

// Тест 3: Проверка метода Create
function testDbCreate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $data = ['title' => 'Page 4', 'content' => 'Content 4'];
    $id = $db->Create("page", $data);
    $record = $db->Read("page", $id);
    return assertExpression($record['title'] == 'Page 4' && $record['content'] == 'Content 4', 'Create method works correctly', 'Create method failed');
}

// Тест 4: Проверка метода Read
function testDbRead()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $record = $db->Read("page", 1);
    return assertExpression($record['title'] == 'Page 1' && $record['content'] == 'Content 1', 'Read method works correctly', 'Read method failed');
}

// Тест 5: Проверка метода Update
function testDbUpdate()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $data = ['title' => 'Updated Page 1', 'content' => 'Updated Content 1'];
    $db->Update("page", 1, $data);
    $record = $db->Read("page", 1);
    return assertExpression($record['title'] == 'Updated Page 1' && $record['content'] == 'Updated Content 1', 'Update method works correctly', 'Update method failed');
}

// Тест 6: Проверка метода Delete
function testDbDelete()
{
    global $config;
    $db = new Database($config["db"]["path"]);
    $db->Delete("page", 1);
    $record = $db->Read("page", 1);
    return assertExpression($record === false, 'Delete method works correctly', 'Delete method failed');
}

// Тест 7: Проверка метода Render класса Page
function testPageRender()
{
    $page = new Page(__DIR__ . '/../templates/index.tpl');
    $data = ['title' => 'Test Title', 'content' => 'Test Content'];
    $output = $page->Render($data);
    return assertExpression(strpos($output, 'Test Title') !== false && strpos($output, 'Test Content') !== false, 'Render method works correctly', 'Render method failed');
}

// Добавление тестов в фреймворк
$testFramework->add('Database connection', 'testDbConnection');
$testFramework->add('Count method', 'testDbCount');
$testFramework->add('Create method', 'testDbCreate');
$testFramework->add('Read method', 'testDbRead');
$testFramework->add('Update method', 'testDbUpdate');
$testFramework->add('Delete method', 'testDbDelete');
$testFramework->add('Page Render method', 'testPageRender');

// Запуск тестов
$testFramework->run();

echo $testFramework->getResult();