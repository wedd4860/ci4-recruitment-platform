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
            $('input').change(function() {
                $('#frm').submit();
            });
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

        li {
            padding: 0.5rem;
            border: 1px solid black;
        }
    </style>
</head>

<body>
    <div><a href='/search'>뒤로가기</a>받은 제안 차단기업</div>
    <div>
        <button type='button'>받은 제안 <?= $data['total']?></button>
        <button type='button'>레포트 조회</button>
    </div>
    <div>
        <form id="frm" method="GET" action="suggest">
            <label>
                <input type='radio' name='sort' value='new' <?= (($data['sort'] ?? false) === 'new') ? 'checked' : '' ?>> 최신순
            </label>
            <label>
                <input type='radio' name='sort' value='old' <?= (($data['sort'] ?? false) === 'old') ? 'checked' : '' ?>> 기한임박순
            </label>
        </form>
    </div>
    <div>
        <ul>
            <?php foreach ($data['suggest'] as $val) : ?>
                <li>
                    <div>
                        <div><?= $val['com_name'] ?> 날짜: <?= $val['rec_end_date'] ?></div>
                        <div><?= $val['rec_title'] ?></div>
                        <div><?= $val['sug_type'] ?>제안이 도착했습니다.</div>
                        <a href='suggest/detail/<?= $val['sug_idx'] ?>'><button type='button'>내용 상세보기</button></a>
                        <?= $val['sug_type'] === '인터뷰' ?  "<button type='button'>인터뷰 시작하기</button>" : '' ?>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?= $data['pager']->links('suggest', 'front_full') ?>
</body>

</html>