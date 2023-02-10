<?php

namespace Alexartwww\Symfony\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController
{
    #[Route('/', methods: ['GET', 'HEAD'])]
    public function index(): Response
    {
        return new Response(
            '<html><body><h1>Hello world!</h1><p>'.date('c').'</p></body></html>'
        );
    }
}
