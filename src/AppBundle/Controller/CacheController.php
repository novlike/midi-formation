<?php
/**
 * Created by PhpStorm.
 * User: hlecouey
 * Date: 29/11/2018
 * Time: 10:43
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheController extends Controller
{
    public function uniqIdAction()
    {
        return (new Response(uniqid()))->setSharedMaxAge(200);
    }

    /**
     * @Route("/home", name="cache_home")
     *
     * @param Request $request
     * @return Response
     */
    public function randomArticleAction(Request $request)
    {
        return (new Response(sprintf('version %s generated at %s', $request->headers->get('X-Version'), date('H:i:s'))))
            ->setSharedMaxAge(60)
            ->setVary('X-Version');
    }

    public function lastArticleAction()
    {
        return (new Response(uniqid()))->setSharedMaxAge(86400);
    }

    /**
     * @Route("/articles", name="some_articles")
     * @Cache(smaxage="10")
     * @return Response
     */
    public function someArticleAction()
    {
        /* Avec annotation, overwirte ne fonctionne pas car cela passe par eventListener et config également.
         L'eventListener de config a plus de poids que celui de l'annotation alors si on veut que
         un controlleur utilise son propre système de cache, faut écrire à l'interieur de la méthode.

         bin/console debug:event-dispatcher kernel.response permet de voir la liste des eventListener et leur poids
        */

        //return $this->render('articles/index.html.twig');
        return (new Response(sprintf('cache generated at %s', date('H:i:s'))))->setSharedMaxAge(10);
    }

    /**
     * @Route("/somethingelse", name="not_some_article")
     * @return Response
     */
    public function notSomeArticleAction()
    {
        return (new Response(sprintf('cache generated at %s', date('H:i:s'))));
    }
}
