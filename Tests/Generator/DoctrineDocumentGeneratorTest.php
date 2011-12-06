<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests\Generator;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;
use IsmaAmbrosi\Bundle\GeneratorBundle\Generator\DoctrineDocumentGenerator;

/**
 * Class DoctrineDocumentGeneratorTest
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

    protected function tearDown()
    {
        $this->getFilesystem()->remove($this->getBundle()->getPath() . DIRECTORY_SEPARATOR . 'Document');
    }

    public function testGenerator()
    {
        $generator = new DoctrineDocumentGenerator($this->getFilesystem(), $this->getDocumentManager());

        $generator->generate(
            $bundle = $this->getBundle(),
            'Translation',
            array(
                 array('fieldName'=> 'locale',
                       'type'     => 'string')
            ), false
        );

        $this->assertFileExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . 'Translation.php');
        $this->assertFileNotExists($bundle->getPath() . DIRECTORY_SEPARATOR . 'Document' . DIRECTORY_SEPARATOR . 'TranslationRepository.php');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    private function getBundle()
    {
        return static::$kernel->getBundle('IsmaAmbrosiGeneratorBundle');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    private function getDocumentManager()
    {
        return static::$kernel->getContainer()->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Util\Filesystem
     */
    private function getFilesystem()
    {
        return static::$kernel->getContainer()->get('filesystem');
    }
}
