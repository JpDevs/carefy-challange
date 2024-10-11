@php
$page = 'patients'
@endphp
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Pacientes
@endsection
@section('headBtn')
    <button onclick="toggleRegister()" class="btn btn-sm btn-outline-primary">Cadastrar</button>
@endsection
@include('../template.head')
<!-- Page Heading -->

<!-- Content Row -->

<div class="row">
    <div class="card shadow mb-4" style="width: 100%;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pacientes Cadastrados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width: 100%; display: none;">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Código</th>
                        <th>Data de nascimento</th>
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
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script>
        let statisticsRoute = '{{route('patientsApi.index')}}';
    </script>
    <script src="{{asset('js/patients/index.js')}}"></script>
@endsection

@include('template.footer')
