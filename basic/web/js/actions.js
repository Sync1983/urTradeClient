(function ( $ ) {  
  
  $.fn.mirror = function( src ) {
    return this.html($(src).html());
  };
  
  $.fn.showBy = function( value ) {
    
    if( Boolean( value*1 ) === true ){
      $(this).removeClass('hidden');
    } else {
      $(this).addClass('hidden');      
    }
    
    return this;
    
  };
  
  $.fn.toOrderList = function( url ) {
    
    function click(event){
      
      var keys = $('#basket-list').yiiGridView('getSelectedRows');
      
      $.ajax({
        url: url,
        method: "POST",
        data: {ids:keys}
      }).done(function (data){
        if( data ){
          //window.location.href = data;
        }
      });
      
    }
    
    $(this).click(click);
    
    return this;
  };
  
  $.fn.toBasket = function( data ) {
    var deferred = new $.Deferred();
    var form = $(this).parent().parent().parent();
    var url = $(form).attr('action');
    
    function load(){
      var send_data = data;      
      
      $(form).find('input').each(function(index,item){
        var name = $(item).attr('name');
        var value= $(item).val();
        send_data[name] = value;
      });
      
      $.ajax({
        url: url,
        method: "POST",
        data:send_data
      }).done(function( data ){
        if( data === "OK" ){
          deferred.resolve();          
        } else {
          deferred.reject(data); 
        }
      }).fail(function (data){
        deferred.reject(data.responseText);         
      });
    }
    
    $(this).click(load);
    
    return deferred.promise();
  };
  
  $.fn.initPartSelect = function( dest, data, url ) {
    
    function load( event ){
      $.ajax({
      url: url,
      method: "POST",
      data: data( $( event.currentTarget ) ),
      beforeSend: function( xhr ) { $(dest).mirror("#loader"); }
      }).done( function( data ) {
        
        $(dest).html(data);
        
        $("#parts").DataTable({
          paging: false,
          order: [[ 0, 'asc' ], [ 3, 'asc' ]],
          language: {
            search: "Быстрый поиск:"
          }
        });
        var html = $("#popup-to-basket").html();
        
        $('button[data-to-basket]').popover({
          content: function(){
            var min = $(this).attr('data-min');
            var max = $(this).attr('data-max');
            var lot = $(this).attr('data-lot');
            var newHtml = html.replace(new RegExp("{min}",'g'),min);
            newHtml = newHtml.replace(new RegExp("{max}",'g'),max);
            newHtml = newHtml.replace(new RegExp("{lot}",'g'),lot);
            return newHtml;
          },
          placement: "left",
          html: true,
          title: 'В корзину <button type="button" class="close data-to-basket-close" style="top:-5px;position:relative;"><span>&times;</span></button>'
        });
        
        $('button[data-to-basket]').on('inserted.bs.popover', function(){
          var parent = $(this).parent();
          var button = $(parent).find('button.data-to-basket-close');
          var submit = $(parent).find('button.to-basket-btn');          
          var $this  = this;
          var data   = $(parent).parent().children('script').text();
          
          $(button).click(function(){            
            $($this).click();
          });
          
          $(submit).toBasket(JSON.parse(data)).done(function(){
            $($this).click();            
          }).fail(function( data){
            var alert = $('<div class="alert alert-danger" role="alert"></div>');            
            console.log(data);
            alert.html('Ошибка! ' + data);
            $(parent).find('div.popover-content').prepend(alert);
            $(submit).attr('disabled',true);
          });
          
        });
        
      });
    }
    
    return this.each(function($index,$item){
      $($item).click( load );
    });    
    
  };
  
  $.fn.main = function( options ) {
    
    var $this = this;
    var settings = $.extend({
    //Default values
    }, options );
  
    return this; 
  };
}( jQuery ));


