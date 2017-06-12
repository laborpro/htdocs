<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 04.03.2017
 * Time: 15:11
 */
class Controller_pages extends Controller{
    // model, view � pointer - ��������� � ������������ ������;

    public function exec_default(){
        // � ����� ������, � ������ �����, ������������ ������� �����, ����� ������� �������� �������������� �������� - ������ ��������� ��� �� ����������;
        // ������� ����� �� ����� ������ �������� �� 404-� ��������;
        header("Location: ".URL."/error_404");
        exit();
    }

    /*
     * ����� � ������ ������ ��� ����� ���������� ��������� ��������. ��� ������� �� ������ ������������ �������� ������� ��� ��� ������;
     * ���� � ��. ������ �������, ��� � ����������� ������������ �� �� �����. ���� �� ��������� � ��, ����������� ������ - �� ����� �������� ��������� MVC;
     * */

    private function get_page_view($page_name){
        $this->view = file_get_contents(ROOT_PATH.'/application/viewers/pages/'.$page_name.'.php');
    }

    // ������ ��� ��������� ������ �������;
    public function page1(){
        $this->get_page_view(__FUNCTION__);
    }


    public function page2(){
        $this->get_page_view(__FUNCTION__);
    }
}