<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>레포트</title>
    <script>
        $(document).ready(function() {
            $('.toggle').on('click', function() {
                if ($(this).val() === 'S') {
                    $('.gunPoint').hide();
                    $('.Point').show();
                } else if ($(this).val() === 'T') {
                    $('.Point').hide();
                    $('.gunPoint').show();
                }
            });
            $('.itvBtn').on('click', function() {
                $('.itvTypes').hide();
                $('.itvPoint').hide();
                let thisNum = $(this).val();
                $('#type' + thisNum).show();
                $('#point' + thisNum).show();
            });
            $('#profileBtn').on('click', function() {
                $('#profile').toggle();
            });
        });
    </script>

    <style>
        .gunPoint>div {
            border: 1px solid black;
            padding: 0.25rem;
        }

        .itvTypes>div {
            border: 1px solid black;
            padding: 0.25rem;
        }
    </style>
</head>

<body>
    <a href='/report'>뒤로가기</a>
    <div>AI 레포트 분석중</div>
    <div>file_idx_thumb <button id='profileBtn' type='button'>수정</button></div>
    <div id='profile' style='display:none'>
        <div>
            <button type='button'>지금 촬영하기</button>
        </div>
        <div>
            <input type='file' accept='.image/.jpeg,.png,.jpg'>앨범에서 선택
        </div>
        <div>
            <button type='button'>기존 프로필에서 선택</button>
        </div>
    </div>
    <div><?= $data['session']['name'] ?> 님의</div>
    <div><?= $data['job'] ?> 인터뷰 점수</div>

    <div>
        <button class='toggle' type='button' value='T'>총점</button>
        <button class='toggle' type='button' value='S'>답변별 점수</button>
    </div>

    <div class='gunPoint'>
        <div>종합점수</div>

        <div>
            레포트에 대해 문의사항이 있으신가요?
        </div>
    </div>
    <div class='Point' style='display:none'>
        <?php for ($i = 0; $i < count($data['S']); $i++) : ?>
            <button type='button' class='itvBtn' value='<?= $i ?>'><?= $i ?></button>
        <?php endfor; ?>

        <?php for ($i = 0; $i < count($data['S']); $i++) : ?>
            <div id='type<?= $i ?>' class='itvTypes' style='display:none'>
                <div>동영상</div>
                <div>
                    <div><?= $i ?> 번째 질문에 대한 답변이에요</div>
                    <div>que_idx</div>
                    <div>repo_speech_txt</div>
                </div>

                <div>
                    레포트에 대해 문의사항이 있으신가요?
                </div>
            </div>
        <?php endfor; ?>
    </div>
</body>

</html>