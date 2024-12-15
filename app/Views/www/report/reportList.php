<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>레포트</title>
    <script>
        //공개한 영상 중 링크복사를 누르면 인터뷰 세부사항을 볼 수 있는 링크가 복사되는 함수
        function modal_share_link(en) {
            let link = 'https://localinterviewr.highbuff.com/report/detail/' + en;
            const t = document.createElement("textarea");
            document.body.appendChild(t);
            t.value = link;
            t.select();
            document.execCommand('copy');
            document.body.removeChild(t);
            alert('링크가 복사되었습니다.');
        }

        //공개한 영상을 페이스북으로 공유하는 함수
        function sns_share_facebook(en) {
            let broswerInfo = navigator.userAgent;
            let link = 'interview.highbuff.com/itv_view.php?index=' + en;
            if (broswerInfo.indexOf("APP_Highbuff_Android") != -1 || broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                location.href = "https://www.facebook.com/sharer/sharer.php?u=" + link;
            } else {
                window.open('https://www.facebook.com/sharer/sharer.php?u=' + link);
            }
        }

        //공개한 영상을 트위터로 공유하는 함수
        function sns_share_twitter(en) {
            let broswerInfo = navigator.userAgent;
            let link = 'interview.highbuff.com/itv_view.php?index=' + en;
            if (broswerInfo.indexOf("APP_Highbuff_Android") != -1 || broswerInfo.indexOf("APP_Highbuff_IOS") != -1) {
                location.href = "https://twitter.com/intent/tweet?url=" + link;
            } else {
                window.open("https://twitter.com/intent/tweet?url=" + link);
            }
        }

        //공개한 영상을 카카오톡으로 공유하는 함수
        function sns_share_kakao(img, name, en) {
            //Kakao.init('a2f74deda739622a9c80f1f0c6adb899');
            let link = 'interview.highbuff.com/itv_view.php?index=' + en;

            Kakao.Link.sendDefault({
                objectType: 'feed',
                content: {
                    title: name + "님의 A.I. 종합분석을 공유합니다.", // 보여질 제목
                    description: "링크를 누르고 " + name + "님의 A.I. 종합분석을 확인하세요.", // 보여질 설명
                    imageUrl: "https://media.highbuff.com/data/uploads_thumbnail/" + img, // 콘텐츠 URL
                    link: {
                        mobileWebUrl: link,
                        webUrl: link
                    }
                }
            });
        }

        let aPoints = [];
        <?php for ($i = 0; $i < count($data['points']); $i++) : ?>
            aPoints[<?= $i ?>] = <?= $data['points'][$i] ?>;
        <?php endfor; ?>
        let jobIdx = [];
        let iSumPoint = 0;
        const aTitle = ['AI가 진단하는 내 면접 습관 완벽 분석', '2번째 문구', '3번째 문구', '4번째 문구', '5번째 문구'];
        const iRandom = Math.floor(Math.random() * aTitle.length);
        $(document).ready(function() {
            for (let i = 0; i < aPoints.length; ++i) {
                aPoints[i] = Math.round(aPoints[i] * 100) / 100;
                iSumPoint += aPoints[i];
            }
            const iMaxPoint = Math.max.apply(null, aPoints);

            $('#max').text(iMaxPoint);
            $('#avg').text(iSumPoint / aPoints.length);

            $('#rTitle').text(aTitle[iRandom]);
            $('select').change(function() {
                $('#frm').submit();
            });

            // $('.shareOption').on('click', function(){
            //     thisJobIdx = $(this).val();
            //     for(let i=0; i<jobIdx.length; i++){
            //         if(jobIdx[i] == thisJobIdx){
            //             if (!confirm('동일 직군의 레포트가 공개되어 있어요')){
            //                 break;
            //             }
            //         }
            //     }
            //     location.herf = 'report/detail/1';
            // });

            $('.shareBtn').on('click', function() {
                let idx = $(this).val();
                Swal.fire({
                    title: '공유하기',
                    html: `
                        <div>
                            <button id='link' class='modalBtn' value='${idx}' type='button'>링크복사</button>
                        </div>
                        <div>
                            <button id='face' class='modalBtn' value='${idx}' type='button'>페이스북</button>
                        </div>
                        <div>
                            <button id='twitter' class='modalBtn' value='${idx}' type='button'>트위터</button>
                        </div>
                        <div>
                            <button id='kakao' class='modalBtn' value='${idx}' type='button'>카카오톡</button>
                        </div>
                    `,
                    focusConfirm: false,
                    confirmButtonText: '닫기',
                    allowOutsideClick: true,
                    preConfirm: () => {
                        return []
                    }
                })

                $('.modalBtn').on('click', function() {
                    let thisId = $(this).attr('id');
                    let thisValue = $(this).val();
                    switch (thisId) {
                        case 'link':
                            modal_share_link(thisValue);
                            break;
                        case 'face':
                            sns_share_facebook(thisValue);
                            break;
                        case 'twitter':
                            sns_share_twitter(thisValue);
                            break;
                        case 'kakao':
                            sns_share_kakao('dsd', 'name', thisValue);
                            break;
                    }
                });
            });

            $("#frm1").on("submit", function(event) {
                if (!confirm("정말 삭제하시겠습니까 재응시불가")) {
                    event.preventDefault();
                    return;
                }
            });

            $("#frm2").on("submit", function(event) {
                if (!confirm("공개를 취소하시겠습니까?")) {
                    event.preventDefault();
                    return;
                }
            });
        });
    </script>
    <style>
        .pagination>li {
            float: left;
            list-style: none;
            padding: 0.25rem;
        }
    </style>
