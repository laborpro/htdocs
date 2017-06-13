<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.03.2017
 * Time: 15:11
 */
class Controller_menu extends Controller{
    // model, view и pointer - объявлены в родительском классе;

    public function exec_default(){
        // На уровне контроллера корректируем view меню в зависимости от состояния сессии или любых других параметров;
        if(isset($_SESSION['name']) && $_SESSION['name'] != '') {
            $this->view = str_replace('<a href="/login"><div class="menu_button">Авторизация</div></a>', "", $this->view);
        }
    }
// новый гита
}