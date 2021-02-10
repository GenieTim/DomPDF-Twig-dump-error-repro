<?php

namespace App\Controller;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    /**
     * @Route("/index", name="index")
     */
    public function index(): Response
    {
        $html = $this->renderView('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);

        // 
        $options = new Options();
        $options->setIsRemoteEnabled(true);
        $options->setIsHtml5ParserEnabled(true);
        $dompdf = new Dompdf($options);
        // disable some stuff to improve image loading; vgl. https://blog.skunkbad.com/php/dompdf-image-not-found-or-type-unknown
        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ],
            'curl' => [
                'curl_verify_ssl_host' => false,
                'curl_verify_ssl_peer' => false
            ]
        ]);
        $dompdf->setHttpContext($contxt);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', "portrait");
        $dompdf->render();
        $content = $dompdf->output();

        // assemble response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', 'application/pdf');
        $response->headers->set('Content-length', strval(strlen($content)));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent($content);

        return $response;
    }
}
