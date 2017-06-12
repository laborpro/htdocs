<div class="page_title">Внутренняя страница</div>

Если вы передадите контроллер и метод <a href="/main/some_simple_method">url/main/some_simple_method</a> - то вы увидите как переменная %some_var% будет заменнена.<br>

<div class="small_title">Придерживайте правильной архитектура MVC паттерна и не допускайте наличие PHP кода в представлении.</div>

<br>

<div class="page_title">Тест AJAX</div>

Этот запрос обработет метод модели и результатом будет сумма %some_var% чисел<br>

<div class="caption" style="margin-top: 5px;">Переменная 1</div>
<input type="number" id="var1" class="input" style="text-align: center;" value="1"><br>

<div class="caption" style="margin-top: 5px;">Переменная 2</div>
<input type="number" id="var2" class="input" style="text-align: center;" value="1"><br>

<div class="button" id="some_ajax_button">Посчитать сумму</div>

<br>

<div class="caption" style="margin-top: 5px;">Результат</div>
<input type="text" id="ajax_answer_block" class="input" style="text-align: center;" value="1"><br>