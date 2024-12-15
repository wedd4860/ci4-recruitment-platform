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
            $('#open').on('click', function() {
                $('.no').toggle();
            });

            $('#jobs').on('click', function() {
                $('.jobCategory').toggle();
            });

            $('#company').on('click', function() {
                $('.company').toggle();
            });

            $('#reportSelect').on('click', function() {
                $('.reportList').toggle();
            });

            $('input[type="radio"]').on('change', function() {
                if ($(this).val() === 'off') {
                    $('input[name="shareCompany"]').prop('checked', false);
                } else if ($(this).attr('name') === 'shareCompany') {
                    $('#shareOn').prop('checked', true);
                }
            });

            $("form").on("submit", function(event) {
                if (!$('input[name="updateIdx"]').val()) {
                    event.preventDefault();
                    return alert('인터뷰(레포트) 선택은 필수 입니다.');
                }

                this.submit();
            });

            $('#search').on('click', function() {
                let keyword = $('#keyword').val();
                let csrf = $('input[name="csrf_highbuff"]');
                $.ajax({
                    type: 'POST',
                    url: '/api/Auth/TelController/create',
                    data: {
                        'keyword': keyword,
                        'csrf_highbuff': csrf.val(),
                    },
                    success: function(data) {
                        alert(data['messages']);
                        csrf.val(data['code']['token']);
                    },
                    error: function(e) {
                        alert(e['responseJSON']['messages']);
                        return;
                    },
                    timeout: 5000
                }); //ajax
            });
        });
    </script>
    <style>
        .share>div {
            border: 1px solid black;
        }

        .share>div>div {
            padding: 0.25rem;
        }

        .share>div>div:nth-child(1) {
            background: #ddd;
        }

        .no {
            display: none;
        }
    </style>
</head>

<body>
    <a href='/report'>뒤로가기</a>
    <form id="frm" method="POST" action="/report/share/action">
        <?= csrf_field() ?>
        <div class='share'>
            <div>
                <div>인터뷰(레포트)선택 *필수 <?= ($data['report'] ?? false) ? '<button type="button" id="reportSelect"> 변경</button>' : '' ?></div>
                <?php if ($data['report'] ?? false) : ?>
                    <div><?= $data['report']['repo_analysis']['grade'] ?></div>
                    <div><?= $data['report']['job_depth_text'] ?></div>
                    <div>질문<?= $data['queCount'] ?></div>
                    <div><?= $data['report']['app_reg_date'] ?></div>
                    <input type='hidden' name='updateIdx' value='<?= $data['report']['idx'] ?>'>
                    <a href='detail/<?= $data['report']['idx'] ?>'>ai레포트 상세보기</a>
                <?php else : ?>
                    <div><button id='reportSelect' type='button'>레포트 선택하기</button></div>
                <?php endif; ?>
                <div class='reportList' style='display:none'>
                    <ul>
                        <?php foreach ($data['reportList'] as $val) : ?>
                            <?php if (($val['app_iv_stat'] ?? false) === '4') : ?>
                                <?php if (($data['report']['idx'] ?? false) === $val['idx']) : ?>
                                    <li>
                                        <a href='?report=<?= $val['idx'] ?>'><?= $val['job_depth_text'] ?> </a> 현재 레포트
                                    </li>
                                <?php else : ?>
                                    <li>
                                        <a href='?report=<?= $val['idx'] ?>'><?= $val['job_depth_text'] ?></a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div>
                <div>이력서 첨부 *선택 <button type='button'>변경</button></div>
                <div><button type='button'>새로 작성하기</button></div>
            </div>
            <div>
                <div>첨부파일 업로드 *선택</div>
                <div><input type='file' accept='.PDF,.HWP'></div>
            </div>
            <div>
                <div>공개설정</div>
                <div>
                    <label>
                        <input type='radio' name='shareCompany' value='all' <?= (($data['report']['app_share_company'] ?? false) === '2') ? 'checked' : '' ?>>기업에게 바로 제안받기
                    </label>
                </div>
                <div>
                    <label>
                        <input type='radio' name='shareCompany' value='half' <?= (($data['report']['app_share_company'] ?? false) === '1') ? 'checked' : '' ?>>제안 먼저 확인하기
                    </label>
                </div>
                <div>
                    <label>
                        <input id='shareOn' type='radio' name='share' value='on' <?= (($data['report']['app_share'] ?? false) === '1') ? 'checked' : '' ?>>내 레포트 자랑하기
                    </label>
                </div>
                <div>
                    <label>
                        <input id='shareOff' type='radio' name='share' value='off' <?= (($data['report']['app_share'] ?? false) === '0') ? 'checked' : '' ?>>비공개 하기
                    </label>
                </div>
                <button id='open' type='button'>상세 공개조건 설정하기</button>
                <div class='no'>
                    <button id='jobs' type='button'>열람제한 업종 등록</button>
                    <button id='company' type='button'>열람제한 기업 등록</button>
                </div>
                <div class='jobCategory' style='display:none'>
                    <?php foreach ($data['jobs'] as $val) : ?>
                        <?php if ($val['job_depth_2'] == null) : ?>
                            <div><?= $val['job_depth_text'] ?></div>
                        <?php else : ?>
                            <div>
                                <label>
                                    <input type='checkbox' value='<?= $val['idx'] ?>'>
                                    <?= $val['job_depth_text'] ?>
                                </label>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class='company' style='display:none'>
                    <div><input id='keyword' placeholder='열람제한 기업을 검색해 주세요.'><button id='search' type='button'>검색</button></div>
                    <div>검색결과<span>??건</span></div>
                    <ul id='companyList'>

                    </ul>
                </div>
            </div>
        </div>
        <button type='submit'>저장하기</button>
    </form>
</body>

</html>