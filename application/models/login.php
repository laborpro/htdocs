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
                // Определяем компанию;
                $sql = "SELECT `item_id`  FROM `employees_items_node` WHERE `employe_id` = '".$login_data['employee_id']."';";
                $item_id = $db->one($sql);

                $sql = "SELECT `company_id` FROM `items_control` WHERE `id` = '".$item_id."';";
                $company_id = $db->one($sql);

                $sql = "SELECT * FROM `company` WHERE `id` = '".$company_id."';";
                $company_data = $db->row($sql);

                $_SESSION['control_company'] = $company_id;
                $_SESSION['control_company_name'] = $company_data['short_name'];
            }
             if($login_data['role_id']==3){// сотрудник
                 // инициалы
                 $employee_name = $db->row_fullname($login_data['employee_id']);
                 $_SESSION['$employee_full_name'] = $employee_name;

                     $sql = "SELECT * FROM `control_tests` WHERE `company_id` = '".$company_id."';";
                     $control_test_array = $db->all($sql);
                     foreach($control_test_array as $control_tests_item) {
                         // Проверяем проходил ли пользователь это тестирование;
                         $sql = "SELECT `date_start`, `date_end` FROM `control_tests_try_results` WHERE `test_id` = '" . $control_tests_item['id'] . "' ORDER BY `date_start` DESC LIMIT 1;";
                         $try_data = $db->row($sql);
                         if ($try_data['date_start'] != '') {
                             if ($try_data['date_end'] != '') {
                                 $result_array['status'] = 'dead_end';// сотрудник, отправляем на тесты
                             } else {
                                 $result_array['status'] = 'employee';// сотрудник, тестов нет
                             }
                         }
                     }
             }

        }   else{
            $result_array['status'] = 'error';
            $result_array['message'] = 'Неверный логин или пароль!';
        }

        $result = json_encode($result_array, true);
        die($result);
    }
}