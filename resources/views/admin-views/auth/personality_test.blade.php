<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ \App\CPU\translate('Cosysta | Personality Test') }}</title>
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
                            <form id="personalitytest">
                                <div class="text-center">
                                    <div class="mb-5">
                                        <h1 class="display-4">Personality Test</h1>
                                    </div>
                                </div>

                                <h3 style="text-align:center">Candidate Name : {{ $candidate->name }}</h3><br>
                                <div id="questionsContainer"></div>
                                <button type="submit" class="btn btn-success mt-3">Submit</button>
                            </form>

                            <div id="result" class="mt-4"></div>
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

    <script>
        const questions = [
            // Type 1
            "I am often focused on getting things right and doing the morally correct thing.",
            "I have a strong inner critic that pushes me to improve constantly.",
            "I feel uncomfortable when things are out of order or imperfect.",
            "I often feel a sense of responsibility to correct mistakes around me.",

            // Type 2
            "I am happiest when I am helping others feel supported and cared for.",
            "I often anticipate other people’s needs before they express them.",
            "I feel uncomfortable when I think people don’t appreciate what I’ve done for them.",
            "I have a deep fear of feeling unwanted or unloved.",

            // Type 3
            "I am highly focused on setting and achieving goals.",
            "I tend to adapt my personality depending on the situation or people around me.",
            "I feel most successful when I receive recognition from others.",
            "I am afraid of failing or being seen as unsuccessful.",

            // Type 4
            "I often feel different from other people and sometimes misunderstood.",
            "I have a rich inner emotional life that others might not understand.",
            "I value authenticity and self-expression over fitting in.",
            "I am afraid of being insignificant or having no personal identity.",

            // Type 5
            "I am energized by learning new things and mastering complex concepts.",
            "I need plenty of time alone to process my thoughts and recharge.",
            "I am more comfortable observing than participating in social situations.",
            "I fear being seen as incompetent or uninformed.",

            // Type 6
            "I am constantly preparing for the worst-case scenario.",
            "I have a strong desire for security and stability.",
            "I am loyal to people and organizations I trust.",
            "I am afraid of being abandoned or left alone.",

            // Type 7
            "I am excited by new opportunities and experiences.",
            "I struggle to stay focused on one thing for long periods.",
            "I am good at finding the positive side of difficult situations.",
            "I fear being trapped in boredom or discomfort.",

            // Type 8
            "I am comfortable taking charge and speaking my mind.",
            "I tend to confront problems directly rather than avoid them.",
            "I don’t like feeling controlled by others.",
            "I am afraid of being vulnerable or weak.",

            // Type 9
            "I am good at understanding and accommodating different perspectives.",
            "I often avoid conflict to keep the peace.",
            "I feel most comfortable when everyone around me is harmonious.",
            "I fear conflict and separation from others."
        ];

        const questionsContainer = document.getElementById("questionsContainer");

        // Render Questions
        questions.forEach((q, index) => {
            const div = document.createElement("div");
            div.classList.add("form-group");
            div.innerHTML = `
                    <label>${index + 1}. ${q}</label>
                    <select name="q${index + 1}" class="form-control" required>
                        <option value="">Select</option>
                        <option value="1">Strongly Disagree</option>
                        <option value="2">Disagree</option>
                        <option value="3">Neutral</option>
                        <option value="4">Agree</option>
                        <option value="5">Strongly Agree</option>
                    </select>
                `;
            questionsContainer.appendChild(div);
        });

        // Form Submission
        document.getElementById("personalitytest").addEventListener("submit", function(e) {
            e.preventDefault();

            const scores = Array(9).fill(0);

            // Calculate Scores
            for (let i = 0; i < questions.length; i++) {
                const typeIndex = Math.floor(i / 4);
                const value = parseInt(document.querySelector(`[name="q${i + 1}"]`).value) || 0;
                scores[typeIndex] += value;
            }

            // Determine Type
            const highestScore = Math.max(...scores);
            const typeIndex = scores.indexOf(highestScore) + 1;

            const types = ["Reformer", "Helper", "Achiever", "Individualist", "Investigator", "Loyalist",
                "Enthusiast", "Challenger", "Peacemaker"
            ];

            // Display Result
            // document.getElementById("result").innerHTML = `
        //     <div class="alert alert-info">
        //         <h5>Your Enneagram Type: <strong>Type ${typeIndex} - ${types[typeIndex - 1]}</strong></h5>
        //         <p>Score: ${highestScore}</p>
        //     </div>
        // `;

            const result_typeindex = typeIndex;
            const result_type = types[typeIndex - 1];
            const result_highestScore = highestScore;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }
            });
            $.ajax({
                url: "{{ route('admin.auth.personality_test_submit') }}",
                method: 'POST',
                data: {
                    id: <?= $candidate->id ?>,
                    result_typeindex: result_typeindex,
                    result_type: result_type,
                    result_highestScore: result_highestScore
                },
                success: function(data) {
                    toastr.success('Test Completed successfully. We will contact you...');
                    location.reload();
                }
            });
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
