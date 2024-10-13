@php
    $page = 'patients'
@endphp
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Visualizar Internação (Rascunho)
@endsection
@section('headBtn')
    <br>
    <a href="{{route('census.index')}}" class="btn btn-sm btn-outline-primary"> <- Voltar</a>
@endsection
@include('../template.head')



<div class="container-fluid" id="errorMessage" style="display: none">


    <div class="text-center">
        <i class="fas fa-sad-cry fa-5x text-gray-300 mb-4"></i>
        <h1>Ooops</h1>
        <p class="lead text-gray-800">Houve um erro ao consultar o paciente. Verifique se o mesmo é valido e tente
            novamente.</p>
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
                        <img class="img-fluid rounded-circle mb-3" id="patientImg" src="{{ asset('img/no-image.png') }}"
                             alt="Imagem do Paciente">
                    </div>
                    <div class="col-md-9">
                        <h4 class="font-weight-bold">Dados do Paciente</h4>
                            <div class="col-md-6">
                                <label for="patientName">Nome Completo:</label>
                                <strong id="patientName"></strong> <br>
                                <label for="birthDate">Data de Nascimento:</label>
                                <strong id="birthDate"></strong> <br>
                                <label for="patientCode">Código:</label>
                                <strong id="codeLabel"></strong>
                            </div>
                        <form method="POST" action="javascript:void(0)" id="draftForm" name="draftForm">
                            <div class="form-group col-md-6" id="codeInput">
                                <div class="input-group" id="fixCode" style="display: none">
                                    <input id="patientCode" type="text" class="form-control">

                                        <input type="hidden" name="patient_id" id="patientId">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" id="codeAction" type="button"
                                                    onclick="fixCode()">
                                                Corrigir
                                            </button>
                                        </div>
                                        <div class="invalid-feedback" id="codeMessage">Paciente já cadastrado no
                                            sistema. Clique em corrigir para associar o paciente.
                                        </div>
                                </div>
                            </div>

                            <h4 class="font-weight-bold mt-4">Dados da Internação</h4>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="entryDate">Entrada</label>
                                    <input type="date" id="entryDate" name="entry" class="form-control"></input>
                                    <div class="invalid-feedback" id="entryMessage"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="exitDate">Saída</label>
                                    <input type="date" id="exitDate" name="exit" class="form-control"></input>
                                    <div class="invalid-feedback" id="exitMessage"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="guide">Guia</label>
                                <div class="input-group">
                                    <input id="guide" name="guide" type="text" class="form-control" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button"
                                                onclick="generateCode()">
                                            Gerar Guia
                                        </button>
                                    </div>
                                    <div class="invalid-feedback" id="guideMessage">Já existe uma internação com essa
                                        guia
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                <button type="submit" class="btn btn-primary">
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




</div>
<!-- /.container-fluid -->

@section('custom-js')
    <script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>


    <script>
        let statisticsRoute = '{{route('drafts.show',$id)}}'
        let draftRoute = '{{route('drafts.index')}}'
        let draftId = {{$id}};
        let internments = '{{route('internments.index')}}';
        let redirectBack = '{{route('census.index')}}';
        let codeRoute = '{{route('patients.search')}}';
    </script>
    <script src="{{asset('js/census/edit.js')}}"></script>
@endsection

@include('template.footer')