</head>

<body>
    <a href='/'>뒤로가기</a>
    <div>AI 레포트</div>
    <div id='rTitle'>AI가 진단하는 내 면접습관 완벽분석</div>
    <div>
        <button type='button'>새 인터뷰 시작하기</button>
        <a href='/report/share'><button type='button' <?= ($data['allCount'] === 0 ?? true) ? 'disabled' : '' ?>>내 레포트 공개하기</button>
    </div>
    <div>
        <a href='?type=all'><button type='button'>전체 레포트 <span><?= $data['allCount'] ?? 0 ?>개</span></button></a>
        <a href='?type=open'><button type='button'>공개중인 레포트<span><?= $data['openCount'] ?? 0 ?>개</span></button></a>
    </div>
    <?php if ($data['type'] === 'all') : ?>
        <div>
            <span><?= $data['session']['name'] ?>님</span>
            <span>최고점<span id='max'>...</span></span>
            <span>평균<span id='avg'>...</span></span>
        </div>
        <div>
            <form id="frm" method="GET" action="/report">
                <select name='reportType'>
                    <option value='all' <?= $data['reportType'] === 'all' ? 'selected' : '' ?>>전체</option>
                    <option value='1' <?= $data['reportType'] === '1' ? 'selected' : '' ?>>공개</option>
                    <option value='0' <?= $data['reportType'] === '0' ? 'selected' : '' ?>>비공개</option>
                    <option value='company' <?= $data['reportType'] === 'company' ? 'selected' : '' ?>>기업용</option>
                </select>
                <select name='reportSort'>
                    <option value='date' <?= $data['reportSort'] === 'date' ? 'selected' : '' ?>>최신순</option>
                    <option value='max' <?= $data['reportSort'] === 'max' ? 'selected' : '' ?>>점수높은순</option>
                    <option value='min' <?= $data['reportSort'] === 'min' ? 'selected' : '' ?>>점수낮은순</option>
                </select>
            </form>
        </div>
        <form id="frm1" method="POST" action="/report/deleteAction">
            <?= csrf_field() ?>
            <ul>
                <?php foreach ($data['list'] as $val) : ?>
                    <?php if ($val['app_iv_stat'] == '4') : ?>
                        <li>
                            공개여부: <?= $val['app_share'] ? '공개' : '비공개' ?>
                            <?= $val['app_type'] === 'C' ? '기업용' : '' ?>
                            <?= $val['job_depth_text'] ?>
                            <?= $val['repo_analysis'] ?>
                            <?= $val['app_reg_date'] ?>
                            질문: <?= $val['queCount'] ?> 개
                            <a href='report/detail/<?= $val['idx'] ?>'>디테일</a>
                            <div>
                                <button class='shareBtn' type='button' value='<?= $val['idx'] ?>' <?= $val['app_share'] ? '' : 'disabled' ?>>공유하기</button>
                                <a href='report/share?report=<?= $val['idx'] ?>'>
                                    <button class='shareOption' type='button'>공개설정</button>
                                </a>
                                <button type='submit' name='deleteIdx' value='<?= $val['idx'] ?>'>삭제하기</button>
                            </div>
                        </li>
                    <?php elseif ($val['app_iv_stat'] == '3') : ?>
                        <li>
                            공개여부: <?= $val['app_share'] ? '공개' : '비공개' ?>
                            <?= $val['app_type'] === 'C' ? '기업용' : '' ?>
                            <?= $val['job_depth_text'] ?>
                            분석중
                            <?= $val['app_reg_date'] ?>
                            질문: <?= $val['queCount'] ?> 개
                            <a href='report/fail/<?= $val['idx'] ?>'>디테일</a>
                            <div>
                                <button type='button' disabled>공유하기</button>
                                <a href='report/share?report=<?= $val['idx'] ?>'><button type='button' disabled>공개설정</button></a>
                                <button type='submit' name='deleteIdx' value='<?= $val['idx'] ?>' disabled>삭제하기</button>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </form>
    <?php elseif ($data['type'] === 'open') : ?>
        <div>총 열람 <?= $data['appCount'] ?> | 제안 ???건</div>
        <form id="frm2" method="POST" action="/report/updateAction">
            <?= csrf_field() ?>
            <ul>
                <?php foreach ($data['list'] as $val) : ?>
                    <?php if ($val['app_iv_stat'] == '4') : ?>
                        <li>
                            <?= $val['job_depth_text'] ?>
                            <?= $val['repo_analysis'] ?>
                            공개일:<?= $val['app_reg_date'] ?>
                            조회수: <?= $val['app_count'] ?>
                            제안: ???건
                            <a href='report/detail/<?= $val['idx'] ?>'>디테일</a>
                            <div>
                                <button type='button'>미리보기</button>
                                <a href='report/share?report=<?= $val['idx'] ?>'><button type='button'>공개설정</button></a>
                                <button class='' type='submit' name='updateIdxMain' value='<?= $val['idx'] ?>'>공개취소하기</button>
                            </div>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </form>
    <?php elseif ($data['type'] === 'none') : ?>
        아직 완성된 인터뷰가 없어요
    <?php endif; ?>
    <?= $data['pager']->links('report', 'front_full') ?>
</body>

</html>