<?php
    
    function wp_zillowbs_errorTemplate(){
        
        $errStr = WPZILLOWBS_ERRSTR;
        
        $template = <<<EOT
            <h2>Sorry</h2>
            <p class="text-error">
                {$errStr}
            </p>
            
EOT;
    
        return $template;
        
    }
?>