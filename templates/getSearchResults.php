<?php 
    function zillow_bs_tGetSearchResults($data){
        
        require_once('outerStructure.php');
        
        $global = wp_zillowbs_generalMarkup();
        
        $result = $data->response->results->result;
    
        $template = <<<EOT
            <h2>Zillow Search Result</h3>
            <h3>Address</h3>
            <p>
                {$result->address->street}<br>
                {$result->address->city}<br>
                {$result->address->state}<br>
                latitude: {$result->address->latitude} 
                longitude: {$result->address->longitude}<br>
            </p>
            <h3>Links</h3>
            <ul>
                <li><a href="{$result->links->homedetails}" target="blank">Home Details</a></li>
                <li><a href="{$result->links->graphsanddata}" target="_blank">Graphs and Data</a></li>
                <li><a href="{$result->links->mapthishome}" target="_blank">Map This Home</a></li>
                <li><a href="{$result->links->comparables}" target="_blank">Comparables</a></li>
            </ul>
            <h3>Zestimate</h3>
            <ul>
                <li>Amount: {$result->zestimate->amount}</li>
                <li>Last Updated: {$result->zestimate->lastupdated}</li>
                <li>Value Change: {$result->zestimate->valuechange}</li>
                <li>Valuation Range: 
                    <ul>
                        <li>low: {$result->zestimate->valuationRange->low}</li>
                        <li>high: {$result->zestimate->valuationRange->high}</li>
                    </ul>
                </li>
            </ul>
            <h3>Local Real Estate</h3>
            <ul>
                <li><a target="_blank" href="{$result->localRealEstate->region->links->overview}">
                        Overview of {$result->localRealEstate->region->attributes()->name}
                    </a>
                </li>
                <li>
                    <a target="_blank" href="{$result->localRealEstate->region->links->forSale}">
                        More For Sale in {$result->localRealEstate->region->attributes()->name}
                    </a>
                </li>
            </ul>
            
EOT;

        $toReturn = str_replace(WPZILLOWBS_TEMPLATESTR,$template,$global);
        
        return $toReturn;
    }
?>