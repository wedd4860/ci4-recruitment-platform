<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<div class="row">
    <div class="col-12">
        <!-- general form elements disabled -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">지원자 회원정보</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="box">
                    <form id="frm" method="post" action="/prime/member/write/action">
                        <input type="hidden" name="memIdx" value="<?= $data['list']['memIdx'] ?>" />
                        <input type="hidden" name="backUrl" value="/prime/member/write/<?= $data['list']['memIdx'] ?>" />
                        <input type="hidden" name="postCase" value="member_write" />
                        <?= csrf_field() ?>
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
                                            <input class="form-check-input" type="radio" name="memType" id="memType1" value="M" <?= $data['list']['memType'] == 'M' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memType1">회원</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memType" id="memType2" value="C" <?= $data['list']['memType'] == 'C' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memType2">기업</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memType" id="memType3" value="L" <?= $data['list']['memType'] == 'L' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memType3">라벨러</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memType" id="memType4" value="A" <?= $data['list']['memType'] == 'A' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memType4">관리자</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>아이디</td>
                                    <td>
                                        <?= $data['list']['memId'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>이름</td>
                                    <td>
                                        <?= $data['list']['memName'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>전화번호</td>
                                    <td>
                                        <?= $data['list']['memTel'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>나이</td>
                                    <td>
                                        <input type="text" name="memAge" value="<?= $data['list']['memAge'] ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>재직여부</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memWorkState" id="memWorkState1" value="Y" <?= $data['list']['memWorkState'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memWorkState1">재직중</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memWorkState" id="memWorkState2" value="N" <?= $data['list']['memWorkState'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memWorkState2">구직중</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>성별</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memGender" id="memGender1" value="Y" <?= $data['list']['memGender'] == 'M' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memGender1">남자</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memGender" id="memGender2" value="N" <?= $data['list']['memGender'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memGender2">여자</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>경력</td>
                                    <td>
                                        <input type="text" name="memCareer" value="<?= $data['list']['memCareer'] ?>" />개월
                                    </td>
                                </tr>
                                <tr>
                                    <td>학력</td>
                                    <td>
                                        <input type="text" name="memEducation" value="<?= $data['list']['memEducation'] ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>주소</td>
                                    <td>
                                        <input type="hidden" id="input_extraAddress" name="" value="" />
                                        <input type="text" id="input_postcode" name="memAddressPostcode" value="<?= $data['list']['memAddressPostcode'] ?>" />
                                        <input type="text" id="input_address" name="memAddress" value="<?= $data['list']['memAddress'] ?>" />
                                        <input type="text" id="input_detailAddress" name="memAddressDetail" value="<?= $data['list']['memAddressDetail'] ?>" />
                                        <button class="btn" type="button" onclick="search_addr();">주소 찾기</button>
                                        <div id="addressLayer"></div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>전공</td>
                                    <td>
                                        <input type="text" name="memMajor" id="input_address" value="<?= $data['list']['memMajor'] ?>" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>이용약관</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType1" id="memPersonalType1_1" value="Y" <?= $data['list']['memPersonalType1'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType1_1">동의</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType1" id="memPersonalType1_2" value="N" <?= $data['list']['memPersonalType1'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType1_2">비동의</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>맞춤채용</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType2" id="memPersonalType2_1" value="Y" <?= $data['list']['memPersonalType2'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType2_1">동의</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType2" id="memPersonalType2_2" value="N" <?= $data['list']['memPersonalType2'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType2_2">비동의</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>푸시알림</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType3" id="memPersonalType3_1" value="Y" <?= $data['list']['memPersonalType3'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType3_1">동의</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType3" id="memPersonalType3_2" value="N" <?= $data['list']['memPersonalType3'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType3_2">비동의</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>튜토리얼 보기</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType4" id="memPersonalType4_1" value="Y" <?= $data['list']['memPersonalType4'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType4_1">동의</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType4" id="memPersonalType4_2" value="N" <?= $data['list']['memPersonalType4'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType4_2">비동의</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>취엽연계</td>
                                    <td>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType5" id="memPersonalType5_1" value="Y" <?= $data['list']['memPersonalType5'] == 'Y' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType5_1">동의</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="memPersonalType5" id="memPersonalType5_2" value="N" <?= $data['list']['memPersonalType5'] == 'N' ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="memPersonalType5_2">비동의</label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>희망포지션</td>
                                    <td>
                                        <?php
                                        if (count($data['category'])) :
                                            foreach ($data['category'] as $row) :
                                        ?>
                                                <?= $row['job_depth_text'] ?>,
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>희망지역</td>
                                    <td>
                                        <?php
                                        if (count($data['area'])) :
                                            foreach ($data['area'] as $row) :
                                        ?>
                                                <?= $row['area_depth_text_1'] ?><?= $row['area_depth_text_2'] ?? '' ?><?= $row['area_depth_text_3'] ?? '' ?>,
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>방문횟수</td>
                                    <td>
                                        <?= $data['list']['memVisitCount'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>마지막 비밀번호 변경일</td>
                                    <td>
                                        <?= $data['list']['memLastPasswordDate'] ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>마지막 방문일</td>
                                    <td>
                                        <?= $data['list']['memVisitDate'] ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="submit" value="저장" class="btn btn-success float-right">
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>
<!-- /.row -->

<script>
    // 우편번호 찾기 화면을 넣을 element
    var element_layer = document.getElementById('addressLayer');

    function closeDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
        element_layer.style.display = 'none';
    }

    function search_addr() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if (data.userSelectedType === 'R') {
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if (data.bname !== '' && /[동|로|가]$/g.test(data.bname)) {
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if (data.buildingName !== '' && data.apartment === 'Y') {
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if (extraAddr !== '') {
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    document.getElementById("input_extraAddress").value = extraAddr;

                } else {
                    document.getElementById("input_extraAddress").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                document.getElementById('input_postcode').value = data.zonecode;
                document.getElementById("input_address").value = addr;
                // 커서를 상세주소 필드로 이동한다.
                document.getElementById("input_detailAddress").focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_layer.style.display = 'none';
            },
            width: '100%',
            height: '100%',
            maxSuggestItems: 5
        }).embed(element_layer);

        // iframe을 넣은 element를 보이게 한다.
        element_layer.style.display = 'block';

        // iframe을 넣은 element의 위치를 화면의 가운데로 이동시킨다.
        initLayerPosition();
    }

    // 브라우저의 크기 변경에 따라 레이어를 가운데로 이동시키고자 하실때에는
    // resize이벤트나, orientationchange이벤트를 이용하여 값이 변경될때마다 아래 함수를 실행 시켜 주시거나,
    // 직접 element_layer의 top,left값을 수정해 주시면 됩니다.
    function initLayerPosition() {
        var width = '100%'; //우편번호서비스가 들어갈 element의 width
        var height = 400; //우편번호서비스가 들어갈 element의 height
        var borderWidth = 8; //샘플에서 사용하는 border의 두께

        // 위에서 선언한 값들을 실제 element에 넣는다.
        element_layer.style.width = ''; //값을 넣으니 너비가 이상해져서 제거
        element_layer.style.height = height + 'px';
        //element_layer.style.border = borderWidth + 'px solid #afafaf';
        element_layer.style.marginBottom = '10px';

        // 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
        //element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width) / 2 - borderWidth) + 'px';
        //element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height) / 2 - borderWidth) + 'px';
    }
</script>