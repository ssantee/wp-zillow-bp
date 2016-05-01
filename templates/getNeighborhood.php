<?php 

    function zillow_bp_tgetNeighborhood($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
  
        $strings = wp_zillow_bp_strings();
        
        $result = $data->response->region->id;
    
        $strings['seeMore'] = str_replace('[address]',$result->address->street,$strings['seeMore']);        
        
        $template = <<<EOT
            <div role="tabpanel" class="tab-pane" id="wpz-neighborhood">
                <div class="wp-z-bp-section clearfix">
                    <h3>Neighborhood {$result}</h3>
                    
                </div>
            </div>
EOT;

        $toReturn = $template;
        
        return $toReturn;
    }
?>