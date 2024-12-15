<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    실제 프로필 찍기!
    <!--s txt-->
    <div class="txt" id="text_">
        얼굴인식을 준비중입니다. <br /> 잠시만 기다려주세요.
    </div>
    <div class="txt" id="text_2" style="display:none">
        3
    </div>
    <!--e txt-->
    <!--s play_icon-->
    <div class="play_icon" id="btn_shot" style="display:none;">
        <div class="play">버튼!!!!!!</div><!-- 다시 찍어야할때 숨김처리 -->
        <div class="play_txt" id="btn_reshot" style="display:none">다시<br />찍기</div><!-- 다시 찍어야할때 나타나게 -->
    </div>
    <!--e play_icon-->

    <!--s fv_next-->
    <div class="fv_iconBtn fv_next" id="btn_next" style="display:none">
        <a href="javascript:void(0)">
            <div class="icon"><img src="/share/img/sub/fv_next_icon.png"></div>
            <div class="txt">다음으로</div>
        </a>
    </div>
    <!--e fv_back-->

    <!--s videoBox-->
    <div class="videoBox">
        <canvas id="_canvas" class="" style="display:none;"></canvas>
        <img id="photo" class="videoContent" style="display:none;-webkit-transform: rotateY(180deg);" /> <!-- 사진이 찍힌 이미지 보여주기-->
        <!--pc 화면일때 확인용--><video class="videoContent" preload="metadata" id="v_pc_1" autoplay playsinline style="-webkit-transform: rotateY(180deg);"></video>
        <!--pc 화면일때 확인용-->
        <!--모바일 화면일때 확인용-->
        <!-- <video class="videoContent" preload="metadata" id="v_pc_3" controls="" src="https://media.highbuff.com/data/uploads/5524-record_01-1-1630388117.mp4#t=0.001"></video> -->
        <!--모바일 화면일때 확인용-->
    </div>
    <!--e videoBox-->
    <div id="btn-start-recording" style="display: none;">
        <button id="btn-start-recording">Start Recording</button>
    </div>
    <?= csrf_field() ?>
</body>

</html>

<script src="https://cdn.socket.io/4.2.0/socket.io.min.js" integrity="sha384-PiBR5S00EtOj2Lto9Uu81cmoyZqR57XcOna1oAuVuIEjzj0wpqDVfD0JA9eXlRsj" crossorigin="anonymous"></script>
<script src="https://www.webrtc-experiment.com/EBML.js"></script>
<script src="https://www.webrtc-experiment.com/DetectRTC.js"></script>
<script src="https://www.WebRTC-Experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://www.webrtc-experiment.com/common.js"></script>
<script src="https://unpkg.com/@tensorflow/tfjs-core@2.4.0/dist/tf-core.js"></script>
<script src="https://unpkg.com/@tensorflow/tfjs-converter@2.4.0/dist/tf-converter.js"></script>
<script src="https://unpkg.com/@tensorflow/tfjs-backend-webgl@2.4.0/dist/tf-backend-webgl.js"></script>
<script src="https://unpkg.com/@tensorflow-models/face-landmarks-detection@0.0.1/dist/face-landmarks-detection.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@tensorflow-models/facemesh"></script>
<script src="/plugins/tfjs/tf-triangulation.js"></script>
<script src="<?= $data['url']['menu'] ?>/plugins/bowser/bundled.js"></script>


