<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Items;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemsFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $item3 = new Items();
        $item3->setClubWebsite('http://www.bvb.de');
        $item3->setClubName('Borussia Dortmund');
        $item3->setClubEmblem('https://crests.football-data.org/4.png');
        $item3->setClubFounded('1909');
        $item3->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item3->setPrice(27.95);
        $item3->setName('Gregor Kobel');
        $item3->setNationality('Switzerland');
        $item3->setPosition('Goalkeeper');
        $item3->setThumbnail('https://images.pexels.com/photos/15033212/pexels-photo-15033212.jpeg');
        $item3->setTeamId('4');
        $item3->setItemId(334);
        $manager->persist($item3);

        $item4 = new Items();
        $item4->setClubWebsite('http://www.bvb.de');
        $item4->setClubName('Borussia Dortmund');
        $item4->setClubEmblem('https://crests.football-data.org/4.png');
        $item4->setClubFounded('1909');
        $item4->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item4->setPrice(27.95);
        $item4->setName('Alexander Meyer');
        $item4->setNationality('Germany');
        $item4->setPosition('Goalkeeper');
        $item4->setThumbnail('https://images.pexels.com/photos/8120617/pexels-photo-8120617.jpeg');
        $item4->setTeamId('4');
        $item4->setItemId(9389);
        $manager->persist($item4);

        $item5 = new Items();
        $item5->setClubWebsite('http://www.bvb.de');
        $item5->setClubName('Borussia Dortmund');
        $item5->setClubEmblem('https://crests.football-data.org/4.png');
        $item5->setClubFounded('1909');
        $item5->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item5->setPrice(29.95);
        $item5->setName('Marcel Lotka');
        $item5->setNationality('Poland');
        $item5->setPosition('Goalkeeper');
        $item5->setThumbnail('https://images.pexels.com/photos/3653343/pexels-photo-3653343.jpeg');
        $item5->setTeamId('4');
        $item5->setItemId(151077);
        $manager->persist($item5);

        $item6 = new Items();
        $item6->setClubWebsite('http://www.bvb.de');
        $item6->setClubName('Borussia Dortmund');
        $item6->setClubEmblem('https://crests.football-data.org/4.png');
        $item6->setClubFounded('1909');
        $item6->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item6->setPrice(25.95);
        $item6->setName('Silas Ostrzinski');
        $item6->setNationality('Germany');
        $item6->setPosition('Goalkeeper');
        $item6->setThumbnail('https://images.pexels.com/photos/15033219/pexels-photo-15033219.jpeg');
        $item6->setTeamId('4');
        $item6->setItemId(176268);
        $manager->persist($item6);

        $item7 = new Items();
        $item7->setClubWebsite('http://www.bvb.de');
        $item7->setClubName('Borussia Dortmund');
        $item7->setClubEmblem('https://crests.football-data.org/4.png');
        $item7->setClubFounded('1909');
        $item7->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item7->setPrice(39.95);
        $item7->setName('Mats Hummels');
        $item7->setNationality('Germany');
        $item7->setPosition('Defence');
        $item7->setThumbnail('https://images.pexels.com/photos/15033219/pexels-photo-15033219.jpeg');
        $item7->setTeamId('4');
        $item7->setItemId(350);
        $manager->persist($item7);

        $item8 = new Items();
        $item8->setClubWebsite('http://www.bvb.de');
        $item8->setClubName('Borussia Dortmund');
        $item8->setClubEmblem('https://crests.football-data.org/4.png');
        $item8->setClubFounded('1909');
        $item8->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item8->setPrice(29.95);
        $item8->setName('Niklas Süle');
        $item8->setNationality('Germany');
        $item8->setPosition('Defence');
        $item8->setThumbnail('https://images.pexels.com/photos/8007498/pexels-photo-8007498.jpeg');
        $item8->setTeamId('4');
        $item8->setItemId(351);
        $manager->persist($item8);

        $item9 = new Items();
        $item9->setClubWebsite('http://www.bvb.de');
        $item9->setClubName('Borussia Dortmund');
        $item9->setClubEmblem('https://crests.football-data.org/4.png');
        $item9->setClubFounded('1909');
        $item9->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item9->setPrice(32.95);
        $item9->setName('Thomas Meunier');
        $item9->setNationality('Belgium');
        $item9->setPosition('Defence');
        $item9->setThumbnail('https://images.pexels.com/photos/18256104/pexels-photo-18256104.jpeg');
        $item9->setTeamId('4');
        $item9->setItemId(3650);
        $manager->persist($item9);

        $item10 = new Items();
        $item10->setClubWebsite('http://www.bvb.de');
        $item10->setClubName('Borussia Dortmund');
        $item10->setClubEmblem('https://crests.football-data.org/4.png');
        $item10->setClubFounded('1909');
        $item10->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item10->setPrice(20.95);
        $item10->setName('Marius Wolf');
        $item10->setNationality('Germany');
        $item10->setPosition('Defence');
        $item10->setThumbnail('https://images.pexels.com/photos/7084055/pexels-photo-7084055.jpeg');
        $item10->setTeamId('4');
        $item10->setItemId(6714);
        $manager->persist($item10);

        $item11 = new Items();
        $item11->setClubWebsite('http://www.bvb.de');
        $item11->setClubName('Borussia Dortmund');
        $item11->setClubEmblem('https://crests.football-data.org/4.png');
        $item11->setClubFounded('1909');
        $item11->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item11->setPrice(39.95);
        $item11->setName('Ramy Bensebaini');
        $item11->setNationality('Algeria');
        $item11->setPosition('Defence');
        $item11->setThumbnail('https://images.pexels.com/photos/14073192/pexels-photo-14073192.jpeg');
        $item11->setTeamId('4');
        $item11->setItemId(8805);
        $manager->persist($item11);

        $item12 = new Items();
        $item12->setClubWebsite('http://www.bvb.de');
        $item12->setClubName('Borussia Dortmund');
        $item12->setClubEmblem('https://crests.football-data.org/4.png');
        $item12->setClubFounded('1909');
        $item12->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item12->setPrice(27.95);
        $item12->setName('Julian Ryerson');
        $item12->setNationality('Norway');
        $item12->setPosition('Defence');
        $item12->setThumbnail('https://images.pexels.com/photos/8007498/pexels-photo-8007498.jpeg');
        $item12->setTeamId('4');
        $item12->setItemId(44030);
        $manager->persist($item12);

        $item13 = new Items();
        $item13->setClubWebsite('http://www.bvb.de');
        $item13->setClubName('Borussia Dortmund');
        $item13->setClubEmblem('https://crests.football-data.org/4.png');
        $item13->setClubFounded('1909');
        $item13->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item13->setPrice(20.95);
        $item13->setName('Antonios Papadopoulos');
        $item13->setNationality('Germany');
        $item13->setPosition('Defence');
        $item13->setThumbnail('https://images.pexels.com/photos/17485149/pexels-photo-17485149.jpeg');
        $item13->setTeamId('4');
        $item13->setItemId(58521);
        $manager->persist($item13);

        $item14 = new Items();
        $item14->setClubWebsite('http://www.bvb.de');
        $item14->setClubName('Borussia Dortmund');
        $item14->setClubEmblem('https://crests.football-data.org/4.png');
        $item14->setClubFounded('1909');
        $item14->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item14->setPrice(21.99);
        $item14->setName('Nico Schlotterbeck');
        $item14->setNationality('Germany');
        $item14->setPosition('Defence');
        $item14->setThumbnail('https://images.pexels.com/photos/7556621/pexels-photo-7556621.jpeg');
        $item14->setTeamId('4');
        $item14->setItemId(75539);
        $manager->persist($item14);

        $item15 = new Items();
        $item15->setClubWebsite('http://www.bvb.de');
        $item15->setClubName('Borussia Dortmund');
        $item15->setClubEmblem('https://crests.football-data.org/4.png');
        $item15->setClubFounded('1909');
        $item15->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item15->setPrice(27.95);
        $item15->setName('Mateu Morey');
        $item15->setNationality('Spain');
        $item15->setPosition('Defence');
        $item15->setThumbnail('https://images.pexels.com/photos/15033210/pexels-photo-15033210.jpeg');
        $item15->setTeamId('4');
        $item15->setItemId(120984);
        $manager->persist($item15);

        $item16 = new Items();
        $item16->setClubWebsite('http://www.bvb.de');
        $item16->setClubName('Borussia Dortmund');
        $item16->setClubEmblem('https://crests.football-data.org/4.png');
        $item16->setClubFounded('1909');
        $item16->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item16->setPrice(25.95);
        $item16->setName('Ian Maatsen');
        $item16->setNationality('Netherlands');
        $item16->setPosition('Defence');
        $item16->setThumbnail('https://images.pexels.com/photos/3653331/pexels-photo-3653331.jpeg');
        $item16->setTeamId('4');
        $item16->setItemId(131120);
        $manager->persist($item16);

        $item17 = new Items();
        $item17->setClubWebsite('http://www.bvb.de');
        $item17->setClubName('Borussia Dortmund');
        $item17->setClubEmblem('https://crests.football-data.org/4.png');
        $item17->setClubFounded('1909');
        $item17->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item17->setPrice(20.95);
        $item17->setName('Guille Bueno');
        $item17->setNationality('Spain');
        $item17->setPosition('Defence');
        $item17->setThumbnail('https://images.pexels.com/photos/8111439/pexels-photo-8111439.jpeg');
        $item17->setTeamId('4');
        $item17->setItemId(177664);
        $manager->persist($item17);

        $item18 = new Items();
        $item18->setClubWebsite('http://www.bvb.de');
        $item18->setClubName('Borussia Dortmund');
        $item18->setClubEmblem('https://crests.football-data.org/4.png');
        $item18->setClubFounded('1909');
        $item18->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item18->setPrice(21.99);
        $item18->setName('Hendry Blank');
        $item18->setNationality('Germany');
        $item18->setPosition('Defence');
        $item18->setThumbnail('https://images.pexels.com/photos/18798576/pexels-photo-18798576.jpeg');
        $item18->setTeamId('4');
        $item18->setItemId(212298);
        $manager->persist($item18);

        $item19 = new Items();
        $item19->setClubWebsite('http://www.bvb.de');
        $item19->setClubName('Borussia Dortmund');
        $item19->setClubEmblem('https://crests.football-data.org/4.png');
        $item19->setClubFounded('1909');
        $item19->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item19->setPrice(25.95);
        $item19->setName('Marco Reus');
        $item19->setNationality('Germany');
        $item19->setPosition('Midfield');
        $item19->setThumbnail('https://images.pexels.com/photos/8120617/pexels-photo-8120617.jpeg');
        $item19->setTeamId('4');
        $item19->setItemId(138);
        $manager->persist($item19);

        $item20 = new Items();
        $item20->setClubWebsite('http://www.bvb.de');
        $item20->setClubName('Borussia Dortmund');
        $item20->setClubEmblem('https://crests.football-data.org/4.png');
        $item20->setClubFounded('1909');
        $item20->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item20->setPrice(39.95);
        $item20->setName('Jadon Sancho');
        $item20->setNationality('England');
        $item20->setPosition('Midfield');
        $item20->setThumbnail('https://images.pexels.com/photos/5246966/pexels-photo-5246966.jpeg');
        $item20->setTeamId('4');
        $item20->setItemId(146);
        $manager->persist($item20);

        $item21 = new Items();
        $item21->setClubWebsite('http://www.bvb.de');
        $item21->setClubName('Borussia Dortmund');
        $item21->setClubEmblem('https://crests.football-data.org/4.png');
        $item21->setClubFounded('1909');
        $item21->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item21->setPrice(21.99);
        $item21->setName('Salih Özcan');
        $item21->setNationality('Turkey');
        $item21->setPosition('Midfield');
        $item21->setThumbnail('https://images.pexels.com/photos/19079608/pexels-photo-19079608.jpeg');
        $item21->setTeamId('4');
        $item21->setItemId(201);
        $manager->persist($item21);

        $item22 = new Items();
        $item22->setClubWebsite('http://www.bvb.de');
        $item22->setClubName('Borussia Dortmund');
        $item22->setClubEmblem('https://crests.football-data.org/4.png');
        $item22->setClubFounded('1909');
        $item22->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item22->setPrice(29.95);
        $item22->setName('Emre Can');
        $item22->setNationality('Germany');
        $item22->setPosition('Midfield');
        $item22->setThumbnail('https://images.pexels.com/photos/7399346/pexels-photo-7399346.jpeg');
        $item22->setTeamId('4');
        $item22->setItemId(3183);
        $manager->persist($item22);

        $item23 = new Items();
        $item23->setClubWebsite('http://www.bvb.de');
        $item23->setClubName('Borussia Dortmund');
        $item23->setClubEmblem('https://crests.football-data.org/4.png');
        $item23->setClubFounded('1909');
        $item23->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item23->setPrice(20.95);
        $item23->setName('Felix Kalu Nmecha');
        $item23->setNationality('Germany');
        $item23->setPosition('Midfield');
        $item23->setThumbnail('https://images.pexels.com/photos/19079606/pexels-photo-19079606.jpeg');
        $item23->setTeamId('4');
        $item23->setItemId(101074);
        $manager->persist($item23);

        $item24 = new Items();
        $item24->setClubWebsite('http://www.bvb.de');
        $item24->setClubName('Borussia Dortmund');
        $item24->setClubEmblem('https://crests.football-data.org/4.png');
        $item24->setClubFounded('1909');
        $item24->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item24->setPrice(22.95);
        $item24->setName('Abdoulaye Kamara');
        $item24->setNationality('Guinea');
        $item24->setPosition('Midfield');
        $item24->setThumbnail('https://images.pexels.com/photos/15033215/pexels-photo-15033215.jpeg');
        $item24->setTeamId('4');
        $item24->setItemId(161415);
        $manager->persist($item24);

        $item25 = new Items();
        $item25->setClubWebsite('http://www.bvb.de');
        $item25->setClubName('Borussia Dortmund');
        $item25->setClubEmblem('https://crests.football-data.org/4.png');
        $item25->setClubFounded('1909');
        $item25->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item25->setPrice(25.95);
        $item25->setName('Jamie Bynoe-Gittens');
        $item25->setNationality('England');
        $item25->setPosition('Midfield');
        $item25->setThumbnail('https://images.pexels.com/photos/3653376/pexels-photo-3653376.jpeg');
        $item25->setTeamId('4');
        $item25->setItemId(179460);
        $manager->persist($item25);

        $item26 = new Items();
        $item26->setClubWebsite('http://www.bvb.de');
        $item26->setClubName('Borussia Dortmund');
        $item26->setClubEmblem('https://crests.football-data.org/4.png');
        $item26->setClubFounded('1909');
        $item26->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item26->setPrice(21.99);
        $item26->setName('Julian Brandt');
        $item26->setNationality('Germany');
        $item26->setPosition('Offence');
        $item26->setThumbnail('https://images.pexels.com/photos/19079601/pexels-photo-19079601.jpeg');
        $item26->setTeamId('4');
        $item26->setItemId(148);
        $manager->persist($item26);

        $item27 = new Items();
        $item27->setClubWebsite('http://www.bvb.de');
        $item27->setClubName('Borussia Dortmund');
        $item27->setClubEmblem('https://crests.football-data.org/4.png');
        $item27->setClubFounded('1909');
        $item27->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item27->setPrice(35.95);
        $item27->setName('Niclas Füllkrug');
        $item27->setNationality('Germany');
        $item27->setPosition('Offence');
        $item27->setThumbnail('https://images.pexels.com/photos/7928625/pexels-photo-7928625.jpeg');
        $item27->setTeamId('4');
        $item27->setItemId(304);
        $manager->persist($item27);

        $item28 = new Items();
        $item28->setClubWebsite('http://www.bvb.de');
        $item28->setClubName('Borussia Dortmund');
        $item28->setClubEmblem('https://crests.football-data.org/4.png');
        $item28->setClubFounded('1909');
        $item28->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item28->setPrice(35.95);
        $item28->setName('Sébastien Haller');
        $item28->setNationality('Ivory Coast');
        $item28->setPosition('Offence');
        $item28->setThumbnail('https://images.pexels.com/photos/19079607/pexels-photo-19079607.jpeg');
        $item28->setTeamId('4');
        $item28->setItemId(6721);
        $manager->persist($item28);

        $item29 = new Items();
        $item29->setClubWebsite('http://www.bvb.de');
        $item29->setClubName('Borussia Dortmund');
        $item29->setClubEmblem('https://crests.football-data.org/4.png');
        $item29->setClubFounded('1909');
        $item29->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item29->setPrice(19.99);
        $item29->setName('Donyell Malen');
        $item29->setNationality('Netherlands');
        $item29->setPosition('Offence');
        $item29->setThumbnail('https://images.pexels.com/photos/19917366/pexels-photo-19917366.jpeg');
        $item29->setTeamId('4');
        $item29->setItemId(7457);
        $manager->persist($item29);

        $item30 = new Items();
        $item30->setClubWebsite('http://www.bvb.de');
        $item30->setClubName('Borussia Dortmund');
        $item30->setClubEmblem('https://crests.football-data.org/4.png');
        $item30->setClubFounded('1909');
        $item30->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item30->setPrice(39.95);
        $item30->setName('Marcel Sabitzer');
        $item30->setNationality('Austria');
        $item30->setPosition('Offence');
        $item30->setThumbnail('https://images.pexels.com/photos/1884580/pexels-photo-1884580.jpeg');
        $item30->setTeamId('4');
        $item30->setItemId(9551);
        $manager->persist($item30);

        $item31 = new Items();
        $item31->setClubWebsite('http://www.bvb.de');
        $item31->setClubName('Borussia Dortmund');
        $item31->setClubEmblem('https://crests.football-data.org/4.png');
        $item31->setClubFounded('1909');
        $item31->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item31->setPrice(20.95);
        $item31->setName('Karim Adeyemi');
        $item31->setNationality('Germany');
        $item31->setPosition('Offence');
        $item31->setThumbnail('https://images.pexels.com/photos/5247136/pexels-photo-5247136.jpeg');
        $item31->setTeamId('4');
        $item31->setItemId(82515);
        $manager->persist($item31);

        $item32 = new Items();
        $item32->setClubWebsite('http://www.bvb.de');
        $item32->setClubName('Borussia Dortmund');
        $item32->setClubEmblem('https://crests.football-data.org/4.png');
        $item32->setClubFounded('1909');
        $item32->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item32->setPrice(20.95);
        $item32->setName('Gio Reyna');
        $item32->setNationality('USA');
        $item32->setPosition('Offence');
        $item32->setThumbnail('https://images.pexels.com/photos/15023680/pexels-photo-15023680.jpeg');
        $item32->setTeamId('4');
        $item32->setItemId(136733);
        $manager->persist($item32);

        $item33 = new Items();
        $item33->setClubWebsite('http://www.bvb.de');
        $item33->setClubName('Borussia Dortmund');
        $item33->setClubEmblem('https://crests.football-data.org/4.png');
        $item33->setClubFounded('1909');
        $item33->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item33->setPrice(21.99);
        $item33->setName('Ole Pohlmann');
        $item33->setNationality('Germany');
        $item33->setPosition('Offence');
        $item33->setThumbnail('https://images.pexels.com/photos/17395073/pexels-photo-17395073.jpeg');
        $item33->setTeamId('4');
        $item33->setItemId(142162);
        $manager->persist($item33);

        $item34 = new Items();
        $item34->setClubWebsite('http://www.bvb.de');
        $item34->setClubName('Borussia Dortmund');
        $item34->setClubEmblem('https://crests.football-data.org/4.png');
        $item34->setClubFounded('1909');
        $item34->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item34->setPrice(25.95);
        $item34->setName('Youssoufa Moukoko');
        $item34->setNationality('Germany');
        $item34->setPosition('Offence');
        $item34->setThumbnail('https://images.pexels.com/photos/15033216/pexels-photo-15033216.jpeg');
        $item34->setTeamId('4');
        $item34->setItemId(150817);
        $manager->persist($item34);

        $item35 = new Items();
        $item35->setClubWebsite('http://www.bvb.de');
        $item35->setClubName('Borussia Dortmund');
        $item35->setClubEmblem('https://crests.football-data.org/4.png');
        $item35->setClubFounded('1909');
        $item35->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item35->setPrice(29.95);
        $item35->setName('Samuel Bamba');
        $item35->setNationality('Germany');
        $item35->setPosition('Offence');
        $item35->setThumbnail('https://images.pexels.com/photos/8217358/pexels-photo-8217358.jpeg');
        $item35->setTeamId('4');
        $item35->setItemId(192607);
        $manager->persist($item35);

        $item36 = new Items();
        $item36->setClubWebsite('http://www.bvb.de');
        $item36->setClubName('Borussia Dortmund');
        $item36->setClubEmblem('https://crests.football-data.org/4.png');
        $item36->setClubFounded('1909');
        $item36->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item36->setPrice(20.95);
        $item36->setName('Paris Brunner');
        $item36->setNationality('Germany');
        $item36->setPosition('Offence');
        $item36->setThumbnail('https://images.pexels.com/photos/5247133/pexels-photo-5247133.jpeg');
        $item36->setTeamId('4');
        $item36->setItemId(244696);
        $manager->persist($item36);

        $item37 = new Items();
        $item37->setClubWebsite('http://www.bvb.de');
        $item37->setClubName('Borussia Dortmund');
        $item37->setClubEmblem('https://crests.football-data.org/4.png');
        $item37->setClubFounded('1909');
        $item37->setClubAddress('Rheinlanddamm 207-209 Dortmund 44137');
        $item37->setPrice(22.95);
        $item37->setName('Julien Duranville');
        $item37->setNationality('Belgium');
        $item37->setPosition('Forward');
        $item37->setThumbnail('https://images.pexels.com/photos/8111295/pexels-photo-8111295.jpeg');
        $item37->setTeamId('4');
        $item37->setItemId(182280);
        $manager->persist($item37);

        $manager->flush();
    }
}