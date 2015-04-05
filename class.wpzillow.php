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
        
        private function checkErrs($res){
            
            $err = false;
            $msg = '';
            $serviceErrs = array(1,2,3,4);
            $inputErrs = array(500,501,502,503,504,505,506,507);
            $resCode = $res->message->code;
            
            if(in_array($resCode,$serviceErrs)){
                //zillow service interruption
                
                $err = true;
                $msg = 'zillow service not available';
                
            }
            if(in_array($resCode,$inputErrs)){
                $err = true;
                $msg = 'invalid search or no results';
            }
            
            return [$err,$msg];
            
        }
        
        private function errOutput($err){
            return str_replace('{{zillowerr}}',$err,$this.errTemplate);
        }
        
        public function setZpid($atts){
            
            $this->zpid = $this->getZpid($atts);
            
        }
        
        private function getZpid($atts){
            $address = $atts['address'];
            $citystatezip = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $rentz = 'false';
            
            $output = '';
            
            $apiurl = 'GetSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $this->rentz;
            $result = $this->dohttp($apiurl);
            
            return $result->response->results->result->zpid;
        }
        
        public function getZestimate($atts){
            //http://www.zillow.com/webservice/GetZestimate.htm
            
            $apiurl = 'GetZestimate.htm?zpid=' . $this->zpid . '&rentzestimate=' . $this->rentz;
            return $this->dohttp($apiurl);
        }
        
        public function getSearchResults($atts){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            //$address,$citystatezip,$rentz
            $address = $atts['address'];
            $citystatezip = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $rentz = 'false';
            
            $output = '';
            
            $apiurl = 'GetSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $this->rentz;
            $result = $this->dohttp($apiurl);

            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = errOutput($errs[1]);
            }
            else{
                require_once('templates/getSearchResults.php');
                $output = zillow_bs_tGetSearchResults($result);
            }
            
            return $output;
            
        }
        
        public function getChart($atts){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?zpid=' . $this->zpid . '&unit-type=' . $unitType . '&width=' . $width . '&height=' . $height . '&chartDuration=' . $duration;
            return $this->dohttp($apiurl);
        }
        
        public function getComps($atts){
            //http://www.zillow.com/webservice/GetComps.htm
            $count = $this->compCount;
            
            $output = '';
            
            $apiurl = 'GetComps.htm?zpid=' . $this->zpid . '&count=' . $count . '&rentzestimate=' . $this->rentz;
            
            $result = $this->dohttp($apiurl);
           
            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = errOutput($errs[1]);
            }
            else{
                require_once('templates/getComps.php');
                $output = zillow_bs_tGetComps($result);
            }
            
            return $output;
            
        }
        
        public function getDeepComps($atts){
            //http://www.zillow.com/webservice/GetDeepComps.htm
            $apiurl = 'GetDeepComps.htm?$zpid=' . $this->zpid . '&count=' . $count . '&rentzestimate=' . $this->rentz;
            return $this->dohttp($apiurl);
        }
        
        public function getDeepSearchResults($atts){
            //http://www.zillow.com/webservice/GetDeepSearchResults.htm
            $apiurl = 'GetDeepSearchResults.htm?$address=' . $address . '&citystatezip=' . $citystatezip . '&rentzestimate=' . $this->rentz;
            return $this->dohttp($apiurl);
        }
        
        public function getUpdatedPropertyDetails($zpid){
            //http://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm
            $apiurl = 'GetUpdatedPropertyDetails.htm?zpid=' . $this->zpid;
            return $this->dohttp($apiurl);
        }
        
        public function init($wsid){
            
            $this->zwsid = $wsid;
            
            $this->zpid = '';
            
            $this->compCount = '2';
            
            $this->rentz = 'false';
            
            require_once('templates/errTemplate.php');
            
            $this->errTemplate = wp_zillowbs_errorTemplate();
            
        }
        
    }
    
    function wpzillow_shortcodes($atts){
        //$atts = shortcode attributes
        //[zillow-data method="getSearchResults" city="" state="" zip=""]
        
        $method = $atts['method'];
        
        $zo = new wpzillow();
        $zo->init('X1-ZWz1e1v29k9jwr_7q4l5');
        
        if($method == 'getSearchResults' || $method == 'getDeepSearchResults'){
            $out = $zo->$method($atts);
        }
        else{
            //must get zpid first
            $zo->setZpid($atts);
         
            $out = $zo->$method($atts);
        }
        
        return $out;
        
    }
    
    function wpzillow_search(){
    
        
        
    }

?>