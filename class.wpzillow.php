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
            
            $result = curl_exec($ch);
            
            return $this->parseXML($result);
        }
        public function getZestimate($zpid,$rentz){
            //http://www.zillow.com/webservice/GetZestimate.htm
            $apiurl = 'GetZestimate.htm?zpid=' . $zpid . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getSearchResults($address,$citystatezip,$rentz){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            $apiurl = 'GetSearchResults.htm?address=' . $address . '&citystatezip=' . $citystatezip . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getChart($zpid,$unitType,$width,$height,$duration){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?zpid=' . $zpid . '&unit-type=' . $unitType . '&width=' . $width . '&height=' . $height . '&chartDuration=' . $duration;
            return $this->dohttp($apiurl);
        }
        public function getComps($zpid,$count,$rentz){
            //http://www.zillow.com/webservice/GetComps.htm
            $apiurl = 'GetComps.htm?zpid=' . $zpid . '&count=' . $count . '&rentzestimate' . $rentz;
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
        
        public function init(){
            $this->zwsid;
        }
        
    }

    $zo = new wpzillow();
    $zo->zwsid = 'X1-ZWz1e1v29k9jwr_7q4l5';
    $return = $zo->getZestimate('48749425','false');
    var_dump($return);
?>