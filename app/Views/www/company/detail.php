<div>
    <h2>회사소개</h2>

    <div style="border: 1px solid;">
        <img src="<?= $data['info']['file_save_name'] ?>" alt="" style="width:100%;">
        <div>(기업 로고 / 이미지)</div>
        <p>(기업명) <?= $data['info']['com_name'] ?></p>

        <?php if (count($data['companyRec']) != 0) : ?>
            <div style="border:1px solid">현재채용중</div>
        <?php endif; ?>

        <br>

        <?php if ($data['info']['com_practice_interview'] == 'Y') : ?>
            <div style="border:1px solid">모의인터뷰O</div>
        <?php else : ?>
            <div style="border:1px solid">모의인터뷰X</div>
        <?php endif; ?>
    </div>

    <br>

    <div style="border:1px solid;">
        <p>(기업태그)</p>
        <ul>
            <?php
            foreach ($data['companyTag'] as $val) :
            ?>
                <li><?= $val['tag_txt'] ?></li>
            <?php
            endforeach
            ?>
        </ul>
    </div>

    <br>

    <?php if (count($data['companyRec']) >= 2) : ?>
        <div style="border:1px solid;background-color: #f4f4f4;">AI 인터뷰로 적극 채용중</div>
    <?php endif; ?>

    <br>

    <div style="padding:10px;border:1px solid;">
        <div>(기업관련이미지)</div>
        <?php
        foreach ($data['companyFile'] as $val) :
        ?>
            <span><img src="<?= $val['file_save_name'] ?>" style="width:100px;"></span>
        <?php
        endforeach
        ?>
    </div>

    <br>

    <div style="border:1px solid;">
        <div>(기업소개)</div>
        <?= $data['info']['com_introduce'] ?>
        <div>더보기</div>
    </div>

    <br>

    <div style="border: 1px solid;">
        <iframe width="424" height="238" src="<?= $data['info']['com_video_url'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        <div>
            유튜브 링크 첨부시 설명필요 (오른쪽 버튼 -> 소스코드복사 -> src="" 안에 링크 입력)
        </div>
    </div>

    <br>

    <?php if (count($data['companyRec']) != 0) : ?>
        <div style="border:1px solid;">
            <div>지금 동료 모집중</div>
            <ul>
                <?php
                foreach ($data['companyRec'] as $recKey => $recVal) :
                ?>
                    <li style="border:1px solid;">
                        <?php if (!$data['isLogin']) : ?>
                            <div id="favBeforeLogin" class="btn-scrap" style="border:1px solid;">즐겨찾기X</div>
                        <?php else : ?>
                            <?php if ($data['getRecScrap'][$recKey] == 0) : ?>
                                <div id="favorite<?= $recKey ?>" class="btn-scrap" data-scrap="add" data-state="recruit" data-idx="<?= $recVal['idx'] ?>" data-key="<?= $recKey ?>" style="border:1px solid;">즐겨찾기X</div>
                            <?php else : ?>
                                <div id="favorite<?= $recKey ?>" class="btn-scrap" data-scrap="delete" data-state="recruit" data-idx="<?= $recVal['idx'] ?>" data-key="<?= $recKey ?>" style="border:1px solid;background-color:#ddd">즐겨찾기O</div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div>(공고idx) <?= $recVal['idx'] ?></div>
                        <div>(공고제목) <?= $recVal['rec_title'] ?></div>
                        <div>(회사명) <?= $data['info']['com_name'] ?></div>

                        <?php
                        foreach ($data['recruitKor'][$recKey]['area'] as $areaKey => $areaVal) :
                        ?>
                            <div>(공고지역 <?= ($areaKey + 1) ?>) <?= $areaVal['area_depth_text_1'] ?></div>
                            <div style="margin-left:20px;">(공고지역 <?= ($areaKey + 1) ?>) <?= $areaVal['area_depth_text_2'] ?></div>
                        <?php
                        endforeach
                        ?>
                    </li>
                <?php
                endforeach
                ?>
            </ul>
        </div>
    <?php endif; ?>

    <br>

    <div style="border:1px solid;">
        <div>모의 인터뷰 응시가능(보류)</div>
        <ul>
            <li>1</li>
        </ul>
    </div>

    <br>

    <div style="border:1px solid">
        <div>이곳의 최신 소식을 알려드릴게요</div>
        <ul>
            <?php
            foreach ($data['companyNews'] as $key => $val) :
            ?>
                <a href="<?= $val['news_link'] ?>" target="_blank">
                    <li style="border:1px solid;margin:10px;">
                        <div>
                            (기사제목) <?= $val['news_title'] ?> <br>
                            (출처) <?= $val['news_referance'] ?>
                        </div>
                    </li>
                </a>
            <?php
            endforeach
            ?>
        </ul>
    </div>

    <br>

    <?php if ($data['isLogin'] == 0) : ?>
        <div style="border:1px solid">로그인안되어 있어서 로그인 후 이용해주세요 띄워야함</div>
    <?php else : ?>
        <div style="border:1px solid">로그인 되어 있어서 기업담기 가능</div>

        <?php if ($data['scrapComState'] == 0) : ?>
            <div style="border:1px solid">즐겨찾기 안되어 있음</div>
        <?php else : ?>
            <div style="border:1px solid">즐겨찾기</div>
        <?php endif; ?>
    <?php endif; ?>

    <br>

    <?php if ($data['scrapComState'] == 0) : ?>
        <div id="comScrap" class="btn-scrap" data-scrap="add" data-state="company" data-idx="<?= $data['comIdx'] ?>" style="border:1px solid;background-color:#ddd;">이 기업 담아놓기</div>
    <?php else : ?>
        <div id="comScrap" class="btn-scrap" data-scrap="delete" data-state="company" data-idx="<?= $data['comIdx'] ?>" style="border:1px solid;background-color:#ddd;">이미 담아놓은 기업입니다.</div>
    <?php endif; ?>

