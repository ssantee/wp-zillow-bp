<?php
    
    class wpzillow{
        
        function parseXML($res){
            return simplexml_load_string($res);
        }
        function buildUrl($args){
            
            $urlPrefix = 'http://www.zillow.com/webservice/';
            
            return $urlPrefix . $args . '&zws-id=' . $this->zwsid;
            
        }
        public function dohttp($uri){
            
            $uri = $this->buildUrl($uri);
            
            //$result = wp_remote_get( $uri );
            
            $ch = curl_init($uri);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);

            $ret = $this->parseXML($result);
            
            return $ret;
            
        }
        public function getZestimate($zpid,$rentz){
            //http://www.zillow.com/webservice/GetZestimate.htm
            $apiurl = 'GetZestimate.htm?zpid=' . $zpid . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getSearchResults($address,$citystatezip,$rentz){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            $apiurl = 'GetSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getChart($zpid,$unitType,$width,$height,$duration){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?zpid=' . $zpid . '&unit-type=' . $unitType . '&width=' . $width . '&height=' . $height . '&chartDuration=' . $duration;
            return $this->dohttp($apiurl);
        }
        public function getComps($zpid,$count,$rentz){
            //http://www.zillow.com/webservice/GetComps.htm
            $apiurl = 'GetComps.htm?zpid=' . $zpid . '&count=' . $count . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getDeepComps($zpid,$count,$rentz){
            //http://www.zillow.com/webservice/GetDeepComps.htm
            $apiurl = 'GetDeepComps.htm?$zpid=' . $zpid . '&count=' . $count . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getDeepSearchResults($address,$citystatezip,$rentz){
            //http://www.zillow.com/webservice/GetDeepSearchResults.htm
            $apiurl = 'GetDeepSearchResults.htm?$address=' . $address . '&citystatezip=' . $citystatezip . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getUpdatedPropertyDetails($zpid){
            //http://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm
            $apiurl = 'GetUpdatedPropertyDetails.htm?zpid=' . $zpid;
            return $this->dohttp($apiurl);
        }
        
        public function init($wsid){
            $this->zwsid = $wsid;
        }
        
    }
    

//refactor this to return markup from the get... method calls

    function wpzillow_start($atts){
        //$atts = shortcode attributes
        //[zillow-data method="getSearchResults" city="" state="" zip=""]
        //var_dump($atts);
        $method = $atts['method'];
        $addr = $atts['address'];
        $csz = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
        
        $zo = new wpzillow();
        $zo->init('X1-ZWz1e1v29k9jwr_7q4l5');
        //$zo->zwsid = 'X1-ZWz1e1v29k9jwr_7q4l5';
        $data = $zo->$method($addr,$csz,'false');
        var_dump($data->response->results->result->address);
        $output = '<div class="zillowdata">';
        //$output .= wp_kses_post($data->response->links->homedetails);
        $output .= '<a href="'.$data->response->results->result->links->mapthishome.'">'.$data->response->results->result->address->street . '</a>';
        $output .= '<h3>Zestimate!</h3><p>$'. $data->response->results->result->zestimate->amount .'</p>';
        $output .= '</div>';
        
        return $output;
        
    }
    
?>