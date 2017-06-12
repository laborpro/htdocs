<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.03.2017
 * Time: 15:11
 */
class Controller_pages extends Controller{
    // model, view и pointer - объявлены в родительском классе;

    public function exec_default(){
        // В нашем случае, в данный метод, пользователь попадет тогда, когда передал название несуществующей страницы - Роутер перенесет его на контроллер;
        // Поэтому здесь мы смело ставим заглушку на 404-ю страницу;
        header("Location: ".URL."/error_404");
        exit();
    }

    /*
     * Здесь я привел пример как можно подгрухать локальные страницы. При желании вы можете организовать хранение страниц как вам удобно;
     * Хоть в БД. Только помните, что в контроллере использовать БД не нужно. Если вы работаете в БД, используйте модель - не нужно нарушать концепцию MVC;
     * */

    private function get_page_view($page_name){
        $this->view = file_get_contents(ROOT_PATH.'/application/viewers/pages/'.$page_name.'.php');
    }

    // Методы для обработки разных страниц;
    public function page1(){
        $this->get_page_view(__FUNCTION__);
    }


    public function page2(){
        $this->get_page_view(__FUNCTION__);
    }
}