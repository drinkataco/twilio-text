<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class TextController
{
    public function text()
    {
        return new Response(
            '<html><body>Text</body></html>'
        );
    }
}