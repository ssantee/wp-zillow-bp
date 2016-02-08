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
            $inputErrs = array(500,501,502,503,504,505,506,507,508);
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
            return str_replace(WPZILLOWBP_ERRSTR,$err,$this->errTemplate);
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
        
        public function applyTemplate($template, $data){
            
            $errs = $this->checkErrs($data);
            
            $output = '';
            
            if($errs[0]==true){
                $output = $this->errOutput($errs[1]);
            }
            else{
                $templateFunction = 'zillow_bp_t' . $template;
                
                require_once('templates/'.$template.'.php');
                
                $output = $templateFunction($data);
            }
            
            return $output;
            
        }
        
        public function getSearchResults($atts){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            //$address,$citystatezip,$rentz
            $address = $atts['address'];
            $citystatezip = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $rentz = 'false';
            
            $apiurl = 'GetSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $this->rentz;
            
            $result = $this->dohttp($apiurl);
            
            $this->zpid = $result->response->results->result->zpid;
            
            return $result;

        }
        
        public function getDeepSearchResults($atts){
            //http://www.zillow.com/webservice/GetDeepSearchResults.htm
            
            $address = $atts['address'];
            $citystatezip = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $rentz = 'false';
            
            $output = '';
            
            $apiurl = 'GetDeepSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $this->rentz;
            
            $result = $this->dohttp($apiurl);
            
            $this->zpid = $result->response->results->result->zpid;
            
            return $result;

        }
        
        public function getZestimate($atts){
            //http://www.zillow.com/webservice/GetZestimate.htm
            
            $apiurl = 'GetZestimate.htm?zpid=' . $this->zpid . '&rentzestimate=' . $this->rentz;
            return $this->dohttp($apiurl);
        }
        
        public function getChart($atts){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?zpid=' . $this->zpid . '&unit-type=dollar&width=400&height=200&chartDuration=5years';
            
            return $this->dohttp($apiurl); 
        }
        
        public function getComps($atts){
            //http://www.zillow.com/webservice/GetComps.htm
            $count = $this->compCount;
            
            $output = '';
            
            $apiurl = 'GetComps.htm?zpid=' . $this->zpid . '&count=' . $count . '&rentzestimate=' . $this->rentz;
            
            return $this->dohttp($apiurl);
           
        }
        
        public function getDeepComps($atts){
            //http://www.zillow.com/webservice/GetDeepComps.htm
            $apiurl = 'GetDeepComps.htm?$zpid=' . $this->zpid . '&count=' . $count . '&rentzestimate=' . $this->rentz;
            return $this->dohttp($apiurl);
        }
        
        public function getNeighborhood($atts){
        //http://www.zillow.com/webservice/GetDemographics.htm
            $apiurl = 'GetDemographics.htm?zip=' . $atts['zip'];
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
            
            $this->errTemplate = wp_zillowbp_errorTemplate();
            
            require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
            
            $this->strings = wp_zillow_bp_strings();
            
        }
        
    }
?>