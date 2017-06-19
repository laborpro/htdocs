<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_pass_test{
    // Данные для обработки POST запросов;
    public $post_array;

    function __construct(){
        //echo 'Это конструкционный метод вызванный подключение модели '.__CLASS__.'<br>';
    }

    // Получаем список тестов;
    public function load_tests_list(){
        global $db, $systems;

        $sql = "SELECT * FROM `control_tests` WHERE `company_id` = '".$_SESSION['control_company']."';";
        $control_test_array = $db->all($sql);

        $result = '';

        foreach($control_test_array as $control_tests_item){
            // Проверяем проходил ли пользователь это тестирование;
            $sql = "SELECT `date_start`, `date_end` FROM `control_tests_try_results` WHERE `test_id` = '".$control_tests_item['id']."' ORDER BY `date_start` DESC LIMIT 1;";
            $try_data = $db->row($sql);

            $try_html = '';

            if($try_data['date_start'] != ''){
                $try_html .= '<br><br>';
                $try_html .= 'Последний раз вы пытались пройти тестирование: '.$systems->get_local_date_time($try_data['date_start'], 1);
                $try_html .= '<br>';
                if($try_data['date_end'] != ''){
                    $try_html .= 'Тестирование было успешно пройдено.';
                }   else{
                    $try_html .= 'Тестирование не было закончено или пройдено.';
                }
            }

            $result = '<div class="control_test_item" test_id="'.$control_tests_item['id'].'"><b>'.$control_tests_item['test_name'].'</b>'.$try_html.'</div>';
        }

        return $result;
    }

    // Начинаем прохождение тестирования;
    public function start(){
        global $db, $elements;

        $test_id = $this->post_array['test_id'];
        $write_doc = $this->post_array['write_doc'];
        $sql = "SELECT * FROM `control_tests` WHERE `id` = '".$test_id."';";
        $test_data = $db->row($sql);
        if($test_data['preread_doc'] != '' && ($write_doc == 0) ){
            $result = file_get_contents(ROOT_PATH.'/application/test_docs/'.$test_data['preread_doc']);
            // правим пути к рисункам
            $img_link = 'SRC="/application/test_docs/';
            $result = str_replace('SRC="', $img_link, $result);
            // Создали кнопку
            $result .= $elements->button('Я ознакомился с документом, перейти к тестированнию', 'go_to_testing','','','', 'test_id ="'. $test_id .'"' );

//           $result = mb_convert_encoding($result, 'utf-8', 'cp1251');

            $result_array['message'] = 'Сначала ознакомитесь с содержанием представленного документа, а затем начнется тестирование.';

        }  else {
            // Если текст для прочтения нет - начинаем тестирование;
            $result = $this->test_try($test_id, $test_data['test_name']);

            $result_array['message'] = 'Тестирование началось. В каждом из представленных вопросов, вам нужно выбрать один из вариантов.';
        }

        $result_array['content'] = $result;
        $result_array['status'] = 'ok';

        $result = json_encode($result_array, true);
        die($result);
    }

    // Приватный метод начала тестирования;
    private function test_try($test_id, $test_name){
        global $db, $elements;

        $result = '<div id="test" test_id="'.$test_id.'">';

        $result .= '<br>';

        // Название теста;
        $result .= '<div class="page_title">'.$test_name.'</div>';

        // Поулчаем списко вопросов;
        $sql = "SELECT * FROM `control_tests_questions` WHERE `test_id` = '".$test_id."';";
//        echo $sql;
        $questions = $db->all($sql);
        $questions_id_array = array();

        foreach($questions as $question){
//            echo $question['id'];
            $questions_id_array[] = $question['id'];
        }

        // УЗнаем, сколько нам надо показать пользователю вопросов;
        $sql = "SELECT `questions_count` FROM `control_tests` WHERE `id` = '".$test_id."';";
        $need_count = $db->one($sql);
        // Если задано какое-то число;


        if($need_count != 0){
            // Получаем все ID вопросов;
            $keys = array_rand($questions_id_array,$need_count);

            foreach ($keys as $key=>$value) {
                $questions_id_array[$key] = $questions_id_array[$value];
            }
        }




        // Получаем список ответов;


        $sql = "SELECT * FROM `control_tests_answers` WHERE `question_id` IN (".implode(',', $questions_id_array).");";
//        echo $sql;
        $answers_array = $db->all($sql);
        $answers = array();

        // Немного переработает массив с вариантами ответов;
        foreach($answers_array as $answer_item){
            $answers[$answer_item['question_id']][] =
                array(
                    'answer_id' => $answer_item['id'],
                    'answer_text' => $answer_item['answer_text']
                );
        }
        // После формирования массива с вариантами ответов, формируем список вопросов;
        foreach($questions as $question){
            // Исключаем вопросы которые мы могли ограничить;
            if(!in_array($question['id'], $questions_id_array)){
                continue;
            }

            $result .= '<div class="test_question" question_id="'.$question['id'].'">';
            $result .= '<b>'.$question['question_text'].'</b>';
            $result .= '<br><br>';

            foreach($answers as $question_id => $answer_array){
                if($question['id'] == $question_id){
                    foreach($answer_array as $answer){
                        $result .= '<div class="test_answer unselected_answer" answer_id="'.$answer['answer_id'].'">';
                        $result .= $answer['answer_text'];
                        $result .= '</div>';
                    }
                }
            }

            $result .= '</div>';
            $result .= '<br>';
        }
        // Снопка для завершения тестированияж
        $result .= $elements->button('Завершить тестирование', 'finish_test');
        $result .= $elements->button('Закрыть тест', 'close_test');

        $result .= '</div>';

        // И в базе помечаем что пользователь начал тестирование;
        $sql = "INSERT INTO `control_tests_try_results` (`user_id`, `test_id`, `date_start`) VALUES('".$_SESSION['user_id']."', '".$test_id."', NOW());";
        $db->query($sql);

        return $result;

    }

    // Обработка результатов тестирования;
    public function processing_results(){
        global $db;

        // Получаем результат;
        $result_array = json_decode($this->post_array['test_result'], true);
        $result_array = $result_array[0];

        // Начинаем его сверять ответы с правильными;
        $test_id = $result_array['test_id'];

        // Получаем ID попытки;
        $sql = "SELECT `id` FROM `control_tests_try_results` WHERE `test_id` = '".$test_id."' ORDER BY `date_start` DESC LIMIT 1;";
        $try_id = $db->one($sql);

        // Ответы;
        $result_answers = $result_array['answers'];

        // Счет правильных ответов;
        $right_answers = 0;

        foreach($result_answers as $answer_id){
            // ПРоверяем, был ли этот ответ правильным;
            $sql = "SELECT `right` FROM `control_tests_answers` WHERE `id` = '".$answer_id."';";
            $right = $db->one($sql);

            if($right == 1){
                $right_answers++;
            }

            // Записываем таблицу в базу информацию о сделанных ответах;
            $sql = "INSERT INTO `control_tests_questions_results` (`try_id`, `answer_id`, `date`) VALUES('".$try_id."', '".$answer_id."', NOW());";
            $db->query($sql);
        }

        // Сколько нужно правильных ответов для теста?;
        $sql = "SELECT `questions_need_for_finish` FROM `control_tests` WHERE `id` = '".$test_id."';";
        $need_right_answers = $db->one($sql);

        // Если правильных ответов большие или столько же сколько нужно для прохождения теста - закрываем тестирование;
        if($right_answers >= $need_right_answers){
            // Закрываем попытку;
            $sql = "UPDATE `control_tests_try_results` SET `date_end` = NOW() WHERE `id` = '".$try_id."';";
            $db->query($sql);

            $result_array['message'] = 'Поздравляем! Вы успешно прошли тестирование ответив правильно на '.$right_answers.' из '.count($result_answers);
            $result_array['status'] = 'ok';
        }   else{
            $result_array['message'] = 'К сожалению вы не прошли тестирование. Вы не набрали минимальное количество правильных вопросов. Повторите попытку позже';
            $result_array['status'] = 'error';
        }

        $result = json_encode($result_array, true);
        die($result);
    }
}