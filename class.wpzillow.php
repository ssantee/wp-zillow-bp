<?php
    
    class $wpzillow{
        
        this->$zwsid;
        function parseXML($res){
            return simplexml_load_string($res);
        }
        function buildUrl($args){
            $urlPrefix = 'http://www.zillow.com/webservice/';
            
            return $urlPrefix . $args . '&zwsid=' . this.$zwsid;
            
        }
        public function dohttp($uri){
            $result = wp_remote_get( $uri );
        }
        public function getZestimate($zpid,$rentz){
            //http://www.zillow.com/webservice/GetZestimate.htm
            $apiurl = 'GetZestimate.htm?';
        }
        public function getSearchResults($address,$citystatezip,$rentz){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            $apiurl = 'GetSearchResults.htm?';
        }
        public function getChart($zpid,$unitType,$width,$height,$duration){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?';
        }
        public function getComps($zpid,$count,$rentz){
            //http://www.zillow.com/webservice/GetComps.htm
            $apiurl = 'GetComps.htm?';
        }
        public function getDeepComps($zpid,$count,$rentz){
            //http://www.zillow.com/webservice/GetDeepComps.htm
            $apiurl = 'GetDeepComps.htm?';
        }
        public function getDeepSearchResults($address,$citystatezip,$rentz){
            //http://www.zillow.com/webservice/GetDeepSearchResults.htm
            $apiurl = 'GetDeepSearchResults.htm?';
        }
        public function getUpdatedPropertyDetails($zpid){
            //http://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm
            $apiurl = 'GetUpdatedPropertyDetails.htm?';
        }
        
    }

?>