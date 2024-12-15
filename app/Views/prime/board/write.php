<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">게시글 <?= $data['aData']['idx'] ? '수정' : '등록' ?></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form method="post" id="frm" action="/prime/board/<?= $data['aData']['code'] ?>/write/action" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idx" value="<?= $data['aData']['idx'] ?>">
                        <input type="hidden" name="postCase" value="board_write">
                        <input type="hidden" name="parentYN" value="N">
                        <input type="hidden" name="backUrl" value="/prime/board/list/<?= $data['aData']['code'] ?>">
                        <input type="hidden" name="adminId" value="<?= session()->get('mem_id') ?>">
                        <table class="table">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>공지</th>
                                    <td>
                                        <input name="bd_notice" type="radio" id="notice-n" value="N" checked><label for="notice-n">일반글</label>
                                        <input name="bd_notice" type="radio" id="notice-y" value="Y" <?= isset($data['aData']['idx']) && $data['bd_data']['bd_notice'] == 'y' ? 'checked' : '' ?>><label for="notice-y">공지사항</label>
                                    </td>
                                </tr>
                                <?php
                                if (isset($data['bdList']['board_list_basic']['secret']) && in_array($data['bdList']['board_list_basic']['secret'], ['cho', 'req'])) :
                                ?>
                                    <tr>
                                        <th>비밀글</th>
                                        <td>
                                            <input name="bd_secret" type="radio" id="secret-n" value="N" checked><label for="secret-n">일반글</label>
                                            <input name="bd_secret" type="radio" id="secret-y" value="Y" <?= isset($data['aData']['idx']) && $data['bd_data']['bd_secret'] == 'y' ? 'checked' : '' ?>><label for="secret-y">비밀글</label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>비밀번호</th>
                                        <td>
                                            <input name="bd_password" type="password" value="">
                                        </td>
                                    </tr>
                                <?php
                                endif;
                                ?>
                                <tr>
                                    <th>썸네일</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_thumb']) && $data['bd_data']['file_idx_thumb'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_thumb']['file_download'] ?>" download="download">썸네일</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_thumb" value="<?= isset($data['bd_data']['file_idx_thumb']) ? $data['bd_data']['file_idx_thumb'] : '' ?>">
                                        <input type="file" id="thumbnail" name="file_data_thumb" class="hide" accept=".jpg,.jpeg,.gif,.png">
                                    </td>
                                </tr>
                                <tr>
                                    <th>파일1</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_data_1']) && $data['bd_data']['file_idx_data_1'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_data_1']['file_download'] ?>" download="download">파일1</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_1" value="<?= isset($data['bd_data']['file_idx_data_1']) ? $data['bd_data']['file_idx_data_1'] : '' ?>">
                                        <input type="file" id="file_data_1" name="file_data_1" class="hide">
                                    </td>
                                </tr>
                                <tr>
                                    <th>파일2</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_data_2']) && $data['bd_data']['file_idx_data_2'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_data_2']['file_download'] ?>" download="download">파일2</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_2" value="<?= isset($data['bd_data']['file_idx_data_2']) ? $data['bd_data']['file_idx_data_2'] : '' ?>">
                                        <input type="file" id="file_data_2" name="file_data_2" class="hide">
                                    </td>
                                </tr>
                                <tr>
                                    <th>파일3</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_data_3']) && $data['bd_data']['file_idx_data_3'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_data_3']['file_download'] ?>" download="download">파일3</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_3" value="<?= isset($data['bd_data']['file_idx_data_3']) ? $data['bd_data']['file_idx_data_3'] : '' ?>">
                                        <input type="file" id="file_data_3" name="file_data_3" class="hide">
                                    </td>
                                </tr>
                                <tr>
                                    <th>파일4</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_data_4']) && $data['bd_data']['file_idx_data_4'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_data_4']['file_download'] ?>" download="download">파일4</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_4" value="<?= isset($data['bd_data']['file_idx_data_4']) ? $data['bd_data']['file_idx_data_4'] : '' ?>">
                                        <input type="file" id="file_data_4" name="file_data_4" class="hide">
                                    </td>
                                </tr>
                                <tr>
                                    <th>파일5</th>
                                    <td>
                                        <?php
                                        if (isset($data['bd_data']['file_idx_data_5']) && $data['bd_data']['file_idx_data_5'] != 0) :
                                        ?>
                                            <a href="/filedown?from=<?= $data['bd_file']['file_idx_data_5']['file_download'] ?>" download="download">파일5</a>
                                        <?php
                                        endif;
                                        ?>
                                        <input type="hidden" name="old_file_data_5" value="<?= isset($data['bd_data']['file_idx_data_5']) ? $data['bd_data']['file_idx_data_5'] : '' ?>">
                                        <input type="file" id="file_data_5" name="file_data_5" class="hide">
                                    </td>
                                </tr>
                                <!-- 이벤트, 갤러리 게시판 S -->
                                <?php
                                if (in_array($data['aData']['code'], ['event', 'gallery'])) :
                                ?>
                                    <!-- 이벤트 게시판 S -->
                                    <?php
                                    if ($data['aData']['code'] == 'event') :
                                    ?>
                                        <tr>
                                            <th>시작일</th>
                                            <td>
                                                <input name="board_start_date" type="text" class="date" value="<?= $data['bd_data']['board_start_date'] ?? '' ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>종료일</th>
                                            <td>
                                                <input name="board_end_date" type="text" class="date" value="<?= $data['bd_data']['board_end_date'] ?? '' ?>">
                                            </td>
                                        </tr>
                                    <?php
                                    endif;
                                    ?>
                                    <!-- 이벤트 게시판 E -->
                                <?php
                                endif;
                                ?>
                                <!-- 이벤트, 갤러리 게시판 E -->

                                <tr>
                                    <th>제목</th>
                                    <td>
                                        <input name="bd_title" type="text" value="<?= $data['bd_data']['bd_title'] ?? '' ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>내용</th>
                                    <td>
                                        <textarea name="bd_content" id="bd-content"><?= $data['bd_data']['bd_content'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div class="bottom-table fome-row">
                            <div class="float-right">
                                <input type="submit" value="저장" class="btn btn-success float-right">
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<script>
    admin_editor_param = {
        customConfig: '/plugins/ckeditor/config.js',
        filebrowserUploadUrl: '/data/editor-upload',
        fileTools_requestHeaders: {
            'X-CSRF-TOKEN': $('meta[name="X-CSRF-TOKEN"]').attr('content')
        }
    };
    CKEDITOR.replace('bd-content', admin_editor_param);
    CKEDITOR.on('dialogDefinition', function(ev) {
        editor_img_chek(ev);
    });
    var mem_index = 100,
        mem_level = <?= $mem_level_json ?? '[]' ?>;


    <?php if (in_array($data['aData']['code'], ['event', 'gallery'])) : ?>
        <?php if ($data['aData']['code'] == 'event') : ?>
            $('form').on('submit', function(event) {
                event.preventDefault();
                if ($('input[name="board_start_date"]').val() === '' || $('input[name="board_end_date"]').val() === '' || $('input[name="bd_title"]').val() === '') {
                    alert('제목, 시작일, 종료일은 필수값 입니다');
                    return;
                }
                this.submit();
            });
        <?php endif; ?>
    <?php endif; ?>
</script>