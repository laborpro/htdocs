<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_main{
    // Данные для обработки POST запросов. В эту переменную мы будем передавать массив для обработки POST запроса;
    public $post_array;

    function __construct(){

    }

    // Обучные метод который можем вызвать из модели:
    public function some_simple_method(){
        // Возвращаем какую-то переменную;
        $var = '"ЭТО ТЕКСТ ИЗ МЕТОДА"';

        return $var;
    }

    // Метод который был вызван из контроллера с передачей параметров из POST запроса;
    public function some_post_method(){
        // Достаем переменные из POST запроса;
        $var1 = $this->post_array['var1'];
        $var2 = $this->post_array['var2'];

        $result = array(
            'result' => $var1 + $var2
        );

        // После обработки данных, результат выполнения мы обязательно должны вернуть данные с функцией die() что бы скрипт не вернул вместе с данными наше представление;
        die(json_encode($result, true));
//        'some message'
    }
}