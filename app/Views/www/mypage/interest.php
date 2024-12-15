<?php
// echo '<pre>';
// print_r($data);
// echo '<br>';
?>

<h2>내 관심사 저장하기</h2>

<div>
    <?php echo $data['member']['mem_name']; ?> 님에 대해 알려주세요!^-^
</div>
<form id="frm" method="POST" action="/mypage/modify/action">
    <?= csrf_field() ?>
    <hr>
    <div>
        <div>
            어떤 포지션에서 일하고 싶나요?
        </div>
        <div>
            <?php
            for ($i = 0; $i < count($data['job']); $i++) { ?>
                <div>
                    <input type="checkbox" name="Jobchkbox[]" value="<?php echo $data['job'][$i]['job_depth_text']; ?>">
                    <?php echo $data['job'][$i]['job_depth_text']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <hr>
    <div>
        <div>일하고 싶은 지역은 어디인가요?</div>
        <div>
            <?php
            for ($i = 0; $i < count($data['area']); $i++) { ?>
                <div>
                    <input type="checkbox" name="Areachkbox[]" value="<?php echo $data['area'][$i]['area_depth_text_1']; ?>">
                    <?php echo $data['area'][$i]['area_depth_text_1']; ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <hr>
    <div>
        <div>희망하는 연봉을 알려주세요!</div>
        <div>
            <div>최저~최고</div>
            <div>
                <input type="checkbox" name="million" id="millionchk" value="1억원이상"> 1억원이상
            </div>
            <div>
                <div>
                    <span id="income">2000</span> 만원
                </div>
                <input type="range" value="2000" min="2000" max="10000" id="inputIncome" name="range" onchange="changeRange()" oninput="changeRange()">

            </div>
        </div>
    </div>
    <hr>
<button type='submit' >저장</button>

</form>

<script src="http://code.jquery.com/jquery-latest.js"></script>

<script>
    var strPay;

    $(document).ready(function() {
        $("#millionchk").attr("checked",false);
    });
    $('#millionchk').click(function() {
        if ($("#millionchk").prop("checked")) {
            $("#inputIncome").attr('disabled', true);
            $('#income').text('2000');
            strPay = "2000만원";
        } else {
            $("#inputIncome").attr('disabled', false);
        }

    })


    function changeRange() {
        $("#income").text($('#inputIncome').val());
        strPay = $('#inputIncome').val() + "만원";
    }

    function sendInfo() {
        var aAddJob = [];
        var aAddArea = [];
        $('input[name=Jobchkbox]:checked').each(function() {
            var strJobchk = $(this).val();
            aAddJob.push(strJobchk);
        });

        $('input[name=Areachkbox]:checked').each(function() {
            var strAreachk = $(this).val();
            aAddArea.push(strAreachk);
        });

        console.log(aAddJob);
        console.log(aAddArea);
        console.log(strPay);
    }

</script>