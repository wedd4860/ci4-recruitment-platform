<?php
// echo "<pre>";
// print_r($data);
?>
<div>
    <!--s headBox-->
    <div class="headBox">
        <!--s logoBox-->
        <div class="logoBox">
            <a href="/new/main.php">
                <div class="logo"><img src="<?= $data['url']['menu'] ?>/static/www/img/inc/logo.png"></div>
                <!--s txtBox-->
                <div class="txtBox">
                    <?php if ($data['session']['idx']) : ?>
                        <div class="name"><span class="logo_hi"><img src="<?= $data['url']['menu'] ?>/static/www/img/inc/logo_hi.png"></span> <span class="logo_name"><?= $data['session']['name'] ?></span></div>
                        <?php endif; ?>
                        <div class="logo_txt"><img src="<?= $data['url']['menu'] ?>/static/www/img/inc/logo_txt.png"></div>
                </div>
                <!--e txtBox-->
            </a>
        </div>
        <!--e logoBox-->

        <!--s rBox-->
        <div class="rBox">
            <!--s hd_alarm-->
            <div class="hd_alarm">
                <a href="alarm_list.php">
                    <span class="icon"><img src="<?= $data['url']['menu'] ?>/static/www/img/inc/hd_alarm_icon.png"></span>
                    <span class="new">N</span>
                </a>
            </div>
            <!--e hd_alarm-->

            <!--s hd_mypage-->
            <div class="hd_mypage">
                <a href="mypage.php"><span class="icon"><img src="<?= $data['url']['menu'] ?>/static/www/img/inc/hd_mypage_icon.png"></span></a>
            </div>
            <!--e hd_mypage-->
        </div>
        <!--e rBox-->
    </div>
    <!--e headBox-->

    <!--s main_slBox-->
    <div class="main_slBox">
        <!--s main_sl-->
        <div class="main_sl">
            <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/main_slimg01.jpg"></a></div>
            <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/main_slimg02.jpg"></a></div>
            <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/main_slimg01.jpg"></a></div>
            <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/main_slimg02.jpg"></a></div>
        </div>
        <!--e main_sl-->

        <!--s 도트버튼-->
        <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
            <span class="slider__label sr-only"></span>
        </div>
        <!--e 도트버튼-->
    </div>
    <!--e main_slBox-->

    <!--s #mcontent-->
    <div id="mcontent">
        <!--s cont-->
        <div class="cont">
            <!--s m_shBox-->
            <div class="m_shBox">
                <a href="#n">
                    <div class="iconBox"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/m_sh_icon.png"></div>
                    <div class="txtBox">공고명, 포지션, 기업명으로 검색해 보세요! </div>
                </a>
            </div>
            <!--e m_shBox-->

            <?php
            if (!$data['session']['idx']) :
            ?>
                <!--s position_bn-->
                <div class="position_bn">
                    <!-- 클릭시 로그인 팝업 -->
                    <a href="#n" class="login_pop_open">
                        <img src="<?= $data['url']['menu'] ?>/static/www/img/main/position_bn.png">
                    </a>
                </div>
                <!--e position_bn-->
            <?php endif; ?>
            <!--s mtltBox-->
            <div class="mtltBox mt_t70">
                <div class="mtlt">어떤 포지션에서 일하고 싶나요?</div>
                <div class="more_btn"><a href="interests_save.php">더보기 <i class="la la-plus"></i></a></div>
            </div>
            <!--e mtltBox-->

            <!--s position_ckBox-->
            <div class="position_ckBox">
                <ul>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a1" name="a">
                            <label for="a1">경영·사무</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a2" name="a">
                            <label for="a2">고객상담</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a3" name="a">
                            <label for="a3">IT인터넷</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a4" name="a">
                            <label for="a4">경영·사무</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a5" name="a">
                            <label for="a5">고객상담</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a6" name="a">
                            <label for="a6">IT인터넷</label>
                        </div>
                    </li>
                    <li>
                        <div class="ck_radio">
                            <input type="checkbox" id="a7" name="a">
                            <label for="a7">IT인터넷</label>
                        </div>
                    </li>
                </ul>
            </div>
            <!--e position_ckBox-->

            <!--s alarmBox-->
            <div class="alarmBox">
                <a href="#n">
                    <div class="tlt point"><span class="icon"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/m_alarm_icon.png"></span>기업에서 인터뷰 제안이 도착했어요!</div>
                    <div class="product_desc">[ UI/UX 기획자 모집]</div>
                    <span class="arrow_icon"></span>
                </a>
            </div>
            <!--e alarmBox-->
        </div>
        <!--e cont-->

        <!--s mtltBox-->
        <div class="mtltBox mt_t70 cont">
            <div class="mtlt">
                부산.수영구에서<br />
                웹기획.PM 찾는중
            </div>
            <div class="more_btn"><a href="#n">더보기 <i class="la la-plus"></i></a></div>
        </div>
        <!--e mtltBox-->

        <!--s lkfBox-->
        <div class="lkfBox cont">
            <!--s lkfUl-->
            <div class="lkfUl">
                <!--s lkf_sl-->
                <div class="lkf_sl">
                    <!--s 무한루프-->
                    <!--s item-->
                    <div class="item">
                        <!--s itemBox-->
                        <div class="itemBox">
                            <a href="#n">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>
                            </a>

                            <!--s txtBox-->
                            <div class="txtBox">
                                <a href="#n">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                </a>

                                <!--s gtxtBox-->
                                <div class="gtxtBox">
                                    <div class="gdata">D-13</div>

                                    <!--s gBtnBox-->
                                    <div class="gBtnBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gBtn"><a href="#n">지원하기</a></div>
                                    </div>
                                    <!--e gBtnBox-->
                                </div>
                                <!--e gtxtBox-->
                            </div>
                            <!--e txtBox-->

                            <!--s bookmark_iconBox-->
                            <div class="bookmark_iconBox">
                                <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                            </div>
                            <!--e bookmark_iconBox-->
                        </div>
                        <!--e itemBox-->
                    </div>
                    <!--e item-->

                    <!--s item-->
                    <div class="item">
                        <!--s itemBox-->
                        <div class="itemBox">
                            <a href="#n">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>
                            </a>

                            <!--s txtBox-->
                            <div class="txtBox">
                                <a href="#n">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                </a>

                                <!--s gtxtBox-->
                                <div class="gtxtBox">
                                    <div class="gdata">D-13</div>

                                    <!--s gBtnBox-->
                                    <div class="gBtnBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gBtn"><a href="#n">지원하기</a></div>
                                    </div>
                                    <!--e gBtnBox-->
                                </div>
                                <!--e gtxtBox-->
                            </div>
                            <!--e txtBox-->

                            <!--s bookmark_iconBox-->
                            <div class="bookmark_iconBox">
                                <button class="bookmark_icon off" tabindex="0"><span class="blind">스크랩</span></button>
                            </div>
                            <!--e bookmark_iconBox-->
                        </div>
                        <!--e itemBox-->
                    </div>
                    <!--e item-->

                    <!--s item-->
                    <div class="item">
                        <!--s itemBox-->
                        <div class="itemBox">
                            <a href="#n">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>
                            </a>

                            <!--s txtBox-->
                            <div class="txtBox">
                                <a href="#n">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                </a>

                                <!--s gtxtBox-->
                                <div class="gtxtBox">
                                    <div class="gdata">D-13</div>

                                    <!--s gBtnBox-->
                                    <div class="gBtnBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gBtn"><a href="#n">지원하기</a></div>
                                    </div>
                                    <!--e gBtnBox-->
                                </div>
                                <!--e gtxtBox-->
                            </div>
                            <!--e txtBox-->

                            <!--s bookmark_iconBox-->
                            <div class="bookmark_iconBox">
                                <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                            </div>
                            <!--e bookmark_iconBox-->
                        </div>
                        <!--e itemBox-->
                    </div>
                    <!--e item-->
                    <!--e 무한루프-->
                </div>
                <!--e lkf_sl-->
            </div>
            <!--e lkfUl-->
        </div>
        <!--e lkfBox-->



        <!--s company_fdBox-->
        <div class="company_fdBox">
            <!--s mtltBox-->
            <div class="mtltBox mt_t70">
                <div class="mtlt cont">내 맘에 쏙 드는 회사 찾기</div>
            </div>
            <!--e mtltBox-->

            <!--s company_fdcont-->
            <div class="company_fdcont">
                <!--s cont-->
                <div class="cont">

                    <!--s company_fd_slBox-->
                    <div class="company_fd_slBox">
                        <!--s company_fd_sl-->
                        <div class="company_fd_sl">
                            <!--s 무한루프-->
                            <div class="item">
                                <!--s company_fd_txtBox-->
                                <div class="company_fd_txtBox">
                                    <!--s txtBox-->
                                    <div class="txtBox">
                                        <div class="tlt">침대에서 책상까지, 출퇴근 5초 컷 </div>
                                        <div class="tag"># 재택근무</div>
                                    </div>
                                    <!--e txtBox-->

                                    <div class="crcBox"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/cfd_crc01.png"></div>
                                </div>
                                <!--e company_fd_txtBox-->

                                <!--s company_fd_wBox-->
                                <div class="company_fd_wBox">
                                    <!--s ul-->
                                    <ul>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집모집모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon on"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                    </ul>
                                    <!--e ul-->

                                    <!--s more_btn-->
                                    <div class="more_btn">
                                        <a href="#n">재택근무 더보기 <span class="arrow"><i class="la la-angle-right"></i></span></a>
                                    </div>
                                    <!--e more_btn-->
                                </div>
                                <!--e company_fd_wBox-->
                            </div>

                            <div class="item">
                                <!--s company_fd_txtBox-->
                                <div class="company_fd_txtBox">
                                    <!--s txtBox-->
                                    <div class="txtBox">
                                        <div class="tlt">침대에서 책상까지, 출퇴근 5초 컷 </div>
                                        <div class="tag"># 재택근무</div>
                                    </div>
                                    <!--e txtBox-->

                                    <div class="crcBox"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/cfd_crc01.png"></div>
                                </div>
                                <!--e company_fd_txtBox-->

                                <!--s company_fd_wBox-->
                                <div class="company_fd_wBox">
                                    <!--s ul-->
                                    <ul>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집모집모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon on"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                    </ul>
                                    <!--e ul-->

                                    <!--s more_btn-->
                                    <div class="more_btn">
                                        <a href="#n">재택근무 더보기 <span class="arrow"><i class="la la-angle-right"></i></span></a>
                                    </div>
                                    <!--e more_btn-->
                                </div>
                                <!--e company_fd_wBox-->
                            </div>

                            <div class="item">
                                <!--s company_fd_txtBox-->
                                <div class="company_fd_txtBox">
                                    <!--s txtBox-->
                                    <div class="txtBox">
                                        <div class="tlt">침대에서 책상까지, 출퇴근 5초 컷 </div>
                                        <div class="tag"># 재택근무</div>
                                    </div>
                                    <!--e txtBox-->

                                    <div class="crcBox"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/cfd_crc01.png"></div>
                                </div>
                                <!--e company_fd_txtBox-->

                                <!--s company_fd_wBox-->
                                <div class="company_fd_wBox">
                                    <!--s ul-->
                                    <ul>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집모집모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon on"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#n">
                                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                                <!--s txtBox-->
                                                <div class="txtBox">
                                                    <div class="tlt">㈜ 기업명</div>
                                                    <div class="product_desc">UX/UX디자이너 모집</div>
                                                    <div class="gtxt">서울.강남구 <span>|</span> 신입 </div>
                                                </div>
                                                <!--e txtBox-->

                                                <!--s bookmark_iconBox-->
                                                <div class="bookmark_iconBox">
                                                    <button class="bookmark_icon off"><span class="blind">스크랩</span></button>
                                                </div>
                                                <!--e bookmark_iconBox-->
                                            </a>
                                        </li>
                                    </ul>
                                    <!--e ul-->

                                    <!--s more_btn-->
                                    <div class="more_btn">
                                        <a href="#n">재택근무 더보기 <span class="arrow"><i class="la la-angle-right"></i></span></a>
                                    </div>
                                    <!--e more_btn-->
                                </div>
                                <!--e company_fd_wBox-->
                            </div>
                            <!--s 무한루프-->
                        </div>
                        <!--e company_fd_sl-->
                    </div>
                    <!--e company_fd_slBox-->
                </div>
                <!--e cont-->
            </div>
            <!--e company_fdcont-->
        </div>
        <!--s company_fdBox-->

        <!--s gu_mvBox-->
        <div class="gu_mvBox cont">
            <a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/gu_mv_bn.png"></a>
        </div>
        <!--e gu_mvBox-->

        <!--s mtltBox-->
        <div class="mtltBox mt_t70 cont">
            <div class="mtlt">요즘 뜨는 기업에서<br />팀원 모집 중</div>
        </div>
        <!--e mtltBox-->

        <!--s team_mbBox-->
        <div class="team_mbBox cont">
            <!--s team_mb_Ul-->
            <div class="team_mb_Ul">
                <!--s team_mb_sl-->
                <div class="team_mb_sl">
                    <!--s 무한루프-->
                    <!--s item-->
                    <div class="item">
                        <a href="#n">
                            <!--s itemBox-->
                            <div class="itemBox">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                <!--s txtBox-->
                                <div class="txtBox">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                    <div class="stxt">내 인터뷰로 지원 가능</div>

                                    <div class="gtxtBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gdata">D-13</div>
                                    </div>
                                </div>
                                <!--e txtBox-->

                                <!--s bookmark_iconBox-->
                                <div class="bookmark_iconBox">
                                    <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                                </div>
                                <!--e bookmark_iconBox-->
                            </div>
                            <!--e itemBox-->
                        </a>
                    </div>
                    <!--e item-->

                    <!--s item-->
                    <div class="item">
                        <a href="#n">
                            <!--s itemBox-->
                            <div class="itemBox">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                <!--s txtBox-->
                                <div class="txtBox">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                    <div class="stxt">내 인터뷰로 지원 가능</div>

                                    <div class="gtxtBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gdata">D-13</div>
                                    </div>
                                </div>
                                <!--e txtBox-->

                                <!--s bookmark_iconBox-->
                                <div class="bookmark_iconBox">
                                    <button class="bookmark_icon off" tabindex="0"><span class="blind">스크랩</span></button>
                                </div>
                                <!--e bookmark_iconBox-->
                            </div>
                            <!--e itemBox-->
                        </a>
                    </div>
                    <!--e item-->

                    <!--s item-->
                    <div class="item">
                        <a href="#n">
                            <!--s itemBox-->
                            <div class="itemBox">
                                <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg"></div>

                                <!--s txtBox-->
                                <div class="txtBox">
                                    <div class="tlt">㈜ 기업명</div>
                                    <div class="product_desc">UX/UX디자이너 모집모집모집모집모집</div>
                                    <div class="stxt">내 인터뷰로 지원 가능</div>

                                    <div class="gtxtBox">
                                        <div class="gtxt">서울.강남구 <span>|</span> 신입</div>
                                        <div class="gdata">D-13</div>
                                    </div>
                                </div>
                                <!--e txtBox-->

                                <!--s bookmark_iconBox-->
                                <div class="bookmark_iconBox">
                                    <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                                </div>
                                <!--e bookmark_iconBox-->
                            </div>
                            <!--e itemBox-->
                        </a>
                    </div>
                    <!--e item-->
                    <!--e 무한루프-->
                </div>
                <!--e team_mb_sl-->
            </div>
            <!--e team_mb_Ul-->
        </div>
        <!--e team_mbBox-->

        <!--s mtltBox-->
        <div class="mtltBox mt_t70 cont">
            <div class="mtlt">실제 면접 질문 연습하기</div>
        </div>
        <!--e mtltBox-->

        <!--s qsBox-->
        <div class="qsBox">
            <!--s qs_sl-->
            <div class="qs_sl">
                <!--s 무한루프-->
                <!--s item-->
                <div class="item">
                    <a href="question_view.php">
                        <div class="tlt">
                            카카오에서<br />
                            개발자에게 묻는 <br />
                            5가지
                        </div>
                        <div class="Btn"><i class="la la-angle-right"></i></div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="question_view.php">
                        <div class="tlt">
                            힐튼 호텔에서<br />
                            디렉터 찾는중
                        </div>
                        <div class="Btn"><i class="la la-angle-right"></i></div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="question_view.php">
                        <div class="tlt">
                            카카오에서<br />
                            개발자에게 묻는 <br />
                            5가지
                        </div>
                        <div class="Btn"><i class="la la-angle-right"></i></div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="question_view.php">
                        <div class="tlt">
                            힐튼 호텔에서<br />
                            디렉터 찾는중
                        </div>
                        <div class="Btn"><i class="la la-angle-right"></i></div>
                    </a>
                </div>
                <!--e item-->
                <!--e 무한루프-->
            </div>
            <!--e qs_sl-->
        </div>
        <!--e qsBox-->

        <!--s cont-->
        <div class="cont">
            <!--s mtltBox-->
            <div class="mtltBox mt_t70">
                <div class="mtlt">
                    여기 추천해요 !<br />
                    <span class="point">김세민</span>님과 핏이 잘 맞는 기업
                </div>
            </div>
            <!--e mtltBox-->

            <!--s perfitUl-->
            <ul class="perfitUl">
                <!--s 무한루프-->
                <li>
                    <!--s itemBox-->
                    <div class="itemBox">
                        <a href="#n">
                            <div class="img">
                                <span class="ai_txt">AI 추천</span>
                                <img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg">
                            </div>
                        </a>

                        <!--s txtBox-->
                        <div class="txtBox">
                            <a href="#n">
                                <div class="tlt">㈜ 기업명</div>
                            </a>
                            <div class="gtxt">금융 <span>|</span> 서울.강남구</div>

                            <!--s gBtn-->
                            <div class="gBtn">
                                <a href="#n">채용공고 2건 보러가기</a>
                            </div>
                            <!--e gBtn-->
                        </div>
                        <!--e txtBox-->

                        <!--s bookmark_iconBox-->
                        <div class="bookmark_iconBox">
                            <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                        </div>
                        <!--e bookmark_iconBox-->
                    </div>
                    <!--e itemBox-->
                </li>
                <li>
                    <!--s itemBox-->
                    <div class="itemBox">
                        <a href="#n">
                            <div class="img">
                                <span class="ai_txt">AI 추천</span>
                                <img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img.jpg">
                            </div>
                        </a>

                        <!--s txtBox-->
                        <div class="txtBox">
                            <a href="#n">
                                <div class="tlt">㈜ 기업명</div>
                            </a>
                            <div class="gtxt">금융 <span>|</span> 서울.강남구</div>

                            <!--s gBtn-->
                            <div class="gBtn">
                                <a href="#n">채용공고 2건 보러가기</a>
                            </div>
                            <!--e gBtn-->
                        </div>
                        <!--e txtBox-->

                        <!--s bookmark_iconBox-->
                        <div class="bookmark_iconBox">
                            <button class="bookmark_icon on" tabindex="0"><span class="blind">스크랩</span></button>
                        </div>
                        <!--e bookmark_iconBox-->
                    </div>
                    <!--e itemBox-->
                </li>
                <!--e 무한루프-->
            </ul>
            <!--e perfitUl-->

            <!--s perfit_moreBtn-->
            <div class="perfit_moreBtn">
                <a href="perfit.php">4개 기업 더보기 <span class="arrow"><i class="la la-angle-right"></i></span></a>
            </div>
            <!--e perfit_moreBtn-->
        </div>
        <!--e cont-->


        <!--s perfit_bn-->
        <div class="perfit_bn cont">
            <a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/perfit_bn.png"></a>
        </div>
        <!--e perfit_bn-->

        <!--s mtltBox-->
        <div class="mtltBox mt_t70 cont">
            <div class="mtlt">
                <span class="tlt_span">다들 어떻게 인터뷰했을까?<br />AI 레포트 구경가기</span>
                <span class="toolp_span"><a href="#n" class="ai_report_pop_open">?</a></span>
            </div>
            <div class="more_btn"><a href="#n">더보기 <i class="la la-plus"></i></a></div>
        </div>
        <!--e mtltBox-->

        <!--s ai_reportBox-->
        <div class="ai_reportBox">
            <!--s ai_report_sl-->
            <div class="ai_report_sl c">
                <!--s 무한루프-->
                <!--s item-->
                <div class="item">
                    <a href="#n">
                        <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/pic_test.jpg"></div>
                        <div class="classBox"><span class="point">A</span>등급 / <span class="point">65</span>점</div>
                        <div class="jopBox">IT – 솔루션 영업</div>
                        <div class="psBox">상위 <span class="point">10%</span></div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="#n">
                        <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/pic_test.jpg"></div>
                        <div class="classBox"><span class="point"></span>등급 / <span class="point"></span>점</div>
                        <div class="jopBox">직무</div>
                        <div class="psBox">전체 지원자 중 위치</div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="#n">
                        <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/pic_test.jpg"></div>
                        <div class="classBox"><span class="point">A</span>등급 / <span class="point">65</span>점</div>
                        <div class="jopBox">IT – 솔루션 영업</div>
                        <div class="psBox">상위 <span class="point">10%</span></div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="#n">
                        <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/pic_test.jpg"></div>
                        <div class="classBox"><span class="point"></span>등급 / <span class="point"></span>점</div>
                        <div class="jopBox">직무</div>
                        <div class="psBox">전체 지원자 중 위치</div>
                    </a>
                </div>
                <!--e item-->

                <!--s item-->
                <div class="item">
                    <a href="#n">
                        <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/pic_test.jpg"></div>
                        <div class="classBox"><span class="point">A</span>등급 / <span class="point">65</span>점</div>
                        <div class="jopBox">IT – 솔루션 영업</div>
                        <div class="psBox">상위 <span class="point">10%</span></div>
                    </a>
                </div>
                <!--e item-->
                <!--e 무한루프-->
            </div>
            <!--e ai_report_sl-->
        </div>
        <!--e ai_reportBox-->

        <!--s cont-->
        <div class="cont">
            <!--s mtltBox-->
            <div class="mtltBox mt_t70">
                <div class="mtlt">쉬어가는 가벼운 글</div>
                <div class="more_btn"><a href="light_writing_list.php">더보기 <i class="la la-plus"></i></a></div>
            </div>
            <!--e mtltBox-->

            <!--s gbwBox-->
            <div class="gbwBox">
                <ul>
                    <li>
                        <a href="light_writing_view.php">
                            <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img02.jpg"></div>
                            <div class="txt">
                                제 취미가 왜 궁금하세요?<br />
                                면접관 심리 들여다보기
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="light_writing_view.php">
                            <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img02.jpg"></div>
                            <div class="txt">
                                제 취미가 왜 궁금하세요?<br />
                                면접관 심리 들여다보기
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="light_writing_view.php">
                            <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img02.jpg"></div>
                            <div class="txt">
                                제 취미가 왜 궁금하세요?<br />
                                면접관 심리 들여다보기
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="light_writing_view.php">
                            <div class="img"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/test_img02.jpg"></div>
                            <div class="txt">
                                제 취미가 왜 궁금하세요?<br />
                                면접관 심리 들여다보기
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            <!--e gbwBox-->

            <!--s mbottom_bn_slBox-->
            <div class="mbottom_bn_slBox">
                <!--s control_box-->
                <div class="control_box">
                    <a href="#n">
                        <span class="pagingInfo"></span>
                        <span class="more_btn"><i class="la la-plus"></i></span>
                    </a>
                </div>
                <!--e control_box-->

                <!--s mbottom_bn_sl-->
                <div class="mbottom_bn_sl">
                    <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/mbottom_bn01.jpg"></a></div>
                    <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/mbottom_bn02.jpg"></a></div>
                    <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/mbottom_bn01.jpg"></a></div>
                    <div class="item"><a href="#n"><img src="<?= $data['url']['menu'] ?>/static/www/img/main/mbottom_bn02.jpg"></a></div>
                </div>
                <!--e mbottom_bn_sl-->
            </div>
            <!--e mbottom_bn_slBox-->

        </div>
        <!--e cont-->
    </div>
    <!--e #mcontent-->

    <!--s AI 레포트 구경가기 모달-->
    <div id="ai_report_mb" class="pop_modal">
        <!--s pop_Box-->
        <div class="spop_Box c">
            <!--s pop_cont-->
            <div class="spop_cont">
                <div class="tlt">AI 레포트 구경하기</div>

                <div class="txt">
                    잘 나온 인터뷰의 AI레포트는<br />
                    공개설정 > [내 레포트 자랑하기] 를 통해<br />
                    공개할 수 있어요!
                </div>

                <div class="stxt">
                    *공개 설정 전인 레포트는 <br />
                    오직 나만 볼 수 있으니 안심하세요
                </div>
            </div>
            <!--e pop_cont-->
            <div class="okBox spop_close">확인</div>
        </div>
        <!--e pop_Box-->
    </div>
    <!--e AI 레포트 구경가기 모달-->

    <script>
        $(document).ready(function() {
            //AI 레포트 구경가기 모달
            $('.ai_report_pop_open').modal({
                target: '#ai_report_mb',
                speed: 350,
                easing: 'easeInOutExpo',
                animation: 'bottom',
                //position: '5% auto',
                overlayClose: false,
                close: '.spop_close'
            });
        });
    </script>