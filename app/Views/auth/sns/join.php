<!--s #scontent-->
<div id="scontent">
    <!--s top_tltBox-->
    <div class="top_tltBox c">
        <!--s top_tltcont-->
        <div class="top_tltcont">
            <a href="javascript:window.history.back();">
                <div class="backBtn"><span>뒤로가기</span></div>
            </a>
            <div class="tlt">회원가입</div>
        </div>
        <!--e top_tltcont-->
    </div>
    <!--e top_tltBox-->

    <!--s cont-->
    <div class="cont">
        <!--s bigtlt-->
        <div class="bigtlt b">
            <span class="point">하이버프에 오신 걸 환영합니다! </span><br />
            시작하기 전, 사용하실 이름만 알려주시겠어요?
        </div>
        <!--e bigtlt-->

        <form id="frm" method="post" action="/join/sns/action/<?= $data['sns']['cache']['snsType'] ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="loginId" value="<?= $data['sns']['cache']['id'] ?>" />
            <input type="hidden" name="loginKey" value="<?= $data['sns']['cache']['key'] ?>" />
            <input type="hidden" name="loginPassword" value="<?= $data['sns']['cache']['enc'] ?>" />
            <input type="hidden" name="loginProvider" value="<?= $data['sns']['cache']['provider'] ?>" />
            <input type="hidden" name="loginEmail" value="<?= $data['sns']['cache']['email'] ?>" />
            <input type="hidden" name="postCase" value="join_write" />
            <input type="hidden" name="backUrl" value="/login" />
            <div class="inp_dlBox">
                <dl class="inp_dl">
                    <dt>이름</dt>
                    <dd><input type="text" name="loginName" maxlength="10" class="wps_100" placeholder="정확한 정보 제공을 위해 실명을 권장합니다."></dd>
                </dl>
            </div>
            <!--s join_agrBox-->
            <div class="join_agrBox">
                <!--s all_agrBox-->
                <div class="all_agrBox">
                    <div class="chek_box checkbox">
                        <input id="chk_all" name="mem_personal_type" type="checkbox">
                        <label for="chk_all" class="lbl">전체동의</label>
                    </div>
                </div>
                <!--e all_agrBox-->

                <!--s chek_cont-->
                <div class="chek_cont">
                    <div class="chek_box checkbox">
                        <input id="agree" class="chk-each" name="mem_personal_type_1" type="checkbox" value="Y">
                        <label for="agree" class="lbl"><a href="javascript:;" class="bline pop_chek02">이용약관</a> 및 <a href="javascript:;" class="bline pop_chek01">개인정보 처리방침</a>에 동의(필수)</label>
                    </div>

                    <div class="chek_box checkbox">
                        <input id="agree2" class="chk-each" name="mem_personal_type_2" type="checkbox" value="Y">
                        <label for="agree2" class="lbl">맞춤 채용정보 제공 및 알림 수신에 동의(선택)</label>
                    </div>
                </div>
                <!--e chek_cont-->

                <!--s BtnBox-->
                <div class="BtnBox">
                    <button type="submit" class="btn btn01 Btn_off wps_100">가입완료</button>
                    <!--on 일때 Btn_off 클래스 없애주세요-->
                </div>
                <!--e BtnBox-->
            </div>
            <!--e join_agrBox-->
        </form>
    </div>
    <!--e cont-->
</div>
<!--e #scontent-->
<script>
    $.validator.setDefaults({
        onkeyup: false,
        onclick: false,
        onfocusout: false,
        showErrors: function(errorMap, errorList) {
            if (this.numberOfInvalids()) {
                // 에러가 있으면
                alert(errorList[0].message); // 경고창으로 띄움
            }
        }
    });
    $.validator.addMethod("regex", function(value, element, regexp) {
        let re = new RegExp(regexp);
        let res = re.test(value);
        return res;
    });

    <?php
    // required : 필수 입력 엘리먼트입니다.
    // remote : 엘리먼트의 검증을 지정된 다른 자원에 ajax 로 요청합니다.
    // minlength : 최소 길이를 지정합니다.
    // maxlength : 최대 길이를 지정합니다.
    // rangelength : 길이의 범위를 지정합니다.
    // min : 최소값을 지정합니다.
    // max : 최대값을 지정합니다.
    // range : 값의 범위를 지정합니다.
    // step : 주어진 단계의 값을 가지도록 합니다.
    // email : 이메일 주소형식으 가지도록 합니다.
    // url : url 형식을 가지도록 합니다.
    // date : 날짜 형식을 가지도록 합니다.
    // dateISO : ISO 날짜 형식을 가지도록 합니다.
    // number : 10진수를 가지도록 합니다.
    // digits : 숫자 형식을 가지도록 합니다.
    // equalTo : 엘리먼트가 다른 엘리먼트와 동일해야 합니다.
    ?>

    $("#frm").validate({
        ignore: [],
        rules: {
            loginId: {
                required: true,
            },
            loginEmail: {
                required: true,
            },
            loginProvider: {
                required: true,
            },
            loginPassword: {
                required: true,
                minlength: 8
            },
            loginName: {
                required: true,
            },
            mem_personal_type_1: {
                required: true,
            },
        },
        messages: {
            loginId: {
                required: "잘못된 접근 입니다.",
            },
            loginEmail: {
                required: "잘못된 접근 입니다.",
            },
            loginProvider: {
                required: "잘못된 접근 입니다.",
            },
            loginPassword: {
                required: "잘못된 접근 입니다.",
            },
            loginName: {
                required: "이름은 필수 입력입니다.",
            },
            mem_personal_type_1: {
                required: "이용약관 및 개인정보 처리 방침은 필수 선택입니다.",
            }
        },
        submitHandler: function(form) {
            // form 전송 이외에 ajax등 어떤 동작이 필요할 때
            form.submit();
        }
    });

    const emlChkAll = $("#chk_all");
    const emlChkEach = $(".chk-each");
    emlChkAll.on("click", () => {
        //전체 체크박스 선택
        if (emlChkAll.prop("checked")) {
            emlChkEach.prop("checked", true);
        } else {
            emlChkEach.prop("checked", false);
        }
    });
    emlChkEach.on("click", () => {
        //전체 체크박스 선택중 체크박스 하나를 풀었을때 "전체" 체크해제
        if ($(".chk-each:checked").length == emlChkEach.length) {
            emlChkAll.prop("checked", true);
        } else {
            emlChkAll.prop("checked", false);
        }
    });
</script>

</html>