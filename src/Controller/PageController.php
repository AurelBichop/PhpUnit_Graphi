<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/hello", name="page")
     */
    public function index()
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/auth", name="auth")
     * @IsGranted("ROLE_USER")
     */
    public function auth()
    {
        return $this->render('page/index.html.twig');
    }

    /**
    * @Route("/admin", name="admin")
    * @IsGranted("ROLE_ADMIN")
    */
    public function admin()
    {
        return $this->render('page/index.html.twig');
    }

    /**
     * @Route("/mail", name="mail")
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function mail(\Swift_Mailer $mailer){
        $message = (new \Swift_Message('Hello','In the body'))
            ->setFrom('noreply@domain.fr')
            ->setTo('contact@doe.fr');

        $mailer->send($message);
        return new Response('Reponse ok');
    }
}
