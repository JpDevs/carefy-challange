
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Internações
@endsection
@section('headBtn')
    <a href="{{route('internments.create')}}" class="btn btn-sm btn-outline-primary">Cadastrar</a>
@endsection
@include('../template.head')
<div class="row">
    <div class="card shadow mb-4" style="width: 100%;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Internações Cadastradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" style="width: 100%; display: none;">
                    <thead>
                    <tr>
                        <th>Paciente</th>
                        <th>Código</th>
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
</div>



</div>

@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
        let statisticsRoute = '{{route('internmentsApi.index')}}';
    </script>
    <script src="{{asset('js/internments/index.js')}}"></script>
@endsection

@include('template.footer')
