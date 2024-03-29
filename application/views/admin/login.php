<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?=SITE_CNAME?> - 登录</title>
    <meta name="keywords" content="H+后台主题,后台bootstrap框架,会员中心主题,后台HTML,响应式后台">
    <meta name="description" content="H+是一个完全响应式，基于Bootstrap3最新版本开发的扁平化主题，她采用了主流的左右两栏式布局，使用了Html5+CSS3等现代技术">

    <link rel="shortcut icon" href="favicon.ico">
    <link href="<?=ADMIN_ASSETS_DIR?>/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="<?=ADMIN_ASSETS_DIR?>/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
    <link href="<?=ADMIN_ASSETS_DIR?>/css/animate.min.css" rel="stylesheet">
    <link href="<?=ADMIN_ASSETS_DIR?>/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>
                <h1 class="logo-name">H+</h1>
            </div>
            <h3>欢迎使用 - <?=SITE_CNAME?>后台</h3>
            <form class="m-t" role="form" method="post" action="<?=ADMIN_DIR?>/home/do_login">
                <div class="form-group">
                    <input type="text" name="account" class="form-control" placeholder="登录帐号" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="登录密码" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>
                <?php
                    if (isset($message)){
                        $message = urldecode($message);
                        echo '<div class="alert alert-danger"><strong>'.$message.'</strong></div>';
                    }
                ?>
            </form>
        </div>
    </div>
    <script src="<?=ADMIN_ASSETS_DIR?>/js/jquery.min.js?v=2.1.4"></script>
    <script src="<?=ADMIN_ASSETS_DIR?>/js/bootstrap.min.js?v=3.3.6"></script>
<!--    <script type="text/javascript" src="http://tajs.qq.com/stats?sId=9051096" charset="UTF-8"></script>-->
</body>

</html>