</div>

<br><br><br><br><br><br><br>

<script>
    const memIdx = '<?= $data['memIdx'] ?>';
    let scrapComState = '<?= $data['scrapComState'] ?>';
    
    $('#favBeforeLogin').on('click', function(){
        alert('로그인해주세요!');
        location.href = '/login';
    });

    $('.btn-scrap').on('click', function() {
        if (!'<?= $data['isLogin'] ?>') {
            alert('로그인해주세요!');
            return;
        } else {
            const emlThis = $(this);
            const strStrap = emlThis.data('scrap');
            const iState = emlThis.data('state');
            const iRecOrComIdx = emlThis.data('idx');
            const iKey = emlThis.data('key');

            scrap(iState, iRecOrComIdx, iKey, strStrap);

            emlThis.data('scrap', strStrap == 'add' ? 'delete' : 'add');
        }
    });

    function scrap(state, recOrComIdx, key, strStrap) {
        $.ajax({
            type: "GET",
            url: `/api/my/scrap/${strStrap}/${state}/${memIdx}/${recOrComIdx}`,
            data: {
                'csrf_highbuff': $('input[name="csrf_highbuff"]').val(),
            },
            success: function(data) {
                if (data.status == 200) {
                    $('input[name="csrf_highbuff"]').val(data.code.token);
                    if (strStrap == 'add') {
                        if (state == 'company') {
                            alert('기업이 담겼습니다!');
                            $('#comScrap').text('이미 담아놓은 기업입니다.');
                        } else if (state == 'recruit') {
                            alert('공고 즐겨찾기 등록');
                            let favorite = $('#favorite' + key);
                            favorite.css('background-color', '#ddd');
                            favorite.text('즐겨찾기O');
                        }
                    } else if (strStrap == 'delete') {
                        if (state == 'company') {
                            alert('담은 기업이 해제되었습니다.');
                            $('#comScrap').text('이 기업 담아놓기');
                        } else if (state == 'recruit') {
                            alert('공고 즐겨찾기 해제');
                            let favorite = $('#favorite' + key);
                            favorite.css('background-color', '#fff');
                            favorite.text('즐겨찾기X');
                        }
                    }
                } else {
                    alert(data.messages);
                }
            },
            error: function(e) {
                alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 '하이버프'로 문의 부탁드립니다.");
                return;
            },
        })
    }
</script>