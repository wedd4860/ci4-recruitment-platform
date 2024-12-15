<a href='faq'>FAQ</a>
<a href='javascript:void(0);'>1:1 문의</a>

<div><a href='qna/write'>새 문의 작성하기</a></div>

<div>나의 문의내역<?= $data['total'] ?? 0 ?>건</div>
<?php foreach ($data['list'] as $val) : ?>

    <div class='question'>
        <div> <?= $val['qna_reg_date'] ?> </div>
        <div> <?= $val['qna_title'] ?></div>
        <div> <?= $val['qna_question'] ?></div>
        <div>
            <?= $val['file_idx_data_1'] ?>
            <?= $val['file_idx_data_2'] ?>
            <?= $val['file_idx_data_3'] ?>
            <?= $val['file_idx_data_4'] ?>
        </div>

    </div>
    <?php if ($val['qna_answer']) : ?>
        <div class='answer'>
            답변
            <?= $val['qna_mod_date'] ?>
            <div> <?= $val['qna_answer'] ?> </div>
        </div>
    <?php endif; ?>

<?php endforeach; ?>

<?= $data['pager']->links('qna', 'front_full') ?>

<style>
    .question {
        border: 1px #ddd solid;
        padding: 0.25rem;
    }

    .answer {
        border: 1px blue solid;
        padding: 0.25rem;
    }
</style>