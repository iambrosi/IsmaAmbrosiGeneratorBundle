<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Symfony\Bundle\DoctrineBundle\Mapping\MetadataFactory;
use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;

abstract class GenerateDoctrineCommand extends DoctrineCommand
{

    protected function parseShortcutNotation($shortcut)
    {
        $document = str_replace('/', '\\', $shortcut);

        if (false === $pos = strpos($document, ':')) {
            throw new \InvalidArgumentException(sprintf('The document name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $document));
        }

        return array(substr($document, 0, $pos), substr($document, $pos + 1));
    }
}
