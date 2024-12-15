<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">게시판 검색</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form method="post" id="frm" action="/prime/board/set/write/action" onsubmit="return admin_board.submit_()" target="frm_ifr">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idx" value="<?= $data['list']['idx'] ?? '' ?>">
                        <input type="hidden" name="table" value="board_list">
                        <input type="hidden" name="stat" value="write">
                        <input type="hidden" name="backUrl" value="<?= $data['list']['idx'] ? '/prime/board/set/write/' . $data['list']['idx'] : '/prime/board/set' ?>">

                        <p>기본설정</p>
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>게시판 아이디</th>
                                    <td>
                                        <input name="board_list_id" type="text" value="<?= $data['list']['board_list_id'] ?? '' ?>" onChange="duplicate_inspection(this)" data-table="board_list" data-field="id" data-required="true" data-name="게시판 아이디" data-pattern="en_only" <?= isset($data['id']) ? 'readonly' : '' ?>>
                                    </td>
                                </tr>
                                <tr>
                                    <th>게시판 이름</th>
                                    <td><input name="board_list_name" type="text" value="<?= $data['list']['board_list_name'] ?? '' ?>" data-required="true" data-name="게시판 이름"></td>
                                </tr>
                                <tr>
                                    <th>유형</th>
                                    <td>
                                        <input name="board_list_type" type="radio" value="default" id="type-default" checked><label for="type-default">일반형</label>
                                        <input name="board_list_type" type="radio" value="gallery" id="type-gallery" <?= $data['list']['board_list_type'] == 'gallery' ? 'checked' : '' ?>><label for="type-gallery">갤러리형</label>
                                        <input name="board_list_type" type="radio" value="event" id="type-event" <?= $data['list']['board_list_type'] == 'event' ? 'checked' : '' ?>><label for="type-event">이벤트형</label>
                                        <input name="board_list_type" type="radio" value="qa" id="type-qa" <?= $data['list']['board_list_type'] == 'qa' ? 'checked' : '' ?>><label for="type-qa">1:1문의형</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>스킨</th>
                                    <td>
                                        <select name="board_list_skin">
                                            <?php
                                            foreach ($data['skin'] as $row) :
                                                $selected = $data['list']['board_list_skin'] == $row ? 'selected' : '';
                                                echo '<option value="' . $row . '" ' . $selected . '>' . $row . '</option>';
                                            endforeach;
                                            ?>

                                        </select>
                                    </td>
                                </tr>

                                <tr class="type-hide for-gallery for-event <?= $data['list']['board_list_type'] == 'gallery' || $data['list']['board_list_type'] == 'event' ? 'hide' : '' ?>">
                                    <th>섬네일 크기</th>
                                    <td>
                                        가로 <input name="board_list_basic[thum_width]" type="text" value="<?= $data['list']['board_list_basic']['thum_width'] ?? '' ?>" style="width: 60px;" data-pattern="num_only"> px
                                        &nbsp;&nbsp;&nbsp;
                                        세로 <input name="board_list_basic[thum_height]" type="text" value="<?= $data['list']['board_list_basic']['thum_height'] ?? '' ?>" style="width: 60px;" data-pattern="num_only"> px
                                    </td>
                                </tr>
                                <tr class="type-hide for-qa <?= $data['list']['board_list_type'] == 'qa' ? 'hide' : '' ?>">
                                    <th>첫화면 글쓰기</th>
                                    <td>
                                        <input name="board_list_basic[list_view]" type="radio" value="n" id=" list-view-n" checked><label for="list-view-n">미사용</label>
                                        <input name="board_list_basic[list_view]" type="radio" value="y" id="list-view-y" <?= $data['list']['board_list_basic']['list_view'] == 'y' ? 'checked' : '' ?>><label for="list-view-y">사용</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p>기능 / 권한 설정</p>
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>코멘트 기능</th>
                                    <td>
                                        <input name="board_list_basic[comment]" class="radio-basic" data-target="comment" type="radio" value="y" id="comment-y" checked><label for="comment-y">사용</label>
                                        <input name="board_list_basic[comment]" class="radio-basic" data-target="comment" type="radio" value="n" id=" comment-n" <?= $data['list']['board_list_basic']['comment'] == 'n' ? 'checked' : '' ?>><label for="comment-n">미사용</label>
                                    </td>
                                </tr>
                                <tr class="for-comment <?= $data['list']['board_list_basic']['comment'] == 'n' ? 'hide' : '' ?> ">
                                    <th>코멘트 권한</th>
                                    <td>
                                        <input name="board_list_auth[comment]" type="radio" class="auth-radio" data-name="comment" value="all" id="auth-comment-all" checked><label for="auth-comment-all">전체</label>
                                        <input name="board_list_auth[comment]" type="radio" class="auth-radio" data-name="comment" value="A" id="auth-comment-a" <?= (isset($data['list']['board_list_auth']['comment']) && $data['list']['board_list_auth']['comment'] == 'A') ? 'checked' : '' ?>><label for="auth-comment-admin">관리자</label>
                                        <input name="board_list_auth[comment]" type="radio" class="auth-radio" data-name="comment" value="L" id=" auth-comment-l" <?= (isset($data['list']['board_list_auth']['comment']) && $data['list']['board_list_auth']['comment'] == 'L') ? 'checked' : '' ?>><label for="auth-comment-sps">라벨러</label>
                                        <input name="board_list_auth[comment]" type="radio" class="auth-radio" data-name="comment" value="M" id="auth-comment-m" <?= (isset($data['list']['board_list_auth']['comment']) && $data['list']['board_list_auth']['comment'] == 'M') ? 'checked' : '' ?>><label for="auth-comment-member">맴버</label>
                                        <input name="board_list_auth[comment]" type="radio" class="auth-radio" data-name="comment" value="C" id=" auth-comment-c" <?= (isset($data['list']['board_list_auth']['comment']) && $data['list']['board_list_auth']['comment'] == 'C') ? 'checked' : '' ?>><label for="auth-comment-sps">기업</label>
                                        <ul id="auth-comment" class="add-list">
                                            <?php
                                            if (isset($data['auth']['sps']['comment']) && count($data['auth']['sps']['comment'])) :
                                                foreach ($data['auth']['sps']['comment'] as $key => $val) :
                                            ?>
                                                    <li id="add-comment-<?= $key ?>">
                                                        <input name="board_list_auth[sps][comment][]" type="hidden" value="<?= $val ?>">
                                                        <input name="board_list_auth[sps][comment_name][]" type="hidden" value="<?= $data['auth']['sps']['comment_name'][$key] ?>">
                                                        <?= $data['auth']['sps']['comment_name'][$key] ?>
                                                        <label class="add-list-dell xi-close-circle-o" onclick="javascript:$('#add-comment-<?= $key ?>').remove()">
                                                        </label>
                                                    </li>
                                            <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </ul>
                                    </td>
                                </tr>
                                <tr>
                                    <th>답변 기능</th>
                                    <td>
                                        <input name="board_list_basic[replay]" class="radio-basic" data-target="replay" type="radio" value="y" id="replay-y" checked><label for="replay-y">사용</label>
                                        <input name="board_list_basic[replay]" class="radio-basic" data-target="replay" type="radio" value="n" id=" replay-n" <?= (isset($data['list']['board_list_basic']['replay']) && $data['list']['board_list_basic']['replay'] == 'n') ? 'checked' : '' ?>><label for="replay-n">미사용</label>
                                    </td>
                                </tr>
                                <tr class="for-replay <?= (isset($data['list']['board_list_basic']['replay']) && $data['list']['board_list_basic']['replay'] == 'n') ? 'hide' : '' ?>">
                                    <th>답변 권한</th>
                                    <td>
                                        <input name="board_list_auth[replay]" type="radio" class="auth-radio" data-name="replay" value="all" id="auth-replay-all" checked><label for="auth-replay-all">전체</label>
                                        <input name="board_list_auth[replay]" type="radio" class="auth-radio" data-name="replay" value="A" id="auth-replay-a" <?= (isset($data['list']['board_list_auth']['replay']) && $data['list']['board_list_auth']['replay'] == 'A') ? 'checked' : '' ?>><label for="auth-replay-a">관리자</label>
                                        <input name="board_list_auth[replay]" type="radio" class="auth-radio" data-name="replay" value="L" id="auth-replay-l" <?= (isset($data['list']['board_list_auth']['replay']) && $data['list']['board_list_auth']['replay'] == 'L') ? 'checked' : '' ?>><label for="auth-replay-l">라벨러</label>
                                        <input name="board_list_auth[replay]" type="radio" class="auth-radio" data-name="replay" value="M" id="auth-replay-m" <?= (isset($data['list']['board_list_auth']['replay']) && $data['list']['board_list_auth']['replay'] == 'M') ? 'checked' : '' ?>><label for="auth-replay-m">맴버</label>
                                        <input name="board_list_auth[replay]" type="radio" class="auth-radio" data-name="replay" value="C" id="auth-replay-c" <?= (isset($data['list']['board_list_auth']['replay']) && $data['list']['board_list_auth']['replay'] == 'C') ? 'checked' : '' ?>><label for="auth-replay-c">기업</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>리스트 권한</th>
                                    <td>
                                        <input name="board_list_auth[list]" type="radio" class="auth-radio" data-name="board_list_list" value="all" id="auth-list-all" checked><label for="auth-list-all">전체</label>
                                        <input name="board_list_auth[list]" type="radio" class="auth-radio" data-name="board_list_list" value="A" id="auth-list-a" <?= (isset($data['list']['board_list_auth']['list']) && $data['list']['board_list_auth']['list'] == 'A') ? 'checked' : '' ?>><label for="auth-list-a">관리자</label>
                                        <input name="board_list_auth[list]" type="radio" class="auth-radio" data-name="board_list_list" value="L" id="auth-list-l" <?= (isset($data['list']['board_list_auth']['list']) && $data['list']['board_list_auth']['list'] == 'L') ? 'checked' : '' ?>><label for="auth-list-l">라벨러</label>
                                        <input name="board_list_auth[list]" type="radio" class="auth-radio" data-name="board_list_list" value="M" id="auth-list-m" <?= (isset($data['list']['board_list_auth']['list']) && $data['list']['board_list_auth']['list'] == 'M') ? 'checked' : '' ?>><label for="auth-list-m">맴버</label>
                                        <input name="board_list_auth[list]" type="radio" class="auth-radio" data-name="board_list_list" value="C" id="auth-list-c" <?= (isset($data['list']['board_list_auth']['list']) && $data['list']['board_list_auth']['list'] == 'C') ? 'checked' : '' ?>><label for="auth-list-c">기업</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>읽기 권한</th>
                                    <td>
                                        <input name="board_list_auth[read]" type="radio" class="auth-radio" data-name="read" value="all" id="auth-read-all" checked><label for="auth-read-all">전체</label>
                                        <input name="board_list_auth[read]" type="radio" class="auth-radio" data-name="read" value="A" id="auth-read-a" <?= (isset($data['list']['board_list_auth']['read']) && $data['list']['board_list_auth']['read'] == 'A') ? 'checked' : '' ?>><label for="auth-read-a">관리자</label>
                                        <input name="board_list_auth[read]" type="radio" class="auth-radio" data-name="read" value="L" id="auth-read-l" <?= (isset($data['list']['board_list_auth']['read']) && $data['list']['board_list_auth']['read'] == 'L') ? 'checked' : '' ?>><label for="auth-read-l">라벨러</label>
                                        <input name="board_list_auth[read]" type="radio" class="auth-radio" data-name="read" value="M" id="auth-read-m" <?= (isset($data['list']['board_list_auth']['read']) && $data['list']['board_list_auth']['read'] == 'M') ? 'checked' : '' ?>><label for="auth-read-m">맴버</label>
                                        <input name="board_list_auth[read]" type="radio" class="auth-radio" data-name="read" value="C" id="auth-read-c" <?= (isset($data['list']['board_list_auth']['read']) && $data['list']['board_list_auth']['read'] == 'C') ? 'checked' : '' ?>><label for="auth-read-c">기업</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>쓰기 권한</th>
                                    <td>
                                        <input name="board_list_auth[write]" type="radio" class="auth-radio" data-name="write" value="all" id="auth-write-all" checked><label for="auth-write-all">전체</label>
                                        <input name="board_list_auth[write]" type="radio" class="auth-radio" data-name="write" value="A" id="auth-write-a" <?= (isset($data['list']['board_list_auth']['write']) && $data['list']['board_list_auth']['write'] == 'A') ? 'checked' : '' ?>><label for="auth-write-admin">관리자 전용</label>
                                        <input name="board_list_auth[write]" type="radio" class="auth-radio" data-name="write" value="L" id="auth-write-l" <?= (isset($data['list']['board_list_auth']['write']) && $data['list']['board_list_auth']['write'] == 'L') ? 'checked' : '' ?>><label for="auth-write-member">라벨러</label>
                                        <input name="board_list_auth[write]" type="radio" class="auth-radio" data-name="write" value="M" id="auth-write-m" <?= (isset($data['list']['board_list_auth']['write']) && $data['list']['board_list_auth']['write'] == 'M') ? 'checked' : '' ?>><label for="auth-write-member">맴버</label>
                                        <input name="board_list_auth[write]" type="radio" class="auth-radio" data-name="write" value="C" id="auth-write-c" <?= (isset($data['list']['board_list_auth']['write']) && $data['list']['board_list_auth']['write'] == 'C') ? 'checked' : '' ?>><label for="auth-write-member">기업</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>비밀글</th>
                                    <td>
                                        <input name="board_list_basic[secret]" type="radio" value="none" id="secret-none" checked><label for="secret-none">사용안함</label>
                                        <input name="board_list_basic[secret]" type="radio" value="req" id="secret-req" <?= (isset($data['list']['board_list_basic']['secret']) && $data['list']['board_list_basic']['secret'] == 'req') ? 'checked' : '' ?>><label for="secret-req">필수</label>
                                        <input name="board_list_basic[secret]" type="radio" value="cho" id=" secret-cho" <?= (isset($data['list']['board_list_basic']['secret']) && $data['list']['board_list_basic']['secret'] == 'cho') ? 'checked' : '' ?>><label for="secret-cho">선택</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>New 아이콘 기간</th>
                                    <td>
                                        <input name="board_list_basic[new]" type="text" style="width:40px;" value="<?= $data['list']['board_list_basic']['new'] ?? '24' ?>" data-pattern="num_only" data-required="true" data-name="아이콘 효력"> 시간
                                    </td>
                                </tr>
                                <tr>
                                    <th>Hot 아이콘 조건</th>
                                    <td>조회수
                                        <input name="board_list_basic[hot]" type="text" style="width:60px;" value="<?= $data['list']['board_list_basic']['hot'] ?? '500' ?>" data-pattern="num_only" data-required="true" data-name="아이콘 조건">회 이상 게시글
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p>리스트 화면 설정</p>
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>공지사항 목록 수</th>
                                    <td><input name="board_list_list[notice]" type="text" style="width:50px;" value="<?= $data['list']['board_list_list']['notice'] ?? '3' ?>" data-pattern="num_only" data-required="true" data-name="공지사항 목록 수"> 개</td>
                                </tr>
                                <tr>
                                    <th>공지사항 노출</th>
                                    <td>
                                        <input name="board_list_list[notice_all]" type="radio" value="n" id=" notice-all-n" checked><label for="notice-all-n">첫페이지만</label>
                                        <input name="board_list_list[notice_all]" type="radio" value="y" id=" notice-all-y" <?= (isset($data['list']['board_list_list']['notice_all']) && $data['list']['board_list_list']['notice_all'] == 'y') ? 'checked' : '' ?>><label for="notice-all-y">모든페이지</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>페이지당 목록 수</th>
                                    <td><input name="board_list_list[pageNm]" type="text" style="width:50px;" value="<?= $data['list']['board_list_list']['pageNm'] ?? '20' ?>" data-pattern="num_only" data-required="true" data-name="페이지당 목록 수"></td>
                                </tr>
                                <tr>
                                    <th>작성날짜</th>
                                    <td>
                                        <input name="board_list_list[date]" type="radio" value="date" id="list-date-date" checked><label for="list-date-date">날짜만</label>
                                        <input name="board_list_list[date]" type="radio" value="date_time" id="list-date-date_time" <?= (isset($data['list']['board_list_list']['date']) && $data['list']['board_list_list']['date'] == 'date_time') ? 'checked' : '' ?>><label for="list-date-date_time">날짜시간</label>
                                        <input name="board_list_list[date]" type="radio" value="none" id="list-date-none" <?= (isset($data['list']['board_list_list']['date']) && $data['list']['board_list_list']['date'] == 'none') ? 'checked' : '' ?>><label for="list-date-none">숨김</label>
                                    </td>
                                </tr>
                                <tr>
                                    <th>조회수</th>
                                    <td>
                                        <input name="board_list_list[hit]" type="radio" value="y" id="list-hit-y" checked><label for="list-hit-y">보임</label>
                                        <input name="board_list_list[hit]" type="radio" value="n" id="list-hit-n" <?= (isset($data['list']['board_list_list']['hit']) && $data['list']['board_list_list']['hit'] == 'n') ? 'checked' : '' ?>><label for="list-hit-n">숨김</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p>상단 하단 꾸미기</p>
                        <table class="table" style="border-bottom: 1px solid #dee2e6;">
                            <colgroup>
                                <col style="background-color: #ccc;width: 150px">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>상단 꾸미기</th>
                                    <td>
                                        <textarea name="board_list_outline[top]" id="outline-top"><?= $data['list']['board_list_outline']['top'] ?? '' ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th>하단 꾸미기</th>
                                    <td>
                                        <textarea name="board_list_outline[bottom]" id="outline-bottom"><?= $data['list']['board_list_outline']['bottom'] ?? '' ?></textarea>
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
    CKEDITOR.replace('outline-top', admin_editor_param);
    CKEDITOR.replace('outline-bottom', admin_editor_param);
    CKEDITOR.on('dialogDefinition', function(ev) {
        editor_img_chek(ev);
    });
    var mem_index = 100,
        mem_level = <?= $mem_level_json ?? '[]' ?>;
</script>