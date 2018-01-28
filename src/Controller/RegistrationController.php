<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

class RegistrationController
{
    public function register()
    {
        $number = mt_rand(0, 100);

        return new Response(
            '<html><body>Register</body></html>'
        );
    }
}