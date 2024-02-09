<?php
declare(strict_types=1);

namespace App\Tests\Integration\Components\Orderflow;

use App\Components\Orderflow\Business\OrderFlowBusinessFacade;
use App\DataFixtures\ItemsFixture;
use App\DataFixtures\OrdersFixture;
use App\DataFixtures\ShoppingCartFixture;
use App\DataFixtures\UserFixture;
use App\Global\DTO\OrderDTO;
use App\Global\DTO\UserDTO;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;


/*
 * Integration testing with fixtures. Are Unit tests necessary?
 */

/*
 * Yeah, I know these are a lot of fixtures. is the Test overloaded?
 */

class OrderFlowBusinessFacadeTest extends KernelTestCase
{
    private readonly OrderFlowBusinessFacade $orderFlowBusinessFacade;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $this->orderFlowBusinessFacade = $container->get(OrderFlowBusinessFacade::class);
        $this->entityManager = $container->get(EntityManagerInterface::class);

        $connection = $this->entityManager->getConnection();
        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');
        $connection->close();

        $this->loadUserFixture();
        $this->loadItemsFixture();
        $this->loadShoppingCartFixture();
        $this->loadOrdersFixture();


        parent::setUp();
    }

    protected function tearDown(): void
    {
        $connection = $this->entityManager->getConnection();

        $connection->executeQuery('DELETE FROM user');
        $connection->executeQuery('ALTER TABLE user AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM shopping_cart');
        $connection->executeQuery('ALTER TABLE shopping_cart AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM orders');
        $connection->executeQuery('ALTER TABLE orders AUTO_INCREMENT=0');

        $connection->executeQuery('DELETE FROM items');
        $connection->executeQuery('ALTER TABLE items AUTO_INCREMENT=0');

        $connection->close();

        parent::tearDown();
    }

    protected function createSampleOrderDTO(): OrderDTO
    {
        $orderDTO = new OrderDTO();

        $orderDTO->id = 123;
        $orderDTO->email = 'john.doe@example.com';
        $orderDTO->firstName = 'John';
        $orderDTO->lastName = 'Doe';
        $orderDTO->address = '123 Main Street';
        $orderDTO->city = 'Anytown';
        $orderDTO->state = 'Anystate';
        $orderDTO->zip = '12345';
        $orderDTO->phoneNumber = '+4821939012';
        $orderDTO->deliveryMethod = 'Standard';
        $orderDTO->paymentMethod = 'Credit Card';
        $orderDTO->due = 199.99;

        $orderDTO->items = [
            'item1',
            'item2',
        ];

        return $orderDTO;
    }

    protected function createSampleUserDTO(): UserDTO
    {
        return new UserDTO(
            id: 1,
            email: 'jane.doe@example.com',
            username: 'JaneDoe',
            password: 'securePassword123'
        );
    }

    protected function loadUserFixture(): void
    {
        (new UserFixture())->load($this->entityManager);
    }

    protected function loadShoppingCartFixture(): void
    {
        (new ShoppingCartFixture())->load($this->entityManager);
    }

    protected function loadOrdersFixture(): void
    {
        (new OrdersFixture())->load($this->entityManager);
    }

    protected function loadItemsFixture(): void
    {
        (new ItemsFixture())->load($this->entityManager);
    }

    public function testFindOneByEmail(): void
    {
        $res = $this->orderFlowBusinessFacade->findOneByEmail('John@doe.com');
        self::assertSame(1, $res->getId());
        self::assertSame('John Doe', $res->getUsername());
        self::assertSame('Qwertz123', $res->getPassword());
    }

    public function testGetItemsInCart(): void
    {
        $res = $this->orderFlowBusinessFacade->getItemsInCart(1);


        self::assertSame(3, $res[0]->quantity);
        self::assertSame(5, $res[1]->quantity);

        self::assertSame(334, $res[0]->itemId);
        self::assertSame(176268, $res[1]->itemId);
    }

    public function testGetMostRecentOrder(): void
    {
        $res = $this->orderFlowBusinessFacade->getMostRecentOrder('John@doe.com');


        self::assertSame(3, $res->getId()); // the id might change due to autoincrement
        self::assertSame('John@doe.com', $res->getEmail());
        self::assertSame('John', $res->getFirstName());
        self::assertSame('Doe', $res->getLastName());
        self::assertSame('62704', $res->getZip());
        self::assertSame('Standard', $res->getDeliveryMethod());

    }

    public function testFindItemsByArrayOfIds(): void
    {
        $arrayOfItemIds = [
            [
                'id' => 334
            ],
            [
                'id' => 9389
            ]
        ];

        $res = $this->orderFlowBusinessFacade->findItemsByArrayOfIds($arrayOfItemIds);


        self::assertSame('1909', $res[0]->getClubFounded());
        self::assertSame(27.95, $res[0]->getPrice());
        self::assertSame('Gregor Kobel', $res[0]->getName());

        self::assertSame('1909', $res[1]->getClubFounded());
        self::assertSame(27.95, $res[1]->getPrice());
        self::assertSame('Alexander Meyer', $res[1]->getName());
    }

    public function testCreateOrderValid(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        self::assertIsArray($res);
        self::assertEmpty($res);
    }

    /*
     * Exception testing begins here:
     */

    public function testCreateOrderWithEmptyPhoneNumber(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->phoneNumber = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('phoneNumber', $res);
        $this->assertEquals('Phone number cannot be empty and must be a string.', $res['phoneNumber']->message);
        $this->assertEquals('Error', $res['phoneNumber']->type);
    }

    public function testCreateOrderWithEmptyAddress(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->address = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('address', $res);
        $this->assertEquals('Address cannot be empty and must be a string.', $res['address']->message);
        $this->assertEquals('Error', $res['address']->type);
    }

    public function testCreateOrderWithInvalidFirstName(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->firstName = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('firstName', $res);
        $this->assertEquals('First name cannot be empty and must be a string.', $res['firstName']->message);
        $this->assertEquals('Error', $res['firstName']->type);
    }

    public function testCreateOrderWithInvalidLastName(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->lastName = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('lastName', $res);
        $this->assertEquals('Last name cannot be empty and must be a string.', $res['lastName']->message);
        $this->assertEquals('Error', $res['lastName']->type);
    }

    public function testCreateOrderWithInvalidCity(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->city = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('city', $res);
        $this->assertEquals('City cannot be empty and must be a string.', $res['city']->message);
        $this->assertEquals('Error', $res['city']->type);
    }

    public function testCreateOrderWithInvalidState(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->state = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('region', $res);
        $this->assertEquals('State cannot be empty and must be a string.', $res['region']->message);
        $this->assertEquals('Error', $res['region']->type);
    }

    public function testCreateOrderWithInvalidZipCode(): void
    {
        $orderDTO = $this->createSampleOrderDTO();
        $orderDTO->zip = '';

        $userDTO = $this->createSampleUserDTO();

        $res = $this->orderFlowBusinessFacade->createOrder($orderDTO, $userDTO);
        $this->assertArrayHasKey('zip', $res);
        $this->assertEquals('Zip code cannot be empty and must be a string.', $res['zip']->message);
        $this->assertEquals('Error', $res['zip']->type);
    }

    /*
     * End of Exceptions Testing!
     */

    public function testMapRequestOrderToDto(): void
    {

        $request = new Request();

        $request->request->set('first-name', 'John');
        $request->request->set('last-name', 'Doe');
        $request->request->set('address', 'AnyStreet');
        $request->request->set('city', 'NYC');
        $request->request->set('region', 'AMERICCAA');
        $request->request->set('postal-code', '40404');
        $request->request->set('phone', '+494010232');
        $request->request->set('delivery-method', 'Standard');
        $request->request->set('payment-type', 'eTransfer');
        $request->request->set('totalCost', 100.00);
        $request->request->set('shippingCost', 4.95);

        $res = $this->orderFlowBusinessFacade->mapRequestOrderToDto($request);


        self::assertSame('John', $res->firstName);
        self::assertSame('Doe', $res->lastName);
        self::assertSame('AnyStreet', $res->address);
        self::assertSame('NYC', $res->city);

    }
}