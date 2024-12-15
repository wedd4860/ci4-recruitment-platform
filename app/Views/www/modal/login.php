<div id="login_mb" class="pop_modal">
    <!--s popBox-->
    <div class="popBox">
        <!--s pop_cont-->
        <div class="pop_cont c">
            <div class="login_logo"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/login_logo.png"></div>
            <div class="login_img"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/login_img.png" class="wps_100"></div>

            <!--s log_memBtn-->
            <div class="log_memBtn">
                <a href="/join" class="ib">일반회원</a>
                <a href="#n" class="gy">기업회원</a>
            </div>
            <!--e log_memBtn-->

            <!--s kakaoBtn-->
            <div class="kakaoBtn">
                <a href="javascript:;" data-sns="kakao" class="btn-sns"><span class="icon"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/kakao_icon.png"></span>카카오로 3초만에 시작하기</a>
            </div>
            <!--e kakaoBtn-->

            <!--s sns_loginBox-->
            <div class="sns_loginBox">
                <div class="tlt">다른 방식으로 로그인하기 </div>

                <!--s sns_loginUl-->
                <ul class="sns_loginUl">
                    <li><a href="javascript:;" data-sns="google" class="btn-sns sns_google"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/sns_google.png"></a></li>
                    <li><a href="javascript:;" data-sns="naver" class="btn-sns sns_naver"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/sns_naver.png"></a></li>
                    <li><a href="javascript:;" data-sns="apple" class="btn-sns sns_apple"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/sns_apple.png"></a></li>
                    <li><a href="/login" class="sns_email"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/sns_email.png"></a></li>
                </ul>
                <!--e sns_loginUl-->
            </div>
            <!--e sns_loginBox-->

            <!--s id_psBtn-->
            <div class="id_psBtn">
                <a href="lost_id.php">아이디/비밀번호 찾기</a>
            </div>
            <!--e id_psBtn-->
        </div>
        <!--e pop_cont-->

        <a href="#n" class="login_close"><img src="<?= $data['url']['menu'] ?>/static/www/img/sub/login_close.png"></a>
    </div>
    <!--e popBox-->
</div>

<script>
    $(document).ready(function() {
        $('.login_pop_open').modal({
            target: '#login_mb',
            speed: 350,
            easing: 'easeInOutExpo',
            animation: 'bottom',
            //position: '5% auto',
            overlayClose: false,
            close: '.login_close'
        });
    });

    const snsUrl = {
        'kakao': {
            'link': () => {
                window.open('<?= $data['sns']['url']['kakao'] ?>', '_blank', 'width=480,height=640');
            }
        },
        'naver': {
            'link': () => {
                window.open('<?= $data['sns']['url']['naver'] ?>', '_blank', 'width=480,height=640');

            }
        },
        'google': {
            'link': () => {
                window.open('<?= $data['sns']['url']['google'] ?>', '_blank', 'width=480,height=640');
            }
        },
        'apple': {
            'link': () => {
                location.href = '<?= $data['sns']['url']['apple'] ?>';
            }
        }
    }

    $('.btn-sns').on("click", function() {
        const snsName = $(this).data("sns");
        snsUrl[snsName].link();
    })
</script>