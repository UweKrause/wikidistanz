<?php

function w_url($un) {
    
    // https://de.wikipedia.org/wiki/Hamburg
    // <a href="http://www.tagesschau.de/">ARD Tagesschau</a>
    
    return '<a href="https://'. LAENDERCODE .'.wikipedia.org/wiki/'. $un. '">'. $un. '</a>';
    

}


?>