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
