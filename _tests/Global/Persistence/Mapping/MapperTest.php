<?php

namespace App\Tests\Global\Persistence\Mapping;
use App\Entity\User;
use App\Global\DTO\ItemDTO;
use App\Global\DTO\UserDTO;
use App\Global\Service\Mapping\Mapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class MapperTest extends TestCase
{
    private Mapper $mapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mapper = new Mapper();
    }

    public function testMapRandomImage()
    {
        $imageArray = [
            'photos' => [
                ['src' => ['original' => 'image1.jpg']],
                ['src' => ['original' => 'image2.jpg']],
                ['src' => ['original' => 'image3.jpg']],
            ],
        ];

        $randomImage = $this->mapper->mapRandomImage($imageArray);

        $this->assertIsString($randomImage);
        $this->assertStringStartsWith('image', $randomImage);
    }

    public function testMapItems()
    {
        $images = [
            'photos' => [
                ['src' => ['original' => 'image1.jpg']],
                ['src' => ['original' => 'image2.jpg']],
            ],
        ];

        $footballArray = [
            'squad' => [
                ['id' => 1, 'name' => 'Player1', 'nationality' => 'Country1', 'position' => 'Position1'],
                ['id' => 2, 'name' => 'Player2', 'nationality' => 'Country2', 'position' => 'Position2'],
            ],
            'website' => 'example.com',
            'name' => 'Club Name',
            'crest' => 'club_crest.jpg',
            'founded' => 2000,
            'address' => 'Club Address',
            'id' => 123,
        ];

        $itemDTOArray = $this->mapper->mapItems($images, $footballArray);

        $this->assertIsArray($itemDTOArray);
        $this->assertCount(2, $itemDTOArray);
        $this->assertInstanceOf(ItemDTO::class, $itemDTOArray[0]);
        $this->assertInstanceOf(ItemDTO::class, $itemDTOArray[1]);
    }

    public function testMapRequest2DTO()
    {
        $request = new Request(['email' => 'test@example.com', 'username' => 'user123', 'password' => 'password123']);

        $userDTO = $this->mapper->mapRequest2DTO($request);

        $this->assertInstanceOf(UserDTO::class, $userDTO);
        $this->assertEquals('test@example.com', $userDTO->email);
        $this->assertEquals('user123', $userDTO->username);
        $this->assertEquals('password123', $userDTO->password);
    }

    public function testGetUserEntity()
    {
        $userDTO = new UserDTO();
        $userDTO->email = 'test@example.com';
        $userDTO->username = 'user123';
        $userDTO->password = 'password123';

        $user = $this->mapper->getUserEntity($userDTO);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('user123', $user->getUsername());
        $this->assertEquals('password123', $user->getPassword());
    }
}
