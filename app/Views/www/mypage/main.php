<?php
// // print_r($data);
// echo '<pre>';
// print_r(count($data['category']));
// echo '<br>';

?>

<h2>
    마이페이지 메인 테스트
</h2>
<div>
    <a href="/mypage/modify">
        <div style="height: 300px">
            <img src="https://interview.highbuff.com/share/img/profile/noprofile.png" alt="" style="height: 100%;">
        </div>
        <div>
            <div><?php echo $data['Member']['mem_name'] ?> 님</div>
            <div>내 정보 수정하기 ></div>
        </div>
    </a>
</div>
<hr>
<div>
    <div>
        <?php for ($i = 0; $i < count($data['category']); $i++) {
            if ($i == (count($data['category']) - 1)) {
                echo $data['category'][$i]['job_depth_text'];
            } else {
                echo $data['category'][$i]['job_depth_text'] . " / ";
            }
        } ?>
    </div>
    <div>
        <?php for ($i = 0; $i < count($data['kor']); $i++) {
            if ($i == (count($data['kor']) - 1)) {
                echo $data['kor'][$i]['area_depth_text_1']." ".$data['kor'][$i]['area_depth_text_2'];
            } else {
                echo $data['kor'][$i]['area_depth_text_1']." ".$data['kor'][$i]['area_depth_text_2'] . " / ";
            }
        } ?>
    </div>
</div>
<hr>
<div>
    <div>내 이력서</div> <!-- 이력서 작성 페이지로 이동-->
    <div>내 레포트</div> <!-- ai 레포트 페이지로 이동-->
    <div>지원 현황</div> <!-- 지원현황 페이지로 이동-->
    <div>받은 제안</div> <!-- 받은 제안 페이지로 이동-->
</div>
<hr>
<div>AI 레포트를 공개하고, 기업 제의를 받아보세요 ></div> <!-- ai 레포트가 있을 시 목록 페이지로 이동-->
<hr>
<div>
    <div>최근 본 공고</div>
    <div>즐겨찾는 공고</div>
    <div>즐겨찾는 기업</div>
</div>
<hr>
<div>
    <div>공지사항</div> <!-- 공지사항 페이지로 이동-->
    <div>이벤트</div> <!-- 이벤트 페이지로 이동-->
    <div>취업증명서 발급</div> <!-- 취업증명서 발급 페이지로 이동-->
    <div>이용가이드</div> <!-- 이용가이드 페이지로 이동-->
    <div>고객센터</div> <!-- 고객센터 페이지로 이동-->
    <div>환경설정</div> <!-- 환경설정 페이지로 이동-->
</div>