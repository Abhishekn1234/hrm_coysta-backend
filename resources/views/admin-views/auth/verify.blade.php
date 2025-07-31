<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ \App\CPU\translate('TaskMaster | Verify') }}</title>
    <link rel="shortcut icon" type="image/x-icon" href="https://www.edxera.com/assets/images/favicon.png">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/vendor/icon-set/style.css">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/toastr.css">

    <style>
        .input-icons i {
            position: absolute;
            cursor: pointer;
        }

        .input-icons {
            width: 100%;
            margin-bottom: 10px;
        }

        .icon {
            padding: 9% 0 0 0;
            min-width: 40px;
        }

        .input-field {
            width: 94%;
            padding: 10px 0 10px 10px;
            text-align: center;
            border-right-style: none;
        }
    </style>
</head>

<body>
    <main id="content" role="main" class="main">
        <div class="position-fixed top-0 right-0 left-0 bg-img-hero"
            style="height: 32rem; background-image: url({{ asset('assets/admin') }}/svg/components/abstract-bg-4.svg);">
            <figure class="position-absolute right-0 bottom-0 left-0">
                <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                    viewBox="0 0 1921 273">
                    <polygon fill="#fff" points="0,273 1921,273 1921,0 " />
                </svg>
            </figure>
        </div>

        <div class="container py-5 py-sm-7">
            <label class="badge badge-soft-success float-right"
                style="z-index: 9;position: absolute;right: 0.5rem;top: 0.5rem;">{{ \App\CPU\translate('Software version') }}
                : {{ env('SOFTWARE_VERSION') }}</label>
            @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)
            <a class="d-flex justify-content-center mb-5" href="javascript:">
                <img class="z-index-2"
                    src="https://cosysta.com/wp-content/uploads/2024/12/cocysta-logo-new-_1_-removebg-preview-1.png"
                    alt="Logo" onerror="this.src='{{ asset('assets/back-end/img/400x400/img2.jpg') }}'">
            </a>

            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="card card-lg mb-5">
                        <div class="card-body">
                            <form id="form-id" action="{{ route('admin.auth.verify') }}" method="post">
                                @csrf
                                <div class="text-center">
                                    <div class="mb-5">
                                        <h1 class="display-4">TaskMaster</h1><br>
                                        <h3>OTP Verification</h3>
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $user->id }}" name="id">

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="otp">* Please Provide Verification Otp Sent in
                                        your email</label>
                                    <input type="number" class="form-control form-control-lg" name="otp"
                                        tabindex="1" placeholder="{{ \App\CPU\translate('enter otp') }}"
                                        aria-label="otp" required
                                        data-msg="Please Provide Verification Otp Sent in your email">
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="signupSrPassword" tabindex="0">
                                        <span class="d-flex justify-content-between align-items-center">
                                            Re-enter Password
                                        </span>
                                    </label>

                                    <div class="input-group input-group-merge">
                                        <input type="password" autocomplete="new-password"
                                            class="js-toggle-password form-control form-control-lg" name="password"
                                            id="signupSrPassword" placeholder="Re-enter Password"
                                            aria-label="Re-enter Password" required
                                            data-msg="Your password is invalid. Please try again."
                                            data-hs-toggle-password-options='{
                                                    "target": "#changePassTarget",
                                                    "defaultClass": "tio-hidden-outlined",
                                                    "showClass": "tio-visible-outlined",
                                                    "classChangeTarget": "#changePassIcon"
                                            }'>

                                        <div id="changePassTarget" class="input-group-append">
                                            <a class="input-group-text" href="javascript:">
                                                <i id="changePassIcon" class="tio-visible-outlined"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="btn btn-lg btn-block btn-primary">{{ \App\CPU\translate('Verify') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('assets/back-end') }}/js/vendor.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/theme.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/js/toastr.js"></script>
    {!! Toastr::message() !!}

    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}', Error, {
                    CloseButton: true,
                    ProgressBar: true
                });
            @endforeach
        </script>
    @endif

    @if (isset($recaptcha) && $recaptcha['status'] == 1)
        <script type="text/javascript">
            var onloadCallback = function() {
                grecaptcha.render('recaptcha_element', {
                    'sitekey': '{{ \App\CPU\Helpers::get_business_settings('recaptcha')['site_key'] }}'
                });
            };
        </script>
        <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
        <script>
            $("#form-id").on('submit', function(e) {
                var response = grecaptcha.getResponse();

                if (response.length === 0) {
                    e.preventDefault();
                    toastr.error("{{ \App\CPU\translate('Please check the recaptcha') }}");
                }
            });
        </script>
    @else
        <script type="text/javascript">
            function re_captcha() {
                $url = "{{ URL('/admin/auth/code/captcha') }}";
                $url = $url + "/" + Math.random();
                document.getElementById('default_recaptcha_id').src = $url;
                console.log('url: ' + $url);
            }
        </script>
    @endif

    <script>
        $(document).on('ready', function() {
            $('.js-toggle-password').each(function() {
                new HSTogglePassword(this).init()
            });

            $('.js-validate').each(function() {
                $.HSCore.components.HSValidation.init($(this));
            });
        });
    </script>
</body>

</html>
