<style>
    .swal2-actions {
        display: none;
    }
</style>

<h2 id="applyTitle">인터뷰 지원하기</h2>

<?php
foreach ($data['job'] as $key => $val) :
?>
    <div style="border:1px solid;">
        <p>(공고idx) <?= $data['job'][$key]['recIdx'] ?> </p>
        <p>(기업idx) <?= $data['job'][$key]['comIdx'] ?> </p>
        <p>(기업명) <?= $data['job'][$key]['com_name'] ?> </p>
        <p>(공고제목) <?= $data['job'][$key]['rec_title'] ?></p>
        <p>삭제</p>
    </div>
<?php
endforeach
?>

<div style="border: 1px solid;">
    <p>인터뷰(레포트)선택 *필수 <span style="border:1px solid" onclick="changeInterview()">변경</span> / <a href="">재응시요청</a></p>
    <?php if ($data['compareCnt'] == '0') : ?>
        <div id="isSame">*직무가 일치하지 않습니다.</div>
    <?php endif; ?>
    <div style="border: 1px solid;">
        <ul id="applyInterview">
            <li>(idx) <?= $data['interviewInfo'][4] ?></li>
            <li>(프로필사진) <?= $data['interviewInfo'][2] ?></li>
            <li>(카테고리) <?= $data['interviewInfo'][1] ?></li>
            <li>(등급) <?= $data['reportInfo'][1] ?></li>
            <li>(질문수) <?= $data['reportInfo'][0] ?></li>
            <li>(날짜) <?= $data['interviewInfo'][0] ?></li>
            <li>(공개여부) <?= $data['interviewInfo'][3] ?></li>
        </ul>
    </div>
</div>

<br><br>

<button> + 새 인터뷰하기</button> (인터뷰 시작하는 화면으로 이동)
<br><br>

<div style="border: 1px solid;">
    <p>이력서 첨부 선택 *필수 <span style="border:1px solid" onclick="changeResume()">변경</span></p>

    <?php if ($data['rCount'] == 0) : ?>
        <b>선택</b>
    <?php else : ?>
        <b>필수</b>
    <?php endif; ?>

    <div style="border: 1px solid;">
        <ul id="applyResume">
            <li>(이력서idx) <?= $data['resume']['idx'] ?></li>
            <li>(이력서제목) <?= $data['resume']['res_title'] ?></li>
            <li>(이력서등록일) <?= $data['resume']['res_reg_date'] ?></li>
        </ul>
        <button> + 새로 작성하기</button>
    </div>
</div>

<br>

<div style="border: 1px solid;">
    <p>첨부파일 업로드 선택 *필수 <a href="">변경</a></p>
    <div style="border: 1px solid;">
        <ul>
            <li>파일명</li>
        </ul>
        <input type="file" name="123" id="imageFileOpenInput" accept="">
    </div>
</div>

<?= csrf_field() ?>

<form action="/jobs/jobApplyAction" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    recState <input type="text" name="applyState" value="<?= $data['aData']['get']['state'] ?>"><br>
    enIdx <input type="text" name="enIdx" value="<?= $data['aData']['get']['recIdx'] ?>"><br>

    enData <input type="text" name="enData" value="<?= $data['aData']['get']['recIdx'] ?>">

    mem_idx <input type="text" name="memIdx" value="<?= $data['session']['idx'] ?>"><br>
    res_idx <input type="text" name="resIdx" value="<?= $data['resume']['idx'] ?>"><br>

    <?php
    foreach ($data['job'] as $val) :
    ?>
        com_idx <input type="text" name="comIdx[]" value="<?= $val['comIdx'] ?>"><br>
    <?php
    endforeach;
    ?>

    <?php
    foreach ($data['_idx'] as $val) :
    ?>
        rec_idx <input type="text" name="recIdx[]" value="<?= $val ?>"><br>
    <?php
    endforeach;
    ?>

    <div id="fileWrap">
        file <input type="file" name="file[]" id="" accept="" onchange="fileCount()"><br>
    </div>

    <br>

    <button type="submit" onclick="" style="border:1px solid">지원완료</button>
