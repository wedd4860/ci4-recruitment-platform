<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">이용약관</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form id="frm" method="post" action="/prime/config/write/action">
                        <?= csrf_field() ?>
                        <input type="hidden" name="postCase" value="config_wrtie">
                        <input type="hidden" name="idx" value="<?= $data['list']['idx'] ?>">
                        <input type="hidden" name="cfgType" value="<?= $data['list']['cfg_type'] ?>">
                        <input type="hidden" name="backUrl" value="/prime/config/write/<?= $data['aData']['code'] ?>">
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <td>회원타입</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="cfgUseYN" id="inlineRadio1" value="Y" <?= $data['list']['cfg_useYN'] == 'Y' ? 'checked=""' : '' ?>>
                                            <label class="form-check-label" for="inlineRadio1">사용</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="cfgUseYN" id="inlineRadio2" value="N" <?= $data['list']['cfg_useYN'] == 'N' ? 'checked=""' : '' ?>>
                                            <label class="form-check-label" for="inlineRadio2">미사용</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>내용</td>
                                    <td>
                                        <textarea name="cfgContent" class="form-control" rows="3" placeholder=""><?= $data['list']['cfg_content'] ?></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <input type="submit" value="저장" class="btn btn-success float-right">
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->