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
}
