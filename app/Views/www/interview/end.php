<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <div>
            수고하셨습니다! <br>
            <?= $data['session']['name'] ?> 님의 <br>
            AI 인터뷰가 완료되었어요.
        </div>
        <hr>
        <div>
            <img src="" alt=""> 축하이미지
        </div>
        <hr>
        <div>
            잠시만 기다려 주시면 <br>
            AI가 열심히 인터뷰를 분석해 <br>
            점수를 알려드릴게요 ! <br>
            (최대 30분 소요)
        </div>
        <hr>
        <div>
            <div id="now">
                바로 지원할래요 <br>
                지원 페이지로 이동하기
            </div>
            <hr>
            <div id="check">
                결과 확인 후 지원할래요 <br>
                기다리는 동안 공고 둘러보기
            </div>
            <hr>
            <div id="report">
                어떻게 나왔을지 궁금해요! <br>
                방금 찍은 인터뷰 확인하러 가기
            </div>
            <hr>
            <div id="main">
                기다리는 동안 둘러볼래요 <br>
                메인으로 돌아가기
            </div>
        </div>
    </div>
</body>

</html>

<script>
    function buttonSet() {
        if ("<?= $data['endInterview']['app_type'] ?>" == "M") {
            $('#now').show();
            $('#check').show();
            $('#main').hide();
        } else {
            $('#now').hide();
            $('#check').hide();
            $('#main').show();

        }
    }
    buttonSet();

    $('#main').on("click", function() {
        location.href = "/";
    });
</script>