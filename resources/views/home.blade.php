@extends('layouts.master')

@section('third_party_stylesheets')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
<link href="/plugins/loading/buttonLoader.css" rel="stylesheet">
@endsection

@section('content')

<section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Orders List</h1>
                </div>
                <!-- <div class="col-sm-6">
                    <a class="btn btn-primary float-right"
                       href=""> BTN
                    </a>
                </div> -->
            </div>
        </div>
    </section>

    <div class="content px-3">


        <div class="clearfix"></div>

            <div class="p-0">

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">

                                <div class="card-body">
                                    @if (session('status'))
                                        <div class="alert alert-success" role="alert">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    
                                    <!-- <h4>Orders List </h4> <br><br> -->

                                    <table class="table table-bordered yajra-datatable">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Order No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>country</th>
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

                <div class="modal fade" id="myModal" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h5>Show Order</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <div id="model_popup"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

    </div>







@endsection

@section('third_party_scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/loading/jquery.buttonLoader.js"></script>

<script type="text/javascript">
  $(function () {
    
    var table = $('.yajra-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('orders.list') }}",
        columns: [
            {data: 'action', name: 'action', orderable: true, searchable: true},
            {data: 'ref', name: 'ref'},
            {data: 'full_name', name: 'full_name'},
            {data: 'email_address', name: 'email_address'},
            {data: 'phone_number', name: 'email_address'},
            {data: 'country', name: 'country'},
        ]
    });
    
  });

function ShowModal(elem){
    var dataId = $(elem).data("id");
        $(elem).buttonLoader('start');

    $.ajax({
        url : '/orders/show?ref='+dataId,
        type : 'GET',
        dataType : 'html',

        success : function(code_html, statut){
            $('#myModal').modal('show');
            $(elem).buttonLoader('stop');
            $('#model_popup').html(code_html);
        },

        error : function(resultat, statut, erreur){
            $(elem).buttonLoader('stop');
        },

        complete : function(resultat, statut){
            $(elem).buttonLoader('stop');
        }

    });


}


$("#save").click(function(){

    $.ajax({
        url : "{{ route('orders.show') }}",
        type : 'GET',
        dataType : 'html',
        success : function(code_html, statut){
            console.log('code_html', code_html)
            console.log('statut', statut)
            
        },

        error : function(resultat, statut, erreur){
            console.log('resultat', resultat)
        },

        complete : function(resultat, statut){

            console.log('resultat', resultat)

        }

    });
    
});


</script>

@endsection