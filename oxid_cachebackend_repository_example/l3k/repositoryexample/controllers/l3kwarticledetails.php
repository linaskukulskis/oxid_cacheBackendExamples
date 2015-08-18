<?php

class l3kwarticledetails extends l3kwarticledetails_parent
{
    /**
     * Template variable getter. Returns similar article list
     *
     * @return object
     */
    public function getSimilarProducts()
    {
        $oRepository = new l3kSimilarProductRepository();
        return $oRepository->getSimilarProduct($this->getProduct()->getId());
    }
}