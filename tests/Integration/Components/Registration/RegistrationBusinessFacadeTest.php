<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Registration;

use App\Components\Registration\Business\RegistrationBusinessFacade;
use App\DataFixtures\UserFixture;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class RegistrationBusinessFacadeTest extends KernelTestCase
{
    private readonly RegistrationBusinessFacade $registrationBusinessFacade;
    private readonly EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = self::getContainer()->get(EntityManagerInterface::class);
        $this->registrationBusinessFacade = self::getContainer()->get(RegistrationBusinessFacade::class);

        $this->loadUserFixture();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->close();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    protected function createRequest(): Request
    {
        $request = new Request();

        $request->request->set('email', 'valid@email.com');
        $request->request->set('username', 'Valid');
        $request->request->set('password', 'Xyz12345*');

        return $request;
    }

    public function testRegisterValid(): void
    {
        $request = $this->createRequest();

        $res = $this->registrationBusinessFacade->register($request);

        self::assertIsArray($res);
        self::assertEmpty($res);
    }

    /*
     * Exception testing begins here:
     */

    public function testRegisterEmailNotUnique(): void
    {
        $request = $this->createRequest();
        $request->request->set('email', 'John@doe.com');

        $res = $this->registrationBusinessFacade->register($request);

        self::assertSame('Email is not unique!!!', $res[0]->message);
        self::assertSame('ERROR', $res[0]->type);

    }

    public function testRegisterBadEmail(): void
    {
        $request = $this->createRequest();
        $request->request->set('email', '');

        $res = $this->registrationBusinessFacade->register($request);

        self::assertSame('Oops, your email doesn\'t look right', $res[0]->message);
        self::assertSame('Exception', $res[0]->type);
    }

    public function testRegisterBadUsername(): void
    {
        $request = $this->createRequest();
        $request->request->set('username', '');

        $res = $this->registrationBusinessFacade->register($request);

        self::assertSame('Oops, your name doesn\'t look right', $res[0]->message);
        self::assertSame('Exception', $res[0]->type);
    }

    public function testRegisterBadPassword(): void
    {
        $request = $this->createRequest();
        $request->request->set('password', '');

        $res = $this->registrationBusinessFacade->register($request);

        self::assertSame('Oops, your password doesn\'t look right!', $res[0]->message);
        self::assertSame('Exception', $res[0]->type);
    }

}