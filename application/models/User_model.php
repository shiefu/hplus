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
        $this->db->select('*')->from('user');
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
}