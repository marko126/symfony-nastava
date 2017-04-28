<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\ArticleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ArticleController extends Controller {
    
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
        
        $repository = $this->getDoctrine()->getRepository('AppBundle:Article');
        
        $query = $repository->createQueryBuilder('a')
                ->where('a.price < 100000000')
                ->orderBy('a.name', 'DESC')
                ->getQuery();
        
        $artikli = $query->getResult();
        
        return $this->render('article/list.html.twig', [
            'text' => $text,
            'artikli' => $artikli
        ]);
        
    }
    
    
    public function showAction($id, $name) {
        
        //$artikal = $this->findArticleByTitle($name);
        
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery(
                'SELECT a
                 FROM AppBundle:Article a
                 WHERE a.id = :id
                '
        )->setParameter('id', $id);
        
        $artikal = $query->setMaxResults(1)->getOneOrNullResult();
        
        if (!$artikal) {
            throw $this->createNotFoundException('No artical found for id '.$artical->id);
        }
        
        return $this->render('article/show.html.twig', ['artikal' => $artikal]);
        
    }
    
    /**
     * /@Security("has_role('ROLE_ADMIN')")
     * @param Request $request
     * @return type
     */
    public function createAction(Request $request) {
        
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Gde si pošo bre?');
        
        $article = new Article();
        
        /*
        $form = $this->createFormBuilder($article)
                ->add('name', TextType::class, ['label' => 'Naziv'])
                ->add('category', TextType::class, ['label' => 'Kategorija'])
                ->add('price', NumberType::class, ['label' => 'Cena'])
                ->add('Submit', SubmitType::class, ['label' => 'Unesi'])
                ->getForm();
        */
        
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            
            $em->persist($article);
            
            $em->flush();
            
            return $this->redirectToRoute('article_list');
            
        }
        
        return $this->render('article/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    public function updateAction(Request $request, $id) {
        
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Gde si pošo bre kad nisi super?');
        
        // Pozivamo entity manager
        $em = $this->getDoctrine()->getManager();
        
        // Pozivamo repozitorijum za entity Article
        $repository = $em->getRepository('AppBundle:Article');
        
        // Prikupljamo objekat entity klase Article
        $article = $repository->find($id);
        
        /*
        $form = $this->createFormBuilder($article)
                ->add('name', TextType::class, ['label' => 'Naziv'])
                ->add('category', TextType::class, ['label' => 'Kategorija'])
                ->add('price', NumberType::class, ['label' => 'Cena'])
                ->add('Submit', SubmitType::class, ['label' => 'Unesi'])
                ->getForm();
        */
        $form = $this->createForm(ArticleType::class, $article);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $article = $form->getData();
            
            $em->flush();
            
            return $this->redirectToRoute('article_list');
            
        }
        
        return $this->render('article/update.html.twig', [
            'form' => $form->createView()
        ]);
        
    }
    
    public function findArticleByTitle($title) {
        foreach ($this->artikli as $artikal) {
            if (in_array($title, $artikal)) {
                return $artikal;
            }
        }
        return false;
    }
    
}

