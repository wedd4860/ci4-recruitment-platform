<!--s #scontent-->
<div id="scontent">
    <!--s top_tltBox-->
    <div class="top_tltBox c">
        <!--s top_tltcont-->
        <div class="top_tltcont">
            <a href="javascript:window.history.back();">
                <div class="backBtn"><span>뒤로가기</span></div>
            </a>
            <div class="tlt">아이디/패스워드 찾기</div>
        </div>
        <!--e top_tltcont-->
    </div>
    <!--e top_tltBox-->

    <!--s cont-->
    <div class="cont">
        <!--s logtab-->
        <ul class="logtab wd_2 c">
            <li><a href="/login/find/person/id">아이디 찾기</a></li>
            <li class="on"><a href="/login/find/person/pwd">비밀번호 찾기</a></li>
        </ul>
        <!--e logtab-->

        <div>
            <p><?= $data['aData']['type'] == 'person' ? '일반회원' : '기업회원' ?> 패스워드 찾기 </p>
        </div>

        <!--s bigtlt-->
        <div class="bigtlt">
            <span class="point b">패스워드</span>를 잊으셨나요?<br />

            <div class="stxt2">
                초기화 된 패스워드를 문자로 보내드리겠습니다.<br>
                문자 또는 데이터 요금이 부과될 수 있습니다.
            </div>
        </div>
        <!--e bigtlt-->

        <!--s inp_dlBox-->
        <div class="inp_dlBox">
            <dl class="inp_dl">
                <dt>아이디(이메일)</dt>
                <dd><input type="text" name="id" id="userId" class="wps_100" maxlength="30" placeholder="example@highbuff.com"></dd>
                <p id="iderrmag" class="data_txt"></p>
            </dl>

            <dl class="inp_dl">
                <dt>휴대폰 인증</dt>
                <dd>
                    <div class="wd50Box">
                        <input type="text" name="phoneNum" id="phoneNum" class="wd50Box_inp" maxlength="20" placeholder="숫자만 입력">
                        <a id='chkNumber' class="dnBtn">인증번호 받기</a>
                        <p id="phoneerrmag" class="data_txt"></p>
                    </div>

                    <div class="wd50Box" id="confirmNum" style="display: none;">
                        <input type="text" name="telchk" id="telchk" class="wd50Box_inp" placeholder="인증번호 입력">
                        <a class="dnBtn" id='confirm'>확인</a>
                    </div>

                    <div class="data_txt" id="timerView" style="display:none;">유효시간 : <span id="timer">03:00</span></div>
                    <div class="data_txt" id="complete" style="display:none;"><i class="la la-check"></i> 인증완료</div>

                    <?= csrf_field() ?>
                    <input type="hidden" name="postCase" value="find_pwd">
                    <input type="hidden" name="backUrl" value="/login/find/<?= $data['aData']['type'] ?>/pwd">
                    <input type="hidden" name="page" value="/login/find/<?= $data['page'] ?>/pwd">
                </dd>
            </dl>
        </div>
        <!--e inp_dlBox-->

        <div id="email"></div>

        <!--s BtnBox-->
        <div class="BtnBox">
            <a href="/login" class="btn btn01 Btn_off wps_100">로그인하러 가기</a>
            <!--on 일때 Btn_off 클래스 없애주세요-->
        </div>
        <!--e BtnBox-->
    </div>
    <!--e cont-->
</div>
<!--e #scontent-->

<br><br><br><br><br><br><br>

