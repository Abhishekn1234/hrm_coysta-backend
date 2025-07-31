<div id="headerMain" class="d-none">
    <header id="header" style="background-color: #041562"
        class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-flush navbar-container navbar-bordered">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value ?? '')
                <a class="navbar-brand" href="{{ route('admin.dashboard') }}" aria-label="">
                    <img class="navbar-brand-logo" style="max-height: 42px;min-width:100%;"
                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                        src="{{ asset("storage/app/public/company/$e_commerce_logo") }}" alt="Logo">
                    <img class="navbar-brand-logo-mini" style="max-height: 42px;min-width:100%;"
                        onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                        src="{{ asset("storage/app/public/company/$e_commerce_logo") }}" alt="Logo">
                </a>
            </div>

            <div class="navbar-nav-wrap-content-left">
                <button type="button" class="js-navbar-vertical-aside-toggle-invoker close mr-3">
                    <i class="tio-first-page navbar-vertical-aside-toggle-short-align" data-toggle="tooltip"
                        data-placement="right" title="Collapse"></i>
                    <i class="tio-last-page navbar-vertical-aside-toggle-full-align"
                        data-template='<div class="tooltip d-none d-sm-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                        data-toggle="tooltip" data-placement="right" title="Expand"></i>
                </button>
            </div>

            <div class="navbar-nav-wrap-content-right"
                style="{{ Session::get('direction') === 'rtl' ? 'margin-left:unset; margin-right: auto' : 'margin-right:unset; margin-left: auto' }}">
                <ul class="navbar-nav align-items-center flex-row">
                    @if(auth('customer')->check())
                        @php($user = auth('customer')->user())
                        <li class="nav-item">
                            <div class="hs-unfold">
                                <a class="js-hs-unfold-invoker navbar-dropdown-account-wrapper" href="javascript:;"
                                    data-hs-unfold-options='{
                                        "target": "#accountNavbarDropdown",
                                        "type": "css-animation"
                                    }'>
                                    <div class="avatar avatar-sm avatar-circle">
                                        <img class="avatar-img"
                                            onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                            src="{{ asset('storage/app/public/banner') }}/{{ $user->image }}"
                                            alt="Image Description">
                                        <span class="avatar-status avatar-sm-status avatar-status-success"></span>
                                    </div>
                                </a>

                                <div id="accountNavbarDropdown"
                                    class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-right navbar-dropdown-menu navbar-dropdown-account"
                                    style="width: 16rem;">
                                    <div class="dropdown-item-text">
                                        <div class="media align-items-center text-break">
                                            <div class="avatar avatar-sm avatar-circle mr-2">
                                                <img class="avatar-img"
                                                    onerror="this.src='{{ asset('assets/front-end/img/image-place-holder.png') }}'"
                                                    src="{{ asset('storage/app/public/banner') }}/{{ $user->image }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="media-body">
                                                <span class="card-title h5">{{ $user->name }}</span>
                                                <span class="card-text">{{ $user->user_type }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item"
                                        href="{{ route('admin.profile.update', $user->id) }}">
                                        <span class="text-truncate pr-2" title="Settings">Settings</span>
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" href="javascript:"
                                        onclick="Swal.fire({
                                            title: 'Do you want to logout?',
                                            showDenyButton: true,
                                            showCancelButton: true,
                                            confirmButtonColor: '#377dff',
                                            cancelButtonColor: '#363636',
                                            confirmButtonText: `Yes`,
                                            denyButtonText: `Don't Logout`,
                                        }).then((result) => {
                                            if (result.value) {
                                                location.href='{{ route('admin.auth.logout') }}';
                                            } else {
                                                Swal.fire('Canceled', '', 'info')
                                            }
                                        })">
                                        <span class="text-truncate pr-2" title="Sign out">Sign out</span>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </header>
</div>

<div id="headerFluid" class="d-none"></div>
<div id="headerDouble" class="d-none"></div>
