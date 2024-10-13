@section('custom-css')
    <link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('title')
    Home
@endsection
@include('template.head')
<!-- Page Heading -->

<!-- Content Row -->
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Internações (Confirmadas)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="internments">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-hospital fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Internações (Pendentes)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingInternments">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Internações (Concluídas)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="doneInternments">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Pacientes cadastrados
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800" id="patients">...</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-smile fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->

<div class="row">
    <div class="card shadow mb-4" style="width: 100%;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Últimas Internações</h6>
        </div>
        <div class="card-body">
            <div class="row d-flex justify-content-center" id="tablePreLoader">
                <div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width: 100%; display: none;">
                    <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Guia</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Area Chart -->


    <!-- Pie Chart -->
</div>

<!-- Content Row -->

</div>
<!-- /.container-fluid -->

@section('custom-js')
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script>
        let internmentAudio = '{{asset('/audio/internmentsNew.mp3')}}';
        let statisticsRoute = '{{route('statistics')}}';
        let internmentsRoute = '{{route('internments.index')}}';
        let censusAudio = '{{asset('/audio/censusNew.mp3')}}';
    </script>
    <script src="js/index.js"></script>
{{--    <script src="js/table.js"></script>--}}
@endsection

@include('template.footer')
