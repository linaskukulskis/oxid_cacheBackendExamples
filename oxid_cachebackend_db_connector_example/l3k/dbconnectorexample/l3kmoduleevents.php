<?php
/**
 * Module event class
 */
class l3kModuleEvents
{
    /**
     * Execute action on activate event
     */
    public static function onActivate()
    {
        $sSql = "CREATE TABLE IF NOT EXISTS `l3kDbCache` (
          `cacheKey` varchar(255) NOT NULL,
          `value` varchar(255) NOT NULL,
          `validTill` datetime NOT NULL,
          PRIMARY KEY  (`cacheKey`),
          KEY `validTill` (`validTill`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        oxDb::getDb()->execute( $sSql );
    }

    /**
     * Execute action on deactivate event
     */
    public static function onDeactivate()
    {
        oxDb::getDb()->execute( 'DROP TABLE `l3kDbCache`' );
    }
}