<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let broswerInfo = navigator.userAgent;
        //sns로그인 주소
        let inputMustName = {
            'id': {
                'msg': '아이디는 필수 입니다.'
            },
            'password': {
                'msg': '패스워드는 필수 입니다.'
            }
        };
        $(function() {
            $("#frm").on("submit", function(event) {
                event.preventDefault();
                let msg = [];
                let ck = false;
                let data = {};
                $.each(inputMustName, function(k, v) {
                    if (!$('input[name="' + k + '"]').val() && msg.length == 0) {
                        msg.push(v.msg);
                        ck = true;
                    } else {
                        data[k] = $('input[name="' + k + '"]').val();
                    }
                });
                if (ck) {
                    alert(msg);
                    return;
                }
                this.submit();
            });
        });

        function kakao() {
            if (broswerInfo.indexOf("APP_Highbuff_Android") != -1) {
                window.interview.kakao_login();
            } else if (broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                webkit.messageHandlers.kakao_login.postMessage("");
            } else {
                window.open(kakao_login_url, '_blank', 'width=480,height=640');
                return false;
            }
        }

        function apple() {
            if (broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                webkit.messageHandlers.apple_login.postMessage("");
            } else if (broswerInfo.indexOf("APP_Highbuff_Android") != -1) {
                location.href = apple_login_url;
            } else {
                location.href = apple_login_url;
            }
        }

        function google() {
            if (broswerInfo.indexOf("APP_Highbuff_Android") != -1) {
                window.interview.google_login();
            } else if (broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                webkit.messageHandlers.google_login.postMessage("");
            } else {
                window.open(google_login_url, '_blank', 'width=480,height=640');
                return false;
            }
        }

        function naver() {
            if (broswerInfo.indexOf("APP_Highbuff_Android") != -1) {
                window.interview.naver_login();
            } else if (broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                webkit.messageHandlers.naver_login.postMessage("");
            } else {
                window.open(naver_login_url, '_blank', 'width=480,height=640');
                return false;
            }
        }
    </script>
</head>

<body>
    <div>
        <p>일반회원 로그인</p>
        <form id="frm" method="post" action="/login/action">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="M">
            <input type="text" name="id" placeholder="이메일">
            <input type="password" name="password" placeholder="비밀번호">
            <button type="submit">로그인</button>
        </form>
    </div>
    <div>
        <p>기업회원 로그인</p>
        <form id="frm" method="post" action="/login/action">
            <?= csrf_field() ?>
            <input type="hidden" name="type" value="C">
            <input type="text" name="id" placeholder="이메일">
            <input type="password" name="password" placeholder="비밀번호">
            <button type="submit">로그인</button>
        </form>
    </div>
    <div>
        <a href="join">회원가입</a>
    </div>
    <div>
        <a href="#" onclick="kakao()">카카오로 3초만에 시작하기</a>
    </div>
    <div>
        <a href="#" onclick="google()">구글</a>
        <a href="#" onclick="naver()">네이버</a>
        <a href="#" onclick="apple()">애플</a>
    </div>
    <div>
        <a href="/login/find">아이디 / 비밀번호 찾기</a>
    </div>
</body>

</html>