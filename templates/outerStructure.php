<?php
    
    function wp_zillowbs_generalMarkup(){
        
        $markup = '<section class="zillow-data">{{zillowdata}}<img src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /></section>';
        
        return $markup;
    }

?>