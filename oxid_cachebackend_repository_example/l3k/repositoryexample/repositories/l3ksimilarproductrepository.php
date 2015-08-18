<?php

class l3kSimilarProductRepository
{
    /**
     * Default ttl in hour
     */
    const DEFAULT_TTL = 6;

    /**
     * @var oxCacheBackend
     */
    private $_cacheBackend = null;

    /**
     * @param oxCacheBackend $cacheBackend
     */
    public function setCacheBackend( $cacheBackend )
    {
        $this->_cacheBackend = $cacheBackend;
    }

    /**
     * @return oxCacheBackend
     */
    public function getCacheBackend()
    {
        return $this->_cacheBackend;
    }

    function __construct( $oCacheBackend = null )
    {
        if( is_null($oCacheBackend) ){

            $this->setCacheBackend( oxRegistry::get('oxCacheBackend') );

            //alternative configuration

            //$oCacheBackend = new oxCacheBackend();
            //$oCacheBackend->registerConnector( 'oxFileCacheConnector' );
            //$this->setCacheBackend( $oCacheBackend );
        }
    }

    /**
     * @param string $productId product identifier
     *
     * @return l3kSimilarProductList
     *
     */
    public function getSimilarProducts( $productId )
    {
        $aSimilarProducts = $this->_loadFromCache( $productId );

        if ( !is_null($aSimilarProducts) ){
            $aSimilarProducts = new l3kSimilarProductsList();
            $aSimilarProducts->load( $productId );

            $this->_storeToCache( $productId, $aSimilarProducts );
        }

        return $aSimilarProducts;
    }

    protected function _getKey( $productId )
    {
        return 'similar_products_'.$productId;
    }

    /**
     * @param $productId
     *
     * @return l3kSimilarProductsList
     */
    protected function _loadFromCache( $productId )
    {
        $oCacheItem = $this->getCacheBackend()->get( $this->_getKey( $productId ) );

        return $oCacheItem->getData();
    }

    /**
     * @param $productId
     * @param $aSimilarProducts
     */
    protected function _storeToCache( $productId, $aSimilarProducts )
    {
        $oCacheItem = new oxCacheItem();
        $oCacheItem->setData( $aSimilarProducts );
        $this->getCacheBackend()->set( $this->_getKey( $productId ), $oCacheItem, $this->_getDefaultTTL() );
    }

    /**
     * Returns default TTL in seconds (6 hours)
     *
     * @return int
     */
    protected function _getDefaultTTL()
    {
        return self::DEFAULT_TTL * 3600;
    }
}