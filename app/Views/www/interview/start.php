<?php
if (count($data['startInterview'][0]['video']) == count($data['startInterview'][0]['report_result'])) {
    alert_url("완료된 인터뷰입니다.", '/');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

</head>

<body>
    <div id="before_interview">
        <div><img src="https://interview.highbuff.com/share/img/sub/mic_ck05_icon.png" alt=""></div>
        <hr>
        <div>
            인터뷰 응시 준비가 완료되었어요!
        </div>
        <hr>
        <div>
            아래 시작하기 버튼을 누를 시, <br>
            카메라가 켜지고<br>
            나의 모습을 확인할 수 있어요. <br>
        </div>
        <hr>
        <div>
            질문에 대한 답변 준비가 완료되면 <br>
            '바로대답하기' 버튼을 눌러 <br>
            촬영을 진행해 주세요! <br>
        </div>
        <hr>
        <div>
            이제 첫 질문을 보러 가볼까요?
        </div>
        <hr>
        <div id="start">시작하기</div>
        <!-- <div id="test" class="pop_retake">재촬영 팝업 테스트</div> -->
    </div>
    <!--s face_vdoBox-->
    <div class="face_vdoBox" id="interview_box" style="display:none;">
        <div class="listen_motion" id="speak_motion" style="display:none;"><img src="" class="speak_gif" alt=""></div>

        <!--s txtBox-->
        <div class="txtBox">
            <!--s txtBox_bg-->
            <div class="txtBox_bg" style="height: 15vh; ">
                <!--s txt-->
                <div class="txt" id="q_text">
                    질문을 불러오고 있습니다. <br />잠시만 기다려주세요.

                </div>
                <!--e txt-->
            </div>
            <div class="x_btn_css">
                <div class="x_location_css"><img src="" class="close_btn"></div>
            </div>
            <!--e txtBox_bg-->

        </div>
        <!--e txtBox-->

        <!--s fv_BtnBox-->
        <div class="fv_BtnBox">
            <!--s fv_Btncontx-->
            <div class="fv_Btncont">

                <!--s fv_stepBox-->
                <div class="fv_stepBox">
                    <div class="fv_step_txt"><span>STEP</span><span id="total_step"></span></div>
                    <!--s fv_step-->
                    <div class="fv_step">
                        <div class="num" id="now_step">1</div>
                    </div>
                    <!--e fv_step-->
                </div>
                <!--e fv_stepBox-->

                <!--s fv_back-->
                <!-- <div class="fv_iconBtn fv_back">
				<a href="javascript:void(0)"onclick="cancle_interview()">
					<div class="icon"><img src="/share/img/sub/fv_back_icon.png"></div>
					<div class="txt">종료하기</div>
				</a>
			</div> -->
                <!--e fv_back-->

                <!--s fv_back-->
                <div class="fv_iconBtn fv_on" id="change_btn" style="display:none">
                    <div id="state_basic" style="display:none;">
                        <div class="icon"><img src=""></div>
                        <div class="txt">기본</div>
                    </div>
                    <div id="state_opacity">
                        <div class="icon"><img src=""></div>
                        <div class="txt">투명도</div>
                    </div>
                    <div id="state_call" style="display:none">
                        <div class="icon"><img src=""></div>
                        <div class="txt">영상통화</div>
                    </div>
                </div>
                <!--e fv_back-->

                <!--s play_icon-->
                <div class="play_icon">
                    <div class="play_txt play_txt2" id="min_text"></div>

                    <!--s 그래프 효과 -->
                    <div class="heart_graphBox">
                        <div class="heart_graph">
                            <div class="area_graph area_graph1 <? //= $strClassSec 
                                                                ?>">
                                <span class="ico_graph"></span>
                            </div>

                            <div class="area_graph area_graph2 <? //= $strClassSec 
                                                                ?>">
                                <span class="ico_graph"></span>
                            </div>
                        </div>
                    </div>
                    <!--e 그래프 효과 -->
                </div>
                <!--e play_icon-->

                <!--s fv_next-->
                <div class="fv_iconBtn fv_retry" id="retry" style="display:none">
                    <a href="#n" class="pop_retake">
                        <div class="icon"><img src=""></div>
                        <div class="txt">재시도</div>
                        <div class="txt"><span id="current_re_cnt"></span> / 3</div>
                    </a>
                </div>
                <!--e fv_back-->

                <!--s fv_next-->
                <div class="fv_iconBtn fv_next" id="answer_btn" style="display:none">
                    <a href="#n">
                        <div class="icon"><img src=""></div>
                        <div class="txt" id="answer_txt">답변하기</div>
                    </a>
                </div>
                <!--e fv_back-->
                <!--s fv_next-->
                <div class="fv_iconBtn fv_next" id="next_btn" style="display:none">
                    <a href="#n">
                        <div class="icon"><img src=""></div>
                        <div class="txt" id="answer_txt">다음으로</div>
                    </a>
                </div>
                <!--e fv_back-->
            </div>
            <!--e fv_Btncontx-->

        </div>
        <!--e fv_BtnBox-->

        <!--s videoBox-->
        <div class="videoBox" id="record_view" style="display:none">
            <!--pc 화면일때 확인용--><video class="videoContent" preload="metadata" id="v_pc_1" style="-webkit-transform: rotateY(180deg);" autoplay playsinline></video>
            <!--pc 화면일때 확인용-->
            <!--모바일 화면일때 확인용-->
            <!-- <video class="videoContent" preload="metadata" id="v_pc_3" controls="" src="https://media.highbuff.com/data/uploads/5524-record_01-1-1630388117.mp4#t=0.001"></video> -->
            <!--모바일 화면일때 확인용-->
        </div>
        <div class="videoBox" id="practice_view" style="width: 100%;height: 100%; background-color: #222;">

            <!-- gif -->
            <div class="listen_motion">
                <div class="" style="position: relative;text-align: center;"><img src="" class="listen_gif" alt=""></div>
                <div class="test_ment" id="test_ment"><?= $data['startInterview'][0]['next_question']['repo_answer_time'] ?>초 동안 생각하시고<br>[답변하기] 버튼을 눌러주세요.</div>
            </div>
            <!-- gif -->
        </div>
        <!--e videoBox-->
    </div>
    <!--e face_vdoBox-->
    </div>
    <!--s 재촬영 팝업에서 재촬영 버튼 클릭시 나타나는 카운트-->
    <div class="countBox point" id="count_box" style="display:none;">5</div>
    <!--e 재촬영 팝업에서 재촬영 버튼 클릭시 나타나는 카운트-->

    <!--s 재촬영 팝업-->
    <div id="retake" class="pop_modal" style="display:none;background:white;">
        <!--s pop_Box-->
        <div class="pop_Box c">
            <!--s pop_cont-->
            <div class="pop_cont">
                <!--s pop_contBox-->
                <div class="pop_contBox">
                    <div id="five_sec" class="rtk_count point">5</div>
                    <!--s txtBox-->
                    <div class="txtBox">
                        <div class="txt">재촬영 하시겠습니까?</div>
                        <div class="stxt">(남은횟수 <span id="remind_count">3</span>/3)</div>
                    </div>
                    <!--e txtBox-->
                </div>
                <!--e pop_contBox-->

                <!--s retake_btn-->
                <div class="retake_btn">
                    <a href="#n" id="retry_btn" class="rtk_btn01">재촬영</a>
                    <a href="#n" id="send_btn" class="rtk_btn02">전송</a>
                </div>
                <!--e retake_btn-->
            </div>
            <!--e pop_cont-->
        </div>
        <!--e pop_Box-->
    </div>
    <!--e 재촬영 팝업-->
    <?= csrf_field() ?>
</body>

</html>
<script src="https://www.webrtc-experiment.com/EBML.js"></script>
<script src="https://www.webrtc-experiment.com/DetectRTC.js"></script>
<script src="https://www.WebRTC-Experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://www.webrtc-experiment.com/common.js"></script>
<script src="https://unpkg.com/@mattiasbuelens/web-streams-polyfill/dist/polyfill.min.js"></script>
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.869.0.min.js"></script>
<script src="https://cdn.socket.io/4.2.0/socket.io.min.js" integrity="sha384-PiBR5S00EtOj2Lto9Uu81cmoyZqR57XcOna1oAuVuIEjzj0wpqDVfD0JA9eXlRsj" crossorigin="anonymous"></script>
<script type="text/javascript" src="/plugins/modal/moaModal.minified.js"></script>
<script type="text/javascript" src="/plugins/modal/Sweefty.js"></script>
<script>
    let audio = document.createElement('audio');
    let source = document.createElement('source');
    let video = document.querySelector('#v_pc_1');
    let question_list; //질문정보
    let now;
    let q_list_count = "";
    let com_q_list_count = 0;
    let device_check = false;
    let count = 30;
    let five_timer = "";
    let fiveCount_timer = "";
    let timer;
    let setSec = "<?= $data['startInterview'][0]['next_question']['repo_answer_time'] ?>";
    let re_count = 3;
    let app_type = "";
    let call_mode_on = 0;

    let constraints = {
        "audio": true,
        "video": {
            width: {
                ideal: $(window).width(),
                min: 640,
                max: 1920
            },
            height: {
                ideal: $(window).height(),
                min: 480,
                max: 1080
            },
            frameRate: {
                ideal: 24
            },
            facingMode: {
                ideal: 'user'
            }
        }
    };

    $(document).ready(function() {
        $('.pop_retake').modal({
            target: '#retake',
            speed: 500,
            easing: 'easeInOutExpo',
            animation: 'bottom',
            position: '5% auto',
            overlayClose: false,
            close: '.pop_close'
        });
    });

    //인터뷰 시작전 허용버튼 클릭
    $("#start").on('click', function() {
        $('#interview_box').show();
        $('#before_interview').hide();

        deviceCheck();
        initAudio();
    });

    $('#answer_btn').on('click', function() { //답변하기
        if (call_mode_on == 1) {
            $('.videoBox').addClass('call_mode_size');
        } else {
            $('.videoBox').removeClass('call_mode_size');
        }

        $('#answer_btn').hide();
        setTimeout(function() {
            $('#next_btn').show();
        }, 3000)
        $('#record_view').show();
        $('#practice_view').hide();
        $('#change_btn').show();

        $('#count_box').hide();
        clearInterval(fiveCount_timer);

        $('.heart_graph').removeClass('heart_on');
        setTimeout(function() {
            answerTimer("stop");
            answerTimer("start_n");
            $('.heart_graph').addClass('heart_on');
        })
        if (audio) audio.pause();
        startRecording();

        $('#test_ment').hide();
    });

    $('#next_btn').on('click', function() { //다음질문
        $('#change_btn').hide();
        if (app_type == "M" || app_type == "M") { //비즈가 아닐때
            $('#remind_count').text(re_count);
            if (re_count < 1) {
                recorder.stopRecording(video.pause());
                answerTimer("stop");
                sendInterview();
            } else {
                fiveSec();
                answerTimer("stop");
                setTimeout(function() {
                    recorder.stopRecording(stopRecording(0));
                }, 500);
                $('.pop_retake').click();
            }
        } else { //비즈이면,
            recorder.stopRecording(video.pause());
            sendInterview();
        }
    });

    //팝업 5초카운트
    function fiveSec() {
        let five = 5;
        $('#five_sec').text(five);
        five_timer = setInterval(function() {
            five--;
            $('#five_sec').text(five);
            if (five == 0) {
                clearInterval(five_timer);
                sendInterview();
            }
        }, 1000);
    }

    //재촬영 5초카운트
    function fiveCount() {
        let five_cnt = 5;
        $('#count_box').text(five_cnt);
        fiveCount_timer = setInterval(function() {
            five_cnt--;
            $('#count_box').text(five_cnt);
            if (five_cnt == 0) {
                clearInterval(fiveCount_timer);
                $('#answer_btn').click();
                $('#count_box').hide();
            }
        }, 1000);
    }

    //팝업 -> 재촬영버튼클릭
    $("#retry_btn").on('click', function() {
        $('.videoBox').removeClass('call_mode_size');

        re_count--;
        clearInterval(five_timer);

        $('#count_box').show();
        $('#test_ment').hide();
        fiveCount();

        $('#retake').modal('close');

        $('#answer_btn').show();
        $('#next_btn').hide();
        $('#record_view').hide();
        $('#practice_view').show();
        $('#retry').hide();

        //naver_tts(question_list[now]["question"], question_list[now]["q_idx"]);

        $('.heart_graph').removeClass('heart_on');
        if (com_q_list_count != 6) {
            $('#total_step').text(' ' + com_q_list_count + ' / ' + q_list_count);
            $('#now_step').text(com_q_list_count);
        }
    });

    $('#send_btn').on('click', function() {
        sendInterview();
    })

    $('#state_basic').on('click', function() {
        $('#record_view').show();
        $('#record_view').css('opacity', '1');
        $('#record_view').css('left', '50%');
        $('#record_view').css('top', '0');

        $('#v_pc_1').css('position', 'unset');
        $('#v_pc_1').removeClass('call_mode');

        $('#state_basic').hide();
        $('#state_opacity').show();
        $('#state_call').hide();

        $('#practice_view').hide();
        $('#speak_motion').hide();
        $('.videoBox').removeClass('call_mode_size');
        call_mode_on = 0;

    });

    $('#state_opacity').on('click', function() {
        $('#interview_box').css('background-color', '#000');
        $('#record_view').show();
        $('#record_view').css('opacity', '0.2');
        $('#record_view').css('left', '50%');
        $('#record_view').css('top', '0');

        $('#v_pc_1').css('position', 'unset');
        $('#v_pc_1').removeClass('call_mode');

        $('#state_basic').hide();
        $('#state_opacity').hide();
        $('#state_call').show();

        $('#practice_view').hide();
        $('#speak_motion').hide();

        $('.videoBox').removeClass('call_mode_size');

        call_mode_on = 0;
    })

    $('#state_call').on('click', function() {
        $('#interview_box').css('background-color', '#d8d8d8');
        $('#record_view').show();
        $('#record_view').css('opacity', '1');

        $('#v_pc_1').addClass('call_mode');

        $('#state_basic').show();
        $('#state_opacity').hide();
        $('#state_call').hide();

        $('#interviewer01').show();
        $('#30sec').hide();
        $('#interview_box').css("background-color", "#222");

        $('#speak_motion').show();
        $('.videoBox').addClass('call_mode_size');
        call_mode_on = 1;
    })

    function initAudio() { //오디오 권한 획득을 위해 동작하는 함수
        let playPromise = audio.play();
        if (playPromise !== undefined) {
            playPromise.then(_ => {

                })
                .catch(error => {

                });
        }

        try {
            audio.appendChild(source);
            source.src = "";
            source.type = "audio/mp3"
            audio.load();
            audio.muted = false;
            //audio.play();
        } catch (error) {
            console.log(error);
        }
    }

    function deviceCheck() { //카메라와 마이크가 허용되었는지 확인하는 함수
        if (navigator.mediaDevices) {
            const setting = {
                //audio: true,
                video: true,
            }
            navigator.mediaDevices.getUserMedia(setting)
                .then(stream => {
                    device_check = true;
                    //alert("시작")
                    // audio.muted = false; //audio 권한 설정 해제
                    //audio.play();
                    setTimeout(function() {
                        initSocket();
                        getQuestion();
                        $('#answer_btn').show();
                    }, 3000)
                })
                .catch((e) => {
                    if (e) {
                        if (e.name == 'OverconstrainedError') {
                            alert('지원하지 않는 카메라의 해상도입니다.\n\n사용하고 계신 카메라를 확인하시거나 고객센터로 문의바랍니다.')
                        } else {
                            alert('카메라, 마이크를 찾을수 없습니다.<br>기기를 확인해주세요. \n\n고객센터로 문의바랍니다.\n\n')
                        }
                        location.href = "/";
                        return;
                    }
                });
        }
    }

    function startRecording() { //동영상 녹화 함수
        captureCamera(function(camera) {
            video.muted = true;
            video.volume = 0;
            video.srcObject = camera;
            let recordingHints = {
                type: 'video',
                mimeType: 'video/webm; codecs=vp9',
                bitsPerSecond: 800000, //100KBbps
            };

            recorder = RecordRTC(camera, recordingHints);
            try {
                recorder.startRecording();
            } catch (error) {
                alert("사파리 버전이 맞지 않아 인터뷰 영상이 올바르게 저장되지 않습니다.\n버전 업데이트 후 진행해주세요.");
            }
        });
    }

    function getQuestion() { //질문 가져오는 함수
        app_type = "<?= $data['startInterview'][0]['app_type']; ?>";
        console.log(app_type);
        question_list = $.parseJSON('<?= $data['reportResult'] ?>');
        q_list_count = "<?php echo count($data['startInterview'][0]['report_result']); ?>"
        com_q_list_count = "<?php echo (count($data['startInterview'][0]['video']) + 1) ?>";
        now = "<?php echo $data['startInterview'][0]['next_question']['idx']; ?>"
        $('#total_step').text(' ' + com_q_list_count + ' / ' + q_list_count);
        $('#now_step').text(com_q_list_count);


        answerTimer("start_a");

        setWaitQuestion(now);

    }



    function setWaitQuestion(idx) { //질문을 세팅하는 함수
        let question = "";
        let wait_time = "";
        for (i = 0; i < question_list.length; i++) {
            if (question_list[i]['idx'] == idx) {
                question = question_list[i]["que_question"];
                wait_time = question_list[i]["repo_answer_time"];

                if (i == (question_list.length - 1)) {
                    question = `마지막 질문입니다.<br>` + question_list[i]["que_question"];
                }
            }
        }
        // var question = question_list[idx]["question"];
        // var wait_time = question_list[idx]["wait_time"];
        $('#q_text').html(question);
        //naver_tts(question_list[now]["question"], question_list[now] ["q_idx"]);


    }

    function sendInterview() {

        re_count = 3;
        clearInterval(five_timer);
        clearInterval(timer);
        $('#min_text').text(<?= $data['startInterview'][0]['next_question']['repo_answer_time'] ?>);

        setTimeout(function() {
            recorder.stopRecording(stopRecording(1));
        }, 500);

        $('#retake').modal('close');

        com_q_list_count = Number(com_q_list_count) + 1;

        if (com_q_list_count != Number(q_list_count) + 1) {
            setTimeout(function() {
                $('#answer_btn').show();
            }, 3000)
            $('#next_btn').hide();
            $('#record_view').hide();
            $('#practice_view').show();
            $('#retry').hide();
            $('#test_ment').show();
        } else {
            $('#test_ment').hide();
        }

        $('.heart_graph').removeClass('heart_on');
        if (com_q_list_count != Number(q_list_count) + 1) {
            $('#total_step').text(' ' + com_q_list_count + ' / ' + q_list_count);
            $('#now_step').text(com_q_list_count);
        }

        setTimeout(function() {
            if (com_q_list_count != Number(q_list_count) + 1) {
                $('.heart_graph').addClass('heart_on');
            }
            answerTimer("stop");
            answerTimer("start_a");
        }, 500);
    }

    function stopRecording(state) {
        //0 -> 재시도 / 1 -> 영상전송
        if (state == 1) { //영상전송
            video.pause();

            // $.blockUI.defaults.baseZ = 999999;
            // $.blockUI({
            //     message: '<div class="loadingio-spinner-spinner-eprubzhrgzv"><div class="ldio-4psqt538f76"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>',
            //     css: {
            //         position: 'absolute',
            //         padding: 0 + 'px',
            //         margin: 0 + 'px',
            //         top: 50 + '%',
            //         left: 50 + '%',
            //         textAlign: 'center',
            //         cursor: 'wait',
            //         opacity: 0.657775,
            //         transform: 'translateX(-50%)'
            //     }
            // });

            let blob = recorder.getBlob();
            let fileObject = new File([blob], {
                type: 'video/webm; codecs=vp9'
            });

            let index;
            let count;
            let qIdx;
            for (i = 0; i < question_list.length; i++) {
                if (question_list[i]['idx'] == now) {
                    qIdx = question_list[i]["que_idx"];
                    count = (i + 1);
                }
            }
            count = String(count).padStart(2, '0');
            index = "<?php echo $data['startInterview'][0]['idx'] ?>";
            let rand = Math.random().toString(36).substr(2, 11);
            let time = new Date().getTime();
            let fileName = index + '-record_' + count + '-' + qIdx + '-' + time + '-' + rand + '.webm';

            if (fileObject.size < 10) {

                $.ajax({
                    type: 'POST',
                    url: '/api/error/page/applier/add',
                    data: {
                        '<?= csrf_token() ?>': emlCsrf.val(),
                        applierIdx: index,
                        questionIdx: qIdx,
                        pullPage: '/Views/interview/start',
                    },
                    success: function(data) {
                        emlCsrf.val(data.code.token);
                        if (data.status == 200) {
                            alert("영상업로드에 오류가 생겼습니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");

                            location.reload();
                        } else {
                            alert(data.messages);
                            return false;
                        }
                        return true;
                    },
                    error: function(e) {
                        alert(`${e.responseJSON.messages} (${e.responseJSON.status})`);
                        return;
                    }
                }) //ajax;
            } else {
                let data = {
                    source: fileObject,
                    name: fileName
                };

                socket.emit('upload', data);
            }

        } else { //재시도
            video.pause();
        }
    }

    function captureCamera(callback) { //카메라 연결확인 함수
        navigator.mediaDevices
            .getUserMedia({
                video: constraints,
                audio: false,
            })
            .then(function(camera) {
                //check_camera=camera;
                callback(camera);
            })
            .catch(function(error) {
                alert('카메라를 인식할 수 없습니다.\n\n카메라 연결 상태를 확인해주세요.');
            });
    }

    function initSocket() {
        socket = io.connect('https://media.highbuff.com:2167', {
            cors: {
                origin: '*'
            }
        });

        socket.on('connect', function() {
            console.log("socket connected");
        });

        socket.on('connect_error', function() {
            if (audio) audio.pause();
            alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [하이버프 인터뷰]로 문의바랍니다.");
            location.reload();
        });

        socket.on('complete', (data) => {
            let count;
            let index;
            let qIdx;
            for (i = 0; i < question_list.length; i++) {
                if (question_list[i]['idx'] == now) {
                    qIdx = question_list[i]["que_idx"];
                    count = (i + 1);
                }
            }
            count = String(count).padStart(2, '0');
            index = "<?php echo $data['startInterview'][0]['idx'] ?>";
            let time = data.split('-')[3].split('.')[0];
            let rand = data.split('-')[4].split('.')[0];
            console.log(time);
            console.log(rand);
            const emlCsrf = $("input[name='<?= csrf_token() ?>']");

            if (data == null || data == "") {
                return false;
            }

            $.ajax({
                type: 'POST',
                url: '/api/applier/file/upload/video',
                data: {
                    '<?= csrf_token() ?>': emlCsrf.val(),
                    index: index,
                    count: count,
                    q_idx: qIdx,
                    time: time,
                    rand: rand,
                    'postCase': 'video_upload',
                    'memIdx': '<?= $data['session']['idx'] ?>',
                    BackUrl: '/'

                },
                success: function(data) {
                    emlCsrf.val(data.code.token);
                    if (data.status == 200) {
                        alert(data.messages);
                        now = data.All[0].next_question.idx;
                        setWaitQuestion(now);
                    } else if (data.status == 201) {
                        alert(data.messages);
                        location.href = "/interview/end/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>";
                    } else {
                        alert(data.messages);
                        return false;
                    }
                    return true;
                },
                error: function(e) {
                    alert(`${e.responseJSON.messages} (${e.responseJSON.status})`);
                    return;
                }
            }) //ajax;
        });

        socket.on('disconnect', function() {
            alert("서버 연결이 끊어졌습니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [하이버프 인터뷰]로 문의바랍니다.");

            // $.ajax({
            //     type: "POST",
            //     url: "erro_telegram_send.php",
            //     data: {
            //         method: "disconnect",
            //     },
            //     success: function(data) {
            //         var json = JSON.parse(data);
            //         if (json["result"] == 200) {
            //             location.reload();
            //         }
            //     },
            //     beforeSend: function() {},
            //     complete: function() {},
            //     error: function(e) {
            //         alert("서버 에러가 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");
            //         return;
            //     },
            //     timeout: 5000,
            // }); //ajax


            // location.reload();
        });
    }

    function answerTimer(time) {
        count = setSec;
        if (time == "stop") {
            $('#min_text').text(count);
            clearInterval(timer);
            $('.heart_graph').removeClass('heart_on');
        } else {
            $('.heart_graph').addClass('heart_on');
            timer = setInterval(function() {
                count--;
                $('#min_text').text(count);
                if (count == 0) {
                    clearInterval(timer);
                    $('.heart_graph').removeClass('heart_on');
                    if (time == "start_a") {
                        $('#answer_btn').click();
                    } else if (time == "start_n") {
                        $('#next_btn').click();
                    }
                }
            }, 1000);
        }
    }
</script>