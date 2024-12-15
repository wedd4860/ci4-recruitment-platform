<style>
    .swal2-actions {
        display: none;
    }
</style>

<h2><?= $data['job']['rec_title'] ?></h2>

<div style="border: 1px solid;">
    <img src="<?= $data['job']['file_save_name'] ?>" alt="">

    <p>AI로 적극채용중인지 표시</p>

    <?php if ($data['job']['rec_airecruit_yn'] == 'Y') : ?>
        <p>적극채용중!!!</p>
    <?php else : ?>
        <p>--적극채용중 아님--</p>
    <?php endif; ?>
</div>

<br>

<?php if ($data['alreadyApply']) : ?>
    <p><b>이미 이 공고에 지원하셨어요!</b></p>
    <div style="border:1px solid">확인하러가기</div>
<?php else : ?>
    <p><b>아직 한번도 지원안한 공고</b></p>
<?php endif; ?>
<br>

<p><b>(공고명) <?= $data['job']['rec_title'] ?></b></p>
<p>(기업명) <?= $data['job']['com_name'] ?> | (지역) <?= $data['job']['com_address'] ?></p>

<div style="border: 1px solid;font-weight:bold;">
    <div>
        (아이콘) (학력) = <?= $data['config']['recruit']['education'][$data['job']['rec_education']] ?>
    </div>

    <div>
        (아이콘) (경력:C,신입:N) =
        <?php if ($data['job']['rec_career'] == 'C') : ?>
            경력
        <?php else : ?>
            신입
        <?php endif;  ?>
    </div>

    <div>
        (아이콘) (고용형태) =
        <?php
        foreach ($data['workType'] as $key => $val) :
        ?>
            <span><?= $data['config']['recruit']['work_type'][$val] ?></span>
        <?php
        endforeach
        ?>
    </div>
</div>
<br>

<div style="border: 1px solid;">
    (해시태그)
    <?php
    foreach ($data['tag'] as $row) : ?>
        <a href="">#<?= $row ?></a>
    <?php
    endforeach;
    ?>
</div>

<br>

<div style="border: 1px solid;">

    <?php
    foreach ($data['categories'] as $key => $val) :
    ?>
        <span>[<?= $val ?>] </span>
    <?php
    endforeach
    ?>

    <br> 인터뷰로 지원할 수 있어요
</div>

<br>

<div style="border: 1px solid;">
    지원방법
    <?php if ($data['job']['rec_apply'] == 'M') : ?>
        <p>내인터뷰로 지원</p>
    <?php else : ?>
        <p>기업인터뷰로 지원 (질문 N개)</p>
    <?php endif; ?>
</div>

<br>

<div style="border: 1px solid;">
    <?= $data['job']['rec_info'] ?>
</div>

<p>접수기간</p>
<ul>
    <li>시작일 : <?= $data['job']['rec_start_date'] ?></li>
    <li>마김일 : <?= $data['job']['rec_end_date'] ?></li>
</ul>

<div style="border: 1px solid;">
    <p>위치</p>
    <p><?= $data['job']['com_address'] ?></p>
    <p>(지도)</p>
</div>

<div style="border: 1px solid;">
    <p>기업정보</p>
    <ul>
        <li>(기업사진)</li>
        <li><?= $data['job']['com_name'] ?></li>
        <li><a href="">자세히</a></li>
    </ul>
</div>

<div style="border: 1px solid;">
    <p>이공고는 어때요?</p>
    <?php
    foreach ($data['randomInfo'] as $row) :
    ?>
        <ul style="border:1px solid;">
            <li>(기업명) <?= $row['com_name'] ?></li>
            <li>(공고제목) <?= $row['rec_title'] ?></li>
            <li>(지역) <?= $row['area_depth_text_1'] ?> | <?= $row['area_depth_text_2'] ?></li>
            <li>(지원방식 내인터뷰:M, 기업인터뷰:C, 둘다가능:A) <?= $row['rec_apply'] ?></li>
            <li>(마감일) <?= $row['rec_end_date'] ?></li>
            <li>(경력:C,신입:N,경력무관:A) <?= $row['rec_career'] ?></li>
            <li>(썸네일) <?= $row['file_save_name'] ?></li>
        </ul>
    <?php
    endforeach
    ?>
</div>

<br>

<form action="/jobs/detailAction" method="post" id="apply">
    <?= csrf_field() ?>
    <input type="" id="recIdx" name="recIdx[]">
    <input type="" id="state" name="state">
    <input type="" id="backUrl" name="backUrl" value="/jobs/detail/<?= $data['aData']['idx'] ?>">
    <input type="" id="postCase" name="postCase" value="detail_view">

    <br>

    <button class='goApply' type="button" style="border: 1px solid;">지원하기</button>
    <button type="button" id="myInterview" style="display:none;">내인터뷰로 지원</button>
    <button type="button" id="companyItv" style="display:none;">기업 인터뷰로 지원</button>
