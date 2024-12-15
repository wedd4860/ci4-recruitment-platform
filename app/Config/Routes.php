<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}


/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Interview\MainController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// interview
$routes->get('/', 'Interview\MainController::main'); //메인 
$routes->group('/', function ($routes) {

    $routes->group('short', function ($routes) {
        $routes->get('(:alphanum)', 'Both\ShortenUrlController::index/$1'); //단축uirl
    });

    $routes->group('report', function ($routes) {
        $routes->add('/', 'Interview\Report\ReportController::index');
        $routes->add('detail/(:any)', 'Interview\Report\ReportController::detail/$1');
        $routes->add('fail/(:any)', 'Interview\Report\ReportController::fail/$1');
        $routes->add('test', 'Interview\Report\ReportController::test');
        $routes->post('deleteAction', 'Interview\Report\ReportController::applierDeleteAction');
        $routes->post('updateAction', 'Interview\Report\ReportController::applierUpdateAction');
        $routes->group('share', function ($routes) {
            $routes->add('/', 'Interview\Report\ReportController::applierShare');
            $routes->Post('action', 'Interview\Report\ReportController::applierShareAction');
        });
    });

    $routes->group('help', function ($routes) {
        // $routes->group('event', function ($routes) {
        //     $routes->get('', 'Interview\Help\EventController::list'); //이벤트 리스트
        //     $routes->get('(:num)', 'Interview\Help\EventController::detail/$1'); //이벤트 디테일
        // });
        $routes->add('faq', 'Interview\Help\FaqController::list'); //faq
        $routes->group('qna', ['filter' => 'www-login'], function ($routes) {
            $routes->add('', 'Interview\Help\QnaController::list'); //qna list
            $routes->group('write', function ($routes) {
                $routes->add('', 'Interview\Help\QnaController::write'); //qna 글쓰기페이지
                $routes->post('action', 'Interview\Help\QnaController::writeAction'); //qna 글쓰기
            });
        });
        $routes->add('guide/interview', 'Interview\Help\GuideController::interview'); //guide
        $routes->add('sample/list', 'Interview\Help\SampleController::list'); //샘플
    });
    $routes->group('jobs', function ($routes) {
        $routes->add('list', 'Interview\Jobs\JobsController::list'); //채용공고 리스트
        $routes->get('detail/(:num)', 'Interview\Jobs\JobsController::detail/$1'); //채용공고 디테일 (공고번호)
        $routes->post('detailAction', 'Interview\Jobs\JobsController::detailAction'); //인터뷰 디테일 내용 저장 페이지
        $routes->get('apply', 'Interview\Jobs\JobsController::apply', ['filter' => 'www-login']); //인터뷰 지원하기 (공고번호/내인터뷰지원인지,기업인터뷰지원인지)
        $routes->get('complete', 'Interview\Jobs\JobsController::complete', ['filter' => 'www-login']); //공고지원완료페이지
        $routes->post('jobApplyAction', 'Interview\Jobs\JobsController::jobApplyAction'); //공고지원완료 submit
        $routes->post('changeInterview', 'Interview\Jobs\JobsController::changeInterview'); //공고지원 (인터뷰변경 ajax)
        $routes->post('changeResume', 'Interview\Jobs\JobsController::changeResume'); //공고지원 (인터뷰변경 ajax)
        $routes->post('applyAtOnce', 'Interview\Jobs\JobsController::applyAtOnce'); //한번에 여러개 지원
    });
    $routes->group('search', function ($routes) {
        $routes->add('/', 'Interview\Search\SearchController::search'); //검색화면
        $routes->get('deleteAction', 'Interview\Search\SearchController::deleteKeyword'); //검색화면
        $routes->get('action', 'Interview\Search\SearchController::searchAction'); // 검색어 입력
    });
    $routes->group('interview', function ($routes) {
        $routes->add('home', 'Interview\Interview\Guide\GuideController::homeGuideList'); //이용가이드
        $routes->get('preview/(:num)', 'Interview\Interview\InterviewController::preview/$1'); //실제 면접 질문 엿보기 디테일
        $routes->add('ready', 'Interview\Interview\InterviewController::ready'); //면접 시작 전 가이드
        $routes->add('type', 'Interview\Interview\InterviewController::type'); //면접 시작 전 카테고리 고르기
        $routes->post('typeAction', 'Interview\Interview\InterviewController::typeAction'); //면접 시작 전 카테고리 insert
        $routes->add('profile/(:num)/(:num)', 'Interview\Interview\InterviewController::profile/$1/$2'); //면접 시작 전 사진 찍는 방법 선택
        $routes->group('profile', function ($routes) {
            $routes->add('photo/(:num)/(:num)', 'Interview\Interview\InterviewController::photo/$1/$2'); //면접 시작 전 사진 찍기
            $routes->post('setProfileAction', 'Interview\Interview\InterviewController::setProfileAction'); // 면접 시작 전 앨범에서 사진 고르고 넘기기
            $routes->add('check/(:num)/(:num)/(:num)', 'Interview\Interview\InterviewController::check/$1/$2/$3'); // 면접 시작 전 사진 확인
            $routes->post('albumAction', 'Interview\Interview\InterviewController::albumAction'); // 면접 시작 전 앨범에서 사진 고르고 넘기기
            $routes->add('exist/(:num)/(:num)', 'Interview\Interview\InterviewController::exist/$1/$2'); //면접 시작 전 기존 프로필에서 고르기
        });
        $routes->add('mic/(:num)/(:num)', 'Interview\Interview\InterviewController::mic/$1/$2'); //면접 시작 전 음성인식
        $routes->post('skipMicAction', 'Interview\Interview\InterviewController::skipMicAction'); //면접 시작 전 음성인식 건너뛰기
        $routes->add('timer/(:num)/(:num)', 'Interview\Interview\InterviewController::timer/$1/$2'); //면접 시작 전 셀프 타이머 선택
        $routes->post('timerAction', 'Interview\Interview\InterviewController::timerAction'); //면접 시작 전 셀프 타이머 db update
        $routes->add('start/(:num)/(:num)', 'Interview\Interview\InterviewController::start/$1/$2'); //면접 시작
        $routes->add('end/(:num)/(:num)', 'Interview\Interview\InterviewController::end/$1/$2'); //면접 끝
    });
    $routes->group('rest', function ($routes) {
        $routes->add('list', 'Interview\Rest\RestController::list'); //쉬어가는 가벼운글 리스트
        $routes->get('detail/(:num)', 'Interview\Rest\RestController::detail/$1'); //쉬어가는 가벼운글 디테일
    });
    $routes->group('my', ['filter' => 'www-login'], function ($routes) {
        $routes->get('scrap/(:alpha)', 'Interview\My\Scrap\ScrapListController::list/$1'); //스크랩(카트)

        $routes->add('leave', 'Interview\Member\MemberLeaveController::index'); //회원탈퇴
        $routes->add('leave/(:alphanum)', 'Interview\Member\MemberLeaveController::leave/$1'); //회원탈퇴
        $routes->post('leave/step2/action', 'Interview\Member\MemberLeaveController::memberLeaveAction');
        $routes->post('leave/password/action', 'Interview\Member\MemberLeaveController::memberLeavePwdCheckAction');

        // $routes->add('notice', 'Interview\My\Scrap\NoticeController::index'); //공지사항

        $routes->group('suggest', function ($routes) {
            $routes->add('/', 'Interview\My\Scrap\SuggestController::index'); //제안
            $routes->add('detail/(:any)', 'Interview\My\Scrap\SuggestController::detail/$1'); //제안
            $routes->post('accept/(:any)', 'Interview\My\Scrap\SuggestController::suggestAccept/$1'); //대면 인터뷰 제안 수락
            $routes->post('acceptInterview/(:any)', 'Interview\My\Scrap\SuggestController::suggestAcceptInterview/$1'); //인터뷰 제안 수락
            $routes->post('refuse/(:any)', 'Interview\My\Scrap\SuggestController::suggestRefuse/$1'); //제안 거절
        });
    });

    $routes->group('board', function ($routes) {
        $routes->add('notice', 'Interview\Board\NoticeController::index'); //공지사항
        $routes->group('event', function ($routes) {
            $routes->get('', 'Interview\Board\EventController::list'); //이벤트 리스트
            $routes->get('(:num)', 'Interview\Board\EventController::detail/$1'); //이벤트 디테일
        });
    });

    $routes->group('company', function ($routes) {
        $routes->add('practice', 'Interview\Company\Practice\PracticeListController::list'); //모의면접
        $routes->get('tag', 'Interview\Company\Tag\TagListController::list'); // 태그별 기업        
        $routes->get('detail/(:num)', 'Interview\Company\CompanyController::detail/$1'); // 기업 상세
        $routes->get('explore', 'Interview\Company\CompanyController::explore');
    });
  
    $routes->group('sns', function ($routes) {
        $routes->add('(:alpha)/web/call', 'Interview\Sns\Login\CallActionController::web/$1'); //sns 리다이렉트 페이지
    });
});

