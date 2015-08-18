<?php
/**
 * Cache connector
 */
class l3kSimilarProductsCacheConnector implements oxiCacheConnector
{
    /**
     * Default ttl in hour
     */
    const DEFAULT_TTL = 6;

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
     * @param array|null $aValues array of products
     * @param int    $iTTL   cache TTL
     *
     * @return bool
     */
    public function set( $sKey, $aValues = null, $iTTL = 0 )
    {
        if (!is_null($aValues) && is_array($aValues)){

            $oDb = oxDB::getDb();
            $sValidTillDate = $this->_getExpirationDateTime($iTTL);

            foreach ( $aValues as $sValue ) {
                $sSql = 'INSERT INTO `l3kSimilarProducts` SET ';
                $sSql .= '`cacheKey` = ' . $oDb->quote($sKey) . ', ';
                $sSql .= '`productId` = ' . $oDb->quote($sValue) . ', ';
                $sSql .= '`validTill` = ' . $oDb->quote($sValidTillDate);

                $blResult = $oDb->execute($sSql);
            }
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
        $aResult = oxDb::getDb()->getCol('SELECT `productId` FROM `l3kSimilarProducts` WHERE `validTill` >= NOW() AND `cacheKey`='.oxdb::getDb()->quote($sKey) );

        return $aResult;
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
        return oxDb::getDb()->execute('DELETE FROM `l3kSimilarProducts` WHERE `cacheKey`='.oxdb::getDb()->quote($sKey));
    }

    /**
     * Invalidate all items in the cache.
     *
     * @return bool
     */
    public function flush()
    {
        return oxDb::getDb()->execute('TRUNCATE TABLE `l3kSimilarProducts`');
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

    /**
     * Calculates expiration date
     *
     * @param $iTTL
     *
     * @return string
     */
    protected function _getExpirationDateTime( $iTTL = 0 )
    {
        $iTTL = ( $iTTL ) ? $iTTL : $this->_getDefaultTTL();

        return date( 'Y-m-d H:i:s', time() + $iTTL );
    }

    /**
     * Invalidates expired data
     *
     * @return bool
     */
    protected function _invalidateExpired()
    {
        return oxDb::getDb()->execute('DELETE FROM `l3kSimilarProducts` WHERE `validTill` < NOW()');
    }
}