<?php

namespace App\Service\Mapping;

use App\Entity\User;
use App\Model\DTO\hp_item;
use App\Model\DTO\UserDTO;
use Symfony\Component\HttpFoundation\Request;

class Mapper
{

    private function mapRandomImage(array $imageArray) : string
    {
        $entry = array_rand($imageArray['photos']);
        return $imageArray['photos'][$entry]['src']['original'];
    }

    /**
     * @param array $imageArray
     * @param array $footballArray
     * @return hp_item[]
     */
    public function mapItems(array $imageArray, array $footballArray) : array
    {
        $itemDTOArray = [];
        $prices = [29.95, 22.95, 39.95, 25.95, 20.95, 32.95, 35.95, 19.99, 27.95, 21.99]; // Array of prices

        foreach ($footballArray['squad'] as $footballItem) {
            $itemDTO = new hp_item();
            $itemDTO->clubWebsite = $footballArray['website'];
            $itemDTO->clubName = $footballArray['name'];
            $itemDTO->clubEmblem = $footballArray['crest'];
            $itemDTO->clubFounded = $footballArray['founded'];
            $itemDTO->clubAddress = $footballArray['address'];
            $itemDTO->id = $footballItem['id'];
            $itemDTO->price = $prices[array_rand($prices)]; // Select a random price
            $itemDTO->name = $footballItem['name'];
            $itemDTO->nationality = $footballItem['nationality'];
            $itemDTO->position = $footballItem['position'];
            $itemDTO->thumbnail = $this->mapRandomImage($imageArray);
            $itemDTOArray [] = $itemDTO;
        }

        return $itemDTOArray;
    }


    public function mapRequest2DTO(Request $request) : UserDTO
    {
        $user = new UserDTO();

        $user->email = $request->get('email', '');
        $user->username = $request->get('username', '');
        $user->password = $request->get('password', '');

        return $user;
    }
    public function getUserEntity(UserDTO $userDTO) : User
    {
        $user = new User();
        $user->setEmail($userDTO->email);
        $user->setPassword($userDTO->password);
        $user->setUsername($userDTO->username);

        return $user;
    }
}
