<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_login{
    // Данные для обработки POST запросов;
    public $post_array;

    function __construct(){
        //echo 'Это конструкционный метод вызванный подключение модели '.__CLASS__.'<br>';
    }

    public function try_login(){
        global $db;

        $login = $this->post_array['login'];
        $password = $this->post_array['password'];

        $sql = "SELECT `id`, `role_id`, `employee_id` FROM `users` WHERE `name` = '".$login."' AND `password` = '".md5($password)."';";
        $login_data = $db->row($sql);

        $result_array = array();

        if($login_data['id'] != ''){
            $login_date = date('Y-m-d H:i:s');
            $sql = "UPDATE `users` SET `date_last_login` = '".$login_date."' WHERE `id` = '".$login_data['id']."';";
            $db->query($sql);

            $result_array['status'] = 'company';
            $result_array['message'] = 'Вы успешно прошли авторизацию';

            $_SESSION['user_id'] = $login_data['id'];
            $_SESSION['role_id'] = $login_data['role_id'];
            $_SESSION['employee_id'] = $login_data['employee_id'];

            // Так же, если пользователь определен к какой-то компании, то подключаем ее;
            if($login_data['employee_id'] != ''){
                // Определяем компанию и её название;
                $sql = "SELECT	company.id, company.short_name
                        FROM company, organization_structure, employees_items_node
                        Where employees_items_node.org_str_id = organization_structure.id
                        AND organization_structure.company_id = company.id
                        AND employees_items_node.employe_id = '".$login_data['employee_id']."';";
                $result = $db->row($sql);

                $_SESSION['control_company'] = $result["id"];
                $_SESSION['control_company_name'] = $result['short_name'];
            }

            //  если пользователь сотрудник
            if($login_data['role_id']==3){
                           // получаем фамилию и инициалы
                 $employee_name = $db->row_fullname($login_data['employee_id']);
                 $_SESSION['$employee_full_name'] = $employee_name;

                 // ищим непройденный материалы
                 $sql = "SELECT route_control_step.step_content_id, route_control_step.son_step,  history_docs.date_finish
                            FROM
                            route_control_step
                            LEFT JOIN history_docs
                            ON history_docs.step_id = route_control_step.id
                            INNER JOIN route_doc
                            ON route_control_step.track_number_id = route_doc.id
                            where
                            history_docs.date_finish IS NULL
                            and
                            route_doc.company_id ='". $_SESSION['control_company']."';";

                     $control_test_array = $db->all($sql);

                         //  Готовим для views результат проверки
                         if(isset($control_test_array[0])){

                             $result_array['status'] = 'employee';// материалы ЕСТЬ
                         } else {
                             $result_array['status'] = 'dead_end';// материалов НЕТ
                         }
             }

        }   else{
            $result_array['status'] = 'error';
            $result_array['message'] = 'Неверный логин или пароль!';
        }
        // Отправили зезультат
        $result = json_encode($result_array, true);
        die($result);
    }
}