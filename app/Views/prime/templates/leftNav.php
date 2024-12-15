        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block"><?= $data['session']['id'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/config/write/terms" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>이용약관</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/config/write/agreement" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>개인정보처리방침</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/config/write/private" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>개인정보수집 동의항목 설정</p>
                            </a>
                        </li>
                       
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?> /prime/member/list/m" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>회원목록</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/board/set/list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>게시판설정</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/board/list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>게시판리스트</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/faq/list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>FAQ</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $data['url']['www'] ?>/prime/qna/list" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Q&A</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>