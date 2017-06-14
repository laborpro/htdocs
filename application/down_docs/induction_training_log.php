<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15.02.2017
 * Time: 23:10
 */
$sql = "
            SELECT CONCAT_WS(' ', `surname`, `employees`.`name`, `second_name`) AS `fio`, `birthday`, GROUP_CONCAT(`items_control`.`name`) AS `items`, `company`.`name` AS `company_name`
            FROM `employees`, `employees_items_node`, `items_control`, `company`
            WHERE `employees`.`id` IN (".implode(', ', $employees_array).")
            AND `employees`.`id` = `employees_items_node`.`employe_id`
            AND `employees_items_node`.`item_id` = `items_control`.`id`
            AND `items_control`.`company_id` = `company`.`id`
            GROUP BY `employees`.`id`
        ";
$employees_data = $db->all($sql);

$table_line = '';

$company_name = '';

foreach($employees_data as $key => $employee_item){
    $company_name = $employee_item['company_name'];
    $table_line .= '
                    <tr>
                    <td width="48">
                    '.date('d.m.Y').'
                    </td>
                    <td width="192">
                    '.$employee_item['fio'].'
                    </td>
                    <td width="77">
                    '.$systems->get_local_date_time($employee_item['birthday']).'
                    </td>
                    <td width="150">
                    '.$employee_item['items'].'
                    </td>
                    <td width="157">
                    '.$employee_item['items'].'
                    </td>
                    <td width="192">
                    <p>&nbsp;</p>
                    </td>
                    <td width="96">
                    <p>&nbsp;</p>
                    </td>
                    <td width="87">
                    <p>&nbsp;</p>
                    </td>
                </tr>
    ';
}

//header('Content-type: application/vnd.ms-word');
//header("Content-Disposition: attachment; Filename = \"Вводный инструктаж журнал.doc\"");

$result_file =
    '<!DOCTYPE html>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Вводный инструктаж журнал</title>
        <style>
                @page Section1 {
                    size:841.9pt 595.3pt; /* Размер бумаги */
                    mso-page-orientation: landscape; /* Ориентация*/
                    margin:3.0cm 2.0cm 42.5pt 2.0cm; /* Отступы*/
                    mso-header-margin:35.4pt; /* Расположение верхнего колонтитула */
                    mso-footer-margin:35.4pt; /* Расположение нижнего колонтитула */
                    mso-paper-source:0; /* Источникбумаги*/
                }
                div.Section1 {
                    page:Section1;
                }
        </style>
        </head>
        <body>
            <div class="Section1">
                <div align="center">НАИМЕНОВАНИЕ ОРГАНИЗАЦИИ <b>'.$company_name.'</b></div>
                <br>
                <div align="center">СТРУКТУРНОЕ ПОДРАЗДЕЛЕНИЕ___________________________________________</div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div style="font-size: 65px; font-weight: bold" align="center">ЖУРНАЛ</div><br>
                <br>
                <div style="font-size: 25px;" align="center">РЕГИСТРАЦИИ ВВОДНОГО ИНСТРУКТАЖА</div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <div style="font-size: 15px;" align="right">Начат_________________'.date('Y').'г.</div>
                <div style="font-size: 15px;" align="right">Окончен_______________'.date('Y').'г.</div>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <br>
                <table style="font-size: 8pt;border-color: black; text-align: center;" border="1" width="999" cellspacing="0" cellpadding="0">
                <tbody>
                <tr>
                    <td rowspan="2" width="48">
                        Дата инструктажа
                    </td>
                    <td rowspan="2" width="192">
                        Фамилия, имя, отчество инструктируемого
                    </td>
                    <td rowspan="2" width="77">
                        Год рождения
                    </td>
                    <td rowspan="2" width="150">
                        Профессия, должность инструктируемого
                    </td>
                    <td rowspan="2" width="157">
                        Наименование производственного подразделения, в которое направляется инструктируемый
                    </td>
                    <td rowspan="2" width="192">
                        Фамилия, инициалы, должность инструктирующего
                    </td>
                    <td colspan="2" width="183">
                        Подпись
                    </td>
                </tr>
                <tr>
                    <td width="96">
                        Инструктирующего
                    </td>
                    <td width="87">
                        Инструктируемого
                    </td>
                </tr>

                <tr>
                    <td width="48">
                        <b>1</b>
                    </td>
                    <td width="192">
                        <b>2</b>
                    </td>
                    <td width="77">
                        <b>3</b>
                    </td>
                    <td width="150">
                        <b>4</b>
                    </td>
                    <td width="157">
                        <b>5</b>
                    </td>
                    <td width="192">
                        <b>6</b>
                    </td>
                    <td width="96">
                        <b>7</b>
                    </td>
                    <td width="87">
                        <b>8</b>
                    </td>
                </tr>

               '.$table_line.'

                </tbody>
                </table>
            </div>
        </body>
        </html>';

$folder_name = $_SERVER['DOCUMENT_ROOT'].'/temp_downloads/'.md5($result_file);
if(!is_dir($folder_name)){
    mkdir($folder_name);
}

file_put_contents($folder_name.'/induction_training_log.doc', $result_file, FILE_APPEND);

$doc_download_url = '/temp_downloads/'.md5($result_file).'/induction_training_log.doc';