</form>

<br><br>

<br><br><br><br><br><br><br><br>

<script>
    const applyState = '<?= $data['aData']['get']['state'] ?>';
    const jobIdx = '<?php echo json_encode($data['jobIdx']) ?>';
    let selected = '';
    let selectedInfo = '';
    let selectedGrade = '';
    let selectedResume = '';
    let selectedResumeRadio = '';

    $(document).ready(function() {
        if (applyState == 'M') {
            $('#applyTitle').text('내 인터뷰 지원하기');
        }
    });

    function fileCount() {
        let fileCnt = $('input[name*=file]').length;

        if (fileCnt < 3) {
            $('#fileWrap').append('file' + (fileCnt + 1) + '<input type="file" name="file[]" id="" accept="" onchange="fileCount()"><br>');
        }
    }

    function selectInterview() {
        Swal.fire({
            html: `<div>
                        <ul id="myInterviewList" style="font-size: 13px;"></ul>
                        <button onclick="chInterview()" style="width:50%;height:40px;">확인</button>
                        <button onclick="cancel()" style="width:50%;height:40px;">취소</button>
                    </div>`,
            focusConfirm: false,
            confirmButtonText: '',
            allowOutsideClick: false,
            closeClick: true,
            preConfirm: () => {
                return []
            }
        });
    }

    function selectResume() {
        Swal.fire({
            html: `<div>
                        <ul id="resumeList" style="font-size: 13px;"></ul>
                        <button onclick="chResume()" style="width:50%;height:40px;">확인</button>
                        <button onclick="cancel()" style="width:50%;height:40px;">취소</button>
                    </div>`,
            focusConfirm: false,
            confirmButtonText: '',
            allowOutsideClick: false,
            closeClick: true,
            preConfirm: () => {
                return []
            }
        });
    }

    function changeInterview() {
        $.ajax({
            type: "GET",
            url: "/api/recruit/applier/apply/" + '<?= $data['session']['idx'] ?>',
            success: function(data) {
                if (data.status == 200) {
                    $('input[name="csrf_highbuff"]').val(data.code.token);

                    selectedInfo = data;
                    selectInterview();

                    var grade_arr = new Array();

                    let aInfo = data.code.info[0]['itv'];

                    for (i = 0; i < aInfo.length; i++) {
                        var analysis = JSON.parse(aInfo[i]['repo_analysis']);
                        grade_arr.push(analysis['grade']);

                        $('#myInterviewList').append(
                            `<li style="border:1px solid">
                             <input type="radio" name="applierIdx" onclick="clickIdx(${i})" value="${aInfo[i]['idx']}">
                             <div>(idx) ${aInfo[i]['idx']}</div>
                             <div>(프로필사진) ${aInfo[i]['file_save_name']}</div>
                             <div>(카테고리) ${aInfo[i]['job_depth_text']}</div>
                             <div>(등급) ${grade_arr[i]}</div>
                             <div>(질문수) ${data.code.info[0]['cnt'][i]['cnt']}</div>
                             <div>(날짜) ${aInfo[i]['app_reg_date']}</div>
                             <div>(공개여부) ${aInfo[i]['app_share']}</div>
                             </li>`
                        );
                    }

                    selectedGrade = grade_arr;
                } else {
                    alert('정상적인 접근이 아닙니다.');
                    location.href = '/';
                }
            },
            beforeSend: function() {},
            complete: function() {},
            error: function(e) {
                alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");
                return;
            },
            timeout: 5000
        }) //ajax;  
    }

    function changeResume() {
        $.ajax({
            type: "GET",
            url: "/api/recruit/resume/apply/<?= $data['session']['idx'] ?>",
            data: {
                'csrf_highbuff': $('input[name="csrf_highbuff"]').val(),
                'memIdx': '<?= $data['session']['idx'] ?>',
            },
            success: function(data) {
                if (data.status == 200) {
                    $('input[name="csrf_highbuff"]').val(data.code.token);

                    selectedResume = data.code.info;

                    selectResume();

                    for (i = 0; i < selectedResume.length; i++) {
                        $('#resumeList').append(
                            `<li style="border:1px solid">
                                <input type="radio" name="resumeIdx" onclick="clickResumeIdx(${i})" value="${selectedResume[i]['idx']}">
                                <div>(이력서idx) ${selectedResume[i]['idx']}</div>
                                <div>(이력서제목) ${selectedResume[i]['res_title']}</div>
                                <div>(작성날짜) ${selectedResume[i]['res_reg_date']}</div>
                            </li>`
                        );
                    }

                } else {
                    alert('정상적인 접근이 아닙니다.');
                    // location.href = '/';
                }
            },
            beforeSend: function() {},
            complete: function() {},
            error: function(e) {
                alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");
                return;
            },
            timeout: 5000
        }) //ajax;  
    }

    function clickIdx(i) {
        selected = $('input[name=applierIdx]:checked').val();
        selected = String(i);
    }

    function clickResumeIdx(i) {
        selectedResumeRadio = $('input[name=resumeIdx]:checked').val();
        selectedResumeRadio = String(i);
    }

    function chInterview() {
        if (selected == "" || selected == null) {
            alert('인터뷰를 선택해주세요.');
        } else {
            var isSame = 0;
            for (i = 0; i < jobIdx.length; i++) {
                if (jobIdx[i] == selected) {
                    isSame++;
                }
            }

            if (isSame == '0') {
                $('#isSame').text('직무가 일치하지 않습니다.');
            } else {
                $('#isSame').text('직무가 일치합니다.');
            }

            $('#applyInterview').empty();

            let aInfo = selectedInfo['code']['info'][0]['itv'][selected];
            let aCnt = selectedInfo['code']['info'][0]['cnt'][selected]['cnt'];

            $('#applyInterview').html(
                `<li style="border:1px solid">
                <div>(idx) ${aInfo['idx']}</div>
                <div>(프로필사진) ${aInfo['file_save_name']}</div>
                <div>(카테고리) ${aInfo['job_depth_text']}</div>
                <div>(등급) ${selectedGrade[selected]}</div>
                <div>(질문수) ${aCnt}</div>
                <div>(날짜) ${aInfo['app_reg_date']}</div>
                <div>(공개여부) ${aInfo['app_share']}</div>
                </li>`
            );

            alert('인터뷰가 변경되었습니다.');
            swal.close();
        }
    }

    function chResume() {
        if (selectedResumeRadio == "" || selectedResumeRadio == null) {
            alert('이력서를 선택해주세요.');
        } else {
            $('#applyResume').empty();
            $('#applyResume').html(
                `<li>
                <div>(이력서idx) ${selectedResume[selectedResumeRadio]['idx']}</div>
                <div>(이력서제목) ${selectedResume[selectedResumeRadio]['res_title']}</div>
                <div>(작성날짜) ${selectedResume[selectedResumeRadio]['res_reg_date']}</div>
                </li>`
            );

            alert('이력서가 변경되었습니다.');
            swal.close();
        }
    }

    function cancel() {
        selected = '';
        selectedResumeRadio = '';
        swal.close();
    }

    function submit() {
        $.ajax({
            type: "POST",
            url: "/jobs/submit",
            data: {
                'csrf_highbuff': $('input[name="csrf_highbuff"]').val(),
                'memIdx': 1,
                'recIdx': [1, 2],
            },
            success: function(data) {
                var json = JSON.parse(data);
                if (json["result"] == 200) {
                    $('input[name="csrf_highbuff"]').val(json['csrf']);
                } else {
                    alert(data);
                }
            },
            beforeSend: function() {},
            complete: function() {},
            error: function(e) {
                alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");
                return;
            },
            timeout: 5000
        })
    }
</script>