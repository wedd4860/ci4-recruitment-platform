<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h2>기업탐색</h2>

    <a href="/search/action?keyword=&type=company">돋보기아이콘(검색시 검색 결과값이 기업으로 선택됨)</a>

    <br><br>

    <div style="border:1px solid;">
        지금 N개의 기업이 하이버프에서 모의 인터뷰 진행중
        <a href="#"><u>전체보기(모의인터뷰 응시하기로 이동)</u></a>
    </div>

    <br>

    <ul style="border:1px solid;">
        <li>전직 삼성 인사과장이 들려주는 삼성면접 주의사항 3가지</li>
        <li>전직 애플 인사과장이 들려주는 애플면접 주의사항 3가지</li>
        <li>전직 쿠팡 인사과장이 들려주는 쿠팡면접 주의사항 3가지</li>
    </ul>

    <br>

    <div style="border:1px solid;">
        나와 딱맞는 핏의 기업,<br>
        인공지능이 찾아드려요<br><br>
        <a href="#">지금 인터뷰하고, PERFIT한 기업 추천받기(인터뷰type 링크로 연결)</a>
    </div>

    <br>

    <div style="border:1px solid;">
        좋아하실만한 기업을 모아봤어요.<br>
        <ul>
            <?php
            foreach ($data['companyTag'] as $val) :
            ?>
                <li style="border:1px solid #ccc;">
                    <div><?= $val['idx'] ?></div>
                    <div><a href="/company/tag?tagCheck%5B%5D=<?= $val['idx'] ?>">#<?= $val['tag_txt'] ?></a></div>
                </li>
            <?php
            endforeach
            ?>
        </ul>
        <a href="/company/tag">전체보기(태그별 기업화면)</a>
    </div>

    <br>

    <div style="border:1px solid;">
        AI 인터뷰로 적극 채용중인 기업
        <?php if (count($data['company']) != 0 && $data['company']) : ?>
            <ul>
                <?php
                foreach ($data['company'] as $val) :
                ?>
                    <li style="border:1px solid #ccc;">
                        <div>즐겨찾기</div>
                        <div><?= $val['idx'] ?></div>
                        <div><?= $val['com_name'] ?></div>
                        <div><?= $val['com_industry'] ?></div>
                        <div><?= $val['com_address'] ?></div>
                        <div><a href="/search/action?keyword=<?= $val['com_name'] ?>&type=recruit">채용공고 <?= $val['recCnt'] ?>건 보러가기</a></div>
                    </li>
                <?php
                endforeach
                ?>
            </ul>
        <?php else : ?>
            <div>채용중인 공고가 없습니다.</div>        
        <?php endif; ?>
    </div>

    <br><br><br><br>

</body>

</html>