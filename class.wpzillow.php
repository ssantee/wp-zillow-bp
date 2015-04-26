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
            return str_replace(WPZILLOWBS_ERRSTR,$err,$this->errTemplate);
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
                $output = $this->errOutput($errs[1]);
            }
            else{
                require_once('templates/getSearchResults.php');
                $output = zillow_bs_tGetSearchResults($result);
            }
            
            return $output;
            
        }
        
        public function getChart($atts){
            //http://www.zillow.com/webservice/GetChart.htm
            $apiurl = 'GetChart.htm?zpid=' . $this->zpid . '&unit-type=dollar&width=200&height=200&chartDuration=5years';
            
            $result = $this->dohttp($apiurl);
           
            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = $this->errOutput($errs[1]);
            }
            else{
                require_once('templates/getChart.php');
                $output = zillow_bs_tGetChart($result);
            }
            
            return $output;
        }
        
        public function getComps($atts){
            //http://www.zillow.com/webservice/GetComps.htm
            $count = $this->compCount;
            
            $output = '';
            
            $apiurl = 'GetComps.htm?zpid=' . $this->zpid . '&count=' . $count . '&rentzestimate=' . $this->rentz;
            
            $result = $this->dohttp($apiurl);
           
            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = $this->errOutput($errs[1]);
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
            
            $address = $atts['address'];
            $citystatezip = $atts['city']. ',' . $atts['state'] . ' ' . $atts['zip'];
            $rentz = 'false';
            
            $output = '';
            
            $apiurl = 'GetDeepSearchResults.htm?address=' . urlencode($address) . '&citystatezip=' . urlencode($citystatezip) . '&rentzestimate=' . $this->rentz;
            $result = $this->dohttp($apiurl);

            $errs = $this->checkErrs($result);
            
            if($errs[0]==true){
                $output = $this->errOutput($errs[1]);
            }
            else{
                require_once('templates/getDeepSearchResults.php');
                $output = zillow_bs_tGetDeepSearchResults($result);
            }
            
            return $output;
        }
        
        public function getUpdatedPropertyDetails($zpid){
            //http://www.zillow.com/webservice/GetUpdatedPropertyDetails.htm
            $apiurl = 'GetUpdatedPropertyDetails.htm?zpid=' . $this->zpid;
            return $this->dohttp($apiurl);
        }
        
        public function doPropertySearch(){
            
        }
        
        public function init($wsid){
            
            $this->zwsid = $wsid;
            
            $this->zpid = '';
            
            $this->compCount = '2';
            
            $this->rentz = 'false';
            
            require_once('templates/errTemplate.php');
            
            $this->errTemplate = wp_zillowbs_errorTemplate();
            
            require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
            
            $this->strings = wp_zillow_bs_strings();
            
        }
        
    }
    
    function atts_are_valid($atts){
    
        if(!isset($atts['address']) || !isset($atts['city']) || !isset($atts['state']) || !isset($atts['zip'])){
            return false;
        }
        else{
            return true;
        }
    }

    function wp_zillow_bs_doOuter($template){
    
        require_once('templates/outerStructure.php');
        
        $global = wp_zillowbs_generalMarkup();
        
        return str_replace(WPZILLOWBS_TEMPLATESTR,$template,$global);
        
    }

    function wp_zillowbs_shortcodes($atts){
        //$atts = shortcode attributes
        //[zillow-data method="getSearchResults" city="" state="" zip=""]
        
        $method = $atts['method'];
        
        $zwsid = get_option('wpzillow_zwsid');
        
        if(!$zwsid || !atts_are_valid($atts)){
            exit;
        }
        
        $zo = new wpzillow();
        $zo->init($zwsid);
        
        if($method == 'all'){
            $out = wp_zillowbs_shortcodes_master($atts);
        }
        
        else if($method == 'getSearchResults' || $method == 'getDeepSearchResults'){
            $out = $zo->$method($atts);
        }
        else{
            //must get zpid first
            $zo->setZpid($atts);
         
            $out = $zo->$method($atts);
        }
        
        global $wp_zillow_bs_gotdata;

        $wp_zillow_bs_gotdata = true;
        
        $out = wp_zillow_bs_doOuter($out);
        
        return $out . wp_zillow_bs_providedby();
        
    }
    
    function wp_zillowbs_shortcodes_master($atts){
    
        $zo = new wpzillow();
        $zo->init($zwsid);
        
        $zo->setZpid($atts);
         
        $out .= $zo->getDeepSearchResults();
        
        global $wp_zillow_bs_gotdata;

        $wp_zillow_bs_gotdata = true;
        
        $out = wp_zillow_bs_doOuter($out);
        
        return $out . wp_zillow_bs_providedby();
    
    }

    global $wp_zillow_bs_results;
    global $wp_zillow_bs_errs;

    $wp_zillow_bs_results = '';
    $wp_zillow_bs_errs = '';

    function wp_zillowbs_doPropertySearch($content){
    
        if ( !isset($_POST['wp_zillow_bs_search']) ) return;
        
        //if( !wp_verify_nonce( $_REQUEST['zillowsearch'], 'zillowsearch' ) ){
                
          //  wp_nonce_ays();
             
        //}
        
        $err = '';
        
        global $wp_zillow_bs_results;
        
        global $wp_zillow_bs_errs;
        
        $address  = ( isset($_POST['wp_zillow_bs_address']) )  ? trim(strip_tags($_POST['wp_zillow_bs_address'])) : null;
        $city = ( isset($_POST['wp_zillow_bs_city']) )  ? trim(strip_tags($_POST['wp_zillow_bs_city'])) : null;
        $zip = ( isset($_POST['wp_zillow_bs_zip']) )  ? trim(strip_tags($_POST['wp_zillow_bs_zip'])) : null;
        
        if($address == '' || $city == '' || $zip == ''){
            $wp_zillow_bs_errs = 'Address, City, and ZIP Code are required to search Zillow.';
        }
        else{
            $data = array(
                'method' => 'getSearchResults',
                'address' => $address,
                'city' => $city,
                'state' => 'FL',
                'zip' => $zip
            );

            $wp_zillow_bs_results = wp_zillowbs_shortcodes($data);
            
            global $wp_zillow_bs_gotdata;

            $wp_zillow_bs_gotdata = true;
        }
    }

    function wp_zillowbs_showPropertySearch(){
        
        global $wp_zillow_bs_results;
        
        echo ( $wp_zillow_bs_results );
        
    }

    function wp_zillowbs_showSearchErrors(){
        
        global $wp_zillow_bs_errs;
        if($wp_zillow_bs_errs != ''){
            require_once('templates/errTemplate.php');
        
            echo ( str_replace(WPZILLOWBS_ERRSTR,$wp_zillow_bs_errs,wp_zillowbs_errorTemplate()) );
        }
    }

    function wp_zillow_bs_footer(){
        $out = '';
        
        global $wp_zillow_bs_gotdata;
        
        if($wp_zillow_bs_gotdata){
            require_once('templates/outerStructure.php');
            
            $out = wp_zillow_bs_global_footer();
            
        }
        
        echo($out);
    }

?>