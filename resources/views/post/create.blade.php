<div class="panel">
                
        <div class="panel-body">
            <form id="post-create" class="form-horizontal" role="form" method="POST" action="{{ url('/post/store') }}">
                {{ csrf_field() }}

                <div class="form-group">
                    <label for="title" class="col-md-4 control-label">Title</label>
                    <div class="col-md-6">
                        <input id="title" type="text" class="form-control" name="title" value="{{ old('title') }}" autofocus required>
                        <div class="help-block">                                
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="url" class="col-md-4 control-label">Url</label>
                    <div class="col-md-6">
                        <input id="url" type="text" class="form-control" name="url" value="{{ old('url') }}" required url>
                        <div class="help-block">                                
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="col-md-4 control-label">Description</label>

                    <div class="col-md-6">
                        <textarea id="description" class="form-control" name="description" required> </textarea>
                        <div class="help-block">                                
                        </div>                        
                    </div>
                </div>
               
                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>                        
                        <button class="btn btn-success" type="button" data-dismiss="modal">Cancel</button>    
                    </div>
                </div>
            </form>
        </div>
</div>
 
<script>
 var errorsAttr = {};
$(document).ready(function(event){
 
    $("#post-create").on("submit", function(event, jqXHR, settings) { 

        var form = $(this);
        $.ajax({
                url: form.attr("action"),
                type: "post",
                data: form.serialize(),      
                success: function(response){ 
                    if(response.status =='success' ){
                          var html  = "<div class=\"alert-"+response.status+" alert fade in\">";
                          html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                          html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                          $(document).find(".panel-body").prepend(html); 
                          $(document).find(".alert-"+response.status+"").delay(2000).slideUp('slow',function(){$(this).remove()});          
                          form.trigger("reset");                       
                    }else{
                         var html  = "<div class=\"alert-danger alert fade in\">";
                         html += "<button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\"><i class=\"fa fa-remove\"></i></button>";                        
                         html += "<i class=\"icon fa fa-check\"></i>" + response.message + "</div>";                      
                         $(document).find(".panel-body").prepend(html); 
                         $(document).find(".alert-danger").delay(2000).slideUp('slow',function(){$(this).remove()});                          
                         $(document).find("#modal").modal('hide'); 
                    }
                },
                error: function(response){
                    //console.log(response);
                    if(response.status == 422){
                        var errors = response.responseJSON;                                              
                        $.each(errors,function(index,value){  
                            errorsAttr[index] = $('#'+index).val();
                            $('#'+index).parent().addClass('has-error');
                            $('#'+index).next('.help-block').html(value);
                        });   
                    }else{
                        var msg = "Sorry but there was an error: ";
                        $('.modal-body').html('<div class="error">' + msg + response.status + " " + response.statusText + '</div>');    
                    }
                }

        });

        return false;
    }); 
});

</script>
