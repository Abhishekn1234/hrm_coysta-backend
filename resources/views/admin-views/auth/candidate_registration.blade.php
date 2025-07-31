<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ \App\CPU\translate('Cosysta | Candidate Registration') }}</title>
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
            @php($e_commerce_logo = \App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)
            <a class="d-flex justify-content-center mb-5" href="javascript:">
                <img class="z-index-2"
                    src="https://cosysta.com/wp-content/uploads/2024/12/cocysta-logo-new-_1_-removebg-preview-1.png"
                    alt="Logo" onerror="this.src='{{ asset('assets/back-end/img/400x400/img2.jpg') }}'">
            </a>

            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-8">
                    <div class="card card-lg mb-5">
                        <div class="card-body">
                            <form id="form-id" action="{{ route('admin.auth.candidate_registration') }}"
                                enctype="multipart/form-data" method="post">
                                @csrf
                                <div class="text-center">
                                    <div class="mb-5">
                                        <h1 class="display-4">Candidate Registration</h1>
                                    </div>
                                </div>

                                <h3 style="text-align:center">Personal Details :</h3><br>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="name">{{ \App\CPU\translate('name') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="name"
                                        placeholder="{{ \App\CPU\translate('name') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="email">{{ \App\CPU\translate('email') }}</label>
                                    <input type="email" class="form-control form-control-lg" name="email"
                                        placeholder="{{ \App\CPU\translate('email') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="phone">{{ \App\CPU\translate('phone') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="phone"
                                        placeholder="{{ \App\CPU\translate('phone') }}" required>
                                </div>






                                <div class="js-form-message form-group">
                                    <label class="input-label" for="place">{{ \App\CPU\translate('place') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="place"
                                        placeholder="{{ \App\CPU\translate('place') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="address">{{ \App\CPU\translate('address') }}</label>
                                    <textarea name="address" placeholder="Enter address" class="editor textarea form-control form-control-lg" cols="30"
                                        rows="10" required></textarea>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="gender">{{ \App\CPU\translate('gender') }}</label>
                                    <select name="gender" class="form-control" id="type_form" required>
                                        <option value="" selected disabled>Select gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="date_of_birth">{{ \App\CPU\translate('date_of_birth') }}</label>
                                    <input type="date" class="form-control form-control-lg" name="date_of_birth"
                                        placeholder="{{ \App\CPU\translate('date_of_birth') }}" required>
                                </div>

                                <br>
                                <h3 style="text-align:center">Educational Qualifications :</h3><br>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="qualification">{{ \App\CPU\translate('qualification') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="qualification"
                                        placeholder="{{ \App\CPU\translate('qualification') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="tenth_mark_percentage">10th mark (%)</label>
                                    <input type="number" class="form-control form-control-lg"
                                        name="tenth_mark_percentage"
                                        placeholder="{{ \App\CPU\translate('tenth_mark_percentage') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="twelveth_mark_percentage">12th mark (%)</label>
                                    <input type="number" class="form-control form-control-lg"
                                        name="twelveth_mark_percentage"
                                        placeholder="{{ \App\CPU\translate('twelveth_mark_percentage') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="degree_mark_percentage">Degree mark (%)</label>
                                    <input type="number" class="form-control form-control-lg"
                                        name="degree_mark_percentage"
                                        placeholder="{{ \App\CPU\translate('degree_mark_percentage') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="last_qualification_certificate">Last Qualification
                                        Certificate</label>
                                    <input accept=".pdf" type="file" class="form-control form-control-lg"
                                        name="last_qualification_certificate"
                                        placeholder="{{ \App\CPU\translate('last_qualification_certificate') }}">
                                </div>







                                <br>
                                <h3 style="text-align:center">Job Details :</h3><br>

                                <div class="js-form-message form-group">
                                    <label for="job_id">{{ \App\CPU\translate('Job') }}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="job_id" required>
                                        <option value="{{ null }}" selected disabled>Select Job</option>
                                        @foreach ($job as $b)
                                            <option value="{{ $b['id'] }}">{{ $b['job_title'] }} (
                                                {{ $b['job_type'] }} )</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="position">{{ \App\CPU\translate('position') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="position"
                                        placeholder="{{ \App\CPU\translate('position') }}" required>
                                </div>

                                <br>
                                <h3 style="text-align:center">Experience Details :</h3><br>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="experience">{{ \App\CPU\translate('experience') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="experience"
                                        placeholder="{{ \App\CPU\translate('experience') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="skills">{{ \App\CPU\translate('skills') }}</label>
                                    <input type="text" class="form-control form-control-lg" name="skills"
                                        placeholder="{{ \App\CPU\translate('skills') }}" required>
                                </div>

                                <br>
                                <h3 style="text-align:center">Portfolio Details :</h3><br>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="portfolio_link">Portfolio Link (GitHub/ Behance/
                                        etc.)</label>
                                    <input type="text" class="form-control form-control-lg" name="portfolio_link"
                                        placeholder="{{ \App\CPU\translate('portfolio_link') }}" required>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label"
                                        for="resume">{{ \App\CPU\translate('resume') }}</label>
                                    <input accept=".pdf" type="file" class="form-control form-control-lg"
                                        name="resume" placeholder="{{ \App\CPU\translate('resume') }}" required>
                                </div>

                                <br>
                                <h3 style="text-align:center">HR-Type Questions :</h3><br>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="strengths">What are your strengths?</label>
                                    <textarea name="strengths" placeholder="Enter strengths" class="editor textarea form-control form-control-lg"
                                        cols="30" rows="10" required></textarea>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="weaknesses">What are your weaknesses?</label>
                                    <textarea name="weaknesses" placeholder="Enter weaknesses" class="editor textarea form-control form-control-lg"
                                        cols="30" rows="10" required></textarea>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="goals">Where do you see yourself in 5
                                        years?</label>
                                    <textarea name="goals" placeholder="Enter goals" class="editor textarea form-control form-control-lg"
                                        cols="30" rows="10" required></textarea>
                                </div>

                                <div class="js-form-message form-group">
                                    <label class="input-label" for="willingness_to_relocate">Are you willing to
                                        relocate for this position if required?</label>
                                    <textarea name="willingness_to_relocate" placeholder="Enter willingness_to_relocate"
                                        class="editor textarea form-control form-control-lg" cols="30" rows="10" required></textarea>
                                </div>


                                <button type="submit"
                                    class="btn btn-lg btn-block btn-primary">{{ \App\CPU\translate('Register') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>

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
