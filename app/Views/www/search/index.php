<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>검색</title>
</head>

<script>
    $(document).ready(function() {
        $('input[type="radio"]').on('click', function() {
            $('#search').attr('disabled', true);
            $('#frm').submit();
        });

        $('.deleteKeyword').on('click', function() {
            let keyword = $(this).val();
            $.ajax({
                type: 'GET',
                url: 'search/deleteAction',
                data: {
                    'keyword': keyword,
                },
                success: function(data) {
                    let json = JSON.parse(data);
                    $('#' + keyword).remove();
                    if (json['status'] === 201) {
                        $('#keyword').append(`<span>최근 검색어가 없습니다.</span>`);
                    }
                },
                error: function(e) {
                    alert('이미 삭제된 검색어입니다.');
                    return;
                },
                timeout: 5000
            }); //ajax
        });
    });
</script>

<body>
    <a href='/'>뒤로가기</a>
    <form id="frm" method="get" action="/search/action">
        <div>
            <input id='search' type="text" name='keyword' placeholder='직무, 회사명, 공고명으로 검색해보세요' require>
            <input name='sort' value='1' style='display:none'>
            <input name='type' value='recruit' style='display:none'>
            <button type='submit'>검색</button>
        </div>
        <hr>
        <p>최근 검색어</p>
        <div id='keyword'>
            <?php
            if ($data['keyword']) :
                foreach ($data['keyword'] as $key => $val) : ?>
                    <label id='<?= $val ?>'>
                        <input type='radio' name='keyword' value='<?= $val ?>'>
                        <?= $val ?>
                        <button class='deleteKeyword' type='button' value='<?= $val ?>'>X</button>
                    </label>
                <?php endforeach;
            else : ?>
                <span>최근 검색어가 없습니다.</span>
            <?php endif; ?>
        </div>
    </form>
    <hr>
    <div>
        <div>나만의 희망 근무조건이 있나요?</div>
        <form id="frm" method="get" action="/search/action">
            <input name='keyword' value='' style='display:none'>
            <input name='sort' value='1' style='display:none'>
            <input name='type' value='deepSearch' style='display:none'>
            <input name='deepSearchChk' value='on' style='display:none'>
            <button type='submit'>상세조건으로 검색하기</button>
        </form>
    </div>
    <hr>
    <p>추천검색어</p>
    <div>
        <div>마케터</div>
        <div>마케터</div>
        <div>마케터</div>
        <div>마케터</div>
    </div>
</body>

</html>