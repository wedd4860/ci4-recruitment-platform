<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>
        <img src="https://interview.highbuff.com/share/img/profile/noprofile.png" alt="">
    </div>
    <hr>
    <div>
        <button><a href="/interview/profile/photo/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>">지금 촬영하기</a></button>
        <form action="/interview/profile/albumAction" method="POST" id="next_form">
            <?= csrf_field() ?>
            <label for="profileFile">앨범에서 선택</label>
            <input type="file" accept="image/*" id="profileFile" name="profileFile">
            <input name="filePath" id="filePath">
            <input name="fileSize" id="fileSize">
            <input name="applyIdx" value="<?= $data['applyIdx'] ?>">
            <input name="memIdx" value="<?= $data['memIdx'] ?>">
            <input name="postCase" value="albumFile" type="hidden">
            <input name="backUrl" value="/" type="hidden">
        </form>
        <button id="btn_next">확인</button>
        <br>
        <button><a href="/interview/profile/exist/<?= $data['applyIdx'] ?>/<?= $data['memIdx'] ?>">기존 프로필에서 선택</a></button>

    </div>


</body>

</html>
<script src="https://cdn.socket.io/4.2.0/socket.io.min.js" integrity="sha384-PiBR5S00EtOj2Lto9Uu81cmoyZqR57XcOna1oAuVuIEjzj0wpqDVfD0JA9eXlRsj" crossorigin="anonymous"></script>
<script>
    initSocket();

    function initSocket() {
        socket = io.connect('<?= $data['url']['mediaFull'] ?>', {
            cors: {
                origin: '*'
            }
        });

        socket.on('connect', function() {
            console.log("socket connected");
        });

        socket.on('connect_error', function() {
            if (audio) audio.pause();
            alert("데이터 전송 지연이 발생합니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [하이버프 인터뷰]로 문의바랍니다.");
            location.reload();
            return false;
        });

        socket.on('complete_thumb', (data) => {
            $('#filePath').val(data.filePath.substr(1));
            $('#fileSize').val(data.size);

            $('#next_form').submit();
        });
        socket.on('disconnect', function() {
            alert("서버 연결이 끊어졌습니다. 잠시후에 시도해주세요.\n같은 현상이 반복되면 고객센터나 카카오톡 채널 [하이버프 인터뷰]로 문의바랍니다.");
            return false;
        });
    }

    function getFileName(fields, fileExtension) {
        let d = new Date();
        let index = "<?= $data['applyIdx'] ?>";
        let rand = Math.random().toString(36).substr(2, 11);
        let times = d.getTime();
        return index + "-" + fields + "-" + times + '-' + rand + '.' + fileExtension;
    }

    $('#btn_next').on("click", function(e) {
        e.preventDefault();
        const filename = getFileName('thumbnail', 'png');
        const file = $('#profileFile').prop('files');
        const data = {
            source: file[0],
            name: filename,
            size: file[0].size,
        };
        socket.emit('thumbnail', data);
    });
</script>