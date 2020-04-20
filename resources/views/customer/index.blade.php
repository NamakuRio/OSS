@extends('layouts.master')

@section('title', 'Pelanggan')

@section('css-library')
    <link rel="stylesheet" href="@asset('assets/modules/bootstrap-daterangepicker/daterangepicker.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/datatables.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')">
@endsection

@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Pelanggan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="@route('dashboard')">Beranda</a></div>
                <div class="breadcrumb-item">Pelanggan</div>
            </div>
        </div>
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card" @if(Agent::isMobile()) style="margin-left:-30px;margin-right:-30px;" @endif>
                        <div class="card-header">
                            <h4>Daftar Pelanggan</h4>
                            @can ('customer.create')
                                <div class="card-header-action">
                                    <a href="javascript:void(0)" class="btn btn-primary" onclick="$('#modal-add-customer').modal('show');focusable('#add-customer-nik', 500);" tooltip="Tambah Pelanggan"><i class="fas fa-plus"></i> Tambah Pelanggan</a>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="customer-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10">
                                                #
                                            </th>
                                            <th>ID Pelanggan</th>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Tanggal Lahir</th>
                                            <th>No. HP</th>
                                            <th>Ditambahkan</th>
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

    @can ('customer.create')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-add-customer">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Pelanggan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-add-customer">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>NIK</label>
                                    <input type="text" class="form-control" name="nik" id="add-customer-nik" required autofocus>
                                </div>
                                <div class="form-group col-12">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="name" id="add-customer-name" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>Tanggal Lahir</label>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" id="add-customer-date-of-birth" required>
                                </div>
                                <div class="form-group col-12">
                                    <label>No HP</label>
                                    <input type="text" class="form-control" name="phone" id="add-customer-phone" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-add-customer">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can ('customer.update')
        <div class="modal fade" tabindex="-1" role="dialog" id="modal-update-customer">
            <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Perbarui Pelanggan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="javascript:void(0)" id="form-update-customer">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="" id="update-customer-id">
                        <div class="modal-body">
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>NIK</label>
                                    <input type="text" class="form-control" name="nik" id="update-customer-nik" required autofocus>
                                </div>
                                <div class="form-group col-12">
                                    <label>Nama</label>
                                    <input type="text" class="form-control" name="name" id="update-customer-name" required >
                                </div>
                                <div class="form-group col-12">
                                    <label>Tanggal Lahir</label>
                                    <input type="text" class="form-control datepicker" name="date_of_birth" id="update-customer-date-of-birth" required >
                                </div>
                                <div class="form-group col-12">
                                    <label>No. HP</label>
                                    <input type="text" class="form-control" name="phone" id="update-customer-phone" required >
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-whitesmoke br">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary" id="btn-update-customer">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@section('js-library')
    <script src="@asset('assets/modules/bootstrap-daterangepicker/daterangepicker.js')"></script>
    <script src="@asset('assets/modules/datatables/datatables.min.js')"></script>
    <script src="@asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')"></script>
    <script src="@asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')"></script>
    <script src="@asset('assets/modules/jquery-ui/jquery-ui.min.js')"></script>
@endsection

@section('js-script')
    <script>
        $(function () {
            "use strict";

            getCustomers();

            @can ('customer.create')
                $("#form-add-customer").on("submit", function(e) {
                    e.preventDefault();
                    addCustomer();
                });
            @endcan
            @can ('customer.update')
                $("#form-update-customer").on("submit", function(e) {
                    e.preventDefault();
                    updateCustomer();
                });
            @endcan
        });

        async function getCustomers()
        {
            $("#customer-list").dataTable({
                processing: true,
                serverSide: true,
                ajax: "@route('customers.data')",
                destroy: true,
                columns: [
                    { data: 'DT_RowIndex' },
                    { data: 'id' },
                    { data: 'nik' },
                    { data: 'name' },
                    { data: 'date_of_birth' },
                    { data: 'phone' },
                    { data: 'created_at' },
                    { data: 'action' },
                ],
                order: [
                    [6, 'desc']
                ]
            });
        }

        @can ('customer.create')
            async function addCustomer()
            {
                var formData = $("#form-add-customer").serialize();

                $.ajax({
                    url: "@route('customers')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-add-customer").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-add-customer").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-add-customer")[0].reset();
                            $('#modal-add-customer').modal('hide');
                            getCustomers();
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

        @can ('customer.update')
            async function getUpdateCustomer(obj)
            {
                var id = $(obj).data('id');

                $('#modal-update-customer').modal('show');
                $('#form-update-customer')[0].reset();

                $.ajax({
                    url: "@route('customers.show')",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "id": id,
                        "_method": "POST",
                        "_token": "{{ csrf_token() }}"
                    },
                    beforeSend() {
                        $("#btn-update-customer").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-update-customer").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        $('#update-customer-id').val(result['data']['id']);
                        $('#update-customer-nik').val(result['data']['nik']);
                        $('#update-customer-name').val(result['data']['name']);
                        $('#update-customer-date-of-birth').val(result['date_of_birth']);
                        $('#update-customer-phone').val(result['data']['phone']);
                    },
                    error(xhr, status, error) {
                        var err = eval('(' + xhr.responseText + ')');
                        notification(status, err.message);
                        checkCSRFToken(err.message);
                    }
                });
            }

            async function updateCustomer()
            {
                var formData = $("#form-update-customer").serialize();

                $.ajax({
                    url: "@route('customers')",
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    beforeSend() {
                        $("#btn-update-customer").addClass('btn-progress');
                        $("input").attr('disabled', 'disabled');
                        $("button").attr('disabled', 'disabled');
                    },
                    complete() {
                        $("#btn-update-customer").removeClass('btn-progress');
                        $("input").removeAttr('disabled', 'disabled');
                        $("button").removeAttr('disabled', 'disabled');
                    },
                    success(result) {
                        if(result['status'] == 'success'){
                            $("#form-update-customer")[0].reset();
                            $('#modal-update-customer').modal('hide');
                            getCustomers();
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

        @can ('customer.delete')
            async function deleteCustomer(object)
            {
                var id = $(object).data('id');
                Swal.fire({
                    title: 'Anda yakin menghapus pelanggan?',
                    text: 'Semua data yang berhubungan dengan pelanggan akan dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus!',
                    showLoaderOnConfirm:true,
                    preConfirm: () => {
                        ajax =  $.ajax({
                                    url: "@route('customers')",
                                    type: "POST",
                                    dataType: "json",
                                    data: {
                                        "id": id,
                                        "_method": "DELETE",
                                        "_token": "{{ csrf_token() }}"
                                    },
                                    success(result) {
                                        if(result['status'] == 'success'){
                                            getCustomers();
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
