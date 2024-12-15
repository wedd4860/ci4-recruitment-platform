<h2>태그 모아보기</h2>

<div>
    <p>태그 3개 선택됨</p>
</div>

<div>
    <p>마음에 드는 공고를 담아 한꺼번에 지원하세요!</p>
</div>

<div>
    <div class="section">
        <form method="self">
            <ul>
                <?php
                foreach ($data['tag'] as $row) :
                ?>
                    <li>
                        <input type="checkbox" name="tagCheck[]" id="tagCheck_<?= $row['idx'] ?>" value="<?= $row['idx'] ?>" <?= isset($data['get']['tag']) && in_array($row['idx'], $data['get']['tag']) ? 'checked' : '' ?>>
                        <label for="tagCheck_<?= $row['idx'] ?>"><?= $row['tag_txt'] ?></label>
                    </li>
                <?
                endforeach;
                ?>
            </ul>
            <input type="submit" value="전송">
        </form>
        <?= csrf_field() ?>
        <ul>
            <?php
            foreach ($data['list'] as $row) :
            ?>
                <li>
                    <img src="<?= $row['fileSave'] ?>" style="width:100px">
                    <?= $row['recIdx'] ? '채용중' : '' ?> / <?= $row['comName'] ?> / <?= $row['comAddr'] ?>
                    <br>
                    <?= $row['configTagTxt'] ?><?= $row['tagCnt'] > 0 ? '+' . $row['tagCnt'] : '' ?>
                    <br>
                    <button type="button" id="btn-company-<?= $row['comIdx'] ?>" data-idx="<?= $row['comIdx'] ?>" data-stat="<?= isset($row['scrapMemIdx']) && $row['scrapMemIdx'] > 0 ? 'delete' : 'add' ?>" class="btn-scrap">별<?= isset($row['scrapMemIdx']) && $row['scrapMemIdx'] > 0 ? '(등록)' : '' ?></button></p>
                </li>
            <?
            endforeach;
            ?>
        </ul>
        <div>
            <?= $data['pager']->links('tagCompany', 'front_full') ?>
        </div>
    </div>
</div>

<script>
    function deleteAjax(memIdx, idx, scrapType, stat) {
        const emlCsrf = $("input[name='csrf_highbuff']");
        $.ajax({
            url: `/api/my/scrap/${stat}/${scrapType}/${memIdx}/${idx}`,
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
                    if (stat == 'add') {
                        $(`.btn-company-${idx}`).html("별 (등록)")
                    } else {
                        $(`.btn-company-${idx}`).html("별")
                    }
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {}
        });
    }

    function getCheck() {
        let getCheck = $(".check-scrap-idx:checked").length;
        if (!getCheck) {
            getCheck = 0;
        }
        return getCheck;
    }

    $(".btn-scrap").on("click", function() {
        const scrapType = 'company';
        const idx = $(this).data('idx');
        const stat = $(this).data('stat');
        const memIdx = '<?= $data['session']['idx'] ?>';
        if (!idx || !memIdx) {
            return false;
        }

        console.error(stat)
        deleteAjax(memIdx, idx, scrapType, stat);
        return true;
    })

    // $("input[name=radio_interview]").on("change", function() {
    //     const strInterview = $('input[name="radio_interview"]:checked').val();
    //     location.href = `/my/scrap/company?${strInterview}=Y`;
    // })
</script>