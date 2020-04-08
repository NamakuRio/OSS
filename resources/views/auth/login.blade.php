@extends('layouts.auth')

@section('title', 'Masuk')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">

                <div class="card card-primary">
                    <div class="card-header">
                        <h4>Masuk</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="javascript:void(0);" class="needs-validation" novalidate="" id="form-login">
                            @csrf
                            <div class="form-group">
                                <label for="username">Nama Pengguna</label>
                                <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                                <div class="invalid-feedback">
                                    Silakan isi Nama Pengguna Anda
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="d-block">
                                    <label for="password" class="control-label">Kata Sandi</label>
                                </div>
                                <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                                <div class="invalid-feedback">
                                    Silakan isi Kata Sandi Anda
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me" checked>
                                    <label class="custom-control-label" for="remember-me">Ingat saya</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4" id="btn-login">
                                    Masuk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="simple-footer text-muted">
                    {{ config('app.name') }} &copy; {{ date('Y') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-script')
    <script>
        $(function () {
            "use strict";

            $("#form-login").on("submit", function (e) {
                e.preventDefault();

                if($("#username").val().length == 0 || $("#password").val().length == 0) {
                    notification('error', 'Mohon isi semua field.');
                    return false;
                }

                login();
            });
        });

        function login()
        {
            var formData = $("#form-login").serialize();

            $.ajax({
                url: "@route('login')",
                type: "POST",
                dataType: "json",
                data: formData,
                beforeSend() {
                    $("#btn-login").addClass("btn-progress");
                    $("input").attr("disabled", "disabled");
                    $("button").attr("disabled", "disabled");
                },
                complete() {
                    $("#btn-login").removeClass("btn-progress");
                    $("input").removeAttr("disabled", "disabled");
                    $("button").removeAttr("disabled", "disabled");
                },
                success(result) {
                    notification(result['status'], result['message']);
                    focusable('#username');
                    $("#username").focus();

                    if(result['status'] == 'success'){
                        window.location = url + result['login_destination'];
                    }
                },
                error(xhr, status, error) {
                    var err = eval('(' + xhr.responseText + ')');
                    notification(status, err.message);
                    checkCSRFToken(err.message);
                    focusable('#username');
                }
            });
        }
    </script>
@endsection
