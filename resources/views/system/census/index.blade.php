@php
    $page = 'patients'
@endphp
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Censo Hospitalar
@endsection
@section('headBtn')
    <br>
    <button class="btn btn-sm btn-outline-primary" onclick="toggleImport()"><i class="fas fa-upload mr-2"></i>Importar CSV</button>
    <button class="btn btn-sm btn-success" onclick="saveValids()"><i class="fas fa-save mr-2"></i>Salvar e revalidar</button>
    <button class="btn btn-sm btn-danger" onclick="truncate()"><i class="fas fa-trash mr-2"></i>Deletar Registros</button>
@endsection
@include('../template.head')




<div class="row">
    <div class="card shadow mb-4" style="width: 100%;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary mb-2">A revisar</h6>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="navIncongruents" href="#"
                   role="tab" aria-controls="navIncongruents" aria-selected="true">Incongruentes <span id="incongruentsCount" class="badge badge-danger" style="display: none">0</span></a>
                <a class="nav-item nav-link" id="navValids" href="#"
                   role="tab" aria-controls="navValids" aria-selected="false">Válidas <span id="validsCount" class="badge badge-success" style="display: none;">0</span></a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="incongruentsDiv">
                <table class="table table-bordered" id="incongruentsTable" style="width: 100%;">
                    <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Guia</th>
                        <th>Entrada</th>
                        <th>Saída</th>
                        <th>Incongruências</th>
                        <th>#</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="table-responsive" id="validsDiv" style="display: none;">
                <table class="table table-bordered" id="validsTable" style="width: 100%;">
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



</div>
<!-- /.container-fluid -->

@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
        let statisticsRoute = '{{route('drafts.index')}}';
        let uploadRoute = '{{route('census.upload')}}';
        let truncateRoute = '{{route('census.truncate')}}';
        let internments = '{{route('internments.index')}}';
    </script>
    <script src="{{asset('js/census/index.js')}}"></script>
@endsection

@include('template.footer')
