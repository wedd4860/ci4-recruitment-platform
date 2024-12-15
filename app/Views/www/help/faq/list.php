<a href='javascript:void(0);'>FAQ</a>
<a href='qna'>1:1 문의</a>

<?php foreach($data['list'] as $val) : ?>

<div> <?= $val['faq_question'] ?></div>
<div> <?= $val['faq_answer'] ?></div>

<?php endforeach; ?>
<div>+ 새 인터뷰 시작하기</div>