@extends('layouts.master_horizontal')

@section('title', 'Detail Pelanggan')

@section('css-library')
    <link rel="stylesheet" href="@asset('assets/modules/datatables/datatables.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')">
    <link rel="stylesheet" href="@asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css')">
@endsection

@section('content')
    <section class="section" @if(Agent::isMobile()) style="margin-left:-30px;margin-right:-30px;" @endif>
        <div class="section-header">
            <h1>Detail Pelanggan</h1>
        </div>

        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-md-12 col-lg-5">
                    <div class="card profile-widget" style="margin-top:0;">
                        <div class="profile-widget-header">
                            <div class="profile-widget-items">
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Terakhir Pesan</div>
                                    <div class="profile-widget-item-value">{{ ($customer->orders->count() == 0 ? 'Belum pernah pesan' : $customer->orders()->latest('created_at')->first()->created_at->diffForHumans()) }}</div>
                                </div>
                                <div class="profile-widget-item">
                                    <div class="profile-widget-item-label">Servis</div>
                                    <div class="profile-widget-item-value">{{ $customer->orders->count() }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-widget-description">
                            <div class="profile-widget-name">
                                {{ $customer->name }}
                                <div class="text-muted d-inline font-weight-normal">
                                    <div class="slash"></div> {{ $customer->nik }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    No. HP : {{ $customer->phone }}
                                </div>
                                <div class="col-12">
                                    Tanggal Lahir : {{ $customer->date_of_birth->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <h4>Riwayat Servis</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="customer-order-list">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="10">
                                                #
                                            </th>
                                            <th>ID Servis</th>
                                            <th>Pelanggan</th>
                                            <th>Jenis</th>
                                            <th>Merek</th>
                                            <th>Warna</th>
                                            <th>Keluhan</th>
                                            <th>Kelengkapan</th>
                                            <th>Harga</th>
                                            <th>Komentar Teknisi</th>
                                            <th>Status</th>
                                            <th>Ditambahkan oleh</th>
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
@endsection

@section('js-library')
    <script src="@asset('assets/modules/datatables/datatables.min.js')"></script>
    <script src="@asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')"></script>
    <script src="@asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js')"></script>
    <script src="@asset('assets/modules/jquery-ui/jquery-ui.min.js')"></script>
@endsection

@section('js-script')
    <script>
        $(function () {
            "use strict";

            getCustomerOrders();
        });

        async function getCustomerOrders()
        {
            $("#customer-order-list").dataTable({
                processing: true,
                serverSide: true,
                ajax: "@route('customers.orders.view', ['customer' => $customer])",
                destroy: true,
                columns: [
                    { data: 'DT_RowIndex' },
                    { data: 'id' },
                    { data: 'customer_name' },
                    { data: 'type' },
                    { data: 'merk' },
                    { data: 'color' },
                    { data: 'complaint' },
                    { data: 'completeness' },
                    { data: 'cost' },
                    { data: 'comment' },
                    { data: 'status' },
                    { data: 'user_name' },
                    { data: 'created_at' },
                    { data: 'action' },
                ],
                order: [
                    [1, 'desc']
                ],
                columnDefs: [{
                    targets: [6],
                    createdCell: function(cell) {
                        var $cell = $(cell);

                        if($cell.text().length > 100){
                            $(cell).contents().wrapAll("<div class='content'></div>");
                            var $content = $cell.find(".content");

                            $(cell).append($("<a href='javascript:void(0);'>Lihat Selengkapnya</a>"));
                            $btn = $(cell).find("a");

                            $content.css({
                                "height": "50px",
                                "overflow": "hidden"
                            });
                            $cell.data("isLess", true);

                            $btn.click(function () {
                                var isLess = $cell.data("isLess");
                                $content.css("height", isLess ? "auto" : "50px");
                                $(this).text(isLess ? "Lebih Sedikit" : "Lihat Selengkapnya");
                                $cell.data("isLess", !isLess);
                            });
                        }
                    }
                }],
                autoWidth: true,
            });
        }
    </script>
@endsection
