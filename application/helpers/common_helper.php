<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//======================================================================================================================
// 檢查是否登入
//======================================================================================================================
if ( ! function_exists('check_login'))
{
    function check_login(){
        if ( ! get_instance()->session->userdata('MemberID')) {
            redirect('/');
        }
    }
}

//======================================================================================================================
// 檢查後台是否登入
//======================================================================================================================
if ( ! function_exists('check_admin_login'))
{
    function check_admin_login(){
        if ( ! get_instance()->session->userdata('account')) {
            redirect(site_url(ADMIN_DIR.'/home/login'));
        }
    }
}

//======================================================================================================================
// 依據不同裝置取得view的路徑
//======================================================================================================================
if ( ! function_exists('get_view_folder'))
{
    function get_view_folder()
    {
        if (get_instance()->agent->is_mobile() )
            //return 'mobile';
            return 'desktop';
        else
            return 'desktop';
    }
}

//======================================================================================================================
// 取得前端asset路徑
//======================================================================================================================
if ( ! function_exists('get_assets_path'))
{
    function get_assets_path()
    {
        if (get_instance()->agent->is_mobile() )
            //return 'mobile';
            return 'desktop';
        else
            return 'desktop';
    }
}

//======================================================================================================================
// 取得輸入的日期是星期幾
//======================================================================================================================
if ( ! function_exists('get_weekday'))
{
    function get_weekday($date)
    {
        $date = date_create($date);
        $w = date_format($date, 'w');
        switch($w){
            case 0:
                return '日';
            case 1:
                return '一';
            case 2:
                return '二';
            case 3:
                return '三';
            case 4:
                return '四';
            case 5:
                return '五';
            case 6:
                return '六';
            default:
                return '';
        }
    }
}

//======================================================================================================================
// 取得日期是否存在於陣列中
//======================================================================================================================
if ( ! function_exists('find_from_array'))
{
    function find_from_array($key, $value, $array)
    {
        $temp = array();
        foreach($array as $a)
        {
            if ($a[$key] == $value)
            {
                array_push($temp, $a);
            }
        }
        return $temp;
    }
}

//======================================================================================================================
// 將DOMElement轉成HTML
//======================================================================================================================
if ( ! function_exists('get_gameresult_HTML')) {
    function get_gameresult_HTML($gamedate = NULL)
    {
        if ( ! isset($gamedate)){
            $gamedate = date('Y-m-d');
        }
        //前台登入
        $account = 'DV027';
        $pwd = 'aa1234';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://w1.tg333.net/controler/session.php');
        curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/32.0.1700.107 Chrome/32.0.1700.107 Safari/537.36');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "account=$account&pwd=$pwd");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie-name');
        curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookiefile');
        curl_exec($ch);
        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        //取得賽事結果
        curl_setopt($ch, CURLOPT_URL, 'http://w1.tg333.net/gameresult.php?checkdata='.$gamedate);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);

        if (curl_error($ch)) {
            echo curl_error($ch);
        }

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        $dom->preserveWhiteSpace = false;

        $finder = new DomXPath($dom);
        $classname = 'gameresult';
        $gameresult = $finder->query("//*[contains(@class, '$classname')]")->item(0);

        $doc = $gameresult->ownerDocument;
        $html = '';
        foreach ($gameresult->childNodes as $node) {
            $html .= $doc->saveHTML($node);
        }

        return $html;
    }
}

