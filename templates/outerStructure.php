<?php
        
    function wp_zillowbs_generalMarkup(){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $templatestr = WPZILLOWBS_TEMPLATESTR;
        
        $provBy = wp_zillow_bs_providedby();
        
        $template = <<<EOT
            <section class="zillow-data">
                {$provBy}
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#wpz-deepsearchresults" aria-controls="wpz-deepsearchresults" role="tab" data-toggle="tab">Home Details By Zillow</a></li>
                        <li role="presentation"><a href="#wpz-comps" aria-controls="wpz-comps" role="tab" data-toggle="tab">Comparable Recent Sales</a></li>
                        <li role="presentation"><a href="#wpz-neighborhood" aria-controls="wpz-neighborhood" role="tab" data-toggle="tab">Neighborhood</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        {$templatestr}
                    </div>
                </div>
            </section>
            
EOT;
        
        return $template;
    }

    function wp_zillowbs_tabbedMarkup($data){
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $templatestr = WPZILLOWBS_TEMPLATESTR;
        
        $provBy = wp_zillow_bs_providedby();

        $result = $data->response->results->result;
      
        $template = <<<EOT
            <section class="zillow-data">
                <div class="wp-z-bs-section clearfix">
                    
                    <p>
                        Zillow Details for 
                        {$result->address->street}, 
                        {$result->address->city} 
                        {$result->address->state} 
                        <!--latitude: {$result->address->latitude} 
                        longitude: {$result->address->longitude}<br>-->
                    </p>
                </div>
                {$provBy} 
                <div role="tabpanel">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#wpz-deepsearchresults" aria-controls="wpz-deepsearchresults" role="tab" data-toggle="tab">Home Details By Zillow</a></li>
                        <li role="presentation"><a href="#wpz-comps" aria-controls="wpz-comps" role="tab" data-toggle="tab">Comparable Recent Sales</a></li>
                        <li role="presentation"><a href="#wpz-chart" aria-controls="wpz-chart" role="tab" data-toggle="tab">Value History</a></li>
                        <li role="presentation"><a href="#wpz-neighborhood" aria-controls="wpz-neighborhood" role="tab" data-toggle="tab">Neighborhood</a></li>
                    </ul>
                    
                    <div class="tab-content">
                        {$templatestr}
                    </div>
                </div>
            </section>
            
EOT;
        
        return $template;
    }

    function wp_zillow_bs_providedby(){
        return '<p class="provided-by-zillow"><a href="http://www.zillow.com" target="_blank"><img src="http://www.zillow.com/widgets/GetVersionedResource.htm?path=/static/logos/Zillowlogo_150x40_rounded.gif" width="150" height="40" alt="Zillow Real Estate Search" /></a></p>';
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