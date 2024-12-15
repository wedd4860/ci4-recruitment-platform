<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">회원목록</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="get" id="frm" action="/prime/member/list/">
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
                                    <select name="code" onchange="javascript:location.href='/prime/member/list/'+this.value">
                                        <option name="type" value="m" <?= $data['aData']['code'] == 'm' ? 'selected' : '' ?>>맴버</option>
                                        <option name="type" value="c" <?= $data['aData']['code'] == 'c' ? 'selected' : '' ?>>기업</option>
                                        <option name="type" value="a" <?= $data['aData']['code'] == 'a' ? 'selected' : '' ?>>관리자</option>
                                        <option name="type" value="l" <?= $data['aData']['code'] == 'l' ? 'selected' : '' ?>>라벨러</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>아이디검색</th>
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
                                <col>
                                <col style="width: 10%">
                                <col style="width: 10%">
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th class="text-center">순번</th>
                                    <th class="text-center">아이디</th>
                                    <th class="text-center">이름</th>
                                    <th class="text-center">전화</th>
                                    <th class="text-center">나이</th>
                                    <th class="text-center">수정</th>
                                </tr>
                                <?php
                                if (count($data['list'])) :
                                    foreach ($data['list'] as $row) :
                                ?>
                                        <tr>
                                            <td class="text-center"><?= $row['idx'] ?></td>
                                            <td class="text-center"><?= $row['mem_id'] ?></td>
                                            <td class="text-center"><?= $row['mem_name'] ?></td>
                                            <td class="text-center"><?= $row['mem_tel'] ?></td>
                                            <td class="text-center"><?= $row['mem_age'] ?></td>
                                            <td class="text-center"><a href="/prime/member/write/<?= $row['idx'] ?>">수정</a></th>
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
                    <?= $data['pager']->links('member', 'front_full') ?>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<!-- /.row -->