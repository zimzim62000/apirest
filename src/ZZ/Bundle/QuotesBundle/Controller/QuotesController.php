<?php

namespace ZZ\Bundle\QuotesBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use ZZ\Bundle\QuotesBundle\Entity\Quotes;

class QuotesController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @return array
     * @View()
     */
    public function getQuotesAction()
    {
        $token = $this->get('security.context')->getToken();
        var_dump($token);

        $view = $this->view($this->getDoctrine()->getManager()->getRepository('ZZQuotesBundle:Quotes')->findAll());
        return $this->handleView($view, 200);
    }

    /**
     * @param Quotes $quote
     * @return array
     * @View()
     * @ParamConverter("quote", class="ZZQuotesBundle:Quotes")
     */
    public function getQuoteAction(Quotes $quote)
    {
        $view = $this->view($quote);
        return $this->handleView($view, 200);
    }

}
