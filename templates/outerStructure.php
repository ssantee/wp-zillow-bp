<?php
        
    function wp_zillowbs_generalMarkup(){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $markup = '<section class="zillow-data">' . WPZILLOWBS_TEMPLATESTR . '</section>';
        
        return $markup;
    }

    function wp_zillow_bs_providedby(){
        return '<a href="http://www.zillow.com" target="_blank"><img src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /></a>';
    }

    function wp_zillow_bs_global_footer(){
        
        global $wp_zillow_bs_gotdata;
        
        if($wp_zillow_bs_gotdata){
            return '<p class="zillow_required">&copy; Zillow, Inc., 2006-2014. Use is subject to <a href="" target="_blank">Terms of Use</a> | <a href="" target="_blank">What\'s a Zestimate?</a></p>';
        }
        else{
            return '';
        }
    }

?>