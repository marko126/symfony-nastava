<?php

namespace AppBundle\Controller;

//use Symfony\Bundle\FrameworkBundle\Controller;
use Symfony\Component\HttpFoundation\Response;

class LuckyController {
    
    
    public function numberAction() {
        
        $number = mt_rand(0, 100);
        
        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
        
    }
    
}

