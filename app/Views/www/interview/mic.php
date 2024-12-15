
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    마이크 체크!!!
    <div id="start_mic">
        <div>
            인터뷰 시작 전, <br>
            목소리가 잘 인식되는지 <br>
            확인해 볼게요!
        </div>
        <hr>
        <form action="/interview/skipMicAction" method="POST" id="skip">
            <?= csrf_field() ?>
            <input name="applyIdx" value="<?= $data['applyIdx'] ?>">
            <input name="memIdx" value="<?= $data['memIdx'] ?>">
            <input name="process" id="process" value="2">
            <input name="postCase" value="skipMic" type="hidden">
            <input name="backUrl" value="/" type="hidden">
        </form>
        <div class="skipMic">다음에 할게요</div>
        <hr>
        <div>
            <img src="https://interview.highbuff.com//share/img/sub/mic_ck01_icon.png" alt="">
        </div>
        <hr>
        <div>
            <button id="mic_start_btn">음성인식 시작</button>
        </div>
    </div>
    <hr>
    <!-- 마이크테스트! -->
    <div class="ht100 overflow" id="ck_mic" style="display:none;">
        <!--s top_tltBox-->
        <div class="top_tltBox c no_line">
            <!--s top_tltcont-->
            <div class="top_tltcont">
                <a href="javascript:window.history.back();">
                    <div class="backBtn"><span>뒤로가기</span></div>
                </a>
                <a href="javascript:void(0)" class="top_gray_txtlink gray_txtlink skipMic">건너뛰기</a>
            </div>
            <!--e top_tltcont-->
        </div>
        <!--e top_tltBox-->

        <!--s scont-->
        <div class="scont">
            <!--s cntBpx-->
            <div class="cntBpx cntBpx2 c">
                <!--s bigtlt-->
                <div class="bigtlt">
                    큰소리로 따라해보세요<br />
                    <span class="b point" id="micSen"></span>
                </div>
                <!--e bigtlt-->

                <!--s 아이콘-->
                <div class="icon mic_icon02_1"><img src="https://interview.highbuff.com/share/img/sub/mic_ck02_icon1.png"></div>
                <div class="mic_num point main-text">10</div>

                <svg preserveAspectRatio="none" id="visualizer" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100px" height="10px" style="background-color:#fff;">
                    <defs>
                        <mask id="mask">
                            <g id="maskGroup"></g>
                        </mask>
                        <linearGradient id="gradient" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#db6247;stop-opacity:1" />
                            <stop offset="40%" style="stop-color:#f6e5d1;stop-opacity:1" />
                            <stop offset="60%" style="stop-color:#5c79c7;stop-opacity:1" />
                            <stop offset="85%" style="stop-color:#b758c0;stop-opacity:1" />
                        </linearGradient>
                    </defs>
                    <rect x="0" y="0" width="100px" height="100px" fill="url(#gradient)" mask="url(#mask)"></rect>
                </svg>

                <div class="icon mic_icon02_2">
                    <div class="mic_icon02_2_1"><img src="https://interview.highbuff.com/share/img/sub/mic_ck02_icon2_1.png"></div>
                    <div class="mic_icon02_2_2"><img src="https://interview.highbuff.com/share/img/sub/mic_ck02_icon2_2.png"></div>
                    <div class="mic_icon02_2_3"><img src="https://interview.highbuff.com/share/img/sub/mic_ck02_icon2_3.png"></div>
                </div>
                <!--e 아이콘-->
            </div>
            <!--e cntBpx-->

            <!--s BtnBox-->
            <div class="BtnBox wd1 abs">
                <button href="" class="btn btn01" id="button">다음</button>
            </div>
            <!--e BtnBox-->
        </div>
        <!--e scont-->
    </div>

    <!-- 마이크테스트실패 -->
    <div class="ht100 overflow" id="mic_cont2" style="display:none;">
        <!--s top_tltBox-->
        <div class="top_tltBox c no_line">
            <!--s top_tltcont-->
            <div class="top_tltcont">
                <a href="javascript:window.history.back();">
                    <div class="backBtn"><span>뒤로가기</span></div>
                </a>
                <a href="javascript:void(0)" class="top_gray_txtlink gray_txtlink skipMic">건너뛰기</a>
            </div>
            <!--e top_tltcont-->
        </div>
        <!--e top_tltBox-->

        <!--s scont-->
        <div class="scont">
            <!--s cntBpx-->
            <div class="cntBpx c">
                <!--s bigtlt-->
                <div class="bigtlt" id="failed_speech">
                    <span class="b point"></span>
                    <br /><br />큰소리로 따라해보세요<br />
                    <div class="b point">“ 안녕하세요! <?= $data['session']['name'] ?>입니다 ”</div>
                </div>
                <!--e bigtlt-->

                <!--s 아이콘-->
                <div class="icon mic_icon03_1"><img src="https://interview.highbuff.com/share/img/sub/mic_ck03_icon.png"></div>
            </div>
            <!--e cntBpx-->

            <!--s BtnBox-->
            <div class="BtnBox wd1 abs">
                <a href="mic_ck01.php" class="btn btn01">재시도</a>
            </div>
            <!--e BtnBox-->
        </div>
        <!--e scont-->
    </div>
