<?php

namespace Zf2Datagrid;

/**
 * Class Translator
 *
 * @package Zf2Datagrid
 */
trait Translator
{
    /**
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

    /**
     * @return \Zend\I18n\Translator\Translator
     */
    protected function getTranslator()
    {
        if (null === $this->translator) {
            $this->translator = $this->getServiceLocator()->get('translator');
        }

        return $this->translator;
    }
}
