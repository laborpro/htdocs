<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_list{
    // Данные для обработки POST запросов. В эту переменную мы будем передавать массив для обработки POST запроса;
    public $post_array;

    function __construct(){

    }

//     Обучные метод который можем вызвать из модели:
    public function some_simple_method(){
        // Возвращаем какую-то переменную;
        $var = '"ЭТО ТЕКСТ ИЗ МЕТОДА"';

        return $var;
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
            $text = $query_result["text"];
        }
        $result = array(
            'result' => $text
        );


        // После обработки данных, результат выполнения мы обязательно должны вернуть данные с функцией die() что бы скрипт не вернул вместе с данными наше представление;
        die(json_encode($result, true));
    }
}