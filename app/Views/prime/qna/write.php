
<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">qna</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form method="post" id="frm" action="/prime/qna/write/action">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idx" value="<?= $data['list']['qnaIdx'] ?>">
                        <input type="hidden" name="postCase" value="qna_write">
                        <input type="hidden" name="backUrl" value="/prime/qna/write/<?= $data['list']['qnaIdx'] ?>">
                        <input type="hidden" name="adminIdx" value="<?= $data['session']['idx'] ?>">
                        <table class="table">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>제목</th>
                                    <td>
                                        <?=$data['list']['qnaTitle']?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>질문</th>
                                    <td>
                                    <?= esc($data['list']['qnaQuestion']) ?? '' ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>답변</th>
                                    <td>
                                        <textarea name="qnaAnswer" id="bd-content"><?= $data['list']['qnaAnswer'] ?? '' ?></textarea>
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
</script>