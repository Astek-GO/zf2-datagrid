<?php

namespace Zf2Datagrid;

use Zend\I18n\Translator\Translator as ZfTranslator;

/**
 * Class Translator
 *
 * @package Zf2Datagrid
 */
trait Translator
{
    /**
     * @var ZfTranslator
     */
    protected $translator;

    /**
     * @return ZfTranslator
     */
    protected function getTranslator()
    {
        if (null === $this->translator) {
            $this->translator = $this->getServiceLocator()->get('translator');
        }

        return $this->translator;
    }
}