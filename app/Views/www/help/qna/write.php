<a href='javascript:window.history.back();'>뒤로가기</a>새 문의 작성하기
<div>어떤 점이 궁금하신가요?</div>
<form id='frm' method='post' action='write/action' enctype="multipart/form-data">
    <?= csrf_field() ?>
    <input type='text' name='title' placeholder='제목을 입력해 주세요.' require>
    <div>
        <textarea type='text' name='question' placeholder='문의하실 내용을 입력해 주세요.' require></textarea>
    </div>
    <div>
        <input name='file1' type='file' accept='.image/.jpeg,.png,.jpg'>
        <input name='file2' type='file' accept='.image/.jpeg,.png,.jpg'>
        <input name='file3' type='file' accept='.image/.jpeg,.png,.jpg'>
        <input name='file4' type='file' accept='.image/.jpeg,.png,.jpg'>
    </div>
    <div><button type='submit'>문의 등록하기</button></div>
</form>

<script>
    $("#frm").validate({
        ignore: [],
        rules: {
            title: {
                required: true,
            },
            question: {
                required: true,
            },
        },
        messages: {
            title: {
                required: "제목은 필수 입력입니다.",
            },
            question: {
                required: "질문은 필수 입력입니다.",
            },
        },
        submitHandler: function(form) {
            // form 전송 이외에 ajax등 어떤 동작이 필요할 때
            if (!confirm('정말 제출 하시겠습니까?')) {
                return;
            }
            form.submit();
        }
    });

    // $(window).bind('pageshow', function(event){
    //     if(event.originalEvent.persisted){
    //         if(!confirm('뒤로가면 초기화됨')){
    //             return;
    //         } else{
    //             window.history.back();
    //         }
    //     }
    // });

    history.pushState(null, null, location.href);
    window.onpopstate = function(event) {
        if (!confirm('뒤로가면 초기화됨')) {
            return;
        } else {
            window.history.back();
        }
    };
</script>