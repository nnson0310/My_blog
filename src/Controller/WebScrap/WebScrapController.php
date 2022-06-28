<?php

namespace App\Controller\WebScrap;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/backdoor/web/github", name="admin_web_")
 */
class WebScrapController extends AbstractController
{
    /**
     * @Route("/login", name="github_login", methods={"GET"})
     */
    public function index(): Response
    {
        $client = new Client(HttpClient::create([
            'verify_peer' => false,
            'verify_host' => false,
        ]));
        $crawler = $client->request('GET', 'https://www.edewakaru.com/');
        $text = $crawler->filter('.article-body-inner')->each(function ($node) {
            return $node->text();
        });
        dd($text);
    }
}
