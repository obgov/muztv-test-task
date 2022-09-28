# muztv-test-task
Выполненное тестовое задание. 

Некоторые сознательные допущения:<br>
*. Таск № 1 содержит только ту часть кода, которая иллюстрирует решение задачи;<br>
*. Таски № 4, 7 содержат только необходимый код (без проектирования какой-либо архитектуры);<br>
*. Классы не соответствуют SOLID, чтобы не раздувать код реализации тестового задания и не создавать исскуственно много файлов (класс БД, отдельный ExcelImport, ExсelExport и т.д.).<br>

1. Подсчитать количество хитов

Задача: есть banner.php, пусть он выводит статичное изображение, какой-то баннер. 

Нужно знать
- сколько раз мы его показали
- количество уникальных пользователей

<b>Вариант реализации содержится в banner.php.</b>

2. Основы работы с unix shell
Задача: есть script.php который что-то там делает критичное. Нужно, чтобы скрипт запускался каждые 13 секунд. 
<br>
<b>Вариант 1 (менее гибкий):
watch --interval=13 /usr/bin/php /path/to/important_script.php
  
Вариант 2 (более гибкий):
Использовать systemd timer.</b>

3. БД — SQL (MySQL)

Задача: требуется хранить свою библиотеку в БД. Волнуют названия книг и авторы — больше ничего хранить не надо. Предложите структуру таблиц.

<b>
Authors (id, name)<br>
Books (id, title)<br>
Authors_books (author_id, book_id)</b><br>
<br>

Выбрать список книг, которые написаны 3-мя со-авторами. То есть получить отчет «книга — количество соавторов» и отфильтровать те, у которых 3 соавтора.

<br>
<b>
SELECT books.title, COUNT(authors_books.book_id) as authors FROM books
JOIN authors_books ON books.id = authors_books.book_id
GROUP BY books.id HAVING COUNT(books.id) = 3</b>

<br>

4. Основы работы с фото

Задача: одну и ту же фото надо показываться в разных разрешения в разных местах сайта. Например, фото в анонсе новости одного размера, в теле новости второго размера, в мобильном приложении – третьего размера. Редактор контента грузит одно исходное фото. Предложите варианты решения задачи.<br>

<b>Вариант 1: использовать resize класс (файл resize.php).
  
Вариант 2: использовать другой resize класс (файл resize2.php).</b>

5. Что такое рекурсия?
Задача: требуется написать функцию вычисления факториала натурального числа.

<b>Вариант реализации в файле factorial.php</b>

6. SQL. Как найти дубликат записи?
Задача: проверить есть ли дубликаты записей в таблице клиентов

Предположим следующую таблицу clients: id, login, pwd, email.

<b>Вариант 1: выведет дубликаты записей по email + их количество
SELECT email, COUNT(email) as amount FROM clients GROUP BY email HAVING COUNT(email) > 1
  
Вариант 2: выведет повторяющиеся записи
SELECT * FROM clients WHERE `email` IN (SELECT `email` FROM `clients` GROUP BY `email` HAVING COUNT(*) > 1) ORDER BY id</b>

7. Импорт / экспорт
Задача: есть excel-таблица со списком книг. Всего один столбец
Требуется проверить есть ли данные книги в БД из задачи №3.
Предоставить отчет в виде Excel (два столбца):

Книга – авторы 

<b>Вариант реализации в файле excel.php</b>
