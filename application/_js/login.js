/*
* ����� �� ����� ��������� ���������� JS �������;
* */

$(document).ready(function() {

    $(document).on("click", "#test_ajax_button", function () {
        var login = $('#login').val();
        var pass = $('#pass').val();
        $.ajax({
            type: "POST",
            url: "/login/test_post_method",
            data:   {
                    login:login,
                     pass:pass
            },
            success: function (answer) {
                answer = jQuery.parseJSON(answer);
                var request_result = answer.result;
                if(request_result == 1 ){
                     // успешно

                    window.location.href = window.location.href.replace(window.location.hostname ,window.location.hostname + '/list');

                } else {
                    // если ошибка логин/пароль
                    $(".input").css("box-shadow", "0px 0px 5px 0px rgba(255,0,0,1)");
                    setTimeout(function() {
                        $(".input").css("box-shadow", "0px 0px 5px 0px rgba(0,0,0,1)");
                    }, 3000);
                }

            },
            error: function () {
            }
        });
    });

    $(".enter_click").keypress(function(e){
        if(e.keyCode==13){
            $("#test_ajax_button").click();
        }
    });


});