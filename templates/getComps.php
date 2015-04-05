<?php 
    function zillow_bs_tGetComps($data){
        
        require_once('outerStructure.php');
        
        $global = wp_zillowbs_generalMarkup();
        
        $result = $data->response->properties->comparables;
   
        $numResults = $result->count();
        
        $template = '<h2>Zillow Comparable Recent Sales</h3>';
        
        foreach($result->comp as $comp){
            var_dump($comp);
        
        $template .= <<<EOT
            
            <p>
                {$comp->address->street}<br>
                {$comp->address->city}<br>
                {$comp->address->state}
            </p>
            
            <ul>
                <li><a href="{$comp->links->homedetails}" target="_blank">Home Details</a></li>
                <li><a href="{$comp->links->mapthishome}" target="_blank">Map This Home</a></li>
            </ul>
            
            
EOT;
        }
        $toReturn = str_replace(WPZILLOWBS_TEMPLATESTR,$template,$global);
        
        return $toReturn;
    }
?>