<?php

class l3kArticle extends l3kArticle_parent
{

    /**
     * @var oxCacheBackend
     */
    private $_cacheBackend = null;
    /**
     * Default ttl in hour
     */
    const DEFAULT_TTL = 6;

    /**
     * Returns similar products list
     *
     * @return oxArticleList
     */
    public function getSimilarProducts()
    {
        $aSimilarProducts = $this->_loadSimilarProductsFromCache();

        if ( !$aSimilarProducts->count() ){
            $aSimilarProducts = $this->_loadSimilarProductsFromDb();
            $this->_storeSimilarProductsToCache( $aSimilarProducts );
        }

        return $aSimilarProducts;
    }

    /**
     * Load similar product list from db
     *
     * @return oxArticleList
     */
    protected function _loadSimilarProductsFromDb()
    {
        return parent::getSimilarProducts();
    }

    /**
     * Load similar product list from cache
     *
     * @return oxArticleList
     */
    protected function _loadSimilarProductsFromCache()
    {
        $oCacheItem = $this->_getSimilarProductCache()->get($this->_getKey());

        $aSimilarProductIds = $oCacheItem->getData();
        $oSimilarList = oxNew('oxArticleList');
        $oSimilarList->loadIds($aSimilarProductIds);

        return $oSimilarList;
    }

    /**
     * Returns cache key
     *
     * @return string
     */
    protected function _getKey()
    {
        return $this->getId();
    }

    /**
     * Returns configured cache backend
     *
     * @return oxCacheBackend
     */
    protected function _getSimilarProductCache()
    {
        if (is_null($this->_cacheBackend)) {
            $oCacheBackend = oxNew( 'oxCacheBackend' );
            $oCacheBackend->registerConnector( new l3kDbConnector() );
            $this->_cacheBackend = $oCacheBackend;
        }

        return $this->_cacheBackend;
    }

    /**
     * Store Similar products ids to cache
     *
     * @param oxArticleList $aSimilarProducts products list
     */
    protected function _storeSimilarProductsToCache( $aSimilarProducts )
    {
        $oCacheItem = oxNew( 'oxCacheItem' );
        $oCacheItem->setData( $aSimilarProducts->arrayKeys() );
        $this->_getSimilarProductCache()->set( $this->_getKey(), $oCacheItem,  $this->_getDefaultTTL());
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
