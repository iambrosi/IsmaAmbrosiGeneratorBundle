<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseWebTestCase;

/**
 * Class WebTestCase
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
abstract class WebTestCase extends BaseWebTestCase
{

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getBundle()
    {
        return static::$kernel->getBundle('IsmaAmbrosiGeneratorBundle');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getTestBundle()
    {
        return static::$kernel->getBundle('IsmaAmbrosiTestBundle');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager()
    {
        return static::$kernel->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Util\Filesystem
     */
    protected function getFilesystem()
    {
        return static::$kernel->getContainer()->get('filesystem');
    }
}
