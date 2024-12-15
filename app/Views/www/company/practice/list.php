<h2>하이버프 모의 인터뷰</h2>
<p>가고 싶었던 기업의 실제 면접 질문 연습하기</p>

<form method="self" id="frmSubmit">
    <input name="searchText" type="text" value="<?= $data['search']['text'] ?? '' ?>">
    <input type="submit" value="검색">
    <br>
    <br>
    <div>
        <?php for ($i = 0; $i < count($data['comForm']); $i++) : ?>
            <input id='cbComForm' name='searchCompanyForm[]' type='checkbox' value='<?= $i ?>' 
            <?= isset($data['check']) && in_array($i, $data['check']) ? 'checked' : '' ?>>
            <label for='cbComForm'><?= $data['comForm'][$i] ?></label>
        <?php endfor; ?>
    </div>
    <br>
    <br>
    <br>
</form>

<?php
for ($i = 0; $i < count($data['list']); $i++) : ?>
    <ul style="border:1px solid">
        <li>
            <div><?= $data['list'][$i]['comName'] ?>
                <img style="width:50px;height:auto" src="<?= $data['list'][$i]['fileComLogo'] ?>" />
                <button type="button" data-idx="<?= $data['list'][$i]['comIdx'] ?>" class="btn-scrap-delete">별</button>
            </div>
            <div><?= $data['list'][$i]['comIdustry'] ?></div>
            <div><?= $data['list'][$i]['comForm'] ?></div>
            <div><?= $data['list'][$i]['comAddress'] ?></div>
            <input type="button" value="모의 인터뷰하기" onClick="location.href='#'">
        </li>
    </ul>
<?php
endfor;
?>
<div>
    <?= $data['pager']->links('practiceList', 'front_full') ?>
</div>

<script>
    $("input[name^=searchCompanyForm]").on('change', function() {
        $('#frmSubmit').submit()
    })
</script>