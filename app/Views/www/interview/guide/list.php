<input type="submit" value="<">
<h2>하이버프활용법</h2>
<div>
    <button>이용가이드</button>
    <button>FAQ </button>
    <button>샘플인터뷰</button>
</div>

<div id="guide">
    <h3>HI, GUIDE</h3>
    <p>쉽게 따라하는 하이버프 인터뷰 가이드</p>
    <br>

    <p>인터뷰를 시작하는 두 가지 방법</p>
    <div>
        <button class="tablinks" onclick="openKind(event, 'practice')">연습부터 할래요</button>
        <button class="tablinks" onclick="openKind(event, 'applay')">바로지원할래요</button>

        <div id="practice" class="tabcontent">
            <p>마음에 드는 영상이 나올때까지</p>
            <p>원하는 포지션으로 무제한 연습하자!</p>
            <p>짧은 준비단계</p>

            <div class="slideshow-container">

                <div class="mySlides fade">
                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl01.png" style="width:20%; height:100px ">
                    <div class="text">1. [새 인터뷰 시작] 버튼을 눌러주세요</div>
                </div>

                <div class="mySlides fade">

                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl02.png" style="width:20%; height:100px">
                    <div class="text">2. 희망 포지션을 선택해 주세요</div>
                </div>

                <div class="mySlides fade">

                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl04.png" style="width:20%; height:100px">
                    <div class="text">3. 프로필 이미지를 설정해 볼까요?</div>
                </div>

                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>

            </div>
            <br>

            <div style="text-align:center">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>

        </div>
        <div id="applay" class="tabcontent">
            <p>내 맘에 쏙 드는 채용 공고 발견!</p>
            <p>바로 5분 인터뷰 후 입사지원 완료하기</p>
            <p>짧은 준비단계</p>

            <div class="slideshow-container">
                <div class="mySlides fade">
                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl01.png" style="width:20%; height:100px">
                    <div class="text">1. 공고아래[지원하기] 버튼을 눌러주세요</div>
                </div>
                <div class="mySlides fade">
                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl02.png" style="width:20%; height:100px">
                    <div class="text">2. 지원방식을 선택해 주세요</div>
                </div>
                <div class="mySlides fade">
                    <img src="https://interview.highbuff.com/share/img/sub/ttrt_sl04.png" style="width:20%; height:100px">
                    <div class="text">3. 프로필 이미지를 설정해 볼까요?</div>
                </div>
                <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                <a class="next" onclick="plusSlides(1)">&#10095;</a>
            </div>
            <br>

            <div style="text-align:center">
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </div>
    </div>

    <div>
        <p>인터뷰 진행하기</p>

        <p>화면 상단에서 현재 질문 순서와 질문 내용, 촬영까지의 대기시간을 확인할 수 있어요.
            답변 준비가 완료되었다면 하단의 [바로 대답하기]를 눌러 촬영을 시작해 주세요!</p>

        <p>느려도 괜찮아요! 천천히 또박또박 말하기 </p>
        <p>살짝 미소를 띠면 매력도 측정이 올라가요 </p>
        <p>시선은 카메라 고정, 제스쳐는 활발하게! </p>

    </div>

    <div>
        <p>지금바로 시작해볼까요?</p>
        <button>+ 새인터뷰시작하기</button>
    </div>
</div>
<style>
    .tabcontent {
        display: none;

    }
</style>


<script>
    function openKind(evt, kind) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(kind).style.display = "block";
        evt.currentTarget.className += " active";
    }

    var slideIndex = 1;
    showSlides(slideIndex);

    function plusSlides(n) {
        showSlides(slideIndex += n);
    }

    function currentSlide(n) {
        showSlides(slideIndex = n);
    }

    function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
            slideIndex = 1
        }
        if (n < 1) {
            slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
            dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
    }
</script>