</form>


<br><br><br><br>
<br><br><br><br>


<p>(주)블루바이저 UI/UX 기획자를 찾습니다.</p>

<div style="border:1px solid">기업인터뷰</div>
<br>
<div>
    <div><span>김OO</span>님께 묻고 싶은 <span>3</span>개의 질문들로 구성되어 있어요.</div>
    <div>편안한 마음으로 응시해 주세요 :)</div>
    <div>*응시 가능 횟수 : <span>1</span>회</div>
    <div>*인터뷰 완료 후, 바로 지원하지 않아도 괜찮아요!</div>
    <div>분석 결과는 AI레포트에서 확인하실 수 있습니다</div>
    <div>비공개 인터뷰</div>
    <div>해당 인터뷰는 공개 처리 및 타 공고 지원이 불가능해요</div>
</div>
<br>
<button>다음에 지원하기</button>
<button>지금 시작하기</button>

<script>
    const detailIdx = '<?= $data['aData']['idx'] ?>';
    const applyState = '<?= $data['job']['rec_apply'] ?>';
    const applierCategory = '<?= $data['applierCategory'] ?>';
    const category = '<?= json_encode($data['categories']) ?>';
    const companyName = '<?= $data['job']['com_name'] ?>';
    const recTitle = '<?= $data['job']['rec_title'] ?>';
    let interviewState = '';

    $('.goApply').on('click', function() {
        if(applierCategory == 'beforeLogin'){
            alert('로그인을 해주세요.');
        }else{
            if (applyState == 'M') {
                myInterviewApply();
            } else if (applyState == 'C') {
                companyInterview();
            } else {
                $('#myInterview').show();
                $('#companyItv').show();
            }
        } 
    });

    $("#myInterview").on('click', function() {
        myInterviewApply();
    })

    $("#companyItv").on('click', function() {
        companyInterview();
    })

    function myInterviewApply() {
        interviewState = 'M';
        Swal.fire({
            html: `
                <div id="noInterview" style="font-weight:bold;">인터뷰 생성 후 지원이 가능해요!<br>새 인터뷰를 시작하시겠어요?
                    <div>${category}</div>
                </div><br> 
                <div id="noSameInterview" style="font-weight:bold;">
                    앗, 보유하고 있는
                    <br>${category}인터뷰가 없어요.
                    <br>새인터뷰 시작 페이지로<br>이동하시겠어요?
                </div><br>
                <div>
                    <button onclick="" style="width:50%;height:40px;">네 좋아요(job category 값 들고 인터뷰 시작페이지로 이동)</button><br>
                    <button id="justApply" onclick="moveApplyPage()" style="width:50%;height:40px;display:none;">그냥 지원할게요</button><br>
                    <button onclick="swal.close()" style="width:50%;height:40px;">다음에 지원할게요</button>
                </div>
                `,
            focusConfirm: false,
            confirmButtonText: '',
            allowOutsideClick: false,
            closeClick: true,
            preConfirm: () => {
                return []
            }
        });

        if (applierCategory == 'none') {
            $('#noSameInterview').hide();
        } else if (applierCategory == 'not') {
            $('#noInterview').hide();
            $('#justApply').show();
        } else {
            swal.close();
            moveApplyPage();
        }
    }

    function moveApplyPage() {
        $('#state').val(interviewState);
        $('#recIdx').val(`${detailIdx}`);
        $('#apply').submit();
    }

    function companyInterview() {
        Swal.fire({
            html: `<div style="font-weight:bold;">(기업명) ${companyName}</div><br>
                   <div style="font-weight:bold;">(공고명) ${recTitle}</div><br>
                   <div style="border:1px solid">기업인터뷰</div>
                   <div style="border:1px solid">OOO님께 묻고싶은 N개의 질문들로 구성되어 있어요</div>
                   <div style="border:1px solid">응시 가능 횟수 : N회</div>
                   <button onclick="swal.close()" style="width:50%;height:40px;">다음에 지원할게요</button>
                   <button onclick="swal.close()" style="width:50%;height:40px;">지금 시작하기(인터뷰 페이지로 이동)</button>`,
            focusConfirm: false,
            confirmButtonText: '',
            allowOutsideClick: false,
            closeClick: true,
            preConfirm: () => {
                return []
            }
        });
    }
</script>