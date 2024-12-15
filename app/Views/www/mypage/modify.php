<?php
// print_r($data);
isset($data['post']) ? $data['post'] : $data['post']="";

var_dump(cache('postData'));

echo '<pre>';
print_r($data);
echo '<br>';


// exit;
?>

<h2>
    내 정보 수정하기
</h2>
<div>
    <div>수정</div>
    <div style="height: 300px">
        <img src="https://interview.highbuff.com/share/img/profile/noprofile.png" alt="" style="height: 100%;">
    </div>
    <div>이름</div>
</div>
<hr>
<div>
    <div style="display: flex;">
        <div style="margin-right: 15px;">이메일</div>
        <input type="text" value="<?php echo $data['member']['mem_id'] ?>">
    </div>
    <div style="display: flex;">
        <div style="margin-right: 15px;">연락처</div>
        <input type="tel" value="<?php echo $data['member']['mem_tel'] ?>">
    </div>
    <div style="display: flex;">
        <div style="margin-right: 15px;">출생년도</div>
        <input type="text" value="<?php echo $data['member']['mem_age'] ?>">
    </div>
</div>
<hr>
<div id="wantCate">
    <div>
        <?php
        if ($data['wantCate'] == 0) { ?>
            <a href="/mypage/modify/interest">
                <div>
                    어떤 포지션에서 일하고 싶나요?<br>내 관심사 입력하러 가기
                </div>
            </a>
        <?php } else { ?>

            <div>
                <div style="display: flex;">
                    <div style="margin-right: 15px;">관심 직무</div>
                    <div>
                        <?php
                        if ($data['post'] != null && $data['post'] != "") {
                            for ($j = 0; $j < count($data['post']['Jobchkbox']); $j++) {
                                if ($j == (count($data['post']['Jobchkbox']) - 1)) {
                                    echo $data['post']['Jobchkbox'][$j];
                                } else {
                                    echo $data['post']['Jobchkbox'][$j] . " / ";
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($data['category']); $i++) {
                                if ($i == (count($data['category']) - 1)) {
                                    echo $data['category'][$i]['job_depth_text'];
                                } else {
                                    echo $data['category'][$i]['job_depth_text'] . " / ";
                                }
                            }
                        } ?>
                    </div>
                </div>
                <div style="display: flex;">
                    <div style="margin-right: 15px;">관심 지역</div>
                    <div>
                        <?php
                        if ($data['post'] != null && $data['post'] != "") {
                            for ($j = 0; $j < count($data['post']['Areachkbox']); $j++) {
                                if ($j == (count($data['post']['Areachkbox']) - 1)) {
                                    echo $data['post']['Areachkbox'][$j];
                                } else {
                                    echo $data['post']['Areachkbox'][$j] . " / ";
                                }
                            }
                        } else {
                            for ($i = 0; $i < count($data['kor']); $i++) {
                                if ($i == (count($data['kor']) - 1)) {
                                    echo $data['kor'][$i]['area_depth_text_1'] . " " . $data['kor'][$i]['area_depth_text_2'];
                                } else {
                                    echo $data['kor'][$i]['area_depth_text_1'] . " " . $data['kor'][$i]['area_depth_text_2'] . " / ";
                                }
                            }
                        } ?>
                    </div>
                </div>
                <div style="display: flex;">
                    <div style="margin-right: 15px;">희망 연봉</div>
                    <div>
                        <?php
                        if($data['post'] != null && $data['post'] != ""){
                            if($data['post']['million']!=null && $data['post']['million']!=""){
                                echo $data['post']['million'];
                            } else {
                                echo $data['post']['range']."만원";
                            }
                        } else {
                            echo $data['member']['mem_pay'];
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div>
                <a href="/mypage/modify/interest">
                    수정하기
                </a>
            </div>
        <?php } ?>
    </div>
    <hr>
    <button>
        저장하기
    </button>

    <script src="http://code.jquery.com/jquery-latest.js"></script>

    <script>

    </script>