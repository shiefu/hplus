<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    // 登入後的主頁
    public function index()
    {
        $this->load->view(ADMIN_DIR.'/index');
    }

    // 登入頁面
    public function login()
    {
        $this->load->view(ADMIN_DIR.'/login');
    }

}
