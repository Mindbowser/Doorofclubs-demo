@extends('layouts.app')

@section('title', 'Post')
@section('header')
<link href="{{ asset('css/jquery.dataTables.min.css') }}" />
<link href="{{ asset('css/dataTables.bootstrap.min.css') }}" />

@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="vote-response-message"></div>            
            <div class="box-header"><h2><u>Posts</u></h2></div>
            <div class="box-body">
                <div class="dataTables_wrapper form-inline dt-bootstrap table-responsive">
                    <table id="posts" class="table table-bordered table-hover table-striped">           
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Url</th>
                            <th>Total Up</th>                                
                            <th>Total Down</th>   
                            <th>Score</th>
                            <th>Actions</th>
                          </tr>
                         </thead>
                         <tbody id="post-list" name="post-list">

                        </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
      

@endsection

@section('footer')

    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
@endsection    
   
@push('scripts')    
    <script>
        $(document).ready(function(){
           
             var oTable = $('#posts').DataTable( {
               // "processing": true,
                "bServerSide": true,
                "sAjaxSource": '{{ url("post/posts") }}',
                "dom" : "<'col-sm-3'l><'col-sm-6 text-center'f><'col-sm-3 text-center'<'add-post'>>" +                      
                "<'col-sm-12't>" +		    
                "<'col-sm-6'i><'col-sm-6 text-right'p>",
                "oLanguage": { "sSearch": "", "sSearchPlaceholder": "Search" }, 
                "autoWidth": false,
                "order": [[ 4, "desc" ]],
                "aoColumnDefs": [
                    {"className": "text-center", "targets": "_all"},
                    {
                       "aTargets":[0],                       
                       "orderable": false, 
                       "bSearchable" : false,
                    },
                    {
                       "aTargets":[1],                       
                       "orderable": false, 
                       "bSearchable" : false,
                       "mData": null,
                       "mRender": function( data, type, full) { 
                           var str = "";
                           str += "<a href='"+full[1]+"' target='_blank'>"+full[1]+"</a><br>"+full[6];
                           return str ;
                       }
                    },
                    {
                       "aTargets":[5],
                       "fnCreatedCell": function(nTd, sData, oData, iRow, iCol)
                       {
                           $(nTd).css("text-align", "center");
                       },
                       "mData": null,
                       "mRender": function( data, type, full) {    // You can use <img> as well if you want
                            var str = "";
                            str +="<td><a class=\"view-detail_row_"+full[5]+" up-post\" style=\"\" href=\"javascript:void(0)\" data-size=\"modal-md\"  controller_url=\"{{ url('post/uppostcount') }}/"+full[5]+"\" >";
                            if(full[7] == 1) {
                                str +="<span class=\"glyphicon glyphicon-thumbs-up\" style=\"color:green;\"></span></a></td>";
                            } else {
                                str +="<span class=\"glyphicon glyphicon-thumbs-up\"></span></a></td>";
                            } 
                            str +="&nbsp &nbsp";
                            str +="<td><a class=\"view-detail_row_"+full[5]+" down-post\" style=\"\" href=\"javascript:void(0)\" data-size=\"modal-md\"  controller_url=\"{{ url('post/downpostcount') }}/"+full[5]+"\" >";
                            if(full[8] == 1) {
                                str +="<span class=\"glyphicon glyphicon-thumbs-down\" style=\"color:green;\"></span></a></td>";
                            } else {
                                str +="<span class=\"glyphicon glyphicon-thumbs-down\"></span></a></td>";
                            }
                            str +="&nbsp &nbsp";
                            str +="<td><a class=\"view-detail_row_"+full[5]+" pop-up-modal\" data-title=\"Post\" style=\"\" href=\"javascript:void(0)\" data-size=\"modal-md\"  controller_url=\"{{ url('post/show') }}/"+full[5]+"\" >";
                            str +="<span class=\"glyphicon glyphicon-eye-open\"></span></a></td>";
                            str +="&nbsp &nbsp";
                            return str;
                       },
                       "orderable": false, 
                       "bSearchable" : false,
                    }
                    ,
            {
                "targets": [ 4 ],
                "visible": false
            }
                ],
            });
           
            $("div.add-post").html("<button controller_url='{{ url('post/create') }}' data-title='Add Post' data-size='modal-md' class='btn btn-success pop-up-modal col-md-offset-6' style='margin-bottom: 6px;'><i class='fa fa-plus'></i> Add New </button>");
            $('#modal').on('hidden.bs.modal', function () {
                oTable.ajax.reload();  
            });
            $(document).on('click', '.up-post', function () {
                
                $.ajax({
                    url: $(this).attr('controller_url'),
                    cache: false,
                    dataType: "json",
                    type: "GET",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        if(response.status =='success' ){
                            var html  = "<div class=\"alert-"+response.status+" alert fade in\">";
                            html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                            html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                            $(document).find(".vote-response-message").prepend(html); 
                            $(document).find(".alert-"+response.status+"").delay(3000).slideUp('slow',function(){$(this).remove()});          
                            //form.trigger("reset");                       
                      }else{
                           var html  = "<div class=\"alert-danger alert fade in\">";
                           html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                           html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                           $(document).find(".vote-response-message").html(html); 
                           $(document).find(".alert-danger").delay(3000).slideUp('slow',function(){$(this).remove()});                          
                           //$(document).find("#modal").modal('hide'); 
                      }
                      oTable.ajax.reload();
                    }
                });
                
            });
            $(document).on('click', '.down-post', function () {
                $.ajax({
                    url: $(this).attr('controller_url'),
                    cache: false,
                    dataType: "json",
                    type: "GET",
                    "headers": {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function (response) {
                        if(response.status =='success' ){
                            var html  = "<div class=\"alert-"+response.status+" alert fade in\">";
                            html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                            html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                            $(document).find(".vote-response-message").prepend(html); 
                            $(document).find(".alert-"+response.status+"").delay(3000).slideUp('slow',function(){$(this).remove()});          
                                                 
                      }else{
                           var html  = "<div class=\"alert-danger alert fade in\">";
                           html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                           html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                           $(document).find(".vote-response-message").html(html); 
                           $(document).find(".alert-danger").delay(3000).slideUp('slow',function(){$(this).remove()});                          
                           
                      }
                      oTable.ajax.reload();
                    }
                }); 
            });
              
        });
    </script>

@endpush

