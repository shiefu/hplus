<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
    }

    //======================================================================================================================
    // 登錄後的主頁
    //======================================================================================================================
    public function index()
    {
        check_admin_login();
        $this->load->view(ADMIN_DIR.'/index');
    }

    //======================================================================================================================
    // 登錄頁面
    //======================================================================================================================
    public function login($message = null)
    {
        $data['message'] = $message;
        $this->load->view(ADMIN_DIR.'/login', $data);
    }

    //======================================================================================================================
    // 進行登錄動作
    //======================================================================================================================
    public function do_login()
    {
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        // 檢查帳號
        if (is_null($account)){
            $message = '登录帐号不能为空白';
            redirect(site_url(ADMIN_DIR.'/home/login/'.$message));
        }
        // 檢查密碼
        if (is_null($password)){
            $message = '登录密码不能为空白';
            redirect(site_url(ADMIN_DIR.'/home/login/'.$message));
        }
        // 檢查帳號、密碼跟資料庫是否相符
        $query = $this->user_model->get_user(null, $account, $password, null);
        if ($query->num_rows() > 0){
            $user = $query->row_array();
            unset($user['password']);
            $this->session->set_userdata($user);
            redirect(site_url(ADMIN_DIR));
        }
        else {
            $message = '登录帐号或密码不正确';
            redirect(site_url(ADMIN_DIR.'/home/login/'.$message));
        }
    }

    //======================================================================================================================
    // 進行登出動作
    //======================================================================================================================
    public function do_logout()
    {
        $this->session->sess_destroy();
        redirect(site_url(ADMIN_DIR.'/home/login/'));
    }
}
