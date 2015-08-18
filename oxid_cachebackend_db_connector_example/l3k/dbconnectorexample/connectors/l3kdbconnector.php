<?php
/**
 * Cache connector
 */
class l3kDbConnector implements oxiCacheConnector
{
    /**
     * Destructor. Invalidate all expired cache
     */
    public function __destruct()
    {
        $this->_invalidateExpired();
    }

    /**
     * Check if connector is available.
     *
     * @return bool
     */
    public static function isAvailable()
    {
        return true;
    }

    /**
     * Store single or multiple items.
     *
     * @param string $sKey key cache item.
     * @param oxCacheItem $oCacheItem array of products
     * @param int    $iTTL   cache TTL
     *
     * @return bool
     */
    public function set( $sKey, $oCacheItem = null, $iTTL = 0 )
    {
        if (!is_null($oCacheItem) && $oCacheItem instanceof oxCacheItem ){

            $oDb = oxDB::getDb();
            $sValidTillDate = $this->_getExpirationDateTime($iTTL);

            $sSql = 'INSERT INTO `l3kDbCache` SET ';
            $sSql .= '`cacheKey` = ' . $oDb->quote($sKey) . ', ';
            $sSql .= '`value` = ' . $oDb->quote(serialize($oCacheItem->getData())) . ', ';
            $sSql .= '`validTill` = ' . $oDb->quote($sValidTillDate);

            $blResult = $oDb->execute($sSql);
        } else {
            $blResult = $this->invalidate($sKey);
        }

        return $blResult;
    }

    /**
     * Retrieve single or multiple cache items.
     *
     * @param string $sKey key
     *
     * @return array
     */
    public function get( $sKey )
    {
        $aResult = oxDb::getDb()->getOne('SELECT `value` FROM `l3kDbCache` WHERE `validTill` >= NOW() AND `cacheKey`='.oxdb::getDb()->quote($sKey) );

        $oCacheItem = new oxCacheItem();
        $oCacheItem->setData(unserialize($aResult));

        return $oCacheItem;
    }

    /**
     * Invalidate single or multiple items.
     *
     * @param string $sKey key
     *
     * @return bool
     */
    public function invalidate( $sKey )
    {
        return oxDb::getDb()->execute('DELETE FROM `l3kDbCache` WHERE `cacheKey`='.oxdb::getDb()->quote($sKey));
    }

    /**
     * Invalidate all items in the cache.
     *
     * @return bool
     */
    public function flush()
    {
        return oxDb::getDb()->execute('TRUNCATE TABLE `l3kDbCache`');
    }

    /**
     * Calculates expiration date
     *
     * @param $iTTL
     *
     * @return string
     */
    protected function _getExpirationDateTime( $iTTL = 0 )
    {
        return date( 'Y-m-d H:i:s', time() + $iTTL );
    }

    /**
     * Invalidates expired data
     *
     * @return bool
     */
    protected function _invalidateExpired()
    {
        return oxDb::getDb()->execute('DELETE FROM `l3kDbCache` WHERE `validTill` < NOW()');
    }
}