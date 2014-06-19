function reload()
{
   //foscam_options defined in foscam.php
   setTimeout('reloadImg("refresh")', foscam_options.refresh);
};

function reloadImg(id) 
{ 
   var obj = document.getElementById(id); 
   var date = new Date(); 
   obj.src = foscam_options.url + "&t=" + Math.floor(date.getTime()/foscam_options.refresh); 
};


jQuery(document).ready(function($) {   
    $('#btn-up').click(function(){
       moveCamera('up');
    });   
    $('#btn-down').click(function(){
       moveCamera('down');
    });
    $('#btn-left').click(function(){
       moveCamera('left');
    });
    $('#btn-right').click(function(){
       moveCamera('right');
    });
    
    function moveCamera(direction){
        var url = '';
            
        switch(direction){
            case 'reset':
                url = foscam_options.url_reset;
                break;
            case 'up':
                url = foscam_options.url_up;
                break;
            case 'down':
                url = foscam_options.url_down;
                break;
            case 'left':
                url = foscam_options.url_left;
                break;
            case 'right':
                url = foscam_options.url_right;
                break;
            case 'right':
                url = foscam_options.url_right;
                break;
        }    
        $.ajax({
            url: url,
            dataType: "jsonp",
            success: function (data) {
                console.log(data);
                alert(data);
            }
        });
        
        // have to tell it to stop
        $.ajax({
            url: foscam_options.url_stop,
            dataType: "jsonp",
            success: function (data) {
                console.log(data);
                alert(data);
            }
        });
    }    
});