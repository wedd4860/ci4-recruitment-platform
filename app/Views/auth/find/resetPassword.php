<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <p>일반회원 비밀번호 리셋 </p>
    </div>

    <form id="frm" method="POST" action="/login/reset/action" onsubmit="return false;">
        <?= csrf_field() ?>
        <input type="hidden" name="memId" value="<?= $data['userData']['id'] ?>">
        <input type="hidden" name="memPhone" value="<?= $data['userData']['phone'] ?>">
        <input type="hidden" name="auth" value="<?= $data['userData']['auth'] ?>">
        <input type="hidden" name="mtype" value="M">
        <input type="hidden" name="postCase" value="reset_write">
        <input type="hidden" name="backUrl" value="/login">

        <input type="text" name="newpassword" maxlength="20" placeholder="새비밀번호">
        <input type="text" name="repassword" maxlength="20" placeholder="비밀번호 확인">
        <button type="submit" id="confirm">RESET</button>
    </form>
    <script>
        let inputMustName = {

            'newpassword': {
                'msg': '비밀번호는 필수 입니다.'
            },
            'repassword': {
                'msg': '비밀번호는 확인은 필수 입니다.'
            },
        };

        $("form").on("submit", function(event) {
            event.preventDefault();
            let msg = [];
            let ck = false;
            let data = {};
            let telBox = $('input[name="newpassword"]').val().trim();
            let retelBox = $('input[name="repassword"]').val().trim();

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

            if (telBox != retelBox) {
                alert("재확인 비밀번호가 맞지 않습니다.");
                return;
            }

            this.submit();

        });
    </script>
</body>

</html>