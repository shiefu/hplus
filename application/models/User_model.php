<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    //==================================================================================================================
    // 取得後台使用者資料
    //==================================================================================================================
    public function get_user( $id = null, $account = null, $password = null, $status = null )
    {
        $this->db->select('*')->from('v_user');
        if ( !empty($id) ) {
            $this->db->where('id', $id);
        }
        if ( !empty($account) ) {
            $this->db->where('account', $account);
        }
        if ( !empty($password) ) {
            $this->db->where('password', md5($password));
        }
        if ( $status !== null ) {
            $this->db->where('status', $status);
        }

        return $this->db->get();
    }

    //==================================================================================================================
    // 更新後台使用者資料
    //==================================================================================================================
    public function update_user($id, $data)
    {
        try {
            $this->db->update('user', $data, array('id'=>$id));
            $result = array('code'=>0, 'message'=>'更新使用者资料成功');
        } catch (Exception $e) {
            $db_error = $this->db->error();
            $result = array('code'=>$db_error['code'], 'message'=>'更新使用者资料失败，'.$db_error['message']);
        }

        return $result;
    }
}