//html 가이드
$routes->get('/html', 'HtmlGuideController::index'); //html 가이드 

// api
$routes->group('/api', ['filter' => 'throttle'], function ($routes) {
    $routes->group('auth', function ($routes) {
        $routes->post('tel', 'API\Auth\TelController::create');
        $routes->add('tel/(:any)/(:num)', 'API\Auth\TelController::inquire/$1/$2');

        $routes->post('find/(:alpha)/id', 'API\Auth\Find\FindController::findId/$1');
        $routes->post('find/(:alpha)/password', 'API\Auth\Find\FindController::findPassword/$1');
    });
    $routes->group('send', function ($routes) {
        $routes->post('message/(:alpha)', 'API\Send\MessageController::show/$1');
    });
    $routes->group('my', ['filter' => 'www-login'], function ($routes) {
        $routes->add('scrap/delete/(:alpha)/(:num)/(:num)', 'API\My\Scrap\ScrapController::delete/$1/$2/$3');
        $routes->add('scrap/add/(:alpha)/(:num)/(:num)', 'API\My\Scrap\ScrapController::create/$1/$2/$3');
    });
    $routes->group('applier', ['filter' => 'www-login'], function ($routes) {
        $routes->post('file/upload/thumb/add', 'API\Applier\Upload\Thumb\FileController::create');
        $routes->post('mic/upload/check', 'API\Applier\Upload\Mic\MicController::check');
        $routes->post('file/upload/video', 'API\Applier\Upload\Thumb\FileController::updateInterview');
    });
    $routes->group('recruit', function ($routes) {
        $routes->add('(:alpha)/apply/(:num)', 'API\Recruit\Apply\ApplyController::show/$1/$2');
    });
    $routes->group('interview', ['filter' => 'www-login'], function ($routes) {
        $routes->add('mic/check', 'API\Interview\Mic\MicController::check');
    });
});

