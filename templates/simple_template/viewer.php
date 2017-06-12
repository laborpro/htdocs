<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Simple Template</title>
    <link rel="icon" type="/image/png" href="/templates/<?echo $current_template;?>/images/icon_core.png" />
    <link rel="stylesheet" href="/templates/<?echo $current_template;?>/css/style.css" type="text/css" />
    <script type="application/javascript" src="/templates/<?echo $current_template;?>/js/jquery.js"></script>
    <?
    // Здесь мы будем выводить блок в js переданные нам viewer;
    if(isset($viewer_js) && $viewer_js != ''){
        echo $viewer_js;
    }
    ?>
</head>
<body>

<div id="header">
    <div class="page_title">Какой-то общий заголовок</div>
    <div id="menu">
        <?
        // Здесь мы будем выводит меню которое передал нам маршрутизатор;
        if(isset($menu_viewer) && $menu_viewer != ''){
            echo $menu_viewer;
        }
        ?>
    </div>
</div>

<div id="body">
    <?
        // Здесь мы будем выводит отображение которое передал нам маршрутизатор;
        if(isset($inside_viewer) && $inside_viewer != ''){
            echo $inside_viewer;
        }
    ?>
</div>

<div id="bottom">Какой-то общий низ (мини-карта или копирайт)</div>

</body>
</html>