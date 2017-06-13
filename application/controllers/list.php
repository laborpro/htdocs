<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Controller_list extends Controller{
    // model, view и pointer - объявлены в родительском классе;

    /*
     * model - модель, отсюда мы можем вызывать ее методы или же корректировать публичные переменные;
     * view - представление. Мы можем оставить его без ихменения, либо корректировать какими-то методами;
     * pointer - указать. формируется из первой переданного маршрута;
     * */

    // Этот метод будет выполняться когда нам не передали никакого метода;
    public function exec_default(){
        $img = isset($_SESSION['img']) && $_SESSION['img'] != '' ? $_SESSION['img'] : 'deactivated_200.png';
        $name = isset($_SESSION['name']) && $_SESSION['name'] != '' ? $_SESSION['name'] : 'Нет имени';
        $text = isset($_SESSION['text']) && $_SESSION['text'] != '' ? $_SESSION['text'] : 'Нет текста';

        global $current_template;
        $url ='/templates/' .$current_template;
        $img_string = '<img src="'.$url .'/images/'. $img .'">';

        $name_string = '<div class="page_title"  id="list_name">'. $name .'</div>';

        $text_string = '<p id="list_text">'. $text .'</p>';

        $this->view = str_replace('<img id="list_images">', $img_string, $this->view);
        $this->view = str_replace('<div class="page_title"  id="list_name"></div>', $name_string, $this->view);
        $this->view = str_replace('<p id="list_text"></p>', $text_string, $this->view);

    }

    // Обычый метод;
//    public function some_simple_method(){
//        // Здесь мы можем провести нужные операции;
//
//        // Вызвать метод из модели получив данные;
//        $model_data = $this->model->some_simple_method();
//
//        // Например с представлением;
//        $this->view = str_replace('%some_var%', $model_data, $this->view);
//    }


    /*
     * Если нам метод вызывается из вне по средствам (например) AJAX и принимает $_POST данные, нам нужно их принять переменно метода;
     * */
    // Пример метода, который будет вызываться из вне, и принимать какие-либо данные через $_POST запрос;
    public function test_post_method($post_data){
        // Записываем массив с данными в модель;
        $this->model->post_array = $post_data;

        // Запускаем метод модели, который обработае POST данные;
        $this->model->test_post_method();
    }


}