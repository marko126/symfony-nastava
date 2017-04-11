<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller {
    
    /**
     * @Route(
     *      "/article/{_locale}/{page}.{_format}", 
     *      name="article_list", 
     *      requirements={
     *          "page" : "\d+",
     *          "_locale" : "sr|en|de",
     *          "_format" : "html|rss"
     *      }
     * )
     */
    public function listAction($page = 1, $_locale = "sr", $_format = "html") {
        
        switch ($_locale) {
            case "sr":
                $text = "Листа артикала, страна број ".$page;
                break;
            case "en":
                $text = "List of articles, page nr. ".$page;
                break;
            case "de":
                $text = "List von Artikeln, Seitenzahl ".$page;
                break;
        }
              
        return new Response(
            '<html><body>'.$text.'</body></html>'
        );
        
    }
    
    /**
     * @Route("/article/{name}", name="show_article")
     */
    public function showAction($name) {
        
        $list_article = $this->generateUrl("article_list", []);
        
        return new Response(
            '<html><body>Details of article '.$name.'; All articles are here '.$list_article.'</body></html>'
        );
        
    }
    
}

