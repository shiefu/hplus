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

        $query = $this->user_model->get_user(null, $account, null, null);
        // 登錄帳號存在
        if ($query->num_rows() > 0){
            $user = $query->row_array();
            // 使用者狀態不是有效的
            if ($user['status'] != 1){
                $message = '帐号状态: '.$user['status_name'];
                redirect(site_url(ADMIN_DIR.'/home/login/'.$message));
            }
            // 登錄密碼不正確
            elseif (md5($password) != $user['password']){
                $login_fail_count = $user['login_fail_count'] + 1;
                $message = '登录密码不正确, 已登录错误'.$login_fail_count.'次, 超过'.LOCK_COUNT.'次将冻结帐号';
                $status = ($login_fail_count >= LOCK_COUNT) ? LOCK_COUNT_STATUS : $user['status'];
                $this->user_model->update_user($user['id'], array('login_fail_count' => $login_fail_count, 'status' => $status));
                redirect(site_url(ADMIN_DIR.'/home/login/'.$message));
            };
            // 登錄成功
            unset($user['password']);
            $this->session->set_userdata($user);
            $this->user_model->update_user($user['id'], array('login_fail_count' => 0, 'last_login_time' => date('Y-m-d H:i:s')));
            redirect(site_url(ADMIN_DIR));
        }
        // 登錄帳號不存在
        else {
            $message = '登录帐号不存在';
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

    public function dashboard()
    {
        $this->load->view(ADMIN_DIR.'/dashboard');
    }
}
