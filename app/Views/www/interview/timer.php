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
        <form action="/interview/timerAction" method="POST" id="timer">
            <?= csrf_field() ?>
            <input name="applyIdx" value="<?= $data['applyIdx'] ?>">
            <input name="memIdx" value="<?= $data['memIdx'] ?>">
            <input name="answerTimer" id="answerTimer" value="">
            <input name="postCase" value="selfTimer" type="hidden">
            <input name="backUrl" value="/" type="hidden">
        </form>
        <div>
            타이머를 설정해주세요.
        </div>
        <hr>
        <?php foreach ($data['selfTimer'] as $val) : ?>
            <div>
                <input type="checkbox" id="selfTimer_<?= $val ?>" name="seletTimer">
                <label for="selfTimer_<?= $val ?>"><?= $val ?></label>
            </div>
        <?php endforeach ?>
        <hr>
        <div id="complete">확인</div>
    </div>
</body>

</html>

<script>
    $('input[type="checkbox"][name="seletTimer"]').on('click', function() {
        if ($(this).prop('checked')) {
            $('input[type="checkbox"][name="seletTimer"]').prop('checked', false);
            $(this).prop('checked', true);
        }
    });

    $('#complete').on('click', function() {
        const Timer = $("input:checkbox[name='seletTimer']:checked").attr('id').split("_")[1];
        $('#answerTimer').val(Timer);
        $('#timer').submit();
    });
</script>