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
        // запрашиваем массив матералов сотрудника
        $sql = "SELECT route_doc.id AS tracs, route_control_step.id as step_id,  route_control_step.step_content_id, route_control_step.son_step,  history_docs.date_finish, start_step.id AS start_step_id, father.id  AS father
                            FROM
                            route_control_step
                            LEFT JOIN history_docs
                            ON history_docs.step_id = route_control_step.id
                            INNER JOIN route_doc
                            ON route_control_step.track_number_id = route_doc.id
                            LEFT JOIN route_doc AS start_step
                            ON route_doc.route_start_step = route_control_step.id
                            left JOIN route_control_step  AS father
                            ON father.son_step = route_control_step.id
                            where
                            route_doc.company_id ='". $_SESSION['control_company']."';";

        $control_test_array = $db->all($sql);
        $step_pointer = 0; // пункт начала пути
        // Формируем ассоциативный массив из ответа
        foreach($control_test_array as $control_tests_item){
            $link[$control_tests_item['step_id']] =
                            [ "son_step"=>$control_tests_item['son_step'],
                              "date_finish"=>$control_tests_item['date_finish'],
                              "step_content_id"=>$control_tests_item['step_content_id'],
                                "step_id"=>$control_tests_item['step_id']
            ];

             if($control_tests_item["start_step_id"]!=null){
                 $step_pointer = $control_tests_item['step_id'];
             };
        }




        // поиск нужной ячейки контента для сотрудника
        $content ="";
        do {
            if($link[$step_pointer]["date_finish"] == NULL){
                $content = $link[$step_pointer]["step_content_id"];
            } else {
                if($link[$step_pointer]["son_step"] != 0){
                    $step_pointer =  $link[$step_pointer]["son_step"];
                    echo '<br>';
                } else { // законьчились материалы для проходжения
                    $content = "No";
                    header("Location: /dead_end");// переходим в тупик
                }
            }
        } while ($content == "");

        // записываем в сессию данные
        $_SESSION['step_id'] = $link[$step_pointer]['step_id']; // номер шага
        $_SESSION['step_content_id'] = $link[$step_pointer]['step_content_id'];// ячейка с контентом
        $_SESSION['son_step'] = $link[$step_pointer]["son_step"];// следующий шаг


         // Lorem
        $result = '<div class="control_test_item" id="start_test" test_id="" ></div>';

        // Отметка о начале тестированния;
        $sql = "INSERT INTO `start_step_history` (`employee_id`, `step_id`, `start_date`) VALUES('".$_SESSION['employee_id']."', '".$_SESSION['step_id']."', NOW());";
        $db->query($sql);

        return $result;
    }

    // Начинаем прохождение тестирования;
    public function start(){
        global $db, $elements;

        // получаем флаги проходжения док/тест
        $write_doc = $this->post_array['write_doc'];
        $go_to_testing = $this->post_array['go_to_testing'];

        // если теста нет а документ пройден пишим в базу конец-шага
        if(($go_to_testing == 1) && ($_SESSION['test_id'] == NULL) ){
            $sql = "INSERT INTO `history_docs` (`employee_id`, `step_id`, `date_start`,`date_finish`) VALUES('".$_SESSION['employee_id']."', '".$_SESSION['step_id']."', NOW(), NOW());";
            $db->query($sql);

            $result_array['content'] = '';
            $result_array['status'] = 'not test';// флаг для view - перейти на следующий щаг
            $result = json_encode($result_array, true);
            die($result);// ответ view
        }

        // запрашиваемм данные по шагу(док/тест названия/пути)
        $sql = "SELECT step_content.test_id , step_content.doc_id ,
                control_doc.read_doc, control_doc.doc_name,
                control_tests.test_name
                FROM step_content
                LEFT JOIN control_doc
                ON control_doc.id = step_content.doc_id
                LEFT JOIN control_tests
                ON control_tests.id = step_content.test_id
                WHERE step_content.id = '".$_SESSION['step_content_id']."';";
        $test_data = $db->row($sql);

        $test_id = $test_data['test_id'];
        $_SESSION['test_id'] = $test_id;
        $_SESSION['test_name'] = $test_data['test_name'];
        $read_doc = $test_data['read_doc'];

        // если есть документ и мы его не читали
        if($read_doc != '' && ($write_doc == 0) ){
            $result = file_get_contents(ROOT_PATH.'/application/test_docs/'.$read_doc);
            // правим пути к рисункам
            $img_link = 'SRC="/application/test_docs/';
            $result = str_replace('SRC="', $img_link, $result);
            // Создали кнопку
            $result .= $elements->button('Я ознакомился с документом.', 'go_to_testing','','','', 'test_id ="'. $test_id .'"' );
            // информационный блок
            $result .= $elements->info_box("Информированние", $read_doc ,$_SESSION['employee_id'],$_SESSION['$employee_full_name']);
            // прогресс бар с количеством вопросов($need_count);
            $result .= $elements->progress_bar_line();
            // навигация
            $result .= $elements->nav_button('Вверх', 'up');
            $result .= $elements->nav_button('Вниз', 'down');
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
        global $test_name_box;
        // информационный блок
        $result .= $elements->info_box("Тестированние",$_SESSION['test_name'],$_SESSION['employee_id'],$_SESSION['$employee_full_name']);
        // прогресс бар с количеством вопросов($need_count)
        $result .= $elements->progress_bar($need_count);
        // навигация
        $result .= $elements->nav_button('Вверх', 'up');
        $result .= $elements->nav_button('Вниз', 'down');
        // Снопка для завершения тестированияж
        $result .= $elements->button('Завершить тестирование', 'finish_test');
        $result .= $elements->button('Закрыть тест', 'close_test');


        $result .= '</div>';



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
        $sql = "SELECT `id` FROM `start_step_history` WHERE `step_id` = '".$_SESSION['step_id']."' ORDER BY `start_date` DESC LIMIT 1;";
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
            // прошли тест - записываем step-end
            $sql = "INSERT INTO `history_docs` (`employee_id`, `step_id`, `date_start`,`date_finish`) VALUES('".$_SESSION['employee_id']."', '".$_SESSION['step_id']."', NOW(), NOW());";
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