<?php 
/*
important notes from zillow terms

    You may not separate address information from the Zestimate® 
    or Rent Zestimate valuation for the property at that address 
    or the for-sale or for-rent information, if applicable.
    
    
*/

    function zillow_bs_tgetDeepSearchResults($data){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $strings = wp_zillow_bs_strings();
        
        $result = $data->response->results->result;
    
        $strings['seeMore'] = str_replace('[address]',$result->address->street,$strings['seeMore']);
        
        $template = <<<EOT
            <div role="tabpanel" class="tab-pane row active clearfix" id="wpz-deepsearchresults">
                
                
                <div class="wp-z-bs-section span4 clearfix">
                    <h3>Details</h3>
                    <ul>
                        <li>Type: {$result->useCode}</li>
                        <li>Year Built: {$result->yearBuilt }</li>
                        <li>Lot Size: {$result->lotSizeSqFt}</li>
                        <li>Size: {$result->finishedSqFt}</li>
                        <li>Bedrooms: {$result->bedrooms}</li>
                        <li>Bathrooms: {$result->bathrooms}</li>
                        <li>Last Sold: {$result->lastSoldDate}</li>
                        <li>Last Sold For: \${$result->lastSoldPrice}</li>
                        <li></li>
                    </ul>
                </div>
                <div class="wp-z-bs-section span4 clearfix">
                    <h3>{$strings['zestimate']}</h3>
                    <ul>
                        <li>Amount: \${$result->zestimate->amount}</li>
                        <li>Last Updated: {$result->zestimate->lastupdated}</li>
                        <li>Value Change: \${$result->zestimate->valuechange}</li>
                        <li>Valuation Range: 
                            <ul>
                                <li>low: \${$result->zestimate->valuationRange->low}</li>
                                <li>high: \${$result->zestimate->valuationRange->high}</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="wp-z-bs-section span4 clearfix">
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
                </div>
                <div class="wp-z-bs-section span4 clearfix">
                    <h3>Links</h3>
                    <ul>
                        <li><a href="{$result->links->homedetails}" target="blank">Home Details</a></li>
                        <li><a href="{$result->links->graphsanddata}" target="_blank">Graphs and Data</a></li>
                        <li><a href="{$result->links->mapthishome}" target="_blank">Map This Home</a></li>
                        <li><a href="{$result->links->comparables}" target="_blank">Comparables</a></li>
                    </ul>
                </div>
                <div class="wp-z-bs-section span4 clearfix">
                    <p><a href="{$result->links->homedetails}" target="_blank">{$strings['seeMore']}</a></p>
                </div>
            </div>
EOT;

        $toReturn = $template;
        
        return $toReturn;
    }
?>