<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.02.2017
 * Time: 15:07
 */
class Controller_exit extends Controller{
    // model, view � pointer - ��������� � ������������ ������;

    public function exec_default(){
        session_destroy();

        $result_array = array();
        $result_array['status'] = 'ok';
        $result_array['message'] = '�� ������� �������� �������';

        echo json_encode($result_array, true);
    }
}