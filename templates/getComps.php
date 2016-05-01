<?php 

    function zillow_bp_tgetComps($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $strings = wp_zillow_bp_strings();
        
        $result = $data->response->properties->comparables;
   
        $numResults = $result->count();
        
        $template = '';
        
        foreach($result->comp as $comp){
        
        $template .= <<<EOT
            <div role="tabpanel" class="tab-pane" id="wpz-comps">
                <div class="wp-z-bp-section clearfix">
                    <h3>Zillow Comparable Recent Sales</h3>
                    <p>
                        {$comp->address->street}<br>
                        {$comp->address->city} {$comp->address->state}<br>
                    </p>
                    <ul>
                        <li>{$strings['zestimate']}: \${$comp->zestimate->amount}</li>
                        <li>Last Updated: {$comp->zestimate->lastupdated}</li>
                        <li>Valuation Range: 
                            <ul>
                                <li>low: \${$comp->zestimate->valuationRange->low}</li>
                                <li>high: \${$comp->zestimate->valuationRange->high}</li>
                            </ul>
                        </li>
                    </ul>
                    <ul>
                        <li><a href="{$comp->links->homedetails}" target="_blank">Home Details</a></li>
                        <li><a href="{$comp->links->graphsanddata}" target="_blank">Graphs and Data</a></li>
                        <li><a href="{$comp->links->mapthishome}" target="_blank">Map This Home</a></li>
                        <li><a href="{$comp->links->comparables}" target="_blank">Comparables</a></li>
                    </ul>
                </div>
            </div>
            
EOT;
        }
        
        $toReturn = $template;
        
        return $toReturn;
    }
?>