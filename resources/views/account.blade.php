@extends('layouts.master')

@section('title', 'Akun')

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Akun</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="@route('dashboard')">Beranda</a></div>
                <div class="breadcrumb-item">Akun</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-12">
                    <div class="card" @if(Agent::isMobile()) style="margin-left:-30px;margin-right:-30px;" @endif>
                        <form method="post" class="needs-validation" novalidate="" action="javascript:void(0)" id="form-update-account" enctype="multipart/form-data">
                            <div class="card-header">
                                <h4>Perbarui Akun</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama Pengguna</label>
                                        <input type="text" class="form-control" name="username" value="{{ auth()->user()->username }}" required="" autocomplete="off">
                                        <span class="help-block text-success" id="help-block-update-account-username">
                                            <div class="spinner-border spinner-border-sm text-primary mr-2" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <small></small>
                                        </span>
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Nama</label>
                                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name }}" required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Email</label>
                                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email }}" required="" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>No HP</label>
                                        <input type="text" class="form-control" name="phone" value="{{ auth()->user()->phone }}" required="" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6 col-12">
                                        <label>Kata Sandi</label>
                                        <input type="password" class="form-control" name="password" placeholder="Kosongkan kata sandi jika tidak ingin mengubahnya" autocomplete="off">
                                    </div>
                                    <div class="form-group col-md-6 col-12">
                                        <label>Konfirmasi Kata Sandi</label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Kosongkan konfirmasi kata sandi jika tidak ingin mengubahnya" autocomplete="off">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-12 col-12">
                                        <label>Foto Profil</label>
                                        <div id="update-photo-preview" class="image-preview">
                                            <label for="update-photo-upload" id="update-photo-label">Pilih Gambar</label>
                                            <input type="file" name="photo" id="update-photo-upload" />
                                        </div>
                                        @if (auth()->user()->photo != null)
                                        <div class="custom-control custom-checkbox mt-2">
                                            <input type="checkbox" class="custom-control-input" id="null-photo" name="null_photo" value="1">
                                            <label class="custom-control-label" for="null-photo">Kosongkan Foto Profile</label>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary" type="submit" id="btn-update-account">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js-library')
    <script src="@asset('assets/modules/upload-preview/assets/js/jquery.uploadPreview.min.js')"></script>
@endsection

@section('js-script')
    <script>
        $(function() {
            "use strict";

            imagePreview();
            $('#update-photo-preview').css('background', `url('{{ auth()->user()->getPhoto() }}') center center / cover transparent`);

            $("#form-update-account").on("submit", function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                updateAccount(formData);
            });
        });

        async function imagePreview()
        {
            $.uploadPreview({
                input_field: "#update-photo-upload",
                preview_box: "#update-photo-preview",
                label_field: "#update-photo-label",
                label_default: "Pilih File",
                label_selected: "Ubah File",
                no_label: false,
                success_callback: null
            });
        }

        async function updateAccount(formData)
        {

            $.ajax({
                url: "{{ route('account') }}",
                type: "POST",
                dataType: "json",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                beforeSend() {
                    $("#btn-update-account").addClass('btn-progress');
                    $("input").attr('disabled', 'disabled');
                    $("button").attr('disabled', 'disabled');
                },
                complete() {
                    $("#btn-update-account").removeClass('btn-progress');
                    $("input").removeAttr('disabled', 'disabled');
                    $("button").removeAttr('disabled', 'disabled');
                },
                success(result) {
                    notification(result['status'], result['message']);

                    if(result['status'] == 'success'){
                        $('input[type="password"]').val('');
                        location.reload();
                    }
                },
                error(xhr, status, error) {
                    var err = eval('(' + xhr.responseText + ')');
                    notification(status, err.message);
                    checkCSRFToken(err.message);
                }
            });
        }
    </script>
@endsection
