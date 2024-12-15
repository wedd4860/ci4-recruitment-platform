<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">게시글</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <table class="table" style="border-bottom: 1px solid #dee2e6;">
                        <colgroup>
                            <col style="width: 5%">
                            <col style="width: 5%">
                            <col style="width: 10%">
                            <col style="width: 5%">
                            <col style="width: 10%">
                            <col style="width: 10%">
                            <col style="width: 10%">
                            <col style="width: 10%">
                            <col style="width: 5%">
                            <col style="width: 5%">
                            <col style="width: 5%">
                            <col style="width: 10%">
                            <col style="width: 10%">
                        </colgroup>
                        <thead>
                            <th class="text-center">순번</th>
                            <th class="text-center">아이디</th>
                            <th class="text-center">이름</th>
                            <th class="text-center">타입</th>
                            <th class="text-center">스킨</th>
                            <th class="text-center">리스트 권한</th>
                            <th class="text-center">읽기 권한</th>
                            <th class="text-center">쓰기 권한</th>
                            <th class="text-center">코멘트</th>
                            <th class="text-center">답변</th>
                            <th class="text-center">게시글수</th>
                            <th class="text-center">생성일</th>
                            <th class="text-center">수정</th>
                        </thead>
                        <tbody>
                            <?php
                            if (count($data['list'])) :
                                foreach ($data['list'] as $row) :
                            ?>
                                    <tr>
                                        <td class="text-center"><?= $row['idx'] ?></td>
                                        <td class="text-center"><?= $row['board_list_id'] ?></td>
                                        <td class="text-center">
                                            <a href="/prime/board/contents?code=<?= $row['board_list_id'] ?>"><?= $row['board_list_name'] ?></a>
                                        </td>
                                        <td class="text-center"><?= $row['board_list_type'] ?></td>
                                        <td class="text-center"><?= $row['board_list_skin'] ?></td>
                                        <td class="text-center"><?= admin_auth($row['board_list_auth']['list']) ?></td>
                                        <td class="text-center"><?= admin_auth($row['board_list_auth']['read']) ?></td>
                                        <td class="text-center"><?= admin_auth($row['board_list_auth']['write']) ?></td>
                                        <td class="text-center"><?= admin_auth($row['board_list_auth']['comment'], $row['board_list_auth']['comment']) ?></td>
                                        <td class="text-center"><?= admin_auth($row['board_list_auth']['replay'], $row['board_list_auth']['replay']) ?></td>
                                        <td class="text-center">
                                            <a href="/prime/board/contents?code=<?= $row['board_list_id'] ?>"><?= number_format($row['board_list_total']) ?></a>
                                        </td>
                                        <td class="text-center"><?= $row['board_list_reg_date'] ?></td>
                                        <td class="text-center"><a href="/prime/board/set/write/<?= $row['idx'] ?>" class="btn btn-sm btn-white">수정</a></td>
                                    </tr>
                                <?php
                                endforeach;
                            else : ?>
                                <tr>
                                    <td colspan="7" class="text-center">검색된 게시판이 없습니다.</td>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <?= $data['pager']->links('set', 'front_full') ?>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->