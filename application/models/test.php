<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Model_test
{
    // Данные для обработки POST запросов;
    public $post_array;

    function __construct()
    {
        //echo 'Это конструкционный метод вызванный подключение модели '.__CLASS__.'<br>';
    }

    // тестим здесь
    public function test()
    {
        global $db, $systems;


        require_once(ROOT_PATH . '/safemysql.class.php');
        require_once(ROOT_PATH . '/DbTree.class.php');
        require_once(ROOT_PATH . '/DbTreeExt.class.php');

// Data base connect
        $dsn['user'] = 'root';
        $dsn['pass'] = 'Ci2OheR7';
        $dsn['host'] = 'localhost';
        $dsn['db'] = 'laborpro';
        $dsn['charset'] = 'utf8';
        $dsn['errmode'] = 'exception';

        define('DEBUG_MODE', false);




        $sql = 'SET NAMES utf8';
        $db->query($sql);

        $tree_params = array(
            'table' => 'organization_structure',
            'id' => 'id',
            'left' => 'left_key',
            'right' => 'right_key',
            'level' => 'level'
        );
        $db = new SafeMySQL($dsn);
        $dbtree = new DbTreeExt($tree_params, $db);









//        $dbtree->MoveAll(1,27);





        // добавление
        // $data - массив контент, кроме обязательных
//        $data = array(
//            'parent' => '1'
//        );
////         id родителя, данные, условия
//        $dbtree->Insert(1,$data,"");



        // id родителя, условия

//
//        $sections = $dbtree->Full(
//            array(
//                'id',
//                'level'
//            )
//        );
//
//        // Обрабатываем полученное дерево
//
//        foreach ($sections as $item) {
//            // Делаем отступы (лесенка) согласно уровню вложенности
//            $item['spacer'] = str_repeat(' |- ', $item['level'] - 1);
//
//            // Печатаем на экран дерево с правильными отступами
//            echo $item['spacer'] . $item['id'] . '<br />';
//        }

//        $dbtree->Delete(19,"");
// Перемешение с потомками, id_item, id будущего родителя
//        $dbtree->MoveAll(4,10,array('and' => array('id = 4')));
        // получить все ветки начиная с id родителя(27)
        $tree_params= $dbtree->Branch(27,"*","");
        // отобразать узлы
        echo $dbtree->MakeUlList($tree_params,"id","",""," ");





// Method 1: Swapping nodes within the same level and limits of one parent with all its children.
        if (!empty($_GET['action']) && 'move_1' == $_GET['action']) {

            // Change node ($_GET['section_id']) position and all its childrens to
            // before or after ($_POST['position']) node 2 ($_POST['section2_id'])
            $dbtree->ChangePositionAll((int)$_GET['section_id'], (int)$_POST['section2_id'], $_POST['position']);

            header('Location:dbtree_demo.php');
            exit;
        }

        /* ------------------------ MOVE FORM------------------------ */

// Move section form
        if (!empty($_GET['action']) && 'move' == $_GET['action']) {

            // Prepare the restrictive data for the first method:
            // Swapping nodes within the same level and limits of one parent with all its children
            $current_section = $dbtree->GetNode((int)$_GET['section_id']);
            $parents = $dbtree->Parents((int)$_GET['section_id'], array('section_id'), array('and' => array('section_level = ' . ($current_section['section_level'] - 1))));

            $item = current($parents);
            $branch = $dbtree->Branch($item['section_id'], array('section_id', 'section_name'), array('and' => array('section_level = ' . $current_section['section_level'])));

            // Create form


        }
    }
}