/**
 * Created by root on 14.03.2017.
 */
$(document).ready(function() {

    // флаги док/тест
    var write_doc = 0;
    var go_to_testing = 0;
    // достаём тест test_id для ajax
    var test_id = $("#start_test").attr('test_id');
        // стартовый запрос на сервер для запуска логики
        $.ajax({
            type: "POST",
            url: "/pass_test/start",
            data: {
                  test_id:test_id,
                write_doc:write_doc
                },
            success: function (answer) {
                //  рисуем первый тест/док
                var result = jQuery.parseJSON(answer);
                var request_result = result.status;
                var request_message = result.message;
                var content = result.content;
                if(request_result == 'ok'){
                    $(".page_title").css("display","none");
                    $(".control_test_item").css("display","none");
                    $("#test_block").css("margin-top","-40px");
                    $("#content_box").css("padding-top","65px")
                    $('#test_block').fadeIn(0);
                    $('#content_box').html(content);
                }
                message(request_message, request_result);
            },
            error: function () {
            }
        });
    //});

// логика работы тестов и прогресс-бара(по клику меняем состояние)
    $(document).on("click", ".test_answer", function () {
        $(this).parent('div').children('.test_answer').each(function(){
            $(this).removeClass('selected_answer');
            $(this).addClass('unselected_answer');
        });
        // шапокляк для progress_bar_items
        var answer_count = 1;
        $('.selected_answer').each(function(index){
            answer_count++;
        });

        var test_question = 0;
        $('.test_question').each(function(){
            test_question++;
        });
        $('.progress_plan').html("");
        $('.progress_plan').html(test_question);

        $('.progress_fact').html("");
        $('.progress_fact').html(answer_count);
        $(".progress_bar_item").each(function(index) {
            if(index<answer_count) {
                $(this).css("background-color", "#00BCD4");
            }
        });
        // шапокляк end

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
    // запрос второго материала по шагу
    $(document).on("click", "#go_to_testing", function () {

        write_doc = 1;
        go_to_testing = 1;
        var test_id = $(this).attr('test_id');
        // Начинаем прохождение теста;
        $.ajax({
            type: "POST",
            url: "/pass_test/start",
            data: {
                test_id:test_id,
                write_doc:write_doc,
                go_to_testing:go_to_testing
            },
            success: function (answer) {

                var result = jQuery.parseJSON(answer);
                var request_result = result.status;
                var request_message = result.message;
                var content = result.content;
                // если 'ok' - рисуем тест
                if(request_result == 'ok'){
                    $(".page_title").css("display","none");
                    $(".control_test_item").css("display","none");
                    $("body").css("margin-top","0px");
                    $('#test_block').fadeIn(0);
                    $('#content_box').html(content);
                    $("html, body").animate({ scrollTop: 0 }, 0);
                }
                // перезагружаем на следующий step, если нет теста
                if(request_result == 'not test'){
                    window.location = "/pass_test";
                }
                message(request_message, request_result);
            },
            error: function () {
            }
        });
    });
    // скрипт работы кнопок навигации
    $(document).on("click", "#down", function () {
        window.scrollBy(0, 200);
    });
    $(document).on("click", "#up", function () {
        window.scrollBy(0, -200);
    });

    // скрипт для progress_bar_line
    $(window).on("scroll resize", function() {
        var proc = $(window).scrollTop() / ($(document).height() - $(window).height());
        $(".progress_bar_line_back").css({
            "width": (100 * proc | 0) + "%"
        });
        $('.progress_line_proc').html("");
        $('.progress_line_proc').html((100*proc|0) + "%");

    })

});
