$(function(){
    $(document).on('click','.pop-up-modal',function(){
        var $modal = $('#modal');
        var $link = $(this);
        $(".loaderContener").css("display","block");   
        $modal.find('.modal-dialog').addClass($link.data('size'));
        $modal.find('.modal-title').text($link.data('title'));
        $modal.find('.modal-body').html('Loading ...');
        $modal.find('.modal-body').load($link.attr('controller_url'),function(response, status, xhr){
          if ( status == "error" ) {            
            var msg = "Sorry but there was an error: ";
            $('.modal-body').html('<div class="error">' + msg + xhr.status + " " + xhr.statusText + '</div>');      
          }                   
          $modal.modal('show');                
        });
    });
    
    $(document).on('change','.form-control',function(){
        var $this = $(this);
        var parent = $this.parent();
        if(parent.hasClass('has-error')){
            parent.removeClass('has-error');
            $this.next('.help-block').html('');
        }  
    });
});