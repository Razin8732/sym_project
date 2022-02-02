<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 **/
class HomeController extends AbstractController
{
    public function __construct()
    {
        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    }
    /**
     * @Route("/", name="homepage")
     */
    public function number()
    {

        return $this->render('home.html.twig', [
            'fname' => 'Test Name'
        ]);
        // return new Response(
        //     '<html><body>Lucky number: '.$number.'</body></html>'
        // );
    }
}
