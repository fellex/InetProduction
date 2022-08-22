<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

function print_res($msg, $res)
{
    echo "<b>" . $msg . ":</b><br>";
    echo '<pre>';
    print_r($res);
    echo '</pre>';
    echo '<br>';
}

$array = [
    ['id' => 1, 'date' => "12.01.2020", 'name' => "test1"],
    ['id' => 2, 'date' => "02.05.2020", 'name' => "test2"],
    ['id' => 4, 'date' => "08.03.2020", 'name' => "test4"],
    ['id' => 1, 'date' => "22.01.2020", 'name' => "test1"],
    ['id' => 2, 'date' => "11.11.2020", 'name' => "test4"],
    ['id' => 3, 'date' => "06.06.2020", 'name' => "test3"],
];
$result = []; // будет содержать результат обработки исходного массива
$ids = []; // будем собирать значения индекса "id"

// определяем, что отправить в результирующий массив, основываясь на уникальности по значению "id"
function check_unique_id($var)
{
    global $ids;
    if(!in_array($var['id'], $ids)) {
        $ids[] = $var['id'];
        return true;
    } else {
        return false;
    }
}

// 1. Оставляем уникальные значения "id"
$result = array_filter($array, "check_unique_id");
//print_res("1. Оставляем уникальные id", $result);
$result = $ids = []; // обнулим результат

// 2. Сортировка по полю
// 2.1 Сортировка исходного массива по "id"
$result = $array;
$ids_arr  = array_column($result, 'id');
array_multisort($ids_arr, SORT_ASC, $result);
print_res("2.1. Сортируем исходный массив по индексу \"id\"", $result);
// 2.2 Сортировка исходного массива по "name"
$result = $array;
$names_arr = array_column($result, 'name');
array_multisort($names_arr, SORT_ASC, $result);
print_res("2.2. Сортируем исходный массив по индексу \"name\"", $result);
$result = []; // обнулим результат

// 3. Извлекаем элементы, удовлетворяющие внешним условиям
// Идея: исходный массив имеет порядоквый индекс.
// Можно получить массив из всех значений столбца "id" функцией array_column, при этом индексы полученного и исходного массивов будут совпадать
// Оставляем только те индексы, у которых id = искомому значению
// Осталось найти пересечения по индексам исходного массива и искомых значений
$need_ids = [1, 3];
$ids_arr  = array_column($array, 'id'); // все значения колонки "id"
//$result = array_intersect_key($array, array_intersect($ids_arr, $need_ids));
$need_by_id = array_intersect($ids_arr, $need_ids); // индексы только тех значений, у которых id = искомым значениям
$result = array_intersect_key($array, $need_by_id); // ищем пересечения по ключу исходного массива и $need_by_id
print_res("3. Извлекаем элементы, у которых \"id\" = 1 или 3", $result);
$result = []; // обнулим результат

// 4. Изменить в массиве значения и ключи (что-то я тут не совсем понял, что нужно сделать)
// заметка! естественно, что для одинаковых "name" будут подставлены values из последних строк
$ids_arr  = array_column($array, 'id'); // все значения колонки "id"
$names_arr = array_column($array, 'name'); // все значения колонки "name"
$result = array_combine($names_arr, $ids_arr); // ключи из одного массива, значения из другого
print_res("4. Изменить в массиве значения и ключи", $result);

/* 5. Выведите id и названия всех товаров, которые имеют все возможные теги в этой базе.
SELECT `g`.`id`, `g`.`name`
FROM `goods` AS `g`
WHERE `g`.`id` IN (
    SELECT `gt`.`goods_id`
    FROM `goods_tags` AS `gt`
    INNER JOIN `tags` AS `t` ON `t`.`id` = `gt`.`tag_id`
    GROUP BY `gt`.`goods_id`
    HAVING COUNT(`gt`.`tag_id`) = (SELECT COUNT(`id`) FROM `tags`)
);
*/

/* 6. Выбрать без join-ов и подзапросов все департаменты...
Идея, отбираем только мужчин, группируем по департаменту, количество primary key должно равняться количеству оценок > 5
Если все так, то это нужный департамент

SET sql_mode = ''; -- выключаем ONLY_FULL_GROUP_BY для MySQL
SELECT *
FROM `evaluations`
WHERE `gender` = 1
GROUP BY `department_id`
HAVING COUNT(`respondent_id`) = SUM(IF(`value` > 5, 1, 0));
*/
