/**
 * Created by root on 01.03.2017.
 */
function message(text, type){
    var message_div = $('#message');
    var icon = '';

    message_div.fadeOut(200);

    setTimeout(function(){

        preloader('', 'hide');

        switch(type){
            case 'info':
                icon = '<img src="/templates/simple_template/images/icons/info.png" width="24px">';
                break;
            case 'error':
                icon = '<img src="/templates/simple_template/images/icons/error.png" width="24px">';
                break;
            case 'ok':
                icon = '<img src="/templates/simple_template/images/icons/ok.png" width="24px">';
                break;
            default:
                icon = '<img src="/templates/simple_template/images/icons/info.png" width="24px">';
        }

        message_div.html(icon + '<br>' + text);

        message_div.fadeIn(200);

        window.show_message = setTimeout(function(){
            message_div.fadeOut(200);
        }, 3000);

    }, 200);
}

function preloader(text, type){
    var preloader_div = $('#preloader');
    var message_div = $('#preloader_text');
    message_div.html(text);

    if(type == 'show'){
        preloader_div.fadeIn(200);
    }   else{
        setTimeout(function(){
            preloader_div.fadeOut(200);
        }, 1000);
    }
}