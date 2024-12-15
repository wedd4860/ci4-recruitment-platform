<!--s 레이어팝업-->
<div class="Modal2 pop_modal">
    <!--s pop_Box-->
    <div class="pop_Box chek_box">
        <!--s pop_cont-->
        <div class="pop_cont">
            <div class="pop_tlt">이용약관</div>

            <!--s pop_txt-->
            <div class="pop_txt">
                <!--s pop_scroll_box-->
                <div class="pop_scroll_box">
                    <div class="tlt">이용약관</div>
                    <?=$data['config']['agreement']?>
                </div>
                <!--e pop_scroll_box-->
            </div>
            <!--e pop_txt-->
        </div>
        <!--e pop_cont-->
        <a class="pop_close"><i class="la la-times"></i></a>
    </div>
    <!--e pop_Box-->
</div>
<!--e 레이어팝업-->

<script>
    $(document).ready(function() { //레이어팝업
        $('.pop_chek02').modal({ //개인정보취급방침
            target: '.Modal2',
            speed: 350,
            easing: 'easeInOutExpo',
            animation: 'bottom',
            position: '5% auto',
            //overlayClose : true
            close: '.pop_close'
        });
    });
</script>