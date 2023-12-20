<?php

namespace App\Tests\Components\Authentication\Presentation;

use App\Components\Authentication\Presentation\Form\LoginFormType;
use Symfony\Component\Form\Test\TypeTestCase;

class LoginFormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];

        $form = $this->factory->create(LoginFormType::class);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($formData['email'], $form->get('email')->getData());
        $this->assertEquals($formData['password'], $form->get('password')->getData());
    }

    public function testFormView()
    {
        $form = $this->factory->create(LoginFormType::class);

        $view = $form->createView();
        $children = $view->children;

        $this->assertArrayHasKey('email', $children);
        $this->assertArrayHasKey('password', $children);
        $this->assertArrayHasKey('submit', $children);

        $this->assertNotNull($view->offsetGet('email'));
        $this->assertNotNull($view->offsetGet('password'));
        $this->assertNotNull($view->offsetGet('submit'));
    }
}
