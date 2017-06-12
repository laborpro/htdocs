<?php
// Параметры БД;
//$sql_db_host = "127.0.0.1:3306";
//$sql_db = "hardcore";
//$sql_db_user = "php";
//$sql_db_password ="VIP1GAsA";

$db_host = "127.0.0.1";
$db_name = "hardcore";
$db_user = "root";
$db_password ="Ci2OheR7";

//echo phpinfo();
// Начало контроллера в маршруте;
/*
 * 0 - быть не может, так как 0 - это всегда пустое значение.
 * По умолчанию 1.
 * Если будет "указатель" (город, филиал) - сдвигаем позицию контроллера;
 *
 * */
$controller_position_in_route = 1;

// Параметры шаблона;
$current_template = 'test';

// Режимя;
$hard_mode = false;