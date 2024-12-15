<h2>지원완료 페이지</h2>

<div style="border:1px solid;height:200px;">완료 이미지</div>

<p>[공고명]</p>

<?php
foreach ($data['recruitTitles'] as $key => $val) :
?>
    <p>공고 <?= $key + 1 ?> : <?= $data['recruitTitles'][$key]['rec_title'] ?></p>
<?php
endforeach
?>

<p>지원이 완료되었습니다.</p>

<div>지원현황은 마이페이지에서 확인해주세요</div>

<div>
    <button>지원현황보기</button>
    <button>닫기</button>
</div>

<p>아래공고에도 동시에 지원하시겠어요?</p>

<div style="border: 1px solid;display:none;" id="moreRec">
    <p>이공고는 어때요?</p>
    <?php
    foreach ($data['randomInfo'] as $key => $val) :
    ?>
        <ul>
            <?php if ($data['scrap'][$key] == 0) : ?>
                <li id="favorite<?= $key ?>" style="border:1px solid;" onclick="insertScrap('<?= $data['randomInfo'][$key]['idx'] ?>', '<?= $key ?>')">즐겨찾기X</li>
            <?php else : ?>
                <li id="favorite<?= $key ?>" style="border:1px solid;background-color:#ddd" onclick="deleteScrap('<?= $data['randomInfo'][$key]['idx'] ?>', '<?= $key ?>')">즐겨찾기O</li>
            <?php endif; ?>

            <li><input type="checkbox" name="recruitCheck" value="<?= $data['randomInfo'][$key]['idx'] ?>" onchange="recruitCheck()"><?= $key ?></li>
            <li>(공고 idx) <?= $data['randomInfo'][$key]['idx'] ?></li>
            <li>(기업명) <?= $data['randomInfo'][$key]['com_name'] ?></li>
            <li>(공고제목) <?= $data['randomInfo'][$key]['rec_title'] ?></li>
            <li>(지역) <?= $data['randomInfo'][$key]['area_depth_text_1'] ?> | <?= $data['randomInfo'][$key]['area_depth_text_2'] ?></li>
            <li>(지원방식 내인터뷰:M, 기업인터뷰:C, 둘다가능:A) <?= $data['randomInfo'][$key]['rec_apply'] ?></li>
            <li>(마감일) <?= $data['randomInfo'][$key]['rec_end_date'] ?></li>
            <li>(경력:C,신입:N,경력무관:A) <?= $data['randomInfo'][$key]['rec_career'] ?></li>
            <li>(썸네일) <?= $data['randomInfo'][$key]['file_save_name'] ?></li>
        </ul>
    <?php
    endforeach
    ?>
</div>

<br><br>

<div id='result'></div>

<br><br>

<form action="/jobs/applyAtOnce" method="post" id="once">
    <?= csrf_field() ?>
    <input type="text" id="state" name="state" value="M">
    <input type="text" id="recIdx" name="recIdx">
    <br>
    <div style="border:1px solid;cursor:pointer;" onclick="atOnce()"><span id='ckcnt'>0</span>개 공고 한꺼번에 지원하기</div>
</form>

<br><br><br><br>

<script>
    let checkCnt = 0;
    const state = '<?= $data['aData']['get']['state'] ?>';
    const memIdx = '<?= $data['session']['idx'] ?>';

    $(document).ready(function() {
        if (state == "M") {
            $('#moreRec').show();
        }
    });

    function recruitCheck() {
        const query = 'input[name="recruitCheck"]:checked';
        const selectedEls = document.querySelectorAll(query);

        let result = '';
        selectedEls.forEach((el) => {
            result += el.value + ' ';
        });

        checkCnt = $('input:checkbox[name="recruitCheck"]:checked').length;

        document.getElementById('ckcnt').innerText = checkCnt;
        document.getElementById('result').innerText = result;
    }

    function atOnce() {
        let recidx = document.getElementById('result').innerText;

        if (checkCnt == 0) {
            alert('공고를 1개이상 선택해주세요');
        } else {
            $('#recIdx').val(recidx);
            document.getElementById('once').submit();
        }

    }

    function insertScrap(recIdx, key) {
        $.ajax({
            type: "GET",
            url: "/api/my/scrap/add/recruit/" + memIdx + "/" + recIdx,
            data: {
                'csrf_highbuff': $('input[name="csrf_highbuff"]').val(),
            },
            success: function(data) {
                if (data.status == 200) {
                    $('input[name="csrf_highbuff"]').val(data.code.token);
                    $('#favorite' + key).attr('onclick', 'deleteScrap(' + recIdx + ', ' + key + ')');
                    $('#favorite' + key).css('background-color', '#ddd');
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

    function deleteScrap(recIdx, key) {
        $.ajax({
            type: "GET",
            url: "/api/my/scrap/delete/recruit/" + memIdx + "/" + recIdx,
            data: {
                'csrf_highbuff': $('input[name="csrf_highbuff"]').val(),
            },
            success: function(data) {
                if (data.status == 200) {
                    $('input[name="csrf_highbuff"]').val(data.code.token);
                    $('#favorite' + key).attr('onclick', 'insertScrap(' + recIdx + ', ' + key + ')');
                    $('#favorite' + key).css('background-color', '#fff');
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
</script>