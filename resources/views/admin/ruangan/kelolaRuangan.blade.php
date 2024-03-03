@extends('admin.themes.app')
@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Kelola Ruangan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item active">Kelola Ruangan</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <div class="mb-4">
                                <a href="{{ url('/tambah-ruangan') }}" type="button" class="btn btn-primary waves-effect btn-label waves-light">
                                    <i class="bx bx-plus label-icon"></i>
                                    Tambah Ruangan
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->

                    <div class="table-responsive">
                        <table class="table align-middle table-bordered datatable dt-responsive table-check nowrap" style="width: 100%;">
                            <thead>
                                <tr class="bg-transparent">
                                    <th style="text-align: center;">No</th>
                                    <th style="text-align: center;">Nama Ruangan</th>
                                    <th style="text-align: center;">Nama Gedung</th>
                                    <th style="text-align: center;">Harga</th>
                                    <th style="text-align: center;">Kapasitas</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody align="center">
                                <tr>
                                    <td style="width: 1%;"><a href="javascript: void(0);" class="text-body fw-medium">1</a> </td>
                                    <td><a href="javascript: void(0);" class="text-body fw-medium">Aula</a> </td>
                                    <td> Gedung Aula </td>
                                    <td> Rp. 500.000,00 </td>
                                    <td> 200 orang </td>
                                    <td>
                                        <div class="badge badge-soft-success font-size-12">kosong</div>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-primary waves-effect waves-light p-1" href="{{ url('/detail-ruangan') }}" style="width: 35px; height:30px; margin-right:5px">
                                            <i class="bx bx-file font-size-16 align-middle"></i>
                                        </a>
                                        <a type="button" class="btn btn-warning waves-effect waves-light p-1" href="{{ url('/edit-ruangan') }}" style="width: 35px; height:30px; margin-right:5px">
                                            <i class="bx bxs-edit font-size-16 align-middle"></i>
                                        </a>
                                        <a type="button" class="btn btn-danger waves-effect waves-light p-1" href="#" style="width: 35px; height:30px; margin-right:5px">
                                            <i class="bx bx-trash font-size-16 align-middle"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- end table responsive -->
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
</div>
@endsection