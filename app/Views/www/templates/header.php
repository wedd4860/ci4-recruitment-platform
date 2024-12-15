<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, target-densitydpi=medium-dpi">
    <title>highbuff</title>
    <!-- 아이콘 폰트 -->
    <link rel="stylesheet" href="//maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- design -->
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/layout.css">
    <?php if ($data['page'] == '/') :
        //메인에서만 보이게
    ?>
        <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/main.css">
    <?php elseif ($data['page'] == '/splash') :
        // 스플레시에서만 보이게 앱 미구현
    ?>
        <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/splash.css">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/animated.css">
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/common.css">
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/sub.css">
    <!-- dev -->
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/static/www/css/dev.css">

    <!-- jQuery -->
    <script src="<?= $data['url']['menu'] ?>/plugins/jquery/jquery.min.js"></script>
    <!-- jQuery.easing -->
    <script src="<?= $data['url']['menu'] ?>/plugins/jquery.easing/jquery.easing.min.js"></script>
    <!-- jquery-validation -->
    <script src="<?= $data['url']['menu'] ?>/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
    <!-- design -->
    <?php if ($data['page'] == '/') :
        //메인에서만 보이게
    ?>
        <script src="<?= $data['url']['menu'] ?>/static/www/js/main.js"></script>
    <?php else :
        // 스플레시에서만 보이게 앱 미구현
    ?>
        <script src="<?= $data['url']['menu'] ?>/static/www/js/layout.js"></script>
    <?php endif; ?>
    <!-- bowser -->
    <script src="<?= $data['url']['menu'] ?>/plugins/bowser/bundled.js"></script>
    <!-- 체크버튼 -->
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/plugins/switch/lc_switch.css">
    <script src="<?= $data['url']['menu'] ?>/plugins/switch/lc_switch.js"></script>
    <!-- 슬라이드 -->
    <link rel="stylesheet" href="<?= $data['url']['menu'] ?>/plugins/slick/slick.css">
    <script src="<?= $data['url']['menu'] ?>/plugins/slick/slick.js"></script>
    <!-- 모달 -->
    <script src="<?= $data['url']['menu'] ?>/plugins/modal/moaModal.minified.js"></script>
    <script src="<?= $data['url']['menu'] ?>/plugins/modal/Sweefty.js"></script>
    <?php
    /*
        * http://dean.edwards.name/packer/
        var userInfo = {};
        userInfo.bowser = bowser.parse(window.navigator.userAgent);
        userInfo.isLogin = '<?= $data['session']['idx'] ? 'Y' : 'N' ?>';
        */
    ?>
    <script>
        eval(function(p, a, c, k, e, r) {
            e = String;
            if (!''.replace(/^/, String)) {
                while (c--) r[c] = k[c] || c;
                k = [function(e) {
                    return r[e]
                }];
                e = function() {
                    return '\\w+'
                };
                c = 1
            };
            while (c--)
                if (k[c]) p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c]);
            return p
        }('2 0={};0.1=1.3(4.5.6);0.7=\'8\';', 9, 9, 'userInfo|bowser|var|parse|window|navigator|userAgent|isLoginYN|<?= $data['session']['idx'] ? 'Y' : 'N' ?>'.split('|'), 0, {}))
    </script>
</head>

<body class="<?= $data['class']['body'] ?>">
    <!--s #wrap-->
    <div id="wrap">