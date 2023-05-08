<?php

namespace Drupal\welcome_module\Controller;
use Drupal\Core\Controller\ControllerBase;
class WelcomeController extends ControllerBase
{
    public function welcome()
    {
        return [
            '#markup' => 'Hello, world',
        ];
    }
}

