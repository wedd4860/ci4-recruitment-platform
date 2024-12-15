<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>기존 프로필 선택 </div>
    <?php foreach ($data['getAllfile'] as $val) : ?>
        <div>
            <input type="checkbox" id="existImg_<?= $val['idx']; ?>" name="seletImg">
            <label for="existImg_<?= $val['idx']; ?>"><img src="<?= $data['url']['media'] ?><?=$val['file_save_name']; ?>" alt="" style="-webkit-transform: rotateY(180deg);"></label>
        </div>
    <?php endforeach ?>
    <hr>
    <div id="complete">선택완료</div>
    <div><?= $data['pager']->links('getAllfile', 'front_full') ?></div>
</body>

</html>

<script>
    $('input[type="checkbox"][name="seletImg"]').on('click', function() {
        if ($(this).prop('checked')) {
            $('input[type="checkbox"][name="seletImg"]').prop('checked', false);
            $(this).prop('checked', true);
        }
    });

    $('#complete').on('click', function() {
        const fileIdx = $("input:checkbox[name='seletImg']:checked").attr('id').split("_")[1];
        location.href = "/interview/profile/check/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>/" + fileIdx;
    });
</script>