<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_login{
    // Данные для обработки POST запросов. В эту переменную мы будем передавать массив для обработки POST запроса;
    public $post_array;

    function __construct(){

    }

//     Обучные метод который можем вызвать из модели:
    public function out_session(){
        // Возвращаем какую-то переменную;
        $_SESSION['img'] = "";
        $_SESSION['name']= "";
        $_SESSION['text'] = "";
    }

    // Метод который был вызван из контроллера с передачей параметров из POST запроса;
    /**
     *
     */
    public function test_post_method(){
        // Достаем переменные из POST запроса;
        $login = htmlspecialchars($this->post_array['login']);
        $pass = htmlspecialchars($this->post_array['pass']);

        $sql = "SELECT * FROM `users` WHERE `login`=\"" . $login . "\" AND `pass`=\"" . $pass . "\" ";

        $query_result = $GLOBALS["db"]->row($sql);
        if($query_result) {
            $_SESSION['img'] = $query_result["img"];
            $_SESSION['name'] = $query_result["name"];
            $_SESSION['text'] = $query_result["text"];
            $flag = 1;
        } else {
            $_SESSION['img'] = "";
            $_SESSION['name']= "";
            $_SESSION['text'] = "";
            $flag = 0;
        }

        $result = array(
            'result' => $flag
        );


        // После обработки данных, результат выполнения мы обязательно должны вернуть данные с функцией die() что бы скрипт не вернул вместе с данными наше представление;
        die(json_encode($result, true));
    }
}