<!doctype html>
<html lang="en">

@include('back.inc.head')
<link href="https://fonts.googleapis.com/css2?family=Alexandria:wght@400;500;700&display=swap" rel="stylesheet">


<body>
    <style>
        body,
        .form-label,
        .form-control,
        .btn,
        h5,
        p,
        label {
            font-family: 'Alexandria', sans-serif !important;
        }
    </style>

    <!--wrapper-->
    <div class="wrapper">
        <!--wrapper-->
        <div class="wrapper">
            <div class="section-authentication-cover">
                <div class="">
                    <div class="row g-0">

                        <div
                            class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                            <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                                <div class="card-body">
                                    <img src="{{ url('back/assets/images/freepik__a-man-sitting-on-a-chair-at-a-desk-working-on-a-co__65725.png') }}"
                                        class="img-fluid auth-img-cover-login" width="650" alt="" />
                                </div>
                            </div>
                        </div>

                        <div
                            class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
                            <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                                <div class="card-body p-sm-5" dir="rtl">
                                    <div class="">
                                        <div class="mb-3 text-center">
                                            <img src="{{ url('back/assets/images/مستر أحمد مكاوي -01.png') }}"
                                                width="130" alt="">
                                        </div>
                                        <div class="text-center mb-4">
                                            <h5 class="">جيو مكاوي</h5>
                                            <p class="mb-0" style="font-size: 15px">يرجى تسجيل الدخول إلى حسابك</p>
                                        </div>
                                        <div class="form-body">
                                            <form method="POST" action="{{ route('login') }}">
                                                @csrf
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label for="email" class="form-label">البريد
                                                            الإلكتروني</label>
                                                        <input id="email" type="email"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            name="email" value="{{ old('email') }}" required
                                                            autocomplete="email" autofocus>
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                    <div class="col-12">
                                                        <label for="password" class="form-label">كلمة المرور</label>
                                                        <div class="input-group" id="show_hide_password">
                                                            <input id="password" type="password"
                                                                class="form-control @error('password') is-invalid @enderror border-end-0"
                                                                name="password" required
                                                                autocomplete="current-password">
                                                            <a href="javascript:;"
                                                                class="input-group-text bg-transparent"><i
                                                                    class="bx bx-hide"></i></a>
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-check form-switch">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="remember" id="flexSwitchCheckChecked"
                                                                {{ old('remember') ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="flexSwitchCheckChecked">تذكرني</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="d-grid">
                                                            <button
                                                                style="    background-color: #FFB97C;
    border-color: #FFB97C;"
                                                                type="submit" class="btn btn-primary">تسجيل
                                                                الدخول</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>

        <!--end wrapper-->
    </div>
    </div>
    </div>
    @include('back.inc.scripts')
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
</body>

</html>
