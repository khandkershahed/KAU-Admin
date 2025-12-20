<x-admin-guest-layout>
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed',
                    text: @json($errors->first()),
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

    <div class="d-flex flex-column flex-lg-row flex-column-fluid">

        <div class="d-flex flex-column flex-lg-row-auto w-xl-600px positon-xl-relative" style="background-color: #d5d0c8">
            <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-600px scroll-y">
                <div class="d-flex flex-row-fluid flex-column text-center p-10 pt-lg-20">

                    <a href="{{ route('homepage') }}" class="py-9 mb-5">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo-2.svg') }}" class="h-60px" />
                    </a>

                    <h1 class="fw-bolder fs-2qx pb-5 pb-md-10" style="color: #450456;">
                        Welcome to {{ optional($setting)->website_name ?? config('app.name') }}
                    </h1>

                    <p class="fw-bold fs-2" style="color: #440265;">
                        Login to Admin Panel
                        <br />using your credentials
                    </p>
                </div>

                <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px"
                    style="background-image: url({{ asset('assets/media/illustrations/sketchy-1/13.png') }})">
                </div>
            </div>
        </div>
        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <div class="w-lg-500px p-10 p-lg-15 mx-auto">

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form class="form w-100" novalidate="novalidate" id="kt_sign_in_form"
                        action="{{ route('admin.login') }}" method="POST">
                        @csrf

                        <div class="text-center mb-10">
                            <h1 class="text-dark mb-3">Sign In to
                                {{ optional($setting)->website_name ?? config('app.name') }}</h1>
                            {{-- <div class="text-gray-400 fw-bold fs-4">New Here?
                                <a href="{{ route('register') }}" class="link-primary fw-bolder">Create an Account</a>
                            </div> --}}
                        </div>

                        <div class="fv-row mb-10">
                            <x-metronic.label
                                class="form-label fs-6 fw-bolder text-dark">{{ __('Email') }}</x-metronic.label>

                            <x-metronic.input type="email" name="email" class="form-control form-control-sm"
                                :value="old('email')" autocomplete="off" required autofocus />
                        </div>

                        <div class="fv-row mb-10">
                            <div class="d-flex flex-stack mb-2">
                                <x-metronic.label
                                    class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Password') }}</x-metronic.label>

                                @if (Route::has('admin.password.request'))
                                    <a href="{{ route('admin.password.request') }}" class="link-primary fs-6 fw-bolder">
                                        {{ __('Forgot Password ?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="position-relative">
                                <x-metronic.input type="password" name="password" id="passwordField"
                                    class="form-control  form-control-sm" autocomplete="off" required />

                                <button type="button"
                                    class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                    onclick="togglePasswordVisibility()">
                                    <i id="eyeIcon" class="bi bi-eye-slash fs-2"></i>
                                </button>
                            </div>
                        </div>

                        <div class="fv-row mb-10">
                            <label class="form-check form-check-custom form-check-solid form-check-inline">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <span
                                    class="form-check-label fw-bold text-gray-700 fs-6">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        <div class="text-center">
                            <x-metronic.button type="submit" id="kt_sign_in_submit"
                                class="btn btn-lg btn-outline btn-outline-info btn-active-info w-200px mb-5 rounded-1">
                                <span class="indicator-label">{{ __('Sign In') }}</span>
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </x-metronic.button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function togglePasswordVisibility() {
                const passwordField = document.getElementById('passwordField');
                const eyeIcon = document.getElementById('eyeIcon');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                }
            }
        </script>
    @endpush

    {{-- <div class="d-flex flex-column flex-lg-row flex-column-fluid min-vh-100"
        style="background: linear-gradient(135deg, #222935, #0f1924);">

        <div class="d-flex flex-column flex-lg-row-fluid py-10">
            <div class="d-flex flex-center flex-column flex-column-fluid">
                <div class="w-lg-600px p-15 p-lg-20 mx-auto bg-white shadow-lg rounded-3">


                    <h1 class="fw-bold text-center mb-5 text-dark mb-15">
                        Welcome to {{ optional($setting)->website_name ?? config('app.name') }}
                    </h1>

                    <x-auth-session-status class="mb-4" :status="session('status')" />


                    <form class="form w-100" action="{{ route('admin.login') }}" method="POST">
                        @csrf


                        <div class="mb-4">
                            <x-metronic.label
                                class="form-label fw-bold text-dark">{{ __('Email') }}</x-metronic.label>
                            <x-metronic.input type="email" name="email"
                                class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Enter your email"
                                value="{{ old('email') }}" autocomplete="off"></x-metronic.input>
                        </div>


                        <div class="mb-8">
                            <x-metronic.label
                                class="form-label fw-bold text-dark">{{ __('Password') }}</x-metronic.label>
                            <div class="position-relative">
                                <x-metronic.input type="password" name="password" id="passwordField"
                                    class="form-control form-control-lg rounded-3 shadow-sm"
                                    placeholder="Enter your password" autocomplete="off">
                                </x-metronic.input>
                                <button type="button"
                                    class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                    onclick="togglePasswordVisibility()">
                                    <i id="eyeIcon" class="bi bi-eye-slash fs-4 text-muted"></i>
                                </button>
                            </div>
                        </div>


                        <div class="d-flex justify-content-between align-items-center mb-8">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <x-metronic.label for="remember_me" class="form-check-label text-dark">
                                    {{ __('Remember me') }}
                                </x-metronic.label>
                            </div>
                            @if (Route::has('admin.password.request'))
                                <a href="{{ route('admin.password.request') }}"
                                    class="text-primary text-decoration-none fw-bold">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>


                        <div class="text-center">
                            <x-metronic.button type="submit" class="btn btn-dark btn-lg w-100 rounded-3 shadow">
                                {{ __('Login Here') }}
                            </x-metronic.button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            function togglePasswordVisibility() {
                const passwordField = document.getElementById('passwordField');
                const eyeIcon = document.getElementById('eyeIcon');
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
                } else {
                    passwordField.type = 'password';
                    eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
                }
            }
        </script>
    @endpush --}}
</x-admin-guest-layout>
