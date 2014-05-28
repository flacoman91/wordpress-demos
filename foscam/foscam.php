<?php


function foscam_func( $atts ) {
     return "<img src='http://cw0101.myfoscam.org:20064/cgi-bin/CGIProxy.fcgi?cmd=snapPicture2&usr=user&pwd=user&t=' name='refresh' id='refresh' onload='reload(this)' onerror='reload(this)'> ";
}
add_shortcode('foscam', 'foscam_func');