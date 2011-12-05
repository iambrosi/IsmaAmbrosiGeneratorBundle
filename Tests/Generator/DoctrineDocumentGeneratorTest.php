<?php

namespace IsmaAmbrosi\GeneratorBundle\Tests\Generator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use IsmaAmbrosi\GeneratorBundle\Generator\DoctrineDocumentGenerator;

/**
 * Class
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
class DoctrineDocumentGeneratorTest extends WebTestCase
{

    protected function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
    }

    public function testGenerator()
    {
        $container = static::$kernel->getContainer();
        $generator = new DoctrineDocumentGenerator($container->get('filesystem'), $container->get('doctrine.odm.mongodb.document_manager'));

        $generator->generate(
            static::$kernel->getBundle('IsmaAmbrosiGeneratorBundle'),
            'Translation',
            array(
                 array('fieldName'=> 'locale',
                       'type'     => 'string')
            ), false
        );
    }
}
