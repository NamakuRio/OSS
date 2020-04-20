@extends('layouts.master')

@section('title', 'Pengguna')

@section('css-library')
    <link rel="stylesheet" href="@asset('assets/modules/datatables/datatables.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/select2/dist/css/select2.min.css')">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pengguna</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="@route('dashboard')">Beranda</a></div>
                <div class="breadcrumb-item">Pengguna</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Pengguna</h4>
                            @can ('user.create')
                                <div class="card-header-action">
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-add-user').modal('show');focusable('#add-user-username', 500);" tooltip="Tambah Pengguna"><i class="fas fa-plus"></i> Tambah Pengguna</a>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="user-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10">
                                                #
                                            </th>
                                            <th>Peran</th>
                                            <th>Nama Pengguna</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No. HP</th>
                                            <th width="150">Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @can ('user.create')
        <div class="modal fade" role="dialog" id="modal-add-user">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-add-user">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Peran</label>
                                    <select name="role_id" id="add-user-role-id" class="form-control" required></select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Nama Pengguna</label>
                                    <input type="text" class="form-control" name="username" id="add-user-username" required autofocus>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="name" id="add-user-name" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" id="add-user-email" required>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="phone" id="add-user-phone" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Kata Sandi</label>
                                    <input type="password" class="form-control" name="password" id="add-user-password" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="add-user-password-confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-add-user">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('user.update')
        <div class="modal fade" role="dialog" id="modal-update-user">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Perbarui Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-user">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="update-user-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <label>Peran</label>
                                    <select name="role_id" id="update-user-role-id" class="form-control" required></select>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Nama Pengguna</label>
                                    <input type="text" class="form-control" name="username" id="update-user-username" required autofocus>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="name" id="update-user-name" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" id="update-user-email" required>
                                </div>
                                <div class="form-group col-lg-12">
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="phone" id="update-user-phone" required>
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Kata Sandi</label>
                                    <input type="password" class="form-control" name="password" id="update-user-password" placeholder="Kosongkan kata sandi jika tidak ingin mengubahnya">
                                </div>
                                <div class="form-group col-lg-6">
                                    <label>Konfirmasi Kata Sandi</label>
                                    <input type="password" class="form-control" name="password_confirmation" id="update-user-password-confirmation" placeholder="Kosongkan konfirmasi kata sandi jika tidak ingin mengubahnya">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-update-user">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('user.manage')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-manage-user">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Kelola Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-manage-user">
                        @csrf
                        @method('PUT')
                        <div class="modal-body" id="view-manage-user">

                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-manage-user">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection


@section('js-library')
    <script src="@asset('assets/modules/datatables/datatables.min.js')"></script>
    <script src="@asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')"></script>
    <script src="@asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')"></script>
    <script src="@asset('assets/modules/jquery-ui/jquery-ui.min.js')"></script>
    <script src="@asset('assets/modules/select2/dist/js/select2.full.min.js')"></script>
@endsection

