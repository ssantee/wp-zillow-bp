<?php 

    function zillow_bs_tgetNeighborhood($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
  
        $strings = wp_zillow_bs_strings();
        
        $result = $data->response->region->id;
    
        $strings['seeMore'] = str_replace('[address]',$result->address->street,$strings['seeMore']);
        
        $template = <<<EOT
            <div role="tabpanel" class="tab-pane" id="wpz-neighborhood">
                <div class="wp-z-bs-section span4 clearfix">
                    <h3>Neighborhood {$result}</h3>
                    
                </div>
            </div>
EOT;

        $toReturn = $template;
        
        return $toReturn;
    }
?>