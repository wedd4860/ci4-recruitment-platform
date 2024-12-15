<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>검색</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            let deepSearchChk = '<?= (isset($data['deepSearchChk']))  ?>';
            let strCareer = '<?= $data['strCareer'] ?? ''  ?>';
            let strPayType = '<?= $data['strPayType'] ?? '' ?>';
            let iCareerMonth = $('input[name="iCareerMonth"]');
            let iPayUnit = $('input[name="iPayUnit"]');

            aArea();
            aJobs();

            if (deepSearchChk) {
                $('#frm2').css('display', 'block');
            }

            if (strCareer == 'old') {
                iCareerMonth.attr('disabled', false);
            }

            if (strPayType == 'after') {
                iPayUnit.attr('disabled', true);
            }

            $('select').change(function() {
                $('#frm').submit();
            });

            $('.tap').on('click', function() {
                $('#frm').submit();
            });

            $('#deepSearch').on('click', function() {
                $('#frm2').show();
            });

            $('#backBtn').on('click', function() {
                $('#frm2').hide();
            });

            $('#reset').on('click', function() {
                $('#frm3').find('input').each(function() {
                    $(this).prop('checked', false);
                });
                $('#frm3').find('label').each(function() {
                    $(this).removeClass('on');
                });
            });

            $('input[name="strCareer"]').on('click', function() {
                $(this).val() === 'new' ? iCareerMonth.attr('disabled', true) : iCareerMonth.attr('disabled', false);
            });

            $('input[name="strPayType"]').on('click', function() {
                $(this).val() === 'after' ? iPayUnit.attr('disabled', true) : iPayUnit.attr('disabled', false);
            });

            $('#tagExtendBtn').on('click', function() {
                $('#tagList').show();
            });

            $('#tags > li > label').on('click', function() {
                $(this).toggleClass('on');
            });
            $('#tagListClose').on('click', function() {
                $('#tagList').hide();
            });

            // 지역 
            $('#chooseArea').on('click', function() {
                $('.aArea').toggle();
            });

            $('#areaSelect').on('click', function() {
                if ($('.aArea').find('input:checkbox:checked').length > 10) {
                    return alert('10개 넘으면 안됩니다');
                }
                aArea();
                $('.aArea').hide();
            });

            $('#areaClose').on('click', function() {
                $('.aArea').find('input:checkbox:checked').each(function() {
                    $(this).prop('checked', false);
                });
                $('.aArea').hide();
            });
            //지역 끝

            //직군 시작
            $('#chooseJobs').on('click', function() {
                $('.aJobs').toggle();
            });

            $('#jobsSelect').on('click', function() {
                if ($('.aJobs').find('input:checkbox:checked').length > 10) {
                    return alert('10개 넘으면 안됩니다');
                }
                aJobs();
                $('.aJobs').hide();
            });

            $('#jobsClose').on('click', function() {
                $('.aJobs').find('input:checkbox:checked').each(function() {
                    $(this).prop('checked', false);
                });
                $('.aJobs').hide();
            });
            //직군 끝

            function aArea() {
                $('#areaPreview').empty();
                $('.aArea').find('input:checkbox:checked').each(function() {
                    strName = $('#aId' + $(this).val()).html();
                    let html = `<li>${strName}</li>`;
                    $('#areaPreview').append(html);
                });
            }

            function aJobs() {
                $('#jobsPreview').empty();
                $('.aJobs').find('input:checkbox:checked').each(function() {
                    strName = $('#jId' + $(this).val()).html();
                    let html = `<li>${strName}</li>`;
                    $('#jobsPreview').append(html);
                });
            }
        });
    </script>
    <style>
        .test>div {
            border: 1px solid black;
            padding: 0.25rem;
            margin: 0.25rem;
        }

        label {
            border: 1px solid #ddd;
        }

        .on {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <a href='/search'>뒤로가기</a>
    <form id="frm" method="get" action="/search/action">
        <div>
            <input value='<?= $data['keyword'] ?>' type="text" name='keyword' placeholder='직무, 회사명, 공고명으로 검색해보세요'>
            <button type='submit'>검색</button>
            <div>

            </div>
        </div>
        <div>
            <label for='recruit' class='tap'>공고<?= $data['count']['recruit'] ?? '' ?>
                <input id='recruit' type='radio' name='type' value='recruit' style='display:' <?= ($data['type'] === 'recruit') ? 'checked' : '' ?>>
            </label>
            <label for='company' class='tap'>기업<?= $data['count']['company'] ?? '' ?>
                <input id='company' type='radio' name='type' value='company' style='display:' <?= ($data['type'] === 'company') ? 'checked' : '' ?>>
            </label>
        </div>
        <?php if ($data['type'] === 'recruit') : ?>
            <select name='sort'>
                <option value='1' <?= ($data['sort'] == '1') ? 'selected' : '' ?>>관련도순</option>
                <option value='2' <?= ($data['sort'] == '2') ? 'selected' : '' ?>>최근등록순</option>
                <option value='3' <?= ($data['sort'] == '3') ? 'selected' : '' ?>>마감임박순</option>
                <option value='4' <?= ($data['sort'] == '4') ? 'selected' : '' ?>>연봉높은순</option>
                <option value='5' <?= ($data['sort'] == '5') ? 'selected' : '' ?>>거리순</option>
            </select>
            <div>
                <span><input type='checkbox'>내 인터뷰로 지원가능</span>
                <span>
                    <button id='deepSearch' type='button'><?= ($data['type'] === 'deepSearch') ? '상세검색중' : '상세검색' ?></button>
                </span>
            </div>
        <?php elseif ($data['type'] === 'company') : ?>
            <select name='sort'>
                <option value='6' <?= ($data['sort'] == '6') ? 'selected' : '' ?>>규모</option>
                <option value='7' <?= ($data['sort'] == '7') ? 'selected' : '' ?>>업종</option>
                <option value='8' <?= ($data['sort'] == '8') ? 'selected' : '' ?>>지역</option>
            </select>
            <div>
                <span><input type='checkbox'>모의 인터뷰 응시 가능</span>
                <span><input type='checkbox'>지금 채용중</span>
            </div>
        <?php elseif ($data['type'] === 'deepSearch') : ?>
            <select name='sort'>
                <option value='1' <?= ($data['sort'] == '1') ? 'selected' : '' ?>>관련도순</option>
                <option value='2' <?= ($data['sort'] == '2') ? 'selected' : '' ?>>최근등록순</option>
                <option value='3' <?= ($data['sort'] == '3') ? 'selected' : '' ?>>마감임박순</option>
                <option value='4' <?= ($data['sort'] == '4') ? 'selected' : '' ?>>연봉높은순</option>
                <option value='5' <?= ($data['sort'] == '5') ? 'selected' : '' ?>>거리순</option>
            </select>
            <div>
                <span>
                    총 <?= $data['count']['deepSearch'] ?? '' ?>개
                </span>
                <span>
                    <button id='deepSearch' type='button'><?= ($data['type'] === 'deepSearch') ? '상세검색적용중' : '상세검색' ?></button>
                </span>
            </div>
        <?php endif; ?>

        <!-- 상세검색 -->
        <div id='frm2' style='display:none'>
            <button id='backBtn' type='button'>뒤로가기</button>
            <div id='frm3'>
                <div class='test'>
                    <div>
                        <div>고용형태</div>
                        <label for='workTypeFullTime'>
                            <input id='workTypeFullTime' name='aWorkType[fullTime]' type='checkbox' <?= isset($data['aWorkType']['fullTime']) ? 'checked' : '' ?>>정규직
                        </label>
                        <label for='workTypehalfTime'>
                            <input id='workTypehalfTime' name='aWorkType[halfTime]' type='checkbox' <?= isset($data['aWorkType']['halfTime']) ? 'checked' : '' ?>>계약직
                        </label>
                        <label for='workTypeintern'>
                            <input id='workTypeintern' name='aWorkType[intern]' type='checkbox' <?= isset($data['aWorkType']['intern']) ? 'checked' : '' ?>>인턴직
                        </label>
                        <label for='workTypePartTime'>
                            <input id='workTypePartTime' name='aWorkType[partTime]' type='checkbox' <?= isset($data['aWorkType']['partTime']) ? 'checked' : '' ?>>아르바이트
                        </label>
                        <label for='workTypeForeign'>
                            <input id='workTypeForeign' name='aWorkType[foreign]' type='checkbox' <?= isset($data['aWorkType']['foreign']) ? 'checked' : '' ?>>해외취업
                        </label>
                    </div>

                    <div>
                        <div>지역></div>
                        <button type='button' id='chooseArea'>
                            지역 선택
                        </button>
                        <ul id='areaPreview'>

                        </ul>
                        <div class='aArea' style='display:none'>
                            <?php foreach ($data['areaCategory'] as $val) : ?>
                                <ul>
                                    <?php foreach ($val as $v) : ?>
                                        <li>
                                            <input id='A<?= $v['idx'] ?>' type='checkBox' name='aArea[A<?= $v['idx'] ?>]' value='<?= $v['idx'] ?>' <?= isset($data['aArea']["A{$v['idx']}"]) ? 'checked' : '' ?>>
                                            <label id='aId<?= $v['idx'] ?>' for='A<?= $v['idx'] ?>'><?= $v['aArea'] ?></label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                            <button id='areaClose' type='button'>취소</button><button id='areaSelect' type='button'>적용하기</button>
                        </div>
                    </div>

                    <div>
                        <div>직군/직무></div>
                        <button type='button' id='chooseJobs'>
                            직무 선택
                        </button>
                        <ul id='jobsPreview'>

                        </ul>
                        <div class='aJobs' style='display:none'>
                            <?php foreach ($data['jobsCategory'] as $val) : ?>
                                <ul>
                                    <?php foreach ($val as $v) : ?>
                                        <li>
                                            <input id='J<?= $v['idx'] ?>' type='checkBox' name='aJobs[J<?= $v['idx'] ?>]' value='<?= $v['idx'] ?>' <?= isset($data['aJobs']["J{$v['idx']}"]) ? 'checked' : '' ?>>

                                            <label id='jId<?= $v['idx'] ?>' for='J<?= $v['idx'] ?>'><?= $v['jobName'] ?></label>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endforeach; ?>
                            <button id='jobsClose' type='button'>취소</button><button id='jobsSelect' type='button'>적용하기</button>
                        </div>
                    </div>

                    <div>
                        <div>경력</div>
                        <label for='careerNew'>신입
                            <input id='careerNew' type='radio' name='strCareer' value='new' <?= (isset($data['strCareer']) && $data['strCareer'] === 'new') ? 'checked' : '' ?>>
                        </label>
                        <label for='careerOld'>경력
                            <input id='careerOld' type='radio' name='strCareer' value='old' <?= (isset($data['strCareer']) && $data['strCareer'] === 'old') ? 'checked' : '' ?>>
                        </label>
                        <div>
                            <input id='' type='number' name='iCareerMonth' value='<?= (isset($data['iCareerMonth'])) ? $data['iCareerMonth'] : '' ?>' disabled>월 이상
                        </div>
                    </div>

                    <div>
                        <div>지원 방식</div>
                        <label for='applyMy'>내 인터뷰로 지원
                            <input id='applyMy' type='radio' name='strApply' value='my' <?= (isset($data['strApply']) && $data['strApply'] === 'my') ? 'checked' : '' ?>>
                        </label>
                        <label for='applyYou'>기업 인터뷰로 지원
                            <input id='applyYou' type='radio' name='strApply' value='you' <?= (isset($data['strApply']) && $data['strApply'] === 'you') ? 'checked' : '' ?>>
                        </label>
                        <div>
                            질문<input id='applyCount' type='number' name='strApply' value='' disabled>개 이하
                        </div>
                    </div>

                    <div>
                        <div>태그</div>
                        <ul id='tags'>
                            <?php foreach ($data['tagCategory'] as $key => $val) : ?>
                                <li>
                                    <label for='T<?= $data['tagCategory'][$key]['idx'] ?>'>
                                        <?= $data['tagCategory'][$key]['tag_txt'] ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <button id='tagExtendBtn' type='button'>더보기</button>

                        <div id='tagList' style='display:none'>
                            <span>태그 선택하기</span>
                            <button id='tagListClose' type='button'>닫기</button>
                            <ul>
                                <?php foreach ($data['tagCategory'] as $key => $val) : ?>
                                    <li>
                                        <label for='T<?= $data['tagCategory'][$key]['idx'] ?>'>
                                            <!-- 인풋시작 -->
                                            <input id='T<?= $data['tagCategory'][$key]['idx'] ?>' type='checkbox' name='aTag[T<?= $data['tagCategory'][$key]['tag_txt'] ?>]' <?= isset($data['aTag']["T{$data['tagCategory'][$key]['tag_txt']}"]) ? 'checked' : '' ?>>
                                            <!-- 인풋끝 -->
                                            <?= $data['tagCategory'][$key]['tag_txt'] ?>
                                        </label>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <div>급여</div>
                        <label for='payTypeTime'>시급
                            <input id='payTypeTime' type='radio' name='strPayType' value='time' <?= (isset($data['strPayType']) && $data['strPayType'] === 'time') ? 'checked' : '' ?>>
                        </label>
                        <label for='payTypeMonth'>월급
                            <input id='payTypeMonth' type='radio' name='strPayType' value='month' <?= (isset($data['strPayType']) && $data['strPayType'] === 'month') ? 'checked' : '' ?>>
                        </label>
                        <label for='payTypeYear'>연봉
                            <input id='payTypeYear' type='radio' name='strPayType' value='year' <?= (isset($data['strPayType']) && $data['strPayType'] === 'year') ? 'checked' : '' ?>>
                        </label>
                        <label for='payTypeAfter'>면접 후 협의
                            <input id='payTypeAfter' type='radio' name='strPayType' value='after' <?= (isset($data['strPayType']) && $data['strPayType'] === 'after') ? 'checked' : '' ?>>
                        </label>
                        <div>
                            <input id='' type='number' name='iPayUnit' value='<?= (isset($data['iPayUnit'])) ? $data['iPayUnit'] : '' ?>'>만원 이상
                        </div>
                    </div>

                    <div>
                        <div>근무기간선택</div>
                        <label for='month1'>
                            <input id='month1' type='radio' name='strPeriod' value='1month' <?= (isset($data['strPeriod']) && $data['strPeriod'] === '1month') ? 'checked' : '' ?>>1개월 이상
                        </label>
                        <label for='month3'>
                            <input id='month3' type='radio' name='strPeriod' value='3month' <?= (isset($data['strPeriod']) && $data['strPeriod'] === '3month') ? 'checked' : '' ?>>3개월 이상
                        </label>
                        <label for='month6'>
                            <input id='month6' type='radio' name='strPeriod' value='6month' <?= (isset($data['strPeriod']) && $data['strPeriod'] === '6month') ? 'checked' : '' ?>>6개월 이상
                        </label>
                        <label for='month12'>
                            <input id='month12' type='radio' name='strPeriod' value='12month' <?= (isset($data['strPeriod']) && $data['strPeriod'] === '12month') ? 'checked' : '' ?>>1년 이상
                        </label>
                    </div>

                    <div>
                        <div>성별</div>
                        <label for='genderMan'>
                            <input id='genderMan' name='aGender[m]' type='checkbox' <?= isset($data['aGender']['m']) ? 'checked' : '' ?>>남자
                        </label>
                        <label for='genderWoman'>
                            <input id='genderWoman' name='aGender[w]' type='checkbox' <?= isset($data['aGender']['w']) ? 'checked' : '' ?>>여자
                        </label>
                        <label for='genderNone'>
                            <input id='genderNone' name='aGender[n]' type='checkbox' <?= isset($data['aGender']['n']) ? 'checked' : '' ?>>무관 제외
                        </label>
                    </div>

                    <div>
                        <div>근무요일
                            <label for='ds'>
                                <input id='ds' name='workDayType' type='checkbox' <?= isset($data['aWorkType']['select']) ? 'checked' : '' ?>>직접 선택
                            </label>
                        </div>

                        <div>
                            <label for='week'>
                                <input id='week' name='aWorkDay[week]' type='checkbox' <?= isset($data['aWorkDay']['week']) ? 'checked' : '' ?>>평일 (월~금)
                            </label>
                            <label for='weekEnd'>
                                <input id='weekEnd' name='aWorkDay[weekEnd]' type='checkbox' <?= isset($data['aWorkDay']['weekEnd']) ? 'checked' : '' ?>>주말 (토, 일)
                            </label>
                        </div>

                        <div>
                            <label for='mon'>
                                <input id='mon' name='aWorkDay[mon]' type='checkbox' <?= isset($data['aWorkDay']['mon']) ? 'checked' : '' ?>>월
                            </label>
                            <label for='tues'>
                                <input id='tues' name='aWorkDay[tues]' type='checkbox' <?= isset($data['aWorkDay']['tues']) ? 'checked' : '' ?>>화
                            </label>
                            <label for='wednes'>
                                <input id='wednes' name='aWorkDay[wednes]' type='checkbox' <?= isset($data['aWorkDay']['wednes']) ? 'checked' : '' ?>>수
                            </label>
                            <label for='thurs'>
                                <input id='thurs' name='aWorkDay[thurs]' type='checkbox' <?= isset($data['aWorkDay']['thurs']) ? 'checked' : '' ?>>목
                            </label>
                            <label for='fri'>
                                <input id='fri' name='aWorkDay[fri]' type='checkbox' <?= isset($data['aWorkDay']['fri']) ? 'checked' : '' ?>>금
                            </label>
                            <label for='satur'>
                                <input id='satur' name='aWorkDay[satur]' type='checkbox' <?= isset($data['aWorkDay']['satur']) ? 'checked' : '' ?>>토
                            </label>
                            <label for='sun'>
                                <input id='sun' name='aWorkDay[sun]' type='checkbox' <?= isset($data['aWorkDay']['sun']) ? 'checked' : '' ?>>일
                            </label>
                        </div>
                    </div>

                    <div>
                        <div>학력</div>
                        <label for='eduNone'>
                            <input id='eduNone' name='aEducation[none]' type='checkbox' <?= isset($data['aEducation']['none']) ? 'checked' : '' ?>>무관 제외
                        </label>
                        <label for='eduMiddle'>
                            <input id='eduMiddle' name='aEducation[middle]' type='checkbox' <?= isset($data['aEducation']['middle']) ? 'checked' : '' ?>>고졸 이하
                        </label>
                        <label for='eduHigh'>
                            <input id='eduHigh' name='aEducation[high]' type='checkbox' <?= isset($data['aEducation']['high']) ? 'checked' : '' ?>>고등학교
                        </label>
                        <label for='eduCollege'>
                            <input id='eduCollege' name='aEducation[college]' type='checkbox' <?= isset($data['aEducation']['college']) ? 'checked' : '' ?>>대학(2, 3년제)
                        </label>
                        <label for='eduUniversity'>
                            <input id='eduUniversity' name='aEducation[university]' type='checkbox' <?= isset($data['aEducation']['university']) ? 'checked' : '' ?>>대학교(4년제)
                        </label>
                        <label for='eduMaster'>
                            <input id='eduMaster' name='aEducation[master]' type='checkbox' <?= isset($data['aEducation']['master']) ? 'checked' : '' ?>>석사
                        </label>
                        <label for='eduDoctor'>
                            <input id='eduDoctor' name='aEducation[doctor]' type='checkbox' <?= isset($data['aEducation']['doctor']) ? 'checked' : '' ?>>박사
                        </label>
                    </div>
                </div>
            </div>
            <button id='reset' type='button'>초기화</button>
            <label for='setDeepSearch' class='tap'>적용하기
                <input id='setDeepSearch' type='radio' name='type' value='deepSearch' style='display:' <?= ($data['type'] === 'deepSearch') ? 'checked' : '' ?>>
            </label>
        </div>
        <!-- 상세검색끝 -->
    </form>
    <!-- 리스트 -->
    <ul>
        <?php foreach ($data['recruitList'] as $key => $val) : ?>
            <li>
                <?= print_r($val) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <!-- 리스트 끝 -->
    <?= $data['pager']->links('search', 'front_full') ?>
    <?= $data['pageCount'] ?>페이지
</body>

</html>