<?php

class l3kSimilarProductsList extends oxList
{
    /**
     * Loads list
     */
    public function load( $sProductId )
    {
        $this->selectString( $this->_getSql( $sProductId ) );
    }

    /**
     * Generates SQL
     *
     * @param string $sProductId product Id
     *
     * @return string
     */
    protected function _getSql( $sProductId )
    {
        //generates sql

    }

}
