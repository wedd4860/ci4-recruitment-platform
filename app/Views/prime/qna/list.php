<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">qna 관리</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="get" id="frm" action="/prime/qna/list/">
                    <?= csrf_field() ?>
                    <table class="table" style="border-bottom: 1px solid #dee2e6;">
                        <colgroup>
                            <col style="width:150px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>검색어</th>
                                <td>
                                    <input name="searchText" type="text" value="<?= $data['search']['text'] ?? '' ?>">
                                    <input type="submit" value="검색">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <div class="box">
                    <p>리스트</p>
                    <div class="row">
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="width: 10%">
                                <col style="width: 10%">
                                <col style="width: 10%">
                                <col style="width: 10%">
                                <col>
                                <col style="width: 10%">
                                <col style="width: 10%">
                            </colgroup>
                            <thead>
                                <th class="text-center">순번</th>
                                <th class="text-center">아이디</th>
                                <th class="text-center">이름</th>
                                <th class="text-center">답변</th>
                                <th class="text-center">제목</th>
                                <th class="text-center">질문일</th>
                                <th class="text-center">답변하기</th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($data['list'])) :
                                    foreach ($data['list'] as $row) :
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $row['qnaIdx'] ?></td>
                                            <td class="text-center"><?= $row['memId'] ?></td>
                                            <td class="text-center"><?= $row['memName'] ?></td>
                                            <td class="text-center"><?= isset($row['memIdxA']) ? 'Y' : 'N' ?></td>
                                            <td class="text-center"><?= $row['qnaTitle'] ?></td>
                                            <td class="text-center"><?= $row['qnaRegDate'] ?></td>
                                            <td class="text-center"><a href="/prime/qna/write/<?= $row['qnaIdx'] ?>" class="btn btn-sm btn-white">답변하기</a></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">질문이 없습니다.</td>
                                    </tr>
                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?= $data['pager']->links('qna', 'front_full') ?>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->