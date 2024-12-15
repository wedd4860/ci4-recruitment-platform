<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title"><?= $data['bd']['board_set']['board_list_name'] ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <table class="table">
                        <colgroup>
                            <col style="background-color: #ccc;width: 150px">
                            <col>
                        </colgroup>
                        <tbody>
                            <tr>
                                <th>작성일시</th>
                                <td><?= $data['bd']['bd_data']['bd_reg_date'] ?></td>
                                <th>수정일시</th>
                                <td><?= $data['bd']['bd_data']['bd_mod_date'] ?></td>
                            </tr>
                            <tr>
                                <th>작성아이피</th>
                                <td><?= $data['bd']['bd_data']['bd_ip'] ?></td>
                                <th>조회수</th>
                                <td><?= $data['bd']['bd_data']['bd_hit'] ?></td>
                            </tr>
                            <!-- 이벤트 게시판 S -->
                            <?php if ($data['bd']['board_set']['board_list_type'] == 'event') { ?>
                                <tr>
                                    <th>이벤트 시작일</th>
                                    <td><?= $data['bd']['bd_data']['bd_start_date'] ?></td>
                                    <th>이벤트 종료일</th>
                                    <td><?= $data['bd']['bd_data']['bd_end_date'] ?></td>
                                </tr>
                            <?php } ?>
                            <!-- 이벤트 게시판 E -->
                            <?php if ($data['bd']['bd_data']['file_idx_thumb']) { ?>
                                <tr>
                                    <th>썸네일</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_thumb']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_thumb']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($data['bd']['bd_data']['file_idx_data_1']) { ?>
                                <tr>
                                    <th>파일1</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_data_1']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_data_1']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($data['bd']['bd_data']['file_idx_data_2']) { ?>
                                <tr>
                                    <th>파일2</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_data_2']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_data_2']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($data['bd']['bd_data']['file_idx_data_3']) { ?>
                                <tr>
                                    <th>파일3</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_data_3']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_data_3']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($data['bd']['bd_data']['file_idx_data_4']) { ?>
                                <tr>
                                    <th>파일4</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_data_4']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_data_4']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <?php if ($data['bd']['bd_data']['file_idx_data_5']) { ?>
                                <tr>
                                    <th>파일5</th>
                                    <td colspan="3" class="">
                                        <a href="/filedown?from=<?= $data['bd']['bd_file']['file_idx_data_5']['file_download'] ?>" download="download">
                                            <?= $data['bd']['bd_file']['file_idx_data_5']['file_org_name'] ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>제목</th>
                                <td colspan="3" class=""><?= $data['bd']['bd_data']['bd_title'] ?></td>
                            </tr>
                            <tr>
                                <th>내용</th>
                                <td colspan="3" class=""><?= esc($data['bd']['bd_data']['bd_content']) ?></td>
                            </tr>
                            <?php
                            if ($data['bd']['board_set']['board_list_basic']['comment'] == 'y') :
                                // 코멘트가 y 일때
                            ?>
                                <?php
                                if ($data['cmt']) :
                                    // 코멘트 있을때
                                ?>
                                    <tr>
                                        <td colspan="4">
                                            <ul>
                                                <?php
                                                foreach ($data['cmt'] as $row) :
                                                ?>
                                                    <li style="padding-left: 0px;" class="">
                                                        <dt><?= $row['memName'] ?> - <?= $row['cmtRegDate'] ?> - <a href="/prime/board/<?= $data['aData']['code'] ?>/comment/del/<?= $row['cmtIdx'] ?>">삭제</a></dt>
                                                        <dl><?= $row['cmtComment'] ?></dl>
                                                    </li>
                                                <?php
                                                endforeach;
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php
                                endif;
                                ?>

                                <tr>
                                    <td colspan="4">
                                        <div>
                                            <form method="post" action="/prime/board/<?= $data['aData']['code'] ?>/comment/action">
                                                <?= csrf_field() ?>
                                                <input name="postCase" type="hidden" value="comment_write">
                                                <input name="mem_idx" type="hidden" value="<?= $data['session']['idx'] ?>">
                                                <input name="cmt_family" type="hidden" value="<?= $data['aData']['idx'] ?>">
                                                <input name="cmt_depth" type="hidden" value="1">
                                                <input name="cmt_bd_idx" type="hidden" value="<?= $data['aData']['idx'] ?>">
                                                <input type="hidden" name="backUrl" value="/prime/board/<?= $data['aData']['code'] ?>/read/<?= $data['aData']['idx'] ?>">
                                                <input value="<?= $data['session']['name'] ?>" readonly>
                                                <div class="input-group input-group-sm">
                                                    <textarea name="cmt_comment" class="form-control" placeholder="댓글을 남겨보세요"></textarea>
                                                    <span class="input-group-append">
                                                        <input type="submit" class="btn btn-info btn-flat" value="전송">
                                                    </span>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif;
                            ?>
                        </tbody>
                    </table>
                    <div class="bottom-table fome-row">
                        <div class="float-right">
                            <a class="nav-link active" href="/prime/board/<?= $data['aData']['code'] ?>/write/<?= $data['bd']['bd_data']['idx'] ?>">수정</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->