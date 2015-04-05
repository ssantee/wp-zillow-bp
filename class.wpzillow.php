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
            $servicErrs = [1,2,3,4];
            $inputErrs = [500,501,502,503,504,505,506,507];
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
        
        public function getZestimate($zpid,$rentz){
            //http://www.zillow.com/webservice/GetZestimate.htm
            $apiurl = 'GetZestimate.htm?zpid=' . $zpid . '&rentzestimate=' . $rentz;
            return $this->dohttp($apiurl);
        }
        public function getSearchResults($atts){
            //http://www.zillow.com/webservice/GetSearchResults.htm
            //$address,$citystatezip,$rentz
            $addr = $atts['address'];
            $csz = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $output = '';
            
            $apiurl = 'GetSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $rentz;
            $result = $this->dohttp($apiurl);
            
            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = '!<-- ERROR: ' . $errs[1] . ' -->';
            }
            else{
                require_once('templates/getSearchResults.php');
                $output = zillow-bs-tGetSearchResults();
            }
            
            return $output;
            
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

    function wpzillow_shortcodes($atts){
        //$atts = shortcode attributes
        //[zillow-data method="getSearchResults" city="" state="" zip=""]
        //var_dump($atts);
        $method = $atts['method'];
        
        
        $zo = new wpzillow();
        $zo->init('X1-ZWz1e1v29k9jwr_7q4l5');
        //$zo->zwsid = 'X1-ZWz1e1v29k9jwr_7q4l5';
        $data = $zo->$method($attrs);
        
        //var_dump($data->response->results->result->address);
        
        $output = '<div class="zillowdata">';
        //$output .= wp_kses_post($data->response->links->homedetails);
        $output .= '<a href="'.$data->response->results->result->links->mapthishome.'">'.$data->response->results->result->address->street . '</a>';
        $output .= '<h3>Zestimate!</h3><p>$'. $data->response->results->result->zestimate->amount .'</p>';
        $output .= '</div>';
        
        return $output;
        
    }
    
    function wpzillow_search(){
    
        
        
    }

?>