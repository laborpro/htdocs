<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Controller_menu extends Controller{
    // model, view и pointer - объявлены в родительском классе;

    public function exec_default(){
        // На уровне контроллера корректируем view меню в зависимости от состояния сессии;
        $login_buttons = '';

        // Есть ли управляемая компания;
        if(isset($_SESSION['control_company']) && $_SESSION['role_id'] == 1){
            $company_buttons = '
            <a href="/items_control"><div class="menu_button">Управление элементами</div></a>
            <a href="/employees_control"><div class="menu_button">Сотрудники</div></a>
            <a href="/documents_download"><div class="menu_button">Выгрузка документов</div></a>
            ';
        }   else{
            $company_buttons = '';
        }

        $this->view = str_replace('%company_buttons%', $company_buttons, $this->view);

        // Состояние авторизации пользователя;
        if(isset($_SESSION['user_id'])){

            if($_SESSION['role_id'] == 1){
                $login_buttons = '<a href="/company_control"><div class="menu_button">Компании</div></a>';
            }

            if($_SESSION['role_id'] == 3){
                $login_buttons = '<a href="/pass_test"><div class="menu_button">Пройти тестирование</div></a>';
            }

            $login_buttons .= '<a href="/exit"><div class="menu_button">Выход</div></a>';

        }   else{
            $login_buttons = '
            <a href="/sing_in"><div class="menu_button">Регистрация</div></a>
            <a href="/login"><div class="menu_button">Войти</div></a>
            ';
        }

        $this->view = str_replace('%users_buttons%', $login_buttons, $this->view);
    }
}