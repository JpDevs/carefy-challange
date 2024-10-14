
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Visualizar Paciente
@endsection
@section('headBtn')
    <br>
    <a href="{{route('patients.index')}}" class="btn btn-sm btn-outline-primary"> <- Voltar</a>
@endsection
@include('../template.head')



<div class="container-fluid" id="errorMessage" style="display: none">


    <div class="text-center">
        <i class="fas fa-sad-cry fa-5x text-gray-300 mb-4"></i>
        <h1>Ooops</h1>
        <p class="lead text-gray-800">Houve um erro ao consultar o paciente. Verifique se o mesmo é valido e tente novamente.</p>
        <a href="{{route('patients.index')}}">← Voltar</a>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="row d-flex justify-content-center" id="preLoader">
            <div class="spinner-border text-primary mb-5" role="status"><span class="sr-only">Loading...</span></div>
        </div>
        <div class="card shadow mb-4" style="display: none;" id="patientCard">
            <div class="card-header">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="navData" href="#"
                       role="tab" aria-controls="navData" aria-selected="true">Dados</a>
                    <a class="nav-item nav-link" id="navInternments" href="#"
                       role="tab" aria-controls="navInternments" aria-selected="false">Internações</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="patientData">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="img-fluid rounded-circle mb-3" id="patientImg"
                                     src="{{asset('img/no-image.png')}}">
                            </div>
                            <div class="col-md-9">
                                <div>
                                    <div class="form-group row">
                                        <div class="form-group col-md-6">
                                            <label for="patientName">Nome</label>
                                            <div class="form-control" id="patientName"></div>

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="birthDate">Data de nascimento</label>
                                            <div class="form-control" id="birthDate"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="patientCode">Código</label>
                                        <div id="patientCode" type="text" class="form-control"></div>
                                    </div>
                                    <div class="d-flex justify-content-center">
                                        <a href="javascript:void(0)" class="btn btn-secondary mr-2"
                                           onclick="window.print()">
                                            <i class="fa fa-print"></i> Imprimir
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive" id="internmentsTable" style="display: none !important;">
                        <table class="table table-bordered" id="dataTable" style="width: 100%; display: none;">
                            <thead>
                            <tr>
                                <th>Paciente</th>
                                <th>Guia</th>
                                <th>Entrada</th>
                                <th>Saida</th>
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




</div>


@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
        let internmentsRoute = '{{route('patients.internments',$id)}}';
        let statisticsRoute = '{{route('patientsApi.show',$id)}}'
        let patientId = {{$id}};
    </script>
    <script src="{{asset('js/patients/show.js')}}"></script>
@endsection

@include('template.footer')
