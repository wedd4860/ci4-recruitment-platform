<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>레포트</title>
    <script>
        let aScoreT = [];
        let aScoreS = [];
        let aTitle = {
            'dialect': "방언빈도",
            'quiver': "음성떨림",
            'volume': "음성크기",
            'tone': "목소리톤",
            'speed': "음성속도",
            'diction': "발음 정확도",
            'sincerity': "성실 답변률",
            'understanding': "질문이해도",
            'eyes': "시선처리",
            'smile': "긍정적표정",
            'mouth_motion': "입움직임",
            'blink': "눈 깜빡임",
            'gesture': "제스처빈도",
            'head_motion': "머리 움직임",
            'foreign': "외국어 사용빈도",
            'glow': "홍조현상",
        };

        let aBestText = {
            'dialect': "방언 사용률이 낮습니다.",
            'quiver': "최소한의 떨린 목소리로 인터뷰를 안정되게 진행하였습니다.",
            'volume': "적절한 성량으로 인터뷰를 안정적으로 진행하였습니다.",
            'tone': "일정한 목소리 톤으로 안정적이게 답변하였습니다.",
            'speed': "적절한 속도로 답변하여 인터뷰를 안정적으로 진행하였습니다.",
            'diction': "답변을 알아듣기 쉽게 정확한 발음으로 인터뷰를 진행하였습니다.",
            'sincerity': "답변시간을 충분히 활용하여 인터뷰를 진행하였습니다.",
            'understanding': "어려운 질문에도 머뭇거림 없이 즉각적인 답변을 하였습니다.",
            'eyes': "인터뷰를 진행하는 동안 최소한의 시선 흔들림을 보였습니다.",
            'smile': "밝은 표정으로 인터뷰를 진행하였습니다.",
            'mouth_motion': "입을 크게 움직이며 적극적으로 인터뷰를 진행하였습니다.",
            'blink': "최소한의 눈깜빡임으로 안정적이게 인터뷰를 진행하였습니다.",
            'gesture': "몸을 활발히 사용하여 질문에 적극적으로 답변하였습니다.",
            'head_motion': "최소한으로 머리를 움직이며 안정되게 인터뷰를 진행하였습니다.",
            'foreign': "외국어를 많이 사용하지 않습니다.",
            'glow': "얼굴색의 변화 없이 안정적으로 인터뷰를 진행하였습니다.",
        };

        let aWorstText = {
            'dialect': "방언 사용률이 높습니다.",
            'quiver': "떨리는 목소리로 인터뷰를 진행하여 긴장과 불안정함을 보였습니다.",
            'volume': "너무 크거나 작은 성량으로 인터뷰를 불안정하게 진행하였습니다.",
            'tone': "일정하지 않은 목소리 톤으로 불안정하게 답변하였습니다. ",
            'speed': "너무 빠르거나 느리게 답변하여 인터뷰를 불안정하게 진행하였습니다. ",
            'diction': "답변을 알아듣기 힘들 정도로 발음이 부정확합니다.",
            'sincerity': "답변시간을 충분히 활용하지 못한 채 인터뷰를 진행하였습니다. ",
            'understanding': "답변을 즉시 하지 못하거나 시작하기까지 시간이 소요되었습니다.",
            'eyes': "인터뷰를 진행하는 동안 시선이 불안정하게 흔들렸습니다.",
            'smile': "다소 어두운 표정으로 인터뷰를 진행하였습니다.",
            'mouth_motion': "입을 거의 움직이지 않으며 다소 소극적으로 인터뷰를 진행하였습니다.",
            'blink': "잦은 눈 깜빡임으로 불안정하게 인터뷰를 진행하였습니다. ",
            'gesture': "몸을 거의 움직이지 않고 인터뷰를 진행하였습니다.",
            'head_motion': "머리를 자주 움직여 집중력이 분산되고 다소 불안정해 보입니다.",
            'foreign': "외국어를 너무 많이 사용하셨습니다",
            'glow': "붉은 얼굴색을 띄며 긴장감과 불안정함을 보였습니다.",
        };

        function sortMethod(aParam) {
            let aTemp = [];
            let sortable = [];
            for (let vehicle in aParam) {
                sortable.push([vehicle, aParam[vehicle]]);
            }

            sortable.sort(function(a, b) {
                return b[1] - a[1];
            });

            return sortable;
        }

        function bestAndWorst(aParam) {
            for (let i = 0; i < aParam.length; i++) {
                if (i < 3) {
                    aParam[i][1] = aBestText[aParam[i][0]];
                    aParam[i][0] = aTitle[aParam[i][0]];
                } else if (i >= aParam.length - 3) {
                    aParam[i][1] = aWorstText[aParam[i][0]];
                    aParam[i][0] = aTitle[aParam[i][0]];
                } else {
                    delete aParam[i];
                }
            }

            return aParam;
        }

        <?php foreach ($data['T']['temp'] as $key => $val) : ?>
            aScoreT['<?= $key ?>'] = <?= $val ?>;
        <?php endforeach; ?>

        let temp = [];
        <?php for ($i = 0; $i < count($data['S']); $i++) : ?>

            temp = [];

            <?php foreach ($data['S'][$i]['temp'] as $key => $val) : ?>
                temp['<?= $key ?>'] = <?= $val ?>;
            <?php endforeach; ?>

            aScoreS[<?= $i ?>] = temp;

        <?php endfor; ?>

        $(document).ready(function() {
            aScoreT = bestAndWorst(sortMethod(aScoreT));

            for (let i = 0; i < aScoreT.length; i++) {
                if (i < 3) {
                    $('#totalBest').append(`<li>${aScoreT[i][0]}: ${aScoreT[i][1]}</li>`);
                } else if (i >= aScoreT.length - 3) {
                    $('#totalWorst').append(`<li>${aScoreT[i][0]}: ${aScoreT[i][1]}</li>`);
                }
            }

            for (let i = 0; i < aScoreS.length; i++) {
                aScoreS[i] = bestAndWorst(sortMethod(aScoreS[i]));

                for (let j = 0; j < aScoreS[i].length; j++) {
                    if (j < 3) {
                        $('#best' + i).append(`<li>${aScoreS[i][j][0]}: ${aScoreS[i][j][1]}</li>`);
                    } else if (j >= aScoreS[i].length - 3) {
                        $('#worst' + i).append(`<li>${aScoreS[i][j][0]}: ${aScoreS[i][j][1]}</li>`);
                    }
                }
            }

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
    <div>AI 레포트</div>
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
    <div class='itvPoint'><?= $data['T']['analysis']['grade'] ?>등급 <?= $data['T']['analysis']['sum'] ?>점</div>
    <?php for ($i = 0; $i < count($data['S']); $i++) : ?>
        <div id='point<?= $i ?>' class='itvPoint' style='display:none'><?= $data['S'][$i]['analysis']['grade'] ?>등급 <?= $data['S'][$i]['analysis']['sum'] ?>점</div>
    <?php endfor; ?>

    <div>
        <button class='toggle' type='button' value='T'>총점</button>
        <button class='toggle' type='button' value='S'>답변별 점수</button>
    </div>

    <div class='gunPoint'>
        <div>종합점수</div>
        <div>
            <div>
                <span>적극성 <?= $data['T']['analysis']['aggressiveness'] ?></span>
                <span>안정성 <?= $data['T']['analysis']['stability'] ?></span>
                <span>신뢰성 <?= $data['T']['analysis']['reliability'] ?></span>
                <span>긍정성 <?= $data['T']['analysis']['affirmative'] ?></span>
            </div>
            <div>
                <span>대응성 <?= $data['T']['analysis']['alacrity'] ?></span>
                <span>의지력 <?= $data['T']['analysis']['willpower'] ?></span>
                <span>능동성 <?= $data['T']['analysis']['activity'] ?></span>
                <span>매력도 <?= $data['T']['analysis']['attraction'] ?></span>
            </div>
        </div>
        <div>
            전체 BEST3
            <ul id='totalBest'>

            </ul>
        </div>
        <div>
            전체 WORST 3
            <ul id='totalWorst'>

            </ul>
        </div>
        <div>
            전체 점수
            <?php foreach ($data['T']['reportScoreRank']['T'] as $val) : ?>
                <?php if ($val['score_rank_grade'] === $data['T']['analysis']['grade']) : ?>
                    <div>
                        <?= $val['score_rank_grade'] ?>랭크
                        사람 수 : <?= $val['score_rank_count_member'] ?> *내랭크*
                    </div>
                <?php else : ?>
                    <div>
                        <?= $val['score_rank_grade'] ?>랭크
                        사람 수 : <?= $val['score_rank_count_member'] ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div>
            <?= $data['job'] ?> 점수
            <?php foreach ($data['T']['reportScoreRank']['C'] as $val) : ?>
                <?php if ($val['score_rank_grade'] === $data['T']['analysis']['grade']) : ?>
                    <div>
                        <?= $val['score_rank_grade'] ?>랭크
                        사람 수 : <?= $val['score_rank_count_member'] ?> *내랭크*
                    </div>
                <?php else : ?>
                    <div>
                        <?= $val['score_rank_grade'] ?>랭크
                        사람 수 : <?= $val['score_rank_count_member'] ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div>성별 예측
            남자:<?= $data['T']['gender']['man'] ?>
            여자:<?= $data['T']['gender']['woman'] ?>
        </div>
        <div>나이 예측
            10대:<?= $data['T']['age']['age10'] ?>
            20대:<?= $data['T']['age']['age20'] ?>
            30대:<?= $data['T']['age']['age30'] ?>
            40대:<?= $data['T']['age']['age40'] ?>
            50대:<?= $data['T']['age']['age50'] ?>
        </div>
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
                    <div>
                        <span>적극성 <?= $data['S'][$i]['analysis']['aggressiveness'] ?></span>
                        <span>안정성 <?= $data['S'][$i]['analysis']['stability'] ?></span>
                        <span>신뢰성 <?= $data['S'][$i]['analysis']['reliability'] ?></span>
                        <span>긍정성 <?= $data['S'][$i]['analysis']['affirmative'] ?></span>
                    </div>
                    <div>
                        <span>대응성 <?= $data['S'][$i]['analysis']['alacrity'] ?></span>
                        <span>의지력 <?= $data['S'][$i]['analysis']['willpower'] ?></span>
                        <span>능동성 <?= $data['S'][$i]['analysis']['activity'] ?></span>
                        <span>매력도 <?= $data['S'][$i]['analysis']['attraction'] ?></span>
                    </div>
                </div>

                <div>
                    <?= $i ?> 번째 답변의BEST3
                    <ul id='best<?= $i ?>'>

                    </ul>
                </div>
                <div>
                    <?= $i ?> 번째 답변의WORST3
                    <ul id='worst<?= $i ?>'>

                    </ul>
                </div>
                <div>
                    레포트에 대해 문의사항이 있으신가요?
                </div>
            </div>
        <?php endfor; ?>
    </div>
</body>

</html>