@section('js-script')
    <script>
        $(function () {
            "use strict";

            $('#add-user-role-id').select2({
                width: '100%',
                placeholder: 'Pilih Peran',
                // minimumInputLength: 1,
                ajax: {
                    url: "@route('users.select2.roles')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (params) {
                        var text = params.term ? params.term : '';
                        var query = {
                            search: text,
                        };
                        return {
                            data: query,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data['data'], function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: (params.page * 25) < data.total
                            }
                        }
                    }
                }
            });

            $('#update-user-role-id').select2({
                width: '100%',
                placeholder: 'Pilih Peran',
                // minimumInputLength: 1,
                ajax: {
                    url: "@route('users.select2.roles')",
                    type: "POST",
                    dataType: "json",
                    quietMillis: 50,
                    delay: 250,
                    data: function (params) {
                        var text = params.term ? params.term : '';
                        var query = {
                            search: text,
                        };
                        return {
                            data: query,
                            page: params.page || 1,
                            _token: '{{ csrf_token() }}',
                        };
                    },
                    processResults : function(data, params) {
                        params.page = params.page || 1;

                        return {
                            results: $.map(data['data'], function (item) {
                                return {
                                    text: item.name,
                                    id: item.id
                                }
                            }),
                            pagination: {
                                more: (params.page * 25) < data.total
                            }
                        }
                    }
                }
            });

            getUsers();

            @can ('user.create')
                $("#form-add-user").on("submit", function(e) {
                    e.preventDefault();
                    addUser();
                });
            @endcan

            @can ('user.update')
                $("#form-update-user").on("submit", function(e) {
                    e.preventDefault();
                    updateUser();
                });
            @endcan

            @can ('user.manage')
                $("#form-manage-user").on("submit", function(e) {
                    e.preventDefault();
                    manageUser();
                });
            @endcan
        });

        async function getUsers()
        {
            $("#user-list").dataTable({
                processing: true,
                serverSide: true,
                ajax: "@route('users.data')",
                destroy: true,
                columns: [
                    { data: 'DT_RowIndex' },
                    { data: 'role' },
                    { data: 'username' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'phone' },
                    { data: 'action' },
                ]
            });
        }

        @can ('user.create')
            async function addUser()
            {
                var formData = $("#form-add-user").serialize();

                $.ajax({
                    url: "@route('users')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-add-user").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-add-user").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-user")[0].reset();
                            $('#add-user-role-id').val('').trigger('change');
                            $('#modal-add-user').modal('hide');
                            getUsers();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can ('user.update')
            async function getUpdateUser(obj)
            {
                var id = $(obj).data('id');

                $('#modal-update-user').modal('show');
                $('#form-update-user')[0].reset();

                $.ajax({
                    url: "@route('users.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-update-user").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                        $('#update-user-role-id').val('').trigger('change');
                        $('#update-user-role-id option').remove();
                    },
                    complete() {
                        $("#btn-update-user").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#update-user-id').val(result['data']['id']);
                        $('#update-user-role-id').append('<option value="'+result['data']['roles'][0]['id']+'" selected>'+result['data']['roles'][0]['name']+'</option>');
                        $('#update-user-username').val(result['data']['username']);
                        $('#update-user-name').val(result['data']['name']);
                        $('#update-user-email').val(result['data']['email']);
                        $('#update-user-phone').val(result['data']['phone']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function updateUser()
            {
                var formData = $("#form-update-user").serialize();

                $.ajax({
                    url: "@route('users')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-update-user").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                        $("select").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-update-user").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                        $("select").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-user")[0].reset();
                            $('#modal-update-user').modal('hide');
                            getUsers();
                        }

                        notification(result['status'], result['message']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }
        @endcan

        @can('user.manage')
            async function getManageUser(obj)
            {
                var id = $(obj).data('id');
                $('#modal-manage-user').modal('show');
                $("#view-manage-user").html('<h4 class="text-center my-4">Loading . . .</h4>');

                $.ajax({
                    url: "@route('users.manage')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-manage-user").addClass('btn-progress');
                        $("#btn-manage-user").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("button").removeAttr('disabled', 'disabled');
                        $("#btn-manage-user").removeAttr('disabled', 'disabled');
                        $("#btn-manage-user").removeClass('btn-progress');
                    },
                    success : function(result) {
                        $("#view-manage-user").html(result['data']);
                    }
                });
            }
            async function manageUser()
            {
                var formData = $("#form-manage-user").serialize();

                $.ajax({
                    url: "@route('users.manage')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-manage-user").addClass('btn-progress');
                        $("#btn-manage-user").attr('disabled', 'disabled');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-manage-user").removeClass('btn-progress');
                        $("#btn-manage-user").removeAttr('disabled', 'disabled');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success : function(result) {
                        if(result['status'] == 'success'){
                            $("#form-manage-user")[0].reset();
                            $('#modal-manage-user').modal('hide');
                            getUsers();
                        }

                        notification(result['status'], result['message']);
                    }
                });
            }
        @endcan

        @can('user.delete')
            async function deleteUser(object)
            {
                var id = $(object).data('id');
                Swal.fire({
                    title: 'Anda yakin menghapus pengguna?',
                    text: 'Semua data yang berhubungan dengan pengguna juga akan di hapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus Pengguna!',
                    showLoaderOnConfirm:true,
                    preConfirm: () => {
                        ajax =  $.ajax({
                                    url: "@route('users')",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        "id": id,
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success(result) {
                                        if(result['status'] == 'success'){
                                            getUsers();
                                        }
                                        swalNotification(result['status'], result['message']);
                                    }
                                });

                        return ajax;
                    }
                })
                .then((result) => {
                    if (result.value) {
                        notification(result.value.status, result.value.message);
                    }
                });
            }
        @endcan
    </script>
@endsection