/*
//檢查使用者帳號是否可被修改
if ( ! function_exists('check_user_editable'))
{
    function check_user_editable($userid, $puserid){
        $ci =& get_instance();
        $ci->load->database();

        if ($puserid == $userid){
            return TRUE;
        }

        $isfound = FALSE;
        $sql = 'SELECT PUserID FROM Users WHERE UserID = ?';
        if ($ci->db->query($sql, $userid)->num_rows() > 0){
            $user = $ci->db->query($sql, $userid)->row_array();
            WHILE (($user["PUserID"] != NULL || !empty(trim($user["PUserID"]))) && $isfound == FALSE){
                if ($user["PUserID"] == $puserid){
                    $isfound = TRUE;
                }else{
                    $user = $ci->db->query($sql, $user["PUserID"])->row_array();
                }
            }
        }else{
            $isfound = FALSE;
        }

        return $isfound;
    }
}

//檢查使用者帳號是否可觀看
if ( ! function_exists('check_user_viewable'))
{
    function check_user_viewable($userid){
        $ci =& get_instance();
        $ci->load->database();

        if ($ci->session->userdata('UserType') == 10 || $userid == $ci->session->userdata('UserID')){
            $isfound = TRUE;
        }else{
            $isfound = FALSE;
            $puserid = $ci->session->userdata('UserID');
            $sql = 'SELECT PUserID FROM Users WHERE UserID = ?';
            if ($ci->db->query($sql, $userid)->num_rows() > 0){
                $user = $ci->db->query($sql, $userid)->row_array();
                while (($user["PUserID"] != NULL || !empty(trim($user["PUserID"]))) && $isfound == FALSE){
                    if ($user["PUserID"] == $puserid){
                        $isfound = TRUE;
                    }else{
                        $user = $ci->db->query($sql, $user["PUserID"])->row_array();
                    }
                }
            }else{
                $isfound = FALSE;
            }
        }

        return $isfound;
    }
}

//檢查會員帳號是否可被修改
if ( ! function_exists('check_member_editable'))
{
    function check_member_editable($memberid, $userid){
        $ci =& get_instance();
        $ci->load->database();
        $ci->load->model('users_model');
        $ci->load->model('members_model');

        if ($ci->users_model->getUser($userid)->num_rows() > 0){
            $user = $ci->users_model->getUser($userid)->row_array();
            if ($user["UserType"] == 1 || $user["UserType"] == 10){
                return TRUE;
            }else{
                $member = $ci->members_model->getMember($memberid)->row_array();
                if ($userid == $member["UserID"]){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }
    }
}

//檢查會員帳號是否可觀看
if ( ! function_exists('check_member_viewable'))
{
    function check_member_viewable($memberid){
        $ci =& get_instance();
        $ci->load->database();
        $ci->load->model('users_model');
        $ci->load->model('members_model');

        $userid = $ci->session->userdata('UserID');
        $usertype = $ci->session->userdata('UserType');
        if ($usertype == 10){
            return TRUE;
        }else{
            $member = $ci->members_model->getMember($memberid)->row_array();
            if (! empty($member)){
                if ($member["CUserID"] == $userid || $member["SHUserID"] == $userid || $member["GAUserID"] == $userid || $member["UserID"] == $userid){
                    return TRUE;
                }else{
                    return FALSE;
                }
            }else{
                return FALSE;
            }
        }
    }
}

//取得帳號的所有上層字串，公司(admin)->股東(sh1)->總代理(ga1)
if ( ! function_exists('get_level_string'))
{
    function get_level_string($userid, $includeself = 0){
        $ci =& get_instance();
        $ci->load->database();

        $level_string = '';
        $sql = 'SELECT PUserID, UserID, UserName, UserType, TypeName FROM vUsers WHERE UserID = ?';
        $user = $ci->db->query($sql, $userid)->row_array();
        if ($includeself){
            $level_string = $user["TypeName"].'('.$user["UserID"].') > '.$level_string;
        }
        $usertype = $user["UserType"];
        while ($usertype > 1){
            $user = $ci->db->query($sql, $user["PUserID"])->row_array();
            $usertype = $user["UserType"];
            $level_string = $user["TypeName"].'('.$user["UserID"].') > '.$level_string;
        }

        return substr($level_string, 0, strlen($level_string) - 2);
    }
}

//將字串用URI Encode
if ( ! function_exists('encodeURI'))
{
    function encodeURI($uri)
    {
        return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i", function ($m) {
            return sprintf('%%%02X', ord($m[0]));
        }, $uri);
    }
}
*/
//取得客户IP
if ( ! function_exists('get_client_ip'))
{
    function get_client_ip()
    {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
}
