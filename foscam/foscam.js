(function($){   
    $( document ).ready(function() {
       $('.foscam').each(function(){
           //console.log($(this));
           var id = $(this).attr('id');           
           var $foscam = $(this);           
           setInterval( function(){
               var src = console.log($foscam[0].src);
               var newurl = $foscam.data('url');
               //console.log($foscam.data('url'));
               var date = new Date(); 
               $foscam[0].src = newurl + "&t=" + Math.floor(date.getTime()/1000);
               console.log($foscam[0].src);
           }, 5000 );
       });
    });    
})(jQuery);
