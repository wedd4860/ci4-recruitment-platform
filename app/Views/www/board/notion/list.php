<div>
    <a href='/help'>뒤로가기</a>
</div>
<hr>
<div>
    <ul>
        <?php foreach ($data['noticeList'] as $val) : ?>
            <li>
                <div>
                    <div> <?= $val['bd_title'] ?></div>
                    <div> <?= $val['bd_reg_date'] ?></div>
                    <button class='conBtn' value=<?= $val['idx'] ?> type='button'>더보기</button>
                </div>
                <div id='content<?= $val['idx'] ?>' class='contents'>
                    내용
                    <?= $val['bd_content'] ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?= $data['pager']->links('event', 'front_full') ?>
</div>


<style>
    li {
        border: 1px solid black;
    }

    .contents {
        display: none;
        padding: 0.25rem;
        border: 1px #ddd solid;
    }
</style>
<script>
    $(document).ready(function() {
        $('.conBtn').on('click', function() {
            let thisIdx = $(this).val();
            $(`#content${thisIdx}`).toggle();
        });
    });
</script>