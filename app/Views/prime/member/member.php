<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">지원자 회원정보</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">순번</th>
                            <th class="text-center">아이디</th>
                            <th class="text-center">이름</th>
                            <th class="text-center">전화</th>
                            <th class="text-center">나이</th>
                            <th class="text-center">수정</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (count($data['member'])) :
                            foreach ($data['member'] as $key => $val) :
                        ?>
                                <tr>
                                    <td class="text-center"><?= $val['idx'] ?></td>
                                    <td class="text-center"><?= $val['mem_id'] ?></td>
                                    <td class="text-center"><?= $val['mem_name'] ?></td>
                                    <td class="text-center"><?= $val['mem_tel'] ?></td>
                                    <td class="text-center"><?= $val['mem_age'] ?></td>
                                    <td class="text-center">수정</th>
                                </tr>
                            <?php
                            endforeach;
                        else :
                            ?>
                            <tr>
                                <td colspan="6" class="text-center">검색된 회원이 없습니다.</td>
                            </tr>
                        <?php
                        endif;
                        ?>
                    </tbody>
                </table>
                <div class="row">
                    <?= $data['pager']->links('page', 'front_full') ?>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- /.row -->