<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.ok').on('click', function() {
                $('#suggest').toggle();
                $('#cancel').hide();
            });
            $('.no').on('click', function() {
                $('#cancel').toggle();
                $('#suggest').hide();
            });

            $('.interviewSubmit').on('click', function() {
                $('#frm1').submit();
            });

            $("form").on("submit", function(event) {
                event.preventDefault();
                if ($(this).attr('id') == 'frm2') {

                    if (!$('select[name="refuseType"]').val()) {
                        return alert('거절 사유는 필수입니다.');
                    }
                }
                if (!confirm('승낙 하시겠습니까?')) {
                    return;
                }
                this.submit();
            });
        });
    </script>
    <style>
        .test>div {
            border: 1px solid black;
            padding: 0.25rem;
            margin: 0.25rem;
        }

        label {
            border: 1px solid #ddd;
        }

        .on {
            background: #f2f2f2;
        }

        body>div>div {
            padding: 0.5rem;
            border: 1px solid black;
        }

        body>div>div>div {
            padding: 0.5rem;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div><a href='/my/suggest'>뒤로가기</a></div>

    <div><?= $data['suggest']['sug_type'] ?> 제안</div>

    <div>

        <div>
            <div><?= $data['suggest']['com_name'] ?></div>
            <div><?= $data['suggest']['rec_title'] ?></div>
            <div><?= $data['suggest']['sug_type'] ?>제안이 도착했습니다.</div>
            <a href='/company/detail/<?= $data['suggest']['comIdx'] ?>'><button type='button'>기업정보 보기</button></a>
            <?= $data['suggest']['sug_type'] === '인터뷰' ?  "<button type='button'>인터뷰 시작하기</button>" : '' ?>
        </div>

        <div>
            <div>기업에서 보낸 메시지</div>
            <?= $data['suggest']['sug_massage'] ?>
        </div>

        <div>
            <div>인사담당자 연락처</div>
            <?= $data['suggest']['sug_manager_name'] ?>
            <?= $data['suggest']['sug_manager_tel'] ?>
        </div>

        <a href='#'>이 기업 차단하기</a>
        <div>
            <button class='no' type='button'>거절하기</button>
            <button class='<?= $data['suggest']['sug_type'] != '인터뷰' ? 'ok' : 'interviewSubmit'  ?>' type='button'>
                <span><?= $data['suggest']['sug_type']?>제안 승낙</span>
                <span><?= $data['suggest']['rec_end_date'] ?>까지</span>
            </button>
        </div>

        <div id='suggest' style='display:none'>
            <form id="frm1" method="POST" action="/my/suggest/accept<?= $data['suggest']['sug_type'] === '인터뷰' ? 'Interview' : '' ?>/<?= $data['suggestIdx'] ?>">
                <?= csrf_field() ?>
                <div>제안승낙하기</div>
                <textarea name='massage'></textarea>
                <div>
                    <button class='ok' type='button'>취소</button>
                    <button type='submit'>제출</button>
                </div>
            </form>
        </div>

        <div id='cancel' style='display:none'>
            <form id="frm2" method="POST" action="/my/suggest/refuse/<?= $data['suggestIdx'] ?>">
                <?= csrf_field() ?>
                <div>제안 거절하기</div>
                <select name='refuseType'>
                    <option value=''>거절사유 선택</option>
                    <option value='A'>경력 불일치</option>
                    <option value='B'>희망 직무 불일치</option>
                    <option value='C'>희망 업종 불일치</option>
                    <option value='D'>희망근무조건 불일치</option>
                    <option value='Z'>기타</option>
                </select>
                <textarea name='massage'></textarea>
                <div>
                    <button class='no' type='button'>취소</button>
                    <button type='submit'>제출</button>
                </div>
            </form>
        </div>

    </div>
</body>

</html>