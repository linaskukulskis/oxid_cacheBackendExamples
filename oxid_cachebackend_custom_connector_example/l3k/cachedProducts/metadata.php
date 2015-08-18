<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'cachedProducts',
    'title'        => 'Similar products cache',
    'description'  => 'Similar products cache for faster loading',
    'version'      => '1.0',
    'author'       => 'Linas Kukulskis',
    'extend'       => array(
        'oxarticle' => 'l3k/cachedProducts/models/l3karticle'
    ),
    'files' => array(
        'l3ksimilarproductscacheconnector' => 'l3k/cachedProducts/connectors/l3ksimilarproductscacheconnector.php',
        'l3kmoduleevents' => 'l3k/cachedProducts/l3kmoduleevents.php',
    ),
    'events'       => array(
        'onActivate'   => 'l3kmoduleevents::onActivate',
        'onDeactivate' => 'l3kmoduleevents::onDeactivate'
    ),
);