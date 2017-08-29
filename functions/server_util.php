<?php

function prepare_cachefolders() {
    
    /**
     * for the cache to work properly, we need to know if
     * # the folders for the desired language is available
     * ## if not, create it
     * 
     * cachedirectories are not supposed to be put under versioncontrol,
     * so a gitignorefile will be added to each cachedirecory
     */
     
    $gitignorecontent = 
        "# Ignore everything in this directory\n
        *\n
        # Except this file\n
        !.gitignore\n";
    
    
    $cachedirs = [ARTICLECACHEDIR, BACKLINKCACHEDIR];
    
    foreach($cachedirs as $dir) {
        
        if(!is_dir($dir)) {
            mkdir($dir);
            
            $gitignorefile = $dir. '.gitignore';
            
            file_put_contents($gitignorefile, $gitignorecontent);
        }
    }
    
}


?>