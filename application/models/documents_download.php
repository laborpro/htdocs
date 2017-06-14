<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_documents_download{
    // Данные для обработки POST запросов;
    public $post_array;

    function __construct(){
        //echo 'Это конструкционный метод вызванный подключение модели '.__CLASS__.'<br>';
    }

    public function show_something_else(){
        //echo 'Это результат выполнения метода модели вызванного из контроллера<br>';
    }

    // Строимс псиок документов
    public function get_doc_list(){
        global $elements;

        $html = $elements->item_with_checkbox('Вводный инструктаж журнал', '', '', '', 'doc_name=induction_training_log');
        $html .= $elements->item_with_checkbox('Вводный инструктаж', '', '', '', 'doc_name=induction_training');
        $html .= $elements->item_with_checkbox('Должностная инструкция бухгалтера', '', '', '', 'doc_name=job_description_accountant_guide');
        $html .= $elements->item_with_checkbox('Инструкция по охране труда для работников офиса', '', '', '', 'doc_name=instruction_on_labor_protection_office');
        $html .= $elements->item_with_checkbox('Инструкция по оказанию первой помощи', '', '', '', 'doc_name=instructions_for_first_aid');

        return $html;
    }

    // Строимс писок сотрудников;
    public function get_employees_list(){
        global $db, $elements;

        $sql = "
            SELECT `employees`.`id`, CONCAT_WS(' ', `surname`, `employees`.`name`, `second_name`) AS `fio`, GROUP_CONCAT(`items_control`.`name`) AS `items`
            FROM `employees`, `employees_items_node`, `items_control`
            WHERE `employees`.`id` = `employees_items_node`.`employe_id`
            AND `employees_items_node`.`item_id` = `items_control`.`id`
            AND `employees`.`status` != 0
            AND `items_control`.`company_id` = '".$_SESSION['control_company']."'
            GROUP BY `employees`.`id`
        ";
        $employees = $db->all($sql);

        $html = '';

        if(count($employees) == 0){
            $html .= $elements->small_title('У вас нет сотрудников в управляемой компании. Добавите их и вернитесь в этот раздел.');
        }   else{
            foreach($employees as $employee){
                $html .= $elements->item_with_checkbox('ФИО: <b>'.$employee['fio'].'</b><br>'.$employee['items'], 'employee_' . $employee['id'], '', '', 'employee_id=' . $employee['id']);
            }
        }

        return $html;
    }

    // Загрузка документов;
    public function download(){
        global $db, $systems;

        $docs_array = json_decode($this->post_array['docs_array'], true);
        $employees_array = json_decode($this->post_array['employees_array'], true);

        $links = array();

        foreach($docs_array as $doc_item){
            // Ссылку нам вернет подключаемый шаблон;
            $doc_download_url = '';

            // Все глобальные переменные этого метода и $employees_array будут использоваться в подключеннмо файле документа;
            // Далее нам надо подключить щаблон файла который мы будем формировать;
            include(ROOT_PATH.'/application/down_docs/'.$doc_item.'.php');
            // Кладем ссылку в массив;
            $links[] = $doc_download_url;
        }

        $result_array = array();
        $result_array['status'] = 'ok';
        $result_array['message'] = 'Сейчас произойдет загрузка сформированного файла';
        $result_array['download_link'] = $links;

        $result = json_encode($result_array, true);

        die($result);
    }
}