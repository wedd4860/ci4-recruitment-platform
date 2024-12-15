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
                    <div class="row">
                        <div class="col-12">
                            <a class="btn btn-primary float-right" href="/prime/board/<?= $data['aData']['code'] ?>/write">게시글 작성</a>
                        </div>
                    </div>

                    <form method="get" id="frm" action="/prime/board/list/<?= $data['aData']['code'] ?>">
                        <?= csrf_field() ?>
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="width:150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>게시판</th>
                                    <td>
                                        <select name="code" onchange="javascript:location.href='/prime/board/list/'+this.value">
                                            <?php
                                            if (count($data['bdList'])) :
                                                foreach ($data['bdList'] as $row) :
                                            ?>
                                                    <option value="<?= $row['board_list_id'] ?>" <?= isset($data['aData']['code']) && $data['aData']['code'] == $row['board_list_id'] ? 'selected' : '' ?>><?= $row['board_list_name'] ?></option>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th>검색어</th>
                                    <td>
                                        <input name="searchText" type="text" value="<?= $data['search']['text'] ?? '' ?>">
                                        <input type="submit" value="검색">
                                    </td>
                                </tr>
                                <?php if (isset($data['bd']['board_set']['type']) && $data['bd']['board_set']['type'] == 'event') { ?>
                                    <tr>
                                        <th>이벤트 기간</th>
                                        <td>
                                            <input name="evt_sdate" type=" text" value="<?= $_GET['evt_sdate'] ?? '' ?>" class="date" style="width: 150px;"> ~
                                            <input name="evt_edate" type=" text" value="<?= $_GET['evt_edate'] ?? '' ?>" class="date" style="width: 150px;">
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </form>

                    <p>리스트</p>
                    <div class="row">
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="width: 10%">
                                <col style="width: 10%">
                                <col style="width: 10%">
                                <col>
                                <col style="width: 10%">
                            </colgroup>
                            <thead>
                                <th class="text-center">순번</th>
                                <th class="text-center">공지</th>
                                <th class="text-center">삭제</th>
                                <th class="text-center">제목</th>
                                <th class="text-center">보기</th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($data['bd'])) :
                                    foreach ($data['bd'] as $row) :
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $row['idx'] ?></td>
                                            <td class="text-center"><?= $row['bd_notice'] ?></td>
                                            <td class="text-center"><?= $row['delyn'] ?></td>
                                            <td class="text-center"><?= $row['bd_title'] ?></td>
                                            <td class="text-center"><a href="/prime/board/<?= $data['aData']['code'] ?>/read/<?= $row['idx'] ?>" class="btn btn-sm btn-white">보기</a></td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">게시글이 없습니다.</td>
                                    </tr>
                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?= $data['pager']->links('board', 'front_full') ?>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->