<style>
    .navbar-vertical .nav-link {
        color: #041562;
    }

    .navbar .nav-link:hover {
        color: #041562;
    }

    .navbar .active>.nav-link,
    .navbar .nav-link.active,
    .navbar .nav-link.show,
    .navbar .show>.nav-link {
        color: #F14A16;
    }

    .navbar-vertical .active .nav-indicastor-icon,
    .navbar-vertical .nav-link:hover .nav-indicator-icon,
    .navbar-vertical .show>.nav-link>.nav-indicator-icon {
        color: #F14A16;
    }

    .nav-subtitle {
        display: block;
        color: #041562;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03125rem;
    }

    .side-logo {
        background-color: #ffffff;
    }

    .nav-sub {
        background-color: #ffffff !important;
    }

    .nav-indicator-icon {
        margin-left: {{ Session::get('direction') === 'rtl' ? '6px' : '' }};
    }

    th {
        color: #111 !important;
    }

    td {
        color: #111 !important;
    }
</style>

<div id="sidebarMain" class="d-none">
    <aside
        style="background: #ffffff!important; text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo" style="height: fit-content;">
                    @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)
                    <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="Front"
                        style="padding-top:30px;">
                        <img style="max-height: 100%;width:100px!important;"
                            onerror="this.src='{{ asset('assets/back-end/img/900x400/img1.jpg') }}'"
                            class="navbar-brand-logo-mini for-web-logo"
                            src="{{ asset('storage/company/logo_new.webp') }}"
                            alt="Logo">
                    </a>
                    <button type="button"
                        class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <div class="navbar-vertical-content mt-2">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <?php //$user_type = auth('customer')->user()->user_type; ?>
                        <?php $user_type = auth('customer')->user()->user_type ?? 'GUEST'; ?>


                        <!--<li class="nav-item">-->
                        <!--    <small class="nav-subtitle" title="">{{ \App\CPU\translate('Accounts Management') }}</small>-->
                        <!--    <small class="tio-more-horizontal nav-subtitle-replacer"></small>-->
                        <!--</li> -->

                        <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{ route('admin.dashboard') }}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{ \App\CPU\translate('Dashboard') }}
                                </span>
                            </a>
                        </li>

                        <!-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/document/*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Document
                                    Management</span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/document/*') ? 'block' : 'none' }}">

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_offer_letters') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_offer_letters') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Offer Letter') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_releiving_letters') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_releiving_letters') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Releiving Letters') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_warning_letters') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_warning_letters') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Warning Letters') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_termination_letters') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_termination_letters') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Termination Letters') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_experiences') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_experiences') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Experience Certificate') }}</span>
                                    </a>
                                </li>

                                <li
                                    class="nav-item {{ Request::is('admin/document/add_certificates') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.document.add_certificates') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Other Certificates') }}</span>
                                    </a>
                                </li>

                            </ul>
                        </li> -->


                        @if ($user_type != 'STAFF')
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/staff/list') || Request::is('admin/staff/view*') || Request::is('admin/hire/list') || Request::is('admin/hire_request*') || Request::is('admin/staff/*') || Request::is('admin/certificate*') || Request::is('admin/interview/*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Staff
                                        Management</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/staff/list') || Request::is('admin/staff/view*') || Request::is('admin/staff/edit*') || Request::is('admin/hire/list') || Request::is('admin/hire_request*') || Request::is('admin/staff/*') || Request::is('admin/certificate*') || Request::is('admin/interview/*') ? 'block' : 'none' }}">
                                    @if (
                                        $user_type == 'ADMIN' ||
                                            $user_type == 'CEO' ||
                                            $user_type == 'SCRUM_MASTER' ||
                                            $user_type == 'HR' ||
                                            $user_type == 'TEAM_LEAD')
                                        <li class="nav-item {{ Request::is('admin/staff/*') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.staff.list') }}">
                                                <i class="tio-user nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('staffs') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (
                                        $user_type == 'ADMIN' ||
                                            $user_type == 'CEO' ||
                                            $user_type == 'CLIENT' ||
                                            $user_type == 'PRODUCT_OWNER' ||
                                            $user_type == 'TEAM_LEAD')
                                        <li class="nav-item {{ Request::is('admin/hire/list') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.hire.list') }}">
                                                <i class="tio-poi-user nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Hire Staffs') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if ($user_type == 'ADMIN' || $user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                        <li class="nav-item {{ Request::is('admin/hire_request*') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.hire_request.list') }}">
                                                <i class="tio-poi-user nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Hire Requests') }}</span>
                                            </a>
                                        </li>

                                        <li class="nav-item {{ Request::is('admin/certificate*') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.certificate.list') }}">
                                                <i class="tio-poi-user nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Certificates') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    @if (
                                        $user_type == 'PRODUCT_OWNER' ||
                                            $user_type == 'HR' ||
                                            $user_type == 'TEAM_LEAD' ||
                                            $user_type == 'TECHNICAL_LEAD' ||
                                            $user_type == 'STAFF' ||
                                            $user_type == 'CEO')
                                        <li class="nav-item {{ Request::is('admin/interview/*') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.interview.list') }}">
                                                <i class="tio-poi-user nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Interviews</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </li> -->
                        @endif

                        @if (
                            $user_type == 'ADMIN' ||
                                $user_type == 'CEO' ||
                                $user_type == 'SCRUM_MASTER' ||
                                $user_type == 'HR' ||
                                $user_type == 'TEAM_LEAD')
                                
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/client*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Client
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/client*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/client*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.client.list') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('clients') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                        @endif

                        @if ($user_type == 'CEO' || $user_type == 'CLIENT' || $user_type == 'TEAM_LEAD' || $user_type == 'MARKETING_MANAGER')
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/lead/*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Lead
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/lead/*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/lead/*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.lead.list') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('leads') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                        @endif

                        @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/candidate*') || Request::is('admin/job*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Candidate
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/candidate*') || Request::is('admin/job*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/candidate*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.candidate.list') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('candidates') }}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{ Request::is('admin/job*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.job.list') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('jobs') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                        @endif

                        @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD' || $user_type == 'CLIENT')
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/proposal*') || Request::is('admin/quotation/*') || Request::is('admin/invoice/*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Accounts
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/proposal*') || Request::is('admin/quotation/*') || Request::is('admin/invoice/*') ? 'block' : 'none' }}">

                                    <li class="nav-item {{ Request::is('admin/proposal*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.proposal.list') }}">
                                            <i class="tio-poi-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Proposals') }}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{ Request::is('admin/quotation/*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.quotation.list') }}">
                                            <i class="tio-poi-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Quotations') }}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{ Request::is('admin/invoice/*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.invoice.list') }}">
                                            <i class="tio-poi-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('invoices') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                        @endif

                        <!-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/project*') || Request::is('admin/backlog*') || Request::is('admin/task*') || Request::is('admin/testcase*') || Request::is('admin/stafftask*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Project
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/project*') || Request::is('admin/backlog*') || Request::is('admin/task*') || Request::is('admin/testcase*') || Request::is('admin/stafftask*') ? 'block' : 'none' }}">

                                @if (
                                    $user_type == 'ADMIN' ||
                                        $user_type == 'CEO' ||
                                        $user_type == 'SCRUM_MASTER' ||
                                        $user_type == 'HR' ||
                                        $user_type == 'TEAM_LEAD' ||
                                        $user_type == 'STAFF' ||
                                        $user_type == 'TECHNICAL_LEAD' ||
                                        $user_type == 'PRODUCT_OWNER' ||
                                        $user_type == 'CLIENT')
                                    <li class="nav-item {{ Request::is('admin/project*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.project.list') }}">
                                            <i class="tio-shop nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('projects') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item {{ Request::is('admin/backlog*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.backlog.list') }}">
                                        <i class="tio-receipt-outlined nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Backlogs') }}</span>
                                    </a>
                                </li>

                                @if ($user_type != 'PRODUCT_OWNER' && $user_type != 'CLIENT')
                                    <li class="nav-item {{ Request::is('admin/task*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.task.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('my tasks') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($user_type != 'STAFF' && $user_type != 'HR')
                                    <li class="nav-item {{ Request::is('admin/stafftask*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.stafftask.list') }}">
                                            <i class="tio-credit-cards nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('staff tasks') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($user_type == 'TEAM_LEAD' || $user_type == 'QAQC')
                                    <li class="nav-item {{ Request::is('admin/testcase*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.testcase.list') }}">
                                            <i class="tio-filter-list nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-tr.uncate">{{ \App\CPU\translate('test cases') }}</span>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li> -->

                        <!-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/post_usage_report*') || Request::is('admin/punching_report*') || Request::is('admin/quotation_report*') || Request::is('admin/invoice_report*') || Request::is('admin/salary_report*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Reports
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/post_usage_report*') || Request::is('admin/punching_report*') || Request::is('admin/quotation_report*') || Request::is('admin/invoice_report*') || Request::is('admin/salary_report*') ? 'block' : 'none' }}">

                                @if ($user_type == 'PRODUCT_OWNER' || $user_type == 'CLIENT' || $user_type == 'TEAM_LEAD' || $user_type == 'CEO')
                                    <li
                                        class="nav-item {{ Request::is('admin/post_usage_report*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.post_usage_report.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Post Usage Report') }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if ($user_type != 'CLIENT' && $user_type != 'PRODUCT_OWNER')
                                    <li class="nav-item {{ Request::is('admin/punching_report*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.punching_report.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Punching Report') }}</span>
                                        </a>
                                    </li>
                                @endif

                                <li class="nav-item {{ Request::is('admin/salary_report*') ? 'active' : '' }}">
                                    <a class="nav-link " href="{{ route('admin.salary_report.list') }}">
                                        <i class="tio-chat nav-icon"></i>
                                        <span
                                            class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Salary Report') }}</span>
                                    </a>
                                </li>

                                @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                    <li
                                        class="nav-item {{ Request::is('admin/quotation_report*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.quotation_report.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Quotation Report') }}</span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{ Request::is('admin/invoice_report*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.invoice_report.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Invoice Report') }}</span>
                                        </a>
                                    </li>
                                @endif

                            </ul>
                        </li> -->

                        @if ($user_type == 'TEAM_LEAD')
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/point_setting*') || Request::is('admin/leader_board*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Leader
                                        Board Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/point_setting*') || Request::is('admin/leader_board*') ? 'block' : 'none' }}">

                                    @if ($user_type == 'CEO' || $user_type == 'TEAM_LEAD')
                                        <li
                                            class="nav-item {{ Request::is('admin/point_setting*') ? 'active' : '' }}">
                                            <a class="nav-link " href="{{ route('admin.point_setting.list') }}">
                                                <i class="tio-chat nav-icon"></i>
                                                <span
                                                    class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Point Setting') }}</span>
                                            </a>
                                        </li>
                                    @endif

                                    <li class="nav-item {{ Request::is('admin/leader_board*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.leader_board.list') }}">
                                            <i class="tio-chat nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Leader Board') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                        @endif


                        @if (
                            $user_type == 'ADMIN' ||
                                $user_type == 'CEO' ||
                                $user_type == 'SCRUM_MASTER' ||
                                $user_type == 'HR' ||
                                $user_type == 'TEAM_LEAD')
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/inventory*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Inventory
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/inventory*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/inventory*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.inventory.list') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('inventory') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/hrm*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">HRM</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/hrm*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/products*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.hrm') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">HRM</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/people*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">People</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/people*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/products*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.people') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">People</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            
                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/products*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Inventory
                                        Management</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/products*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/products*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.products') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Inventory Management') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            

                            <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/inventory_new*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Inventory Group
                                        </span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/inventory_new*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/inventory_new*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.inventory_new') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Inventory Group') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            
                            
                            <!-- <li
                                class="navbar-vertical-aside-has-menu {{ Request::is('admin/inventory_new*') ? 'active' : '' }}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                    href="javascript:">
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Project Planning</span>
                                </a>

                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{ Request::is('admin/inventory_planning*') ? 'block' : 'none' }}">


                                    <li class="nav-item {{ Request::is('admin/inventory_planning*') ? 'active' : '' }}">
                                        <a class="nav-link " href="{{ route('admin.inventory_planning') }}">
                                            <i class="tio-user nav-icon"></i>
                                            <span
                                                class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('project_planning') }}</span>
                                        </a>
                                    </li>

                                </ul>
                            </li> -->
                        @endif



                       <li class="navbar-vertical-aside-has-menu {{ Request::is('admin/profile*') ? 'active' : '' }}">
    <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Profile Management</span>
    </a>
    <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
        style="display: {{ Request::is('admin/profile*') ? 'block' : 'none' }}">
        @if (auth('customer')->check())
            <li class="nav-item {{ Request::is('admin/profile*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.profile.update', auth('customer')->user()->id) }}">
                    <i class="tio-key nav-icon"></i>
                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Settings') }}</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">
                    <i class="tio-key nav-icon"></i>
                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{ \App\CPU\translate('Login') }}</span>
                </a>
            </li>
        @endif
    </ul>
</li>
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/crm*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">CRM
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/crm*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/crm*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.crm') }}">
                                        <i class="tio-key nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('CRM') }}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        <!-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/pos*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">POS
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/pos*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/pos*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.pos') }}">
                                        <i class="tio-key nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('POS') }}
                                        </span>
                                    </a>
                                </li>

                            </ul>

                            
                        </li> -->
                         <!-- <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/purchase*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Purchase
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/purchase*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/purchase*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.purchase') }}">
                                        <i class="tio-key nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('Purchase Management') }}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li> -->
                        <li
                            class="navbar-vertical-aside-has-menu {{ Request::is('admin/purchase*') ? 'active' : '' }}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">Quotation
                                    Management</span>
                            </a>

                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{ Request::is('admin/quotation*') ? 'block' : 'none' }}">

                                <li class="nav-item {{ Request::is('admin/quotation*') ? 'active' : '' }}">
                                    <a class="nav-link" href="{{ route('admin.quotation') }}">
                                        <i class="tio-key nav-icon"></i>
                                        <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{ \App\CPU\translate('Quotation Management') }}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>
                        

                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </ul>
                </div>
            </div>
        </div>
    </aside>
</div>
