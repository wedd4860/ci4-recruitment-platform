<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>test</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(function() {
            $('#next').click(function() {
                if (!$('#agree').is(':checked')) {
                    return alert('유의사항에 동의에 주세요.');
                } else {
                    $('.step1').hide();
                    $('.step2').show();
                }
            });

            $('#next2').click(function() {

                var cval = $(':radio[name="reason"]:checked').val();
                var tval = $("textarea#reasonmemo").val().trim();

                if (!$('input:radio[name=reason]').is(':checked')) {
                    return alert('떠나는 이유를 선택해 주세요');
                } else if (cval == '5' && tval.length == 0) {
                    return alert('기타사유 선택시 이유를 입력해 주세요.');
                } else {
                    $('.pop_wrap').show();
                }
            });

            $('#leave').click(function() {

                $("#frm").submit();

            });
        });
    </script>
</head>

<body>
    <div class="step1">
        <p>회원 탈퇴1</p>
        <div>정말 하이버프를 떠나실 건가요 …? 😢 </div>
        <ul>
            <li>구직 생각이 사라졌어요</li>
            <li>레포트 비공개하기 </li>
            <li>다른 불편사항이 있어요</li>
            <li>빠르게 해결해드리도록 노력할게요!</li>
            <li>1:1문의하기</li>
            </ui>
            <div>
                떠나시기 전, 아래 사항을 꼭 확인해 주세요
            </div>
            <div>
                탈퇴 유의사항
            </div>
            <p>위 내용을 모두 확인했어요</p>
            <input name="agree" id="agree" type="checkbox">
            <button onclick="location.href='/'">취소</button>
            <button type="button" id="next">다음</button>
    </div>

    <div class="step2" style="display: none;">
        <p>회원 탈퇴2</p>
        <div>떠나시게 되는 이유를 알려주세요!</div>
        <form id="frm" method="POST" action="/my/leave/step2/action">
            <?= csrf_field() ?>
            <input type="hidden" name="postCase" value="leave_write">
            <input type="hidden" name="backUrl" value="/my/leave/step2">
            <?php
            foreach ($data['leave'] as $key => $val) :
            ?>
                <input type="radio" name="reason" value=<?= $key ?>><?= $val ?><br>
            <?php
            endforeach;
            ?>
            <textarea id="reasonmemo" name="memo" maxlength="255" style="height: 150px; width: 300px; border:1px solid gray;"></textarea>
        </form>
        <br>
        <button type="button" onclick="location.href='/'">취소</button>
        <button type="button" id="next2" class="btn_open">다음</button>
    </div>

    <!-- 팝업1 -->
    <div id="pop_info_1" class="pop_wrap">
        <div class="pop_inner">
            <p class="dsc">정말 떠나시겠어요?</p>
            <div>
                더 좋은 서비스를 제공해드리기 위해
                노력하는 하이버프가 될게요

                한번 더 고민해 보시면 어떨까요?
            </div>
            <div>00개월간 (00일간) 재가입은 블가능해요</div>
            <button onclick="location.href='/'">조금만 더 있을께요</button>
            <button id="leave">탈퇴하기</button>
        </div>
    </div>
</body>