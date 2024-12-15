<h2>채용</h2>

<form method="self">
    <input name="searchText" type="text" value="<?= $data['search']['text'] ?? '' ?>">
    <input type="submit" value="검색">
    <br>

    <a href="">내가 원하는 조건으로 검색하기(아이콘)</a>
    <div>
        <input name="searchMyApply" type="checkbox" id="searchMyApply" value="M" <?= (isset($data['searchMyApply']) && $data['searchMyApply'] == true) ? 'checked' : '' ?> onChange="this.form.submit()"><label>내 인터뷰로 지원가능</label>

        <select name="searchOrder" id="searchOrder" onchange='this.form.submit()'>
            <!-- <option value="1">추천순</option> -->
            <option value="rec_hit" <?= isset($data['searchOrder']) && $data['searchOrder'] == "rec_hit" ? 'selected' : '' ?>>조회순</option>
            <option value="rec_end_date" <?= isset($data['searchOrder']) && $data['searchOrder'] == "rec_end_date" ? 'selected' : '' ?>>마감임박순</option>
            <option value="rec_start_date" <?= isset($data['searchOrder']) && $data['searchOrder'] == "rec_start_date" ? 'selected' : '' ?>>최근등록순</option>
        </select>
    </div>
</form>
<br>
<?php if ($data['interest'] == false) : ?>
    <a href="">
        <div>
            어떤 포지션에서 일하고 싶나요? <br>
            관심사 입력하고, 맞춤 공고 확인하기
        </div>
    </a>
<?php endif; ?>

<?php
for ($i = 0; $i < count($data['recruit']); $i++) : ?>
    <ul style="border:1px solid">
        <li>
            <div><?= $data['recruit'][$i]['comName'] ?><img style="width:50px;height:auto" src="<?= $data['recruit'][$i]['fileComLogo'] ?>" /><button type="button" data-idx="<?= $data['recruit'][$i]['recIdx'] ?>" class="btn-scrap-delete">별</button></div>
            <div><?= $data['recruit'][$i]['recTitle'] ?></div>
            <div><?= $data['recruit'][$i]['comAddress'] ?> | <?= $data['recruit'][$i]['recCareer'] ?> | <?= $data['recruit'][$i]['recEndDate'] ?></div>
            <div><?= $data['recruit'][$i]['recApply'] ?></div>
        </li>
    </ul>
<?php
endfor;
?>
<div>
    <?= $data['pager']->links('jobsList', 'front_full') ?>
</div>