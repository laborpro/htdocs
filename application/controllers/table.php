<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Controller_table extends Controller{
    // model, view и pointer - объявлены в родительском классе;

    /*
     * model - модель, отсюда мы можем вызывать ее методы или же корректировать публичные переменные;
     * view - представление. Мы можем оставить его без ихменения, либо корректировать какими-то методами;
     * pointer - указать. формируется из первой переданного маршрута;
     * */

    // Этот метод будет выполняться когда нам не передали никакого метода;
    public function exec_default(){
        global $current_template;
        if(isset($_SESSION['user_id']) && $_SESSION['user_id']){

            $name_author_elem = "";
            $photo_author_elem = "";
            $title_book_elem = "";
            $text_book_elem = "";

        } else {
        //             имя автора
            $name_author = 'Имя автора';
            $name_author_elem = '<div class="name_author">'. $name_author .'</div>';
        //            фото автора
            $photo_author = 'deactivated_200.png';
            $url ='/templates/' .$current_template;
            $photo_author_elem = '<img class="photo_author" src="'.$url .'/images/'. $photo_author .'">';
        //            название книги
            $title_book = 'Название книги';
            $title_book_elem = '<div class="title_book">'. $title_book .'</div>';
        //            текст книги
            $text_book = 'Содержание книги';
            $text_book_elem = '<div class="text_book">'. $text_book .'</div>';
        }

        $this->view = str_replace('<div class="name_author"></div>', $name_author_elem, $this->view);
        $this->view = str_replace('<img class="photo_author">', $photo_author_elem, $this->view);
        $this->view = str_replace('<div class="title_book"></div>', $title_book_elem, $this->view);
        $this->view = str_replace('<div class="text_book"></div>', $text_book_elem, $this->view);
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