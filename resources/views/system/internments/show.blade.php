@php
    $page = 'patients'
@endphp
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Visualizar Internação
@endsection
@section('headBtn')
    <br>
    <a href="{{route('internments.index')}}" class="btn btn-sm btn-outline-primary"> <- Voltar</a>
@endsection
@include('../template.head')



<div class="container-fluid" id="errorMessage" style="display: none">


    <div class="text-center">
        <i class="fas fa-sad-cry fa-5x text-gray-300 mb-4"></i>
        <h1>Ooops</h1>
        <p class="lead text-gray-800">Houve um erro ao consultar o paciente. Verifique se o mesmo é valido e tente novamente.</p>
        <a href="{{route('internments.index')}}">← Voltar</a>
    </div>

</div>
<div class="row">
    <div class="col-md-12">
        <div class="row d-flex justify-content-center" id="preLoader">
            <div class="spinner-border text-primary mb-5" role="status"><span class="sr-only">Loading...</span></div>
        </div>
        <div class="card shadow mb-4" id="patientCard" style="display: none;">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img class="img-fluid rounded-circle mb-3" id="patientImg" src="{{ asset('img/no-image.png') }}" alt="Imagem do Paciente">
                    </div>
                    <div class="col-md-9">
                        <h4 class="font-weight-bold">Dados do Paciente</h4>

                        <div class="col-md-6">
                            <label for="patientName">Nome Completo:</label>
                            <strong id="patientName">Olga</strong>  <br>
                            <label for="birthDate">Data de Nascimento:</label>
                            <strong id="birthDate">07/07/1967</strong> <br>
                            <label for="patientCode">Código:</label>
                            <strong id="patientCode">741257</strong>
                        </div>

                        <h4 class="font-weight-bold mt-4">Dados da Internação</h4>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="entryDate">Entrada</label>
                                <div id="entryDate" class="form-control"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="exitDate">Saída</label>
                                <div id="exitDate" class="form-control"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="guide">Guia</label>
                            <div id="guide" class="form-control"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <a href="javascript:void(0)" class="btn btn-secondary" onclick="window.print()">
                                <i class="fa fa-print"></i> Imprimir
                            </a>
                        </div>

                        <div class="table-responsive mt-4" id="internmentsTable" style="display: none;">
                            <table class="table table-bordered" id="dataTable" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Guia</th>
                                    <th>Entrada</th>
                                    <th>Saída</th>
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




</div>
<!-- /.container-fluid -->

@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
        let statisticsRoute = '{{route('internmentsApi.show',$id)}}'
        let patientId = {{$id}};
    </script>
    <script src="{{asset('js/internments/show.js')}}"></script>
@endsection

@include('template.footer')