</body>

</html>
<script>
    let timerInterval;
    let success = false;
    let base64AudioFormat;
    let permission = false;
    let audioStream;
    let chunks;
    let device_check = false;
    let mediaRecorder;
    $("#mic_start_btn").on('click', function() {
        deviceCheck();

    });

    $("#button").click(function() {
        $("#visualizer").css("visibility", "hidden");
        mediaRecorder.stop();
    });

    function deviceCheck() {
        if (navigator.mediaDevices) {
            const constraints = {
                audio: true
            }
            navigator.mediaDevices.getUserMedia(constraints)
                .then(stream => {
                    device_check = true;
                    let paths = document.getElementsByTagName('path');
                    let visualizer = document.getElementById('visualizer');
                    let mask = visualizer.getElementById('mask');
                    let AudioContext;
                    let audioContent;
                    let start = false;
                    let path;

                    let soundAllowed = function(stream) {
                        permission = true;
                        audioStream = audioContent.createMediaStreamSource(stream);
                        let analyser = audioContent.createAnalyser();
                        let fftSize = 1024;
                        analyser.fftSize = fftSize;
                        audioStream.connect(analyser);
                        let bufferLength = analyser.frequencyBinCount;
                        let frequencyArray = new Uint8Array(bufferLength);
                        visualizer.setAttribute('viewBox', '0 0 255 255');

                        for (let i = 0; i < 255; i++) {
                            path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                            path.setAttribute('stroke-dasharray', '4,1');
                            mask.appendChild(path);
                        }
                        let doDraw = function() {
                            requestAnimationFrame(doDraw);
                            if (start) {
                                analyser.getByteFrequencyData(frequencyArray);
                                let adjustedLength;
                                for (let i = 0; i < 255; i++) {
                                    adjustedLength = Math.floor(frequencyArray[i]) - (Math.floor(frequencyArray[i]) % 5);
                                    paths[i].setAttribute('d', 'M ' + (i) + ',255 l 0,-' + adjustedLength);
                                }
                            } else {
                                for (let i = 0; i < 255; i++) {
                                    paths[i].setAttribute('d', 'M ' + (i) + ',255 l 0,-' + 0);
                                }
                            }
                        }
                        doDraw();
                        //audio recording
                        mediaRecorder = new MediaRecorder(stream);
                        mediaRecorder.start();
                        chunks = [];
                        mediaRecorder.ondataavailable = function(e) {
                            chunks.push(e.data);
                            checkSentence();
                            clearInterval(timerInterval);
                        }
                    }

                    let soundNotAllowed = function(error) {
                        alert("마이크 연결을 확인해주세요.");
                    }

                    $('#start_mic').hide();
                    $('#ck_mic').show();
                    if (this.innerHTML == "제출하기") {
                        mediaRecorder.stop();
                    } else {
                        $(".main-text").text("10");
                        sentenceTimer();
                        if ("<?= $data['session']['name'] ?>" == "" || "<?= $data['session']['name'] ?>" == null) {
                            $("#micSen").html('<span>"안녕하세요. 마이크테스트입니다."</span>');
                        } else {
                            $("#micSen").html('<span>"안녕하세요. <?= $data['session']['name'] ?> 입니다."</span>');
                        }

                        if (screen.width > 768) {
                            $(".button-container").css("margin-top", "-450px");
                        } else {
                            $(".button-container").css("margin-top", "-450px");
                        }

                        if (!permission) {
                            navigator.mediaDevices.getUserMedia({
                                    audio: true
                                })
                                .then(soundAllowed)
                                .catch(soundNotAllowed);

                            AudioContext = window.AudioContext || window.webkitAudioContext;
                            audioContent = new AudioContext();
                        }
                        //getSentence();
                        start = true;
                    }

                    $('#mic_start_btn').attr('src', '/share/img/sub/mic_icon01_able.png');
                })
                .catch(error => {
                    device_check = false;
                    console.log(error);
                    $('#mic_start_btn').attr('src', '/share/img/sub/mic_icon01_disable.png');
                })
        }
    }

    function sentenceTimer() {
        timerInterval = setInterval(() => {
            let countTime = parseInt($(".main-text").text());
            countTime--;
            $(".main-text").text(countTime);
            if (countTime <= 0) {
                clearInterval(timerInterval);
                mediaRecorder.stop();
            }
        }, 1000);
    }

    function getAjax(params) {
        const emlCsrf = $("input[name='<?= csrf_token() ?>']");
        let data = {};
        // if (!params['type']) {
        //     return false;
        // }
        // if (params['type'] == 'mic_check') {
        //     data = {
        //         'postCase': 'mic_file',
        //         'memIdx': '<?= $data['session']['idx'] ?>',
        //         'micFile': params['micData'],
        //     }
        // }
        console.log(params);

        $.ajax({
            type: 'POST',
            url: "/api/applier/mic/upload/check",
            data: params,
            contentType: false,
            processData: false,
            success: function(data) {
                emlCsrf.val(data.code.token);
                if (data.status == 200) {
                    alert(data.messages + "!!!!!!!!!!!!");
                    //location.href = '/';
                    return;
                    if (params['type'] = 'mic_check') {
                        //location.href = "/interview/profile/check/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>/" + data.fileIdx;
                    }
                } else {
                    alert(data.messages);
                    return false;
                }
                return true;
            },
            error: function(e) {
                alert(`${e.responseJSON.messages} (${e.responseJSON.status})!!!!!!!!`);
                return;
            }
        }) //ajax;
    }

    function checkSentence() {
        $("#visualizer").css("visibility", "hidden");
        const blob = new Blob(chunks, {
            'type': 'audio/wav; codecs=0'
        });
        if (navigator.maxTouchPoints > 1) {
            let checkDevice = 1;
        } else {
            let checkDevice = 2;
        }
        chunks = [];
        let data = new FormData();
        let micTest = "";
        const emlCsrf = $("input[name='<?= csrf_token() ?>']");

        data.append('file', blob);
        data.append('postCase', "mic_file");
        data.append('checkDevice', checkDevice);
        data.append('applyIdx', "<?= $data['applyIdx']; ?>");
        data.append('csrf_highbuff', emlCsrf.val());
        data.append('memIdx', '<?= $data['session']['idx'] ?>');

        if ("<?= $data['session']["name"] ?>" == "" || "<?= $data['session']["name"] ?>" == null) {
            data.append('word', "마이크테스트");
            // micTest = "마이크테스트";
        } else {
            data.append('word', "<?= $data['session']["name"] ?>");
            // micTest = "<?= $data['session']["name"] ?>";
        }

        //console.log(blob);

        // let aParam = {
        //     'type': 'mic_check',
        //     'method': 'POST',
        //     'url': '/api/applier/mic/upload/check',
        //     'micData': data,
        // };

        for (let pair of data.entries()) {
            console.log(pair[0] + ', ' + pair[1]);
        }
        getAjax(data);
    }

    $('.skipMic').on('click', function() {
        $('#skip').submit();
    })

    // function checkSentence() {
    //     $("#visualizer").css("visibility", "hidden");
    //     const blob = new Blob(chunks, {
    //         'type': 'audio/wav; codecs=0'
    //     });
    //     if (navigator.maxTouchPoints > 1) {
    //         let checkDevice = 1;
    //     } else {
    //         let checkDevice = 2;
    //     }
    //     chunks = [];
    //     let data = new FormData();
    //     data.append('file', blob);
    //     data.append('method', "check");
    //     data.append('checkDevice', checkDevice);
    //     data.append('index', "</?= $index ?>");
    //     if ("<? //= $userinfo["user_name"] 
                ?>" == "" || "<? //= $userinfo["user_name"] 
                                ?>" == null) {
    //         data.append('word', "마이크테스트");
    //     } else {
    //         data.append('word', "<? //= $userinfo["user_name"] 
                                    ?>");
    //     }

    //     $.ajax({
    //         url: "mic_ajax.php",
    //         type: "POST",
    //         data: data,
    //         contentType: false,
    //         processData: false,
    //         success: function(data) {
    //             //return ; 
    //             let json = JSON.parse(data);

    //             if (json["result"] == 200) {
    //                 audioStream.disconnect(); //audio stream disconnect
    //                 permission = false;

    //                 $(".button-container").css("margin-top", "-700px");
    //                 $(".main-text").text("");
    //                 $(".red-button").hide();
    //                 $("#confirm2").show();
    //                 $("h2").html("음성 인식이 완료 되었습니다. </br>다음 버튼을 눌러주세요.");

    //                 location.href = 'mic_ck04.php?index=<? //= $en_index 
                                                            ?>';

    //                 Swal.fire({
    //                     position: 'center',
    //                     icon: 'success',
    //                     title: '음성 인식이 완료되었습니다.',
    //                     showConfirmButton: false,
    //                     timer: 1500,
    //                 })
    //             } else if (json["result"] == 999) {
    //                 $("#mic_cont2").show();
    //                 $("#ck_mic").hide();
    //                 $(".mic_cont").hide();
    //                 let speech_to_text = json["speech_to_text"];
    //                 $("#failed_speech span").text('테스트결과 : ' + speech_to_text);
    //                 return;

    //                 $("h2").html(json["message"] + "<br/> speech : " + speech_to_text);
    //                 $("#button").text("다시하기").addClass("green-button").removeClass("red-button");
    //                 $(".button-container").css("margin-top", "-700px");
    //                 $(".main-text").text("");

    //                 audioStream.disconnect(); //audio stream disconnect
    //                 permission = false;
    //             } else {
    //                 alert(json["message"]);
    //             }
    //         },
    //         beforeSend: function() {
    //             $.blockUI.defaults.baseZ = 999999;

    //             $.blockUI.defaults.css = {
    //                 position: 'absolute',
    //                 padding: 0 + 'px',
    //                 margin: 0 + 'px',
    //                 top: 50 + '%',
    //                 left: 50 + '%',
    //                 textAlign: 'center',
    //                 cursor: 'wait',
    //                 opacity: 0.657775,
    //                 transform: 'translateX(-50%)'
    //             };

    //             $.blockUI({
    //                 message: '<div class="loadingio-spinner-spinner-eprubzhrgzv"><div class="ldio-4psqt538f76"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>'
    //             });
    //         },
    //         complete: function() {
    //             $.unblockUI();
    //         },
    //         error: function(e) {
    //             alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [인터뷰]로 문의 부탁드립니다.1");
    //             return;
    //         }
    //     });
    // }
</script>