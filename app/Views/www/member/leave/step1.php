<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $("form").on("submit", function(event) {
            event.preventDefault();

            if ($('input[name="password"]').val().length < 2) {
                return alert('비밀번호를 8자리 이상 입력해주세요.');
            }

            this.submit();
        });
    });
</script>

<p>회원탈퇴</p>

<div>본인 확인을 위해 비밀번호를 입력해주세요</div>

<form id="frm" method="POST" action="/my/leave/password/action">
    <?= csrf_field() ?>
    <input type="hidden" name="postCase" value="check_password">
    <input type="hidden" name="backUrl" value="/my/leave">
    <input type="password" name="password" maxlength="20" placeholder="비밀번호 입력">
    <button type="submit" id="confirm">확인</button>
</form>