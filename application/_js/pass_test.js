/**
 * Created by root on 14.03.2017.
 */
$(document).ready(function() {

    $(document).on("click", ".control_test_item", function () {
        // Начинаем прохождение теста;
        var test_id = $(this).attr('test_id');

        $.ajax({
            type: "POST",
            url: "/pass_test/start",
            data: "test_id=" + test_id,
            success: function (answer) {

                var result = jQuery.parseJSON(answer);
                var request_result = result.status;
                var request_message = result.message;
                var content = result.content;



                if(request_result == 'ok'){
                    $('#test_block').fadeIn(200).html(content);
                }

                message(request_message, request_result);
            },
            error: function () {
            }
        });
    });


    $(document).on("click", ".test_answer", function () {
        $(this).parent('div').children('.test_answer').each(function(){
            $(this).removeClass('selected_answer');
            $(this).addClass('unselected_answer');
        });

        $(this).removeClass('unselected_answer');
        $(this).addClass('selected_answer');
    });

    $(document).on("click", "#finish_test", function () {
        var test_result = new Array();

        var test_id = $(this).parents('#test').attr('test_id');

        var answers = new Array();

        // Сичет наличия ответов в вопросах;
        var exist_questions = 0;

        // Сичет наличия ответов в вопросах;
        var exist_answers = 0;

        // Проверяем, на все ли вопросы ответил пользователь;
        $('.test_question').each(function(){
            exist_questions++;

            // Проверяем что есть ответ на этот вопрос;
            $(this).children('.test_answer').each(function(){
                var answer_id = $(this).attr('answer_id');

                if($(this).hasClass('selected_answer') == true){
                    answers.push(answer_id);
                    exist_answers++;
                }
            });
        });

        // Количество сделаных ответов и вопросов должны совпадать;
        if(exist_questions != exist_answers){
            message('Вам нужно ответить на все вопросы для завершения тестирования!', 'error');
            return;
        }

        // ДОбавляем полученные ответы в результирующий массив;
        test_result.push({'test_id' : test_id, 'answers' : answers});

        // Отдаем серверу результаты тестирования на проверку;
        $.ajax({
            type: "POST",
            url: "/pass_test/processing_results",
            data: "test_result=" + JSON.stringify(test_result),
            success: function (answer) {

                var result = jQuery.parseJSON(answer);
                var request_result = result.status;
                var request_message = result.message;

                message(request_message, request_result);

                setTimeout(function(){
                    location.reload();
                }, 3000);

            },
            error: function () {
            }
        });

    });

    $(document).on("click", "#close_test", function () {
        location.reload();
    });


});
