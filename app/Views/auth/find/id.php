<!--s #scontent-->
<div id="scontent">
    <!--s top_tltBox-->
    <div class="top_tltBox c">
        <!--s top_tltcont-->
        <div class="top_tltcont">
            <a href="javascript:window.history.back();"><div class="backBtn"><span>뒤로가기</span></div></a>
            <div class="tlt">아이디/패스워드 찾기</div>
        </div>
        <!--e top_tltcont-->
    </div>
    <!--e top_tltBox-->
    
    <!--s cont-->
    <div class="cont">
        <!--s logtab-->
        <ul class="logtab wd_2 c">
            <li class="on"><a href="/login/find/person/id">아이디 찾기</a></li>
            <li><a href="/login/find/person/pwd">비밀번호 찾기</a></li>
        </ul>
        <!--e logtab-->

        <div>
            <p><?= $data['aData']['type'] == 'person' ? '일반회원' : '기업회원' ?> 아이디(이메일) 찾기 </p>
        </div>

        <!--s bigtlt-->
        <div class="bigtlt">
            <span class="point b">이메일</span>를 잊으셨나요?<br />
            
            <div class="stxt2">
                이메일에 대한 정보를 찾을 수 있습니다.<br />
                회원님의 전화번호를 입력해 주세요
            </div>
        </div>
        <!--e bigtlt-->

        <!--s inp_dlBox-->
        <div class="inp_dlBox">
            <dl class="inp_dl">
                <dt>휴대폰 인증</dt>
                <dd>
                    <div class="wd50Box">
                        <input type="text" name="phoneNum" class="wd50Box_inp" maxlength="20" placeholder="숫자만 입력">
                        <a class="dnBtn" id='chkNumber'>인증번호 받기</a>
                    </div>

                    <div class="wd50Box" id="confirmNum" style="display:none;">
                        <input type="text" name="telchk" id="telchk" class="wd50Box_inp" maxlength="20" placeholder="인증번호 입력">
                        <a class="dnBtn" id='confirm'>확인</a>
                    </div>

                    <div class="data_txt" id="timerView" style="display:none;">유효시간 : <span id="timer">03:00</span></div>
                    <div class="data_txt" id="complete" style="display:none;"><i class="la la-check"></i> 인증완료</div>

                    <div id="resultId">찾으시는 아이디는<br><span id="email"></span> 입니다.</div>
                </dd>
            </dl>
        </div>
        <!--e inp_dlBox-->

        <!--s BtnBox-->
        <div class="BtnBox">
            <a href="/login" class="btn btn01 Btn_off wps_100">로그인하러 가기</a><!--on 일때 Btn_off 클래스 없애주세요-->
        </div>
        <!--e BtnBox-->
    </div>
    <!--e cont-->
</div>
<!--e #scontent-->
<input type="hidden" name="postCase" value="find_id">
<?= csrf_field() ?>

<br><br><br><br><br><br><br>

<style>
    #resultId{
        text-align:center;
        display:none;
        margin: 30px 0;
    }
</style>

<script>
    const regPhone = /^01([0-9])?([0-9]{3,4})?([0-9]{4})$/;
    const inputMustName = {
        'phoneNum': {
            'msg': '연락처는 필수 입니다.'
        },
        'telchk': {
            'msg': '인증번호는 필수 입니다.'
        },
    };
    let interval = "";

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
        aParams['telBox'] = $('input[name="phoneNum"]').val();
        aParams['telChk'] = $('input[name="telchk"]').val();
        aParams['postCase'] = $("input[name='postCase']").val();
        aParams['emlCsrf'] = $("input[name='csrf_highbuff']");
        aParams['backUrl'] = $("input[name='backUrl']").val();

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

        getAjax('findId', aParams);
    });

    $('#chkNumber').on("click", function() {
        let aParams = [];
        aParams['telBox'] = $('input[name="phoneNum"]').val();
        aParams['emlTelChk'] = $('input[name="telchk"]');
        aParams['emlCsrf'] = $("input[name='csrf_highbuff']");
        aParams['postCase'] = $("input[name='postCase']").val();
        aParams['backUrl'] = $("input[name='backUrl']").val();
        if (!regPhone.test(aParams['telBox'])) {
            alert('유효하지 않은 전화번호입니다.');
            return false;
        }
        getAjax('sms', aParams);  
    });

    function getAjax(type, params) {
        let strUrl = '';
        let urlType = '<?= $data['aData']['type']; ?>';
        if(!urlType){
            return false;
        }
        let objData = {};
        if (type == 'sms') {
            strUrl = "/api/auth/tel";
            objData = {
                'phone': params['telBox'],
                'type': 'I',
                '<?= csrf_token() ?>': params['emlCsrf'].val(),
                'postCase': params['postCase'],
                'backUrl': params['backUrl']
            };
        } else if (type == 'findId') {
            strUrl = `/api/auth/find/${urlType}/id`;
            objData = {
                tel: params['telBox'],
                code: params['telChk'],
                '<?= csrf_token() ?>': params['emlCsrf'].val(),
                'postCase': params['postCase'],
                'backUrl': params['backUrl']
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
                    } else if (type == 'findId') {
                        const email = res.code.id;

                        clearInterval(interval);
                        $('#timerView').hide();
                        $('#confirmNum').hide();
                        $('#complete').show();
                        $('#resultId').show();
                        $("#email").html(email);
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
