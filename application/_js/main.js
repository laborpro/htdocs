/*
* ����� �� ����� ��������� ���������� JS �������;
* */

$(document).ready(function() {

    $(document).on("click", "#some_ajax_button", function () {
        var var1 = $('#var1').val();
        var var2 = $('#var2').val();

        $.ajax({
            type: "POST",
            url: "/main/some_post_method",
            data: "var1=" + var1 + "&var2=" + var2,
            success: function (answer) {
                answer = jQuery.parseJSON(answer);
                var request_result = answer.result;

                $('#ajax_answer_block').val(request_result);
            },
            error: function () {
            }
        });
        
    });

});