// api
$routes->group('/api', ['filter' => 'throttle-error'], function ($routes) {
    $routes->group('error', function ($routes) {
        $routes->post('page/(:alpha)/add', 'API\Error\Page\ErrorController::create/$1');
    });
});
//download
$routes->group('/', ['filter' => 'throttle'], function ($routes) {
    $routes->get('filedown', '\App\Models\File::download'); //다운로드
});
// interview, biz 로그아웃
$routes->group('/logout', function ($routes) {
    $routes->add('/', 'Auth\AuthController::logout');
});

// 회원의 마이페이지
$routes->group('/mypage', function ($routes) {
    $routes->add('main', 'Interview\Mypage\MypageController::main');
    $routes->add('modify', 'Interview\Mypage\MypageController::modify');
    $routes->group('modify', function ($routes) {
        $routes->add('interest', 'Interview\Mypage\MypageController::interest');
        $routes->post('action', 'Interview\Mypage\MypageController::interestAction');
    });
});

// interview, biz 로그인 페이지
$routes->group('/login', function ($routes) {
    $routes->add('/', 'Auth\AuthController::login');
    $routes->post('action', 'Auth\AuthController::loginAction');
    $routes->add('sns/action/(:alpha)/(:any)', 'Auth\AuthController::snsLoginCheck/$1/$2');
    $routes->group('find', ['filter' => 'www-not-login'], function ($routes) {
        $routes->add('/', 'Auth\AuthController::find');
        $routes->add('(:alpha)/id', 'Auth\AuthController::findId/$1');
        $routes->add('(:alpha)/pwd', 'Auth\AuthController::findPwd/$1');
    });
    $routes->group('reset', function ($routes) {
        $routes->add('(:alpha)/pwd', 'Auth\AuthController::reset/$1');
        $routes->add('action', 'Auth\AuthController::resetAction');
    });
});
// interview, 회원가입페이지
$routes->group('/join', ['filter' => 'www-not-login'], function ($routes) {
    $routes->add('/', 'Auth\AuthController::join');
    $routes->get('sns', 'Auth\AuthController::snsJoin');
    $routes->post('action', 'Auth\AuthController::joinAction');
    $routes->post('sns/action/(:alpha)', 'Auth\AuthController::joinAction/$1');
});
// 문자 메세지 발송
$routes->group('/smsSend', function ($routes) {
    $routes->add('/', 'Auth\AuthController::smsSend');
});
// interview, 회원가입 관심사
$routes->group('/interest', ['filter' => 'www-login'], function ($routes) {
    $routes->add('/', 'Auth\AuthController::interest');
    $routes->post('action', 'Auth\AuthController::interestAction');
});
// admin
$routes->group('/prime/login', ['filter' => 'admin-login'], function ($routes) {
    $routes->add('', 'Auth\AuthController::adminLogin');
    $routes->post('action', 'Auth\AuthController::adminLoginAction');
});
$routes->group('/prime', ['filter' => 'admin-main'], function ($routes) {
    $routes->add('/', 'Auth\AuthController::adminMain');
    $routes->add('main', 'Admin\MainController::main');
    $routes->get('logout', 'Auth\AuthController::logout');
    //member
    $routes->group('member', function ($routes) {
        $routes->get('list/(:alpha)', 'Admin\Member\MemberListController::list/$1');
        $routes->add('write/(:num)', 'Admin\Member\MemberWriteController::write/$1');
        $routes->post('write/action', 'Admin\Member\MemberActionController::memberAction');
    });
    //config
    $routes->group('config', function ($routes) {
        //add rull
        $routes->addPlaceholder('customConfig', '\bterms\b|\bagreement\b|\bprivate\b');
        $routes->add('write/(:customConfig)', 'Admin\Config\ConfigWriteController::configWrite/$1');
        $routes->add('write/action', 'Admin\Config\ConfigActionController::configAction');
    });
    //qna
    $routes->group('qna', function ($routes) {
        $routes->get('list', 'Admin\Qna\QnaListController::list');
        $routes->get('write/(:num)', 'Admin\Qna\QnaWriteController::write/$1');
        $routes->post('write/action', 'Admin\Qna\QnaActionController::qnaAction');
    });
    //faq
    $routes->group('faq', function ($routes) {
        $routes->get('list', 'Admin\Faq\FaqListController::list');
        $routes->post('write/action', 'Admin\Faq\FaqActionController::writeAction');
        $routes->post('del', 'Admin\Faq\FaqActionController::faqDel');
    });
    //board
    $routes->group('board', function ($routes) {
        //add rull
        $routes->addPlaceholder('customNumWord', '[0-9]|\ball\b'); //숫자,all
        //게시판설정 리스트
        //write
        $routes->get('(:alpha)/write', 'Admin\Board\BoardWriteController::index/$1');
        $routes->get('(:alpha)/write/(:num)', 'Admin\Board\BoardWriteController::index/$1/$2');
        //action
        $routes->post('(:alpha)/write/action', 'Admin\Board\BoardActionController::index/$1');
        $routes->post('(:alpha)/comment/action', 'Admin\Board\BoardActionController::commentAction/$1');
        $routes->add('(:alpha)/comment/del/(:num)', 'Admin\Board\BoardActionController::commentDel/$1/$2');
        //게시판 list
        $routes->get('list', 'Admin\Board\BoardListController::list');
        $routes->get('list/(:alpha)', 'Admin\Board\BoardListController::list/$1');
        $routes->get('set/list', 'Admin\Board\BoardListController::setList');
        //read
        $routes->get('read/(:any)', 'Admin\Board\BoardReadController::read/$1');
        //read
        $routes->get('(:alpha)/read/(:num)', 'Admin\Board\BoardReadController::read/$1/$2');
    });
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
