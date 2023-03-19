<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
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

    /**
     * @Route("/request_check", name="request_check")
     */
    public function request_check()
    {
        return $this->render('check/check1.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     * @Route("/request_check1", name="request_check1")
     */
    public function request_check1(LoggerInterface $logger)
    {
        ob_start();
        ignore_user_abort(false);
        sleep(3);

        $logger->info('Request check 1');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 2');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 3');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 4');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 5');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 6');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 7');

        ob_end_flush();
        sleep(1);
        $logger->info('Request check 8');
        

        return $this->json([
            'message' => 'Request check 1',
        ]);
    }
}
