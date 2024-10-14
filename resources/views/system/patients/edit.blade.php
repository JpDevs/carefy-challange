
@section('custom-css')
    <link href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section('title')
    Editar Paciente
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
        <p class="lead text-gray-800">Houve um erro ao consultar o paciente. Verifique se o mesmo é valido e tente
            novamente.</p>
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
                       role="tab" aria-controls="navData" aria-selected="true">Editar Paciente</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="patientData">
                        <div class="row">
                            <div class="col-md-3">
                                <img class="img-fluid rounded-circle mb-3"
                                     src="{{asset('img/no-image.png')}}"
                                     id="patientImagePreview">
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" name="image" class="custom-file-input" id="patientImage" accept="image/*">
                                        <label class="custom-file-label" for="patientImage">Alterar Imagem</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div>
                                    <form id="patientForm" name="patientForm" method="post" action="javascript:void(0)">
                                    <div class="form-group row">
                                        <div class="form-group col-md-6">
                                            <label for="patientName">Nome</label>
                                            <input class="form-control" name="name" id="patientName">

                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="birthDate">Data de nascimento</label>
                                            <input class="form-control" name="birth" id="birthDate" type="date">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="patientCode">Código</label>
                                        <div class="input-group">
                                            <input id="patientCode" name="code" type="text" class="form-control">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="button"
                                                        onclick="generateCode()">
                                                    Gerar Código
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <script>
                                        function generateCode() {
                                            let code = Math.floor(100000 + Math.random() * 900000);
                                            $('#patientCode').val(code);
                                        }
                                    </script>

                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary mr-2">
                                                <i class="fa fa-save"></i> Salvar
                                            </button>
                                        </div>
                                  </form>
                                </div>
                            </div>
                        </div>
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
        let updateRoute = '{{route('patientsApi.update',$id)}}';
        let statisticsRoute = '{{route('patientsApi.show',$id)}}'
        let patientId = {{$id}};
    </script>
    <script src="{{asset('js/patients/edit.js')}}"></script>
@endsection

@include('template.footer')
