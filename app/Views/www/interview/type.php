<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div>원하는 인터뷰를 골라볼까요?</div>
    <hr>
    <div>
        <span>"경영사무/마케팅광고분석"</span> 을 추천해드릴께요.
    </div>
    <hr>
    <div>
        <div class="type_fithBox">
            <ul id="category_1">
                <?php foreach ($data['jobCate'] as $key => $val) : ?>
                    <li rel="tab<?= $key ?>"><?= $val[0]['job_depth_text'] ?></li>
                <?php endforeach ?>

            </ul>
        </div>
        <div class="type_sethBox" id="category_2">
            <?php foreach ($data['jobCate'] as $catekey => $val) : ?>
                <div id="tab<?= $catekey ?>" class="type_fith_cont" style="border:solid 1px;">
                    <ul>
                        <?php foreach ($data['jobCate'][$catekey] as $sCate => $val) :
                            if ($sCate != 0) :
                        ?>
                                <li data-idx="<?= $val['idx'] ?>"><a href="javascript:void(0)"> <?= $val['job_depth_text']; ?></a></li>

                        <?php endif;
                        endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <hr>
    <div>
        <form action="/interview/typeAction" method="POST" id="next_form">
            <?= csrf_field() ?>
            <input name="cateIdx" id="cateIdx" value="">
            <input name="appType" id="appType" value="M">
            <input name="appBrowserName" id="appBrowserName" value="">
            <input name="appBrowserVersion" id="appBrowserVersion" value="">
            <input name="appPlatform" id="appPlatform" value="">
            <input name="postCase" value="type">
            <input name="backUrl" value="/">

        </form>
        <a href="javascript:void(0)" id="next">다음</a>
    </div>
</body>

</html>

<script src="<?= $data['url']['menu'] ?>/plugins/bowser/bundled.js"></script>
<script>
    let type_txt1 = "";
    let type_txt2 = "";
    let info = bowser.parse(window.navigator.userAgent);

    $(document).ready(function() {
        getClientInfo();
    });

    //카테고리 스크립트
    $(function() {
        $(".type_fith_cont").hide();
        $(".type_fith_cont.fast").show();
        $(".type_fithBox li").on("click", function() {
            $(".type_fithBox li").removeClass("active");
            $(this).addClass("active");
            $(".type_fith_cont").hide()
            let activeTab = $(this).attr("rel");
            $("#" + activeTab).show();
            type_txt1 = $(this).text();
            $(".type_fith_cont li").removeClass("active");
            type_txt2 = $().text();
        });

        $(".type_fith_cont li").on("click", function() {
            $(".type_fith_cont li").removeClass("active");
            $(this).addClass("active");
            type_txt2 = $(this).data('idx');
        });
    });

    $("#next").on("click", function() {
        if (type_txt2 == "" || type_txt2 == null) {
            alert("세부 카테고리를 선택해주세요");
        } else {
            $("#cateIdx").val(type_txt2);
            $("#next_form").submit();
        }
    });

    // 클라이언트 정보
    function getClientInfo() {
        $("#appBrowserName").val(info.browser.name);
        $('#appBrowserVersion').val(info.browser.version);
        $('#appPlatform').val(info.platform.type);
    }
</script>