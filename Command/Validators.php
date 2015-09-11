<?php

namespace IsmaAmbrosi\Bundle\GeneratorBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\Validators as BaseValidators;

/**
 * Class Validators.
 *
 * @author Ismael Ambrosi<ismaambrosi@gmail.com>
 */
class Validators extends BaseValidators
{
    /**
     * Validates the document name.
     *
     * @static
     *
     * @param string $document
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public static function validateDocumentName($document)
    {
        if (false === strpos($document, ':')) {
            throw new \InvalidArgumentException(sprintf('The document name must contain a : ("%s" given, expecting something like AcmeBlogBundle:Blog/Post)', $document));
        }

        return $document;
    }
}
