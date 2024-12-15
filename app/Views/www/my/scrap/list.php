<h2>마이 스크랩</h2>

<div>
    <ul>
        <li><a href="/my/scrap/recruit">담은공고 <?= $data['count']['R'] ?>건</a></li>
        <li><a href="/my/scrap/company">담은기업 <?= $data['count']['C'] ?>개</a></li>
    </ul>
</div>

<div>
    <p>마음에 드는 공고를 담아 한꺼번에 지원하세요!</p>
</div>

<?php
if ($data['aData']['type'] == 'company') :
?>
    <input type="radio" name="radio_interview" value="recruit" id="radio_recruit" <?= $data['get']['recruit'] == 'Y' ? 'checked' : '' ?>>
    <label for="radio_recruit">지금 채용중</label>
    <input type="radio" name="radio_interview" value="practice" id="radio_practice" <?= $data['get']['practice'] == 'Y' ? 'checked' : '' ?>>
    <label for="radio_practice">모의인터뷰 응시 가능</label>
<?php
endif;
?>
<div>
    <div class="section">
        <?php
        if ($data['aData']['type'] == 'recruit') :
        ?>
            <form method="post" action="/jobs/detailAction" id="scrapForm">
                <?= csrf_field() ?>
                <input type="hidden" name="postCase" value="scrap_write">
                <input type="hidden" name="backUrl" value="/my/scrap/<?= $data['aData']['type'] ?>">
                <ul>
                    <?php
                    foreach ($data['list'] as $row) :
                    ?>
                        <li id="list_<?= $row['recIdx'] ?>">
                            <?php
                            if (in_array($row['recApply'], ['M', 'A'])) :
                            ?>
                                <input type="checkbox" name="recIdx[]" class="check-scrap-idx" value="<?= $row['recIdx'] ?>">
                            <?php
                            endif;
                            ?>
                            <p><?= $row['comName'] ?></p>
                            <p><?= $row['areaDepth1'] . '.' . $row['areaDepth2'] ?> | <?= $row['recCareer'] ?> <?= $row['recEndDate'] ?></p>
                            <button type="button" data-idx="<?= $row['recIdx'] ?>" data-type="recruit" class="btn-scrap-delete">별</button>
                            <img style="width:50px;height:auto" src="<?= $row['fileComLogo'] ?>" />
                            <a href="#">지원하기</a>
                        </li>
                    <?php
                    endforeach;
                    if (count($data['list']) == 0) :
                    ?>
                        <li>
                            <p>아직 담은 공고가 없어요. 마음에 드는 공고를 카트에 담아주세요 !</p>
                            <p><a href="#">추천 공고 탐색하러 가기 ></a></p>
                        </li>
                    <?php
                    endif;
                    ?>

                </ul>
                <div>
                    <?= $data['pager']->links('scrap', 'front_full') ?>
                </div>
                <input type="submit" id="btnScrapSubmit" value="0건 한꺼번에 지원하기">
            </form>
        <?php
        elseif ($data['aData']['type'] == 'company') :
        ?>
            <ul>
                <?php
                foreach ($data['list'] as $row) :
                ?>
                    <li id="list_<?= $row['comIdx'] ?>">
                        <p><?= $row['comName'] ?></p>
                        <p><?= $row['comIndustry'] ?></p>
                        <p><?= $row['comAddress'] ?></p>
                        <p><button type="button" data-idx="<?= $row['comIdx'] ?>" data-type="company" class="btn-scrap-delete">별</button></p>
                        <?php
                        if ($row['comPractice'] == 'Y') :
                        ?>
                            <p>
                                <a href="#">모의인터뷰하기</a>
                            </p>
                        <?php
                        endif;
                        ?>
                        <a href="#">지원하기</a>
                    </li>
                <?php
                endforeach;
                if (count($data['list']) == 0) :
                ?>
                    <li>
                        <p>아직 담은 기업이 없어요. 마음에 드는 기업을 카트에 담아주세요 !</p>
                        <p><a href="#">추천 기업 탐색하러 가기 ></a></p>
                    </li>
                <?php
                endif;
                ?>
            </ul>
        <?php
        endif;
        ?>
        <div>
            <?= $data['pager']->links('scrap', 'front_full') ?>
        </div>
    </div>
</div>

<script>
    function deleteAjax(memIdx, idx, scrapType) {
        const emlCsrf = $("input[name='csrf_highbuff']");
        $.ajax({
            url: `/api/my/scrap/delete/${scrapType}/${memIdx}/${idx}`,
            type: 'post',
            dataType: "json",
            cache: false,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            data: {
                '<?= csrf_token() ?>': emlCsrf.val()
            },
            success: function(res) {
                if (res.code.stat || res.code.stat == 'success') {
                    emlCsrf.val(res.code.token);
                    $(`#list_${idx}`).remove();
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }

    function getCheck() {
        let getCheck = $(".check-scrap-idx:checked").length;
        if (!getCheck) {
            getCheck = 0;
        }
        return getCheck;
    }

    $(".check-scrap-idx").on("change", function() {
        $("#btnScrapSubmit").val(getCheck() + "건 한꺼번에 지원하기");
    })

    $(".btn-scrap-delete").on("click", function() {
        const scrapType = $(this).data('type');
        const idx = $(this).data('idx');
        const memIdx = '<?= $data['session']['idx'] ?>';
        if (!idx || !memIdx) {
            return false;
        }
        if (scrapType == 'recruit' || scrapType == 'company') {
            deleteAjax(memIdx, idx, scrapType);
            return true;
        }
        return false;
    })

    $("#scrapForm").submit(function() {
        if (getCheck() == 0) {
            alert("공고를 선택해주세요.");
            return false;
        }
    });

    $("input[name=radio_interview]").on("change", function() {
        const strInterview = $('input[name="radio_interview"]:checked').val();
        location.href = `/my/scrap/company?${strInterview}=Y`;
    })
</script>