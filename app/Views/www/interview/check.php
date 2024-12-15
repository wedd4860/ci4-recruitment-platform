<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>인터뷰의 프로필 사진을 설정해주세요!</div>
    <hr>
    <div>
        <img src="<?= $data['url']['media'] . $data['fileSaveDir']['file_save_name'] ?>" alt="" style="-webkit-transform: rotateY(180deg);">
        <form action="/interview/profile/setProfileAction" method="POST">
            <?= csrf_field() ?>
            <input name="applyIdx" value="<?= $data['applyIdx'] ?>">
            <input name="memIdx" value="<?= $data['memIdx'] ?>">
            <input name="fileIdx" value="<?= $data['fileIdx'] ?>">
            <input name="postCase" value="setFile" type="hidden">
            <input name="backUrl" value="/" type="hidden">
            <button type="submit">설정 완료</button>
        </form>
    </div>
    <hr>
    <div>
        <button><a href="/interview/profile/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>">다시 선택하기</a></button>
    </div>
</body>

</html>