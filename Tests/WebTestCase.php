<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Tests;

/**
 * Class WebTestCase
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 */
abstract class WebTestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    protected function setUp()
    {
        $this->kernel = $this->getKernel();
        $this->kernel->boot();
    }

    protected function tearDown()
    {
        if (null !== $this->kernel) {
            $this->kernel->shutdown();
        }
    }

    /**
     * @return \Symfony\Component\HttpKernel\Bundle\BundleInterface
     */
    protected function getBundle()
    {
        return $this->kernel->getBundle('IsmaAmbrosiGeneratorBundle');
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    /**
     * @return \Symfony\Component\HttpKernel\Util\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->get('filesystem');
    }

    protected function get($service)
    {
        return $this->kernel->getContainer()->get($service);
    }

    /**
     * @return \Symfony\Component\HttpKernel\KernelInterface
     */
    private function getKernel()
    {
        /* @var $mock \Symfony\Component\HttpKernel\Kernel */
        $mock = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel')
            ->setConstructorArgs(array('test', true))
            ->setMethods(array('getLogDir', 'getCacheDir', 'registerBundles', 'registerContainerConfiguration'))
            ->getMock();

        $mock->expects($this->once())
            ->method('registerBundles')
            ->will($this->returnValue(array(
                new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
                new \Symfony\Bundle\DoctrineBundle\DoctrineBundle(),
                new \Symfony\Bundle\DoctrineMongoDBBundle\DoctrineMongoDBBundle(),
                new \IsmaAmbrosi\Bundle\GeneratorBundle\IsmaAmbrosiGeneratorBundle()
            ))
        );

        $mock->expects($this->any())
            ->method('getCacheDir')
            ->will($this->returnValue(sys_get_temp_dir().'/IsmaAmbrosi/cache'));

        $mock->expects($this->any())
            ->method('getLogDir')
            ->will($this->returnValue(sys_get_temp_dir().'/IsmaAmbrosi/logs'));

        return $mock;
    }

}
