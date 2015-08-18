<?php
/**
 * Metadata version
 */
$sMetadataVersion = '1.1';

/**
 * Module information
 */
$aModule = array(
    'id'           => 'repositoryexample',
    'title'        => 'Similar products cache',
    'description'  => 'Similar Product lists Cache for faster loading',
    'version'      => '1.0',
    'author'       => 'Linas Kukulskis',
    'extend'       => array(
        'oxwarticledetails' => 'l3k/repositoryExample/controllers/l3kwarticledetails'
    ),
    'files' => array(
        'l3ksimilarproductlist' => 'l3k/repositoryExample/models/l3ksimilarproductlist.php',
        'l3ksimilarproductrepository' => 'l3k/repositoryExample/repositories/l3ksimilarproductrepository.php',
    ),
);