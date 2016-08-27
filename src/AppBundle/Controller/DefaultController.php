<?php

namespace AppBundle\Controller;

use Github\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        ));
    }

    /**
     * @Route("/add", name="repo_add")
     */
    public function addAction() {
        try{
            $client = new Client();

            //token secret
            

            $repo = $client->api('repo')->create('repo', 'This is the description of a repo', 'http://maciejpiatek.pl', true);
//            $emails = $client->api('me')->emails()->all();

            var_dump($repo);
            return Response::create('test');
        }catch(Exception $e){
            return Response::json(array('failed',$e->getMessage()));
        }
    }
}
