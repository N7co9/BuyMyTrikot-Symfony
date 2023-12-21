<?php

namespace App\Tests\Components\ShoppingCart\Persistence;

use App\Components\ShoppingCart\Persistence\ShoppingCartRepository;
use App\Entity\Items;
use App\Entity\ShoppingCart;
use http\Exception\RuntimeException;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Components\ShoppingCart\Persistence\ShoppingCartEntityManager;
use App\Global\Persistence\Repository\UserRepository;
use App\Global\Persistence\Repository\ItemRepository;

class ShoppingCartEntityManagerTest extends TestCase
{
    public function testNoUser()
    {
        $slug = 'add';
        $request = $this->createMock(Request::class);
        $userRepository = $this->createMock(UserRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);
        $itemRepository = $this->createMock(ItemRepository::class);
        $security = $this->createMock(Security::class);
        $shoppingCartManager = new ShoppingCartEntityManager();

        $user = new User();
        $user->setEmail('test@test.com');

        $security->method('getUser')
            ->willReturn($user);

        $this->expectExceptionMessage('User not authenticated');

        $shoppingCartManager->manage(
            $slug,
            $request,
            $userRepository,
            $security,
            $entityManager,
            $cartRepository,
            $itemRepository
        );
    }
    public function testManageAddItemToCartEmptyCart()
    {
        $slug = 'add';
        $request = $this->createMock(Request::class);
        $userRepository = $this->createMock(UserRepository::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);
        $itemRepository = $this->createMock(ItemRepository::class);
        $security = $this->createMock(Security::class);


        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $user = new User();
        $user->setEmail('test@lol.com');
        $user->setPassword('Xyz12345*');

        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepository->expects($this->once())->method('findOneByEmail')->willReturn($user);

        $itemId = 1337;
        $request->expects($this->once())->method('get')->with('id')->willReturn($itemId);

        $cartRepository->expects($this->once())
            ->method('findOneByUserIdAndItemId')
            ->with($user->getId(), $itemId)
            ->willReturn(null);

        $itemToBeAdded = new Items();

        $itemRepository->expects($this->once())
            ->method('findOneByItemId')
            ->with($itemId)
            ->willReturn($itemToBeAdded);

        $shoppingCartManager = new ShoppingCartEntityManager();
        $shoppingCartManager->manage(
            $slug,
            $request,
            $userRepository,
            $security,
            $entityManager,
            $cartRepository,
            $itemRepository
        );
    }

    public function testManageAddItemToCartNotEmptyCart()
    {
        $slug = 'add';
        $request = $this->createMock(Request::class);
        $userRepository = $this->createMock(UserRepository::class);
        $security = $this->createMock(Security::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);
        $itemRepository = $this->createMock(ItemRepository::class);

        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $user = new User();
        $user->setEmail('test@lol.com');
        $user->setPassword('Xyz12345*');

        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepository->expects($this->once())->method('findOneByEmail')->willReturn($user);

        $itemId = 1337;
        $request->expects($this->once())->method('get')->with('id')->willReturn($itemId);

        $cart = new ShoppingCart();
        $cart->setItemId(1337);
        $cart->setQuantity(1);

        $cartRepository->expects($this->once())
            ->method('findOneByUserIdAndItemId')
            ->with($user->getId(), $itemId)
            ->willReturn($cart);

        $itemToBeAdded = new Items();

        $itemRepository->expects($this->once())
            ->method('findOneByItemId')
            ->with($itemId)
            ->willReturn($itemToBeAdded);

        $shoppingCartManager = new ShoppingCartEntityManager();
        $shoppingCartManager->manage(
            $slug,
            $request,
            $userRepository,
            $security,
            $entityManager,
            $cartRepository,
            $itemRepository
        );
    }

    public function testManageRemoveCartQuantityOver1()
    {
        $slug = 'remove';
        $request = $this->createMock(Request::class);
        $userRepository = $this->createMock(UserRepository::class);
        $security = $this->createMock(Security::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);
        $itemRepository = $this->createMock(ItemRepository::class);

        $entityManager->expects($this->once())->method('persist');
        $entityManager->expects($this->once())->method('flush');

        $user = new User();
        $user->setEmail('test@lol.com');
        $user->setPassword('Xyz12345*');

        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepository->expects($this->once())->method('findOneByEmail')->willReturn($user);

        $itemId = 1337;
        $request->expects($this->once())->method('get')->with('id')->willReturn($itemId);

        $cart = new ShoppingCart();
        $cart->setItemId(1337);
        $cart->setQuantity(3);

        $cartRepository->expects($this->once())
            ->method('findOneByUserIdAndItemId')
            ->with($user->getId(), $itemId)
            ->willReturn($cart);

        $itemToBeAdded = new Items();

        $itemRepository->expects($this->once())
            ->method('findOneByItemId')
            ->with($itemId)
            ->willReturn($itemToBeAdded);

        $shoppingCartManager = new ShoppingCartEntityManager();
        $shoppingCartManager->manage(
            $slug,
            $request,
            $userRepository,
            $security,
            $entityManager,
            $cartRepository,
            $itemRepository
        );
    }

    public function testManageRemoveCartQuantity1()
    {
        $slug = 'remove';
        $request = $this->createMock(Request::class);
        $userRepository = $this->createMock(UserRepository::class);
        $security = $this->createMock(Security::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);
        $itemRepository = $this->createMock(ItemRepository::class);

        $entityManager->expects($this->once())->method('remove');
        $entityManager->expects($this->once())->method('flush');

        $user = new User();
        $user->setEmail('test@lol.com');
        $user->setPassword('Xyz12345*');

        $security->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $userRepository->expects($this->once())->method('findOneByEmail')->willReturn($user);

        $itemId = 1337;
        $request->expects($this->once())->method('get')->with('id')->willReturn($itemId);

        $cart = new ShoppingCart();
        $cart->setItemId(1337);
        $cart->setQuantity(1);

        $cartRepository->expects($this->once())
            ->method('findOneByUserIdAndItemId')
            ->with($user->getId(), $itemId)
            ->willReturn($cart);

        $itemToBeAdded = new Items();

        $itemRepository->expects($this->once())
            ->method('findOneByItemId')
            ->with($itemId)
            ->willReturn($itemToBeAdded);

        $shoppingCartManager = new ShoppingCartEntityManager();
        $shoppingCartManager->manage(
            $slug,
            $request,
            $userRepository,
            $security,
            $entityManager,
            $cartRepository,
            $itemRepository
        );
    }

    public function testRemoveAllItemsFromUser()
    {
        $email = 'test@lol.com';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $userRepository = $this->createMock(UserRepository::class);
        $cartRepository = $this->createMock(ShoppingCartRepository::class);

        $user = new User();
        $user->id = 1;

        $userRepository->expects($this->once())
            ->method('findOneByEmail')
            ->with($email)
            ->willReturn($user);

        $shoppingCart1 = new ShoppingCart();
        $shoppingCart1->setId(1);

        $shoppingCart2 = new ShoppingCart();
        $shoppingCart2->setId(2);

        $shoppingCarts = [$shoppingCart1, $shoppingCart2];

        $cartRepository->expects($this->once())
            ->method('findByUserId')
            ->with($user->getId())
            ->willReturn($shoppingCarts);

        $entityManager->expects($this->exactly(2))
            ->method('remove');

        $entityManager->expects($this->once())->method('flush');

        $shoppingCartManager = new ShoppingCartEntityManager();
        $shoppingCartManager->removeAllItemsFromUser($email, $entityManager, $cartRepository, $userRepository);
    }
}
