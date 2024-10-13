@php
    $page = 'patients'
@endphp
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Editar Internação
@endsection
@section('headBtn')
    <br>
    <a href="{{route('internments.index')}}" class="btn btn-sm btn-outline-primary"> <- Voltar</a>
@endsection
@include('../template.head')
<!-- Page Heading -->

<!-- Content Row -->
<div class="container-fluid" id="errorMessage" style="display: none">

    <!-- 404 Error Text -->
    <div class="text-center">
        <i class="fas fa-sad-cry fa-5x text-gray-300 mb-4"></i>
        <h1>Ooops</h1>
        <p class="lead text-gray-800">Houve um erro ao exibir a consulta. Verifique se a mesma é valido e tente novamente.</p>
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
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="patientName">Nome Completo:</label>
                                <strong id="patientName"></strong>  <br>
                                <label for="birthDate">Data de Nascimento:</label>
                                <strong  id="birthDate"></strong> <br>
                                <label for="patientCode">Código:</label>
                                <strong id="patientCode" ></strong>
                            </div>
                        </div>

                        <h4 class="font-weight-bold mt-4">Dados da Internação</h4>
                        <form id="internmentForm" name="internmentForm" method="PUT" action="javascript:void(0)">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="entryDate">Entrada</label>
                                <input id="entryDate" name="entry" class="form-control" type="date"></input>
                            </div>
                            <div class="col-md-6">
                                <label for="exitDate">Saída</label>
                                <input id="exitDate"  name="exit" class="form-control" type="date"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="patientCode">Guia</label>
                            <div class="input-group">
                                <input id="guide" name="guide" type="text" readonly class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                            onclick="generateCode()">
                                        Gerar Guia
                                    </button>
                                </div>
                            </div>
                        </div>
                        <script>
                            function generateCode() {
                                let code = Math.floor(100000 + Math.random() * 900000);
                                $('#guide').val(code);
                            }
                        </script>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fa fa-save"></i> Salvar
                            </button>
                        </div>
                        </form>

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


    <!-- Content Row -->

</div>
<!-- /.container-fluid -->

@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>

    <!-- Page level custom scripts -->
    <script>
        let statisticsRoute = '{{route('internmentsApi.show',$id)}}'
        let patientId = {{$id}};
    </script>
    <script src="{{asset('js/internments/edit.js')}}"></script>
@endsection

@include('template.footer')
