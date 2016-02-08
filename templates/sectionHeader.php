<?php 

    function zillow_bs_tsectionHeader($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $strings = wp_zillow_bs_strings();
        
        $result = $data->response->results->result;
        
        $template = <<<EOT
            
                <div class="wp-z-bs-section clearfix">
                    
                    <p>
                        Zillow Details for 
                        {$result->address->street}, 
                        {$result->address->city} 
                        {$result->address->state} 
                        latitude: {$result->address->latitude} 
                        longitude: {$result->address->longitude}<br>
                    </p>
                </div>
EOT;
        
        return $template;
    }
?>