<script>
    //jQuery 셀렉터는 이벤트를 가져오지 못합니다.
    const video = document.getElementById("v_pc_1");
    const canvas = document.getElementById("_canvas");
    const photo = document.querySelector('#photo'); //사진 이미지 변수
    let _winWidth = video.innerWidth;
    let _winHeight = video.innerHeight;
    let timer;
    let count = 3;
    let reshot = false;
    let shot_btn_ck = true;
    let changeImg = true;
    let info = bowser.parse(window.navigator.userAgent);

    let constraints = { //화면 크기
        width: {
            ideal: _winWidth,
            min: 640,
            max: 1920
        },
        height: {
            ideal: _winHeight,
            min: 480,
            max: 1080
        },
        frameRate: {
            ideal: 30,
            max: 30
        },
        facingMode: {
            ideal: 'user'
        }
    };

    checkDevice();

    $(document).ready(function() {
        $("#btn-start-recording").trigger("click");
        $('#_canvas').attr('width', constraints.width.min);
        $('#_canvas').attr('height', constraints.height.min);
        //bizCheck();
    });

    const setupCamera = () => {
        navigator.mediaDevices
            .getUserMedia({
                video: constraints,
                audio: false,
            })
            .then((stream) => {
                video.srcObject = stream;
                initSocket();
            })

            .catch((e) => {
                if (e) {
                    if (e.name == 'OverconstrainedError') {
                        alert('지원하지 않는 카메라의 해상도입니다.\n\n사용하고 계신 카메라를 확인하시거나 고객센터로 문의바랍니다.')
                    } else {
                        console.log(e);
                        alert('카메라가 연결되어 있지 않거나 정상적인 접근이 아닙니다. \n\n고객센터로 문의바랍니다.\n\n')
                    }
                    //location.href="/";
                    return;
                }
            });
    };

    document.getElementById('btn-start-recording').onclick = function() {
        setupCamera(function(camera) {
            video.muted = true;
            video.volume = 0;
            video.srcObject = camera;
            let recordingHints = {
                type: 'video',
                mimeType: 'video/webm;codecs=vp8',
                frameRate: 15
            };
        });
    };

    function initSocket() {
        socket = io.connect('<?= $data['url']['mediaFull'] ?>', {
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
            return false;
        });

        socket.on('complete_thumb', (data) => {
            let aParam = {
                'type': 'complete_thumb',
                'method': 'POST',
                'url': '/api/applier/file/upload/thumb/add',
                'fileData': data
            };
            getAjax(aParam);
        });
        socket.on('disconnect', function() {
            alert("서버 연결이 끊어졌습니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [하이버프 인터뷰]로 문의바랍니다.");
            return false;
        });
    }

    const detectFaces = async () => { //미소인식 함수

        if (shot_btn_ck) {
            $('#btn_shot').show();
            shot_btn_ck = false;
        }

        const prediction = await model.estimateFaces({
            input: video,
            returnTensors: false,
            flipHorizontal: false,
            predictIrises: false
        });

        let w = video.videoWidth;
        let h = video.videoHeight;

        //face detect
        if (prediction.length > 0) {
            let mesh = prediction[0].scaledMesh;
            //smile check
            let d1 = distance(prediction[0].annotations["noseBottom"][0], prediction[0].annotations["lipsUpperOuter"][5]); //인중거리
            let d2 = distance(prediction[0].annotations["lipsUpperInner"][5], prediction[0].annotations["lipsLowerInner"][5]); //입벌리는거리
            let p1 = (d2 / (2 * d1)) * 0.2; //입벌리는거 체크 가중치 20%

            let result = [];
            let left_upper_lip = prediction[0].annotations["lipsUpperOuter"][0][1];
            let right_upper_lip = prediction[0].annotations["lipsUpperOuter"][10][1];
            for (i = 0; i < prediction[0].annotations["lipsUpperOuter"].length; i++) {
                if (prediction[0].annotations["lipsUpperOuter"][i][1] > left_upper_lip && prediction[0].annotations["lipsUpperOuter"][i][1] > right_upper_lip) {
                    result.push(i);
                }
            }
            for (i = 0; i < prediction[0].annotations["lipsUpperInner"].length; i++) {
                if (prediction[0].annotations["lipsUpperInner"][i][1] > left_upper_lip && prediction[0].annotations["lipsUpperInner"][i][1] > right_upper_lip) {
                    result.push(i);
                }
            }
            for (i = 0; i < prediction[0].annotations["lipsLowerInner"].length; i++) {
                if (prediction[0].annotations["lipsLowerInner"][i][1] > left_upper_lip && prediction[0].annotations["lipsLowerInner"][i][1] > right_upper_lip) {
                    result.push(i);
                }
            }
            for (i = 0; i < prediction[0].annotations["lipsLowerOuter"].length; i++) {
                if (prediction[0].annotations["lipsLowerOuter"][i][1] > left_upper_lip && prediction[0].annotations["lipsLowerOuter"][i][1] > right_upper_lip) {
                    result.push(i);
                }
            }
            let lips_sum = prediction[0].annotations["lipsLowerOuter"].length + prediction[0].annotations["lipsLowerInner"].length + prediction[0].annotations["lipsUpperInner"].length + prediction[0].annotations["lipsUpperOuter"].length;
            let p2 = (result.length / lips_sum) * 0.4; //입의 방향 체크 가중치 40% 

            let dlc = distance(prediction[0].annotations["lipsUpperOuter"][0], mesh[58]); //왼쪽입과 볼사이의 거리
            let drc = distance(prediction[0].annotations["lipsUpperOuter"][10], mesh[288]); //오른쪽입과 볼사이의 거리
            let dlj = distance(prediction[0].annotations["lipsUpperOuter"][0], mesh[149]); //왼쪽입과 턱사이의 거리
            let drj = distance(prediction[0].annotations["lipsUpperOuter"][10], mesh[378]); //오른쪽입과 턱사이의 거리
            let p3 = ((dlj + drj) / (dlj + drj + dlc + drc)) * 0.4; //입의 위치 체크 가중치 40%

            //console.log(p1+p2+p3);
            $('#text_').html("프로필 저장을 위해 촬영을 진행합니다. <br /> 프로필 촬영 전 한번 웃어 볼까요?");
            if (p1 + p2 + p3 > 0.5) {
                console.log(p1 + p2 + p3);
                clearInterval(timer);
                $('#btn_shot').click();
            }
        } else {
            //alert('얼굴인식 못함');
            $('#text_').html("프로필 저장을 위해 촬영을 진행합니다. <br /> 얼굴을 화면 중앙에 인식 시켜주세요.");
        }
        await tf.nextFrame();
    };

    //얼굴인식 함수 실행시키는 함수
    video.addEventListener("loadeddata", async () => {
        model = await faceLandmarksDetection.load(faceLandmarksDetection.SupportedPackages.mediapipeFacemesh);
        try {
            timer = setInterval(detectFaces, 50);
        } catch (error) {
            alert('얼굴 인식 기능이 작동하지 않습니다. \n\n고객센터로 문의바랍니다.\n\n');
        }
    });

    function distance(a, b) {
        return Math.sqrt(Math.pow(a[0] - b[0], 2) + Math.pow(a[1] - b[1], 2));
    }

    function setup() { //카운터가 돌아가는 함수
        count--;
        $('#text_2').text(count);
        if (count < 1) {
            //console.log(count);
            if (changeImg == true) {
                takePicture();
            }
            clearInterval(setupInterval);

            $('#btn_shot').show();
            $('#btn_reshot').show();
            $('#btn_next').show();
            $('#v_pc_1').hide();
            $('#photo').show();
            $('#text_2').text("프로필 촬영이 완료되었습니다.");

        }
    }

    function takePicture() {
        changeImg = false;
        let context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        let data = canvas.toDataURL('image/png');
        //console.log(data);
        photo.setAttribute('src', data);
        photo.style.display = "";
    }

    function getFileName(fields, fileExtension) {
        let d = new Date();
        let index = "<?= $data['applyIdx'] ?>";
        let rand = Math.random().toString(36).substr(2, 11);
        let times = d.getTime();
        return index + "-" + fields + "-" + times + '-' + rand + '.' + fileExtension;
    }

    $('#btn_shot').on("click", function(ev) {
        ev.preventDefault();
        clearInterval(timer);
        if (reshot == false) {
            clearInterval(timer);
            if (count > 0) {
                setupInterval = setInterval(setup, 1500);
                $('#btn_shot').hide();
                $('#btn_next').hide();
                $('#text_').hide();
                $('#v_pc_1').show();
                $('#photo').hide();
                $('#text_2').show();

                reshot = true;
            }
        } else {
            clearInterval(timer);
            count = 3;
            changeImg = true;
            if (count > 0) {
                setupInterval = setInterval(setup, 1500);
                $('#btn_shot').hide();
                $('#btn_next').hide();
                $('#text_').hide();
                $('#v_pc_1').show();
                $('#photo').hide();
                $('#text_2').show();
                $('#text_2').text(count);

            }
        }
    })

    $('#btn_next').on("click", function(e) {
        e.preventDefault();
        const filename = getFileName("thumbnail", "png");
        fetch(photo.src)
            .then(res => res.blob())
            .then(blob => {
                const file = new File([blob], filename, {
                    type: 'image/png'
                });
                const data = {
                    source: file,
                    name: filename,
                    size: file.size,
                };
                socket.emit('thumbnail', data);
            });

    });

    function getAjax(params) {
        const emlCsrf = $("input[name='<?= csrf_token() ?>']");
        let data = {};
        if (!params['type']) {
            return false;
        }
        if (params['type'] == 'complete_thumb') {

            data = {
                '<?= csrf_token() ?>': emlCsrf.val(),
                'fileOrgName': params['fileData']['name'],
                'fileSaveName': params['fileData']['filePath'].substr(1),
                'fileSize': params['fileData']['size'],
                'fileType': 'A',
                'postCase': 'file_write',
                'memIdx': '<?= $data['session']['idx'] ?>'
            };
        }
        console.log(data);

        $.ajax({
            type: params['method'],
            url: params['url'],
            data: data,
            success: function(data) {
                emlCsrf.val(data.code.token);
                if (data.status == 200) {
                    alert(data.messages);
                    if (params['type'] = 'complete_thumb') {
                        location.href = "/interview/profile/check/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>/" + data.fileIdx;
                    }
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
    }

    function checkDevice() {
        if (info.platform.type == 'desktop') {
            if (info.os.name == 'Windows') {
                if (info.browser.name != "Chrome") {
                    alert('Windows 환경에서는 Chrome 브라우저를 이용해주세요.');
                    location.href = "/";
                }
            } else if (info.os.name == "macOS") {
                if (info.browser.name != "Safari") {
                    alert("Mac 환경에서는 Safari 브라우저를 이용해주세요.");
                    location.href = "/";
                }
            }
        } else { //모바일
            if (info.os.name == 'Android') {
                if (info.browser.name != "Chrome") {
                    alert('ANDROID 환경에서는 Chrome 브라우저를 이용해주세요.');
                    location.href = "/";
                }
            } else if (info.os.name == "iOS") {
                if (info.browser.name != "Safari") {
                    alert("IOS 환경에서는 Safari 브라우저를 이용해주세요.");
                    location.href = "/";
                }
            }

        }
    }
</script>