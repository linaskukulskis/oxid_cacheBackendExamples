<?php

class l3kArticle extends l3kArticle_parent
{
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

            $this->_getSimilarProductCache()->set( $this->_getKey(), $aSimilarProducts->arrayKeys() );
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
        $aSimilarProductIds = $this->_getSimilarProductCache()->get($this->_getKey());

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
        $oCacheBackend = oxNew( 'oxCacheBackend' );
        $oCacheBackend->registerConnector( new l3kSimilarProductsCacheConnector() );

        return $oCacheBackend;
    }
}
