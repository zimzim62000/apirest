<?php
namespace ZZ\Bundle\QuotesBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\DateTime;
use ZZ\Bundle\QuotesBundle\Entity\Quotes;

class LoadQuotesData implements FixtureInterface
{

    function load(ObjectManager $manager)
    {

        $quote1 = new Quotes();
        $quote1->setAuthor(1);
        $quote1->setCategory(1);
        $quote1->setText(
            '<p>Loren ipsum .. Loren ipsum .. Loren ipsum .. Loren ipsum .. Loren ipsum .. Loren ipsum ..</p>'
        );
        $quote1->setCreated(new \DateTime('now'));
        $quote1->setUpdated(new \DateTime('now'));


        $quote2 = new Quotes();
        $quote2->setAuthor(2);
        $quote2->setCategory(2);
        $quote2->setText(
            '<p>Loren ipsum  22.. Loren ipsum 22.. Loren ipsum .22. Loren ipsum .22. Loren ipsum .22. Loren ipsum 22..</p>'
        );
        $quote2->setCreated(new \DateTime('now'));
        $quote2->setUpdated(new \DateTime('now'));

        $manager->persist($quote1);
        $manager->persist($quote2);

        $manager->flush();
    }
}