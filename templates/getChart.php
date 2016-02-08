<?php 

    function zillow_bp_tgetChart($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $strings = wp_zillow_bp_strings();
        
        $result = $data->response->url;
 
        $template = <<<EOT
            <div role="tabpanel" class="tab-pane" id="wpz-chart">
                <img src="{$result}" alt="" />
            </div>
            
EOT;

        $toReturn = $template;
        
        return $toReturn;
    }
?>