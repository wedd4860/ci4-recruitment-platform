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

    </script>
</head>

<body>
    <div>
        <p>일반회원</p>
        <div>
            <a href="find/person/id">아이디 찾기</a>
        </div>
        <div>
            <a href="find/person/pwd">비밀번호 찾기</a>
        </div>
    </div>
    <div>
        <p>기업회원</p>
        <div>
            <a href="find/company/id">아이디 찾기</a>
        </div>
        <div>
            <a href="find/company/pwd">비밀번호 찾기</a>
        </div>
    </div>
</body>

</html>