<script>
    // 이메일 유효성 체크
    function CheckEmail(str) {
        const reg_email = /^([0-9a-zA-Z_\.-]+)@([0-9a-zA-Z_-]+)(\.[0-9a-zA-Z_-]+){1,2}$/;
        if (!reg_email.test(str)) {
            return false;
        }
        return true;
    }

    const regPhone = /^01([0-9])?([0-9]{3,4})?([0-9]{4})$/;
    const inputMustName = {
        'id': {
            'msg': '아이디는 필수 입니다.'
        },
        'phoneNum': {
            'msg': '연락처는 필수 입니다.'
        },
        'telchk': {
            'msg': '인증번호는 필수 입니다.'
        },
    };
    let interval = '';

    function startTimer(duration, emlAuthTimer) {
        let timer = duration
        let minutes, seconds;
        interval = setInterval(() => {
            if (minutes == 0 && seconds == 0) {
                clearInterval(interval);
            } else {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                emlAuthTimer.text(minutes + ":" + seconds);

                if (--timer < 0) {
                    timer = duration;
                    alert('시간이 초과되었습니다. 다시 인증번호받기를 해주세요.');
                    $('#confirmNum').hide();
                    $('#timerView').hide();
                }
            }
        }, 1000);
    }

    $('#confirm').on("click", function() {
        let msg = [];
        let aParams = [];
        let ck = false;
        let data = {};
        aParams['emlTelBox'] = $('input[name="phoneNum"]');
        aParams['telBox'] = aParams['emlTelBox'].val();
        aParams['telChk'] = $('input[name="telchk"]').val();
        aParams['postCase'] = $("input[name='postCase']").val();
        aParams['emlCsrf'] = $("input[name='csrf_highbuff']");
        aParams['backUrl'] = $("input[name='backUrl']").val();
        aParams['emlUserId'] = $('#userId');
        aParams['userId'] = aParams['emlUserId'].val();
        aParams['page'] = $("input[name='page']").val();

        //validation
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
            return false;
        }

        if (!regPhone.test(aParams['telBox'])) {
            $("#phoneerrmag").text("휴대폰 번호 형식이 잘못되었습니다.");
            aParams['emlTelBox'].focus();
            return false;
        }

        if (!CheckEmail(aParams['emlUserId'].val())) {
            $("#iderrmag").text("이메일 형식이 잘못되었습니다.");
            aParams['emlUserId'].focus();
            return false;
        }
        getAjax('findPassword', aParams);
    })

    $('#chkNumber').on("click", function() {
        let aParams = [];
        aParams['telBox'] = $('input[name="phoneNum"]').val();
        aParams['emlTelChk'] = $('input[name="telchk"]');
        aParams['emlCsrf'] = $("input[name='csrf_highbuff']");
        aParams['postCase'] = $("input[name='postCase']").val();
        aParams['backUrl'] = $("input[name='backUrl']").val();
        aParams['page'] = $("input[name='page']").val();

        if (!aParams['telBox']) {
            alert('휴대폰 번호를 입력해 주세요.');
            return false;
        }

        if (!regPhone.test(aParams['telBox'])) {
            alert('유효하지 않은 전화번호입니다.');
            return false;
        }
        getAjax('sms', aParams);
    });

    function getAjax(type, params) {
        let strUrl = '';
        let urlType = '<?= $data['aData']['type']; ?>';
        if (!urlType) {
            return false;
        }
        let objData = {};
        if (type == 'sms') {
            strUrl = "/api/auth/tel";
            objData = {
                'phone': params['telBox'],
                'type': 'P',
                '<?= csrf_token() ?>': params['emlCsrf'].val(),
                'postCase': params['postCase'],
                'backUrl': params['backUrl'],
            };
        } else if (type == 'findPassword') {
            strUrl = `/api/auth/find/${urlType}/password`;
            objData = {
                id: params['userId'],
                tel: params['telBox'],
                code: params['telChk'],
                '<?= csrf_token() ?>': params['emlCsrf'].val(),
                'postCase': params['postCase'],
                'backUrl': params['backUrl'],
                'page' : params['page']
            };
        } else {
            alert('잘못된 접근 입니다.');
            return false;
        }

        $.ajax({
            async: false,
            type: 'post',
            url: strUrl,
            dataType: "json",
            cache: false,
            data: objData,
            success: function(res) {
                if (res.code.stat || res.code.stat == 'success') {
                    params['emlCsrf'].val(res.code.token);
                    if (type == 'sms') {
                        alert("인증번호가 발송되었습니다. 조금만 기다려주세요.");
                        params['emlTelChk'].removeAttr("disabled");

                        $('#confirmNum').show();
                        $('#timerView').show();
                        
                        const timerMinutes = 60 * 3;
                        const emlAuthTimer = $('#timer');
                        startTimer(timerMinutes, emlAuthTimer);
                    } else if (type == 'findPassword') {
                        params['emlCsrf'].val(res.code.token);
                        clearInterval(interval);
                        $('#timerView').hide();
                        $('#confirmNum').hide();
                        $("#email").html("새로운 비밀번호 세팅을 위해 이메일이 발송되었습니다.<br>입력하신 아이디(이메일)을 확인해주세요.");
                    }
                }
            },
            error: function(e) {
                params['emlCsrf'].val(e.responseJSON.code.token);
                alert(e.responseJSON.messages);
            },
        }); //end ajax
    } // end getAjax()
</script>