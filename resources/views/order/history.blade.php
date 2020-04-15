@extends('layouts.master')

@section('title', 'Riwayat Perubahan')

@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="@route('orders')" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Riwayat Perubahan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="@route('dashboard')">Beranda</a></div>
                <div class="breadcrumb-item active"><a href="@route('orders')">Pesanan</a></div>
                <div class="breadcrumb-item">Riwayat Perubahan</div>
            </div>
        </div>
        <div class="section-body">
            {{-- <h2 class="section-title">September 2018</h2> --}}
            <div class="row">
                <div class="col-12">
                    <div class="activities" id="history-view">
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js-script')
    <script>
        var page = 1;
        var loadAgain = true;
        var dataAll = true;

        $(function () {
            "use strict";

            loadMoreHistory(page);
        });

        $(window).scroll(function () {
            if($(window).scrollTop() + $(window).height() > $(document).height() - $(window).height()) {
                if(loadAgain) {
                    loadAgain = false;

                    if(dataAll) {
                        page++;
                        loadMoreHistory(page);
                    }
                }
            }
        });

        function loadMoreHistory(page){
            $.ajax({
                url: "@route('orders.history', $order)?page="+page,
                type: "POST",
                dataType: "json",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function(){
                    // $(".loading-more").show();
                    // $(".no-more-data").hide();
                },
                complete: function(){
                    // $(".loading-more").hide();
                },
                success: function(result) {
                    loadAgain = true;
                    $.each(result['data']['data'], function(prop, obj) {
                        var icon = "";
                        if(obj['type'] == 'create') icon = "fas fa-plus";
                        if(obj['type'] == 'update') icon = "far fa-edit";
                        if(obj['type'] == 'cost') icon = "fas fa-dollar-sign";
                        if(obj['type'] == 'comment') icon = "fas fa-comments";
                        if(obj['type'] == 'status') icon = "fas fa-adjust";

                        $(`
                        <div class="activity">
                            <div class="activity-icon bg-primary text-white shadow-primary">
                                <i class="`+icon+`"></i>
                            </div>
                            <div class="activity-detail">
                                <div class="mb-2">
                                    <span class="text-job text-primary" tooltip="`+obj['created_at_new_tooltip']+`">`+obj['created_at_new']+`</span>
                                    <span class="bullet"></span>
                                    <a class="text-job" href="javascript:void(0)">`+obj['user']['name']+`</a>
                                </div>
                                <p>`+obj['description']+`</p>
                            </div>
                        </div>
                        `).appendTo('#history-view');
                    });
                }
            });
        }
    </script>
@endsection
