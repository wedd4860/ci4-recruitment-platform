<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">faq 관리</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="get" id="frm" action="/prime/faq/list/">
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
                    <form id="frmDel" method="post" action="/prime/faq/del" onsubmit="ckeckEditFrom('del')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="faq_stat" value="del" />
                        <table class="table table-row " style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="width:5%">
                                <col style="width:10%">
                                <col style="width:35%;">
                                <col style="width:40%">
                                <col style="width:10%">
                            </colgroup>
                            <thead>
                                <th><input type="checkbox" id="allClick"><label for="allClick" class="no-margin"></label></th>
                                <th class="text-center">순번</th>
                                <th class="text-center">질문</th>
                                <th class="text-center">답변</th>
                                <th class="text-center">수정</th>
                            </thead>
                            <tbody>
                                <?php
                                if (count($data['list'])) :
                                    foreach ($data['list'] as $row) :
                                ?>
                                        <tr id="tr-<?= $row['idx'] ?>">
                                            <td class="">
                                                <input class="anos" type="checkbox" name="faqDelIdx[]" value="<?= $row['idx'] ?>" id="anos-<?= $row['idx'] ?>"><label class="no-margin" for="anos-<?= $row['idx'] ?>"></label>
                                            </td>
                                            <td class="text-center"><?= $row['faq_sort'] ?></td>
                                            <td><?= $row['faq_question'] ?></td>
                                            <td><?= $row['faq_answer'] ?></td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-primary btn-mod" data-idx="<?= $row['idx'] ?>" data-sort="<?= $row['faq_sort'] ?>" data-question="<?= $row['faq_question'] ?>" data-answer="<?= $row['faq_answer'] ?>">수정</button>
                                            </td>
                                        </tr>
                                    <?php
                                    endforeach;
                                else :
                                    ?>
                                    <tr>
                                        <td colspan="5" class="">
                                            등록된 FAQ가 없습니다.
                                        </td>
                                    </tr>
                                <?php
                                endif;
                                ?>
                            </tbody>
                        </table>
                        <div class="bottom-table fome-row">
                            <div class="float-right">
                                <input type="submit" value="삭제" class="btn btn-success float-right">
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <?= $data['pager']->links('faq', 'front_full') ?>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>

        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">FAQ 수정</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form id="frmEdit" method="post" action="/prime/faq/write/action" onsubmit="ckeckEditFrom('edit')">
                        <?= csrf_field() ?>
                        <table class="table table-row " style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="width:10%">
                                <col style="width:35%;">
                                <col style="width:40%">
                            </colgroup>
                            <thead>
                                <th class="text-center">순번</th>
                                <th class="text-center">질문</th>
                                <th class="text-center">답변</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <input type="hidden" id="editIdx" name="idx" value="" />
                                    <input type="hidden" id="editStat" name="faqStat" value="" />
                                    <td>
                                        <input type="text" id="editSort" name="faqSort" value="" style="width:100%">
                                    </td>
                                    <td>
                                        <input type="text" id="editQuestion" name="faqQuestion" value="" style="width:100%">
                                    </td>
                                    <td>
                                        <input type="text" id="editAnswer" name="faqAnswer" value="" style="width:100%">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="bottom-table fome-row">
                            <div class="float-right">
                                <button type="button" class="btn btn-danger btn-md" id="faqAdd">추가</button>
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
    $(document).ready(function() {
        $(".btn-mod").on("click", function() {
            $("#editStat").val('mod');
            $("#editIdx").val($(this).data('idx'));
            $("#editSort").val($(this).data('sort'));
            $("#editQuestion").val($(this).data('question'));
            $("#editAnswer").val($(this).data('answer'));
        })

        $("#faqAdd").on("click", function() {
            $("#editIdx").val('');
            $("#editStat").val('add');

            $("#frmEdit").submit();
        })

    })

    function ckeckEditFrom(type) {
        if (type == 'del') {

        } else if ('edit') {
            const strQuestion = $("#editQuestion").val();
            const strAnswer = $("#editAnswer").val();
            if (strQuestion == '' || strAnswer == '') {
                return false;
            }
        }
    }
</script>