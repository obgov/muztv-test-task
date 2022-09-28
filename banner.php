<?php
$db = new PDO('mysql:host=localhost;dbname=muztv', 'root', 'root');

/* Подсчет показов баннера (предположим, что наш баннер внесен под id = 5)*/

$db->query('UPDATE banner_shows SET shows = shows + 1 WHERE id = 5');

/**
 * Подсчет уникальных показов баннера
 * Необходимо определиться с критериями отнесения посетителя к уникальному
 * Вариант 1: хранить в БД идентификатор юзера (напр., ip) с временем внесения.
 * При показе баннера проверять, есть ли такой юзер в БД, если нет -> увеличивать счетчик уникального показа баннера
 * При этом удалять записи юзеров из БД, которые старше определенного времени
 *
 * Вариант 2: сессии.
 * Устанавливать сессию, при отсутствии которой пользователь считается уникальным.
 * Плохой вариант из-за отсутствия гибкости.
 *
 * Вариант 3: куки. Механизм аналогичен сессиям.
 *
 * Сильно упрощенный вариант реализации первого варианта ниже.
 */
$ip = $_SERVER['REMOTE_ADDR'];
$checkUnique = $db->prepare('SELECT * FROM visitors WHERE ip = ?');
$checkUnique->execute([$ip]);

if(!$checkUnique->fetch()) {
    $newVisitor = $db->prepare('INSERT INTO visitors (ip, time, banner_id) VALUES (?, now(), 5)');
    $newVisitor->execute([$ip]);
    $db->query('UPDATE banner_shows SET unique_shows = unique_shows + 1 WHERE id = 5');
}

$db->query('DELETE FROM visitors WHERE time < (NOW() - INTERVAL 1 MINUTE)');

// некая логика показа баннера
header('Content-Type: image/jpeg');
readfile('cat.jpg');