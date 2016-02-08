<?php

    function wp_zillowbp_errorTemplate(){
        
        require_once(WPZILLOW__PLUGIN_DIR . '/language.php');
        
        $errStr = WPZILLOWBP_ERRSTR;
        
        $template = <<<EOT
            <h2>Sorry</h2>
            <p class="text-error">
                {$errStr}
            </p>
            
EOT;
    
        return $template;
        
    }
?>