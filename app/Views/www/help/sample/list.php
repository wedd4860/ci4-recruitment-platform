
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>하이버프 활용법</title>
</head>

<body>
    <div>
        <div>이용가이드</div>
        <div>FAQ</div>
        <div>샘플 인터뷰(체크)</div>
    </div>
    <hr>
    <div class="type_fithBox">
        <form method="self" id="selectUpdown">
            <?php foreach ($data['jobCate'] as $key => $val) :
                if ($key <= 5) : ?>
                    <div>
                        <input type="checkbox" name="cateCheck[]" id="cateCheck_<?= $val[0]['idx'] ?>" value="<?= $val[0]['idx'] ?>" <?= isset($data['get']['cate']) && in_array($val[0]['idx'], $data['get']['cate']) ? 'checked' : '' ?>>
                        <label for="cateCheck_<?= $val[0]['idx'] ?>"><?= $val[0]['job_depth_text'] ?></label>
                    </div>
                <?php else : ?>
                    <div style="display:none" id="cate_<?= $key ?>">
                        <input type="checkbox" name="cateCheck[]" id="cateCheck_<?= $val[0]['idx'] ?>" value="<?= $val[0]['idx'] ?>" <?= isset($data['get']['cate']) && in_array($val[0]['idx'], $data['get']['cate']) ? 'checked' : '' ?>>
                        <label for="cateCheck_<?= $val[0]['idx'] ?>"><?= $val[0]['job_depth_text'] ?></label>
                    </div>
            <?php
                endif;
            endforeach ?>
            <input type="submit" value="전송">
    </div>
    <div id="more">더보기 v</div>
    <hr>
    <select name="" id="selectScore" onchange="changeUpdown()">
        <option value="up" id="up">점수 높은 순 v</option>
        <option value="down" id="down">점수 낮은 순 v</option>
    </select>
    <input name="updown" id="updown" value="">
    </form>
    <?= csrf_field() ?>
    <hr>
    <div>
        <?php foreach ($data['sampleList'] as $val) : ?>
            <div style="border: 1px solid; margin: 10px;">
                <div>
                    <img src="<?= $data['url']['media'] ?><?= $val['file_save_name'] ?>" alt="" style="height: 150px;">
                </div>
                <div>
                    <?= $val['grade'] ?> / <?= $val['sum'] ?>
                </div>
                <div>
                    <?= $val['job_depth_text'] ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <hr>
    <div>펼쳐보기</div>
    <div><?= $data['pager']->links('sample', 'front_full') ?></div>
    <hr>
    <div>
        <a href="/interview/type">
            <div>
                지금 바로 시작해볼까요?
            </div>
            <div>+ 새 인터뷰 시작하기</div>
        </a>
    </div>

</body>

</html>

<script>
    $('#updown').val("<?= $data['get']['updown'] ?>");
    if ("<?= $data['get']['updown'] ?>" == "up") {
        $('#up').prop('selected', true);
        $('#down').prop('selected', false);
    } else {
        $('#down').prop('selected', true);
        $('#up').prop('selected', false);
    }

    $('#more').on('click', function() {
        $('div[id^=cate]').show();
    });

    function changeUpdown() {
        $('#updown').val($("#selectScore option:selected").val());
        $('#selectUpdown').submit();
    }
</script>