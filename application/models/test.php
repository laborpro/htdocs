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


//        require_once(ROOT_PATH . '/safemysql.class.php');
//        require_once(ROOT_PATH . '/DbTree.class.php');
//        require_once(ROOT_PATH . '/DbTreeExt.class.php');
//
//// Data base connect
//        $dsn['user'] = 'root';
//        $dsn['pass'] = 'Ci2OheR7';
//        $dsn['host'] = 'localhost';
//        $dsn['db'] = 'laborpro';
//        $dsn['charset'] = 'utf8';
//        $dsn['errmode'] = 'exception';
//
//        define('DEBUG_MODE', false);
//
//
//
//
//        $sql = 'SET NAMES utf8';
//        $db->query($sql);
//
//        $tree_params = array(
//            'table' => 'organization_structure',
//            'id' => 'id',
//            'left' => 'left_key',
//            'right' => 'right_key',
//            'level' => 'level'
//        );
//        $db = new SafeMySQL($dsn);
//        $dbtree = new DbTreeExt($tree_params, $db);









//        $dbtree->MoveAll(1,27);


        $sql = "SELECT
                CONCAT_WS (':', items_control_types.name, items_control.name) AS erarh,
                organization_structure.`level`,
                organization_structure.id,
                organization_structure.left_key,
                organization_structure.right_key,
                employees_items_node.org_str_id as 'employee',
                CONCAT_WS (' ',employees.surname , employees.name, employees.second_name) AS fio
                FROM organization_structure
                inner join items_control on organization_structure.kladr_id = items_control.id
                inner join items_control_types on organization_structure.items_control_id = items_control_types.id
                left join employees_items_node on employees_items_node.org_str_id = organization_structure.id
                left join employees on employees_items_node.employe_id = employees.id
                Where organization_structure.company_id =".$_SESSION['control_company']."  ORDER BY left_key";

        $employees = $db->all($sql);
//        print_r($employees);
        $down_id = 20;// если надо вывести конкретный отдел
        $left = 0;
        $right = 0;
        if(isset($down_id)){
            foreach($employees as $employee_key) {
                if ($down_id == $employee_key['id']) {
                    $left = $employee_key['left_key'];
                    $right = $employee_key['right_key'];
                }
            }
        }

        foreach($employees as $employee){
            if($down_id>0){

            if((($left <= $employee['left_key']) && ($right >= $employee['right_key'])) || (($left >= $employee['left_key']) && ($right <= $employee['right_key']))){
                $item = str_repeat('&#8195;', $employee['level'] - 1);
                if (isset($employee['fio'])) {
                    $name = $employee['fio'];
                } else {
                    $name = "";
                }
                echo $item . $employee['erarh'] . " / " . $name . "<br>";
            }
        } else {
                $item = str_repeat('&#8195;', $employee['level'] - 1);
                if (isset($employee['fio'])) {
                    $name = $employee['fio'];
                } else {
                    $name = "";
                }
                echo $item . $employee['erarh'] . " / " . $name . "<br>";
            }
        }


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
//        $tree_params= $dbtree->Branch(27,"*","");
//        // отобразать узлы
//        echo $dbtree->MakeUlList($tree_params,"id","",""," ");






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