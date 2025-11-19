@extends('layouts.master' )
@section('title', 'Dashboard - CRM')
@section('content')
    <!-- Content wrapper -->
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0"
                        style="margin-bottom: 10px;">
                        <div class="card-title mb-0">
                            <div class="avatar flex-shrink-0" style=" margin-bottom: 10px;">
                                <img src="{{ asset('admin/assets/img/icons/unicons/chart-success.png') }}" alt="Credit Card"
                                    class="rounded">
                            </div>
                            <h6 class="m-0 me-2">Registered Companies</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2">{{ $companies }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection