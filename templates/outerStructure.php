<?php
    
    function wp_zillowbs_generalMarkup(){
        
        $markup = '<section class="zillow-data">{{zillowdata}}<a href="http://www.zillow.com" target="_blank"><img src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /></a></section>';
        
        return $markup;
    }

?>