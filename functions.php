<?php
    
function atts_are_valid($atts){
    
        if(!isset($atts['address']) || !isset($atts['city']) || !isset($atts['state']) || !isset($atts['zip'])){
            return false;
        }
        else{
            return true;
        }
    }

    function queue_styles(){
        wp_enqueue_style( 'wp-zillow-bp-css', plugins_url('',__FILE__) . '/css/wp-zillow-bp.css', array(), '1', 'all' );
    }

    function wp_zillow_bp_doOuter($template){
    
        require_once('templates/outerStructure.php');
        
        $global = wp_zillowbp_generalMarkup();
        
        return str_replace(WPZILLOWBP_TEMPLATESTR,$template,$global);
        
    }

    function wp_zillow_bp_doAllOuter($template, $data){
        
        require_once('templates/outerStructure.php');
        
        $global = wp_zillowbp_tabbedMarkup($data);
        
        return str_replace(WPZILLOWbp_TEMPLATESTR,$template,$global);
        
    }

    function wp_zillowbp_shortcodes($atts){
        //$atts = shortcode attributes
        //[zillow-data method="getSearchResults" city="" state="" zip=""]
        
        $method = $atts['method'];
        
        $data;
        $out = '';
        
        $zwsid = get_option('wpzillow_zwsid');
        
        if(!$zwsid || !atts_are_valid($atts)){
            exit;
        }
        
        $zo = new wpzillow();
        $zo->init($zwsid);
        
        if($method == 'all'){
            $allResults = wp_zillowbp_shortcodes_master($atts);
            
            $out = $allResults['template'];
            
            $data = $allResults['data'];
       
        }
        
        else if($method !== 'getSearchResults' && $method !== 'getDeepSearchResults'){
            //must get zpid first
            $zo->setZpid($atts);
            
            $data = $zo->$method($atts);
            
            $out = $zo->applyTemplate($method, $data);
            
        }
        else{
            $data = $zo->$method($atts);
            
            $out = $zo->applyTemplate($method, $data);
            
            $out = wp_zillow_bp_doOuter($out);
        }
        
        global $wp_zillow_bp_gotdata;

        $wp_zillow_bp_gotdata = true;
        
        queue_styles();
        
        return $out; //. wp_zillow_bp_providedby();
        
    }
    
    function wp_zillowbp_shortcodes_master($atts){
    
        $zwsid = get_option('wpzillow_zwsid');
        
        $zo = new wpzillow();
        $zo->init($zwsid);
        
        $data;
        $out = '';
        
        //search data is special instance of data because it 
        //is used more than once in the templates
        //it gets its own var
        $searchData = $zo->getDeepSearchResults($atts);
        
        $out .= $zo->applyTemplate('getDeepSearchResults',$searchData);
        
        $data = $zo->getComps($atts);
        
        $out .= $zo->applyTemplate('getComps',$data);
        
        $data = $zo->getChart($atts);

        $out .= $zo->applyTemplate('getChart',$data);

        //$data = $zo->getNeighborhood($atts);

        //$out .= $zo->applyTemplate('getNeighborhood',$data);
        
        $out = wp_zillow_bp_doAllOuter($out, $searchData);

        //$out = $zo->applyTemplate('sectionHeader',$searchData) . $out;
     
        //getSearchResults or getDeepSearchResults will set the zpid
        //if called before other methods, make sure here
        if(!$zo->zpid){
            $zo->setZpid($atts);
        }
        
        return Array('template'=>$out, 'data'=>$searchData);
    
    }

    global $wp_zillow_bp_results;
    global $wp_zillow_bp_errs;

    $wp_zillow_bp_results = '';
    $wp_zillow_bp_errs = '';

    function wp_zillowbp_doPropertySearch($content){
    
        if ( !isset($_POST['wp_zillow_bp_search']) ) return;
        
        //if( !wp_verify_nonce( $_REQUEST['zillowsearch'], 'zillowsearch' ) ){
                
          //  wp_nonce_ays();
             
        //}
        
        $err = '';
        
        global $wp_zillow_bp_results;
        
        global $wp_zillow_bp_errs;
        
        $address  = ( isset($_POST['wp_zillow_bp_address']) )  ? trim(strip_tags($_POST['wp_zillow_bp_address'])) : null;
        $city = ( isset($_POST['wp_zillow_bp_city']) )  ? trim(strip_tags($_POST['wp_zillow_bp_city'])) : null;
        $zip = ( isset($_POST['wp_zillow_bp_zip']) )  ? trim(strip_tags($_POST['wp_zillow_bp_zip'])) : null;
        
        if($address == '' || $city == '' || $zip == ''){
            $wp_zillow_bp_errs = 'Address, City, and ZIP Code are required to search Zillow.';
        }
        else{
            $data = array(
                'method' => 'all',
                'address' => $address,
                'city' => $city,
                'state' => 'FL',
                'zip' => $zip
            );

            $wp_zillow_bp_results = wp_zillowbp_shortcodes($data);
            
            global $wp_zillow_bp_gotdata;

            $wp_zillow_bp_gotdata = true;
        }
    }

    function wp_zillowbp_showPropertySearch(){
        
        global $wp_zillow_bp_results;
        
        echo ( $wp_zillow_bp_results );
        
    }

    function wp_zillowbp_showSearchErrors(){
        
        global $wp_zillow_bp_errs;
        if($wp_zillow_bp_errs != ''){
            require_once('templates/errTemplate.php');
        
            echo ( str_replace(WPZILLOWBP_ERRSTR,$wp_zillow_bp_errs,wp_zillowbp_errorTemplate()) );
        }
    }

    function wp_zillow_bp_footer(){
        $out = '';
        
        global $wp_zillow_bp_gotdata;
        
        if($wp_zillow_bp_gotdata){
            require_once('templates/outerStructure.php');
            
            $out = wp_zillow_bp_global_footer();
            
        }
        
        echo($out);
    }
    ?>