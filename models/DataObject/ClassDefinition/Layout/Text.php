<?php

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Model\DataObject\ClassDefinition\Layout;

use Pimcore\Model;
use Pimcore\Model\DataObject\Concrete;

class Text extends Model\DataObject\ClassDefinition\Layout implements Model\DataObject\ClassDefinition\Data\LayoutDefinitionEnrichmentInterface
{
    /**
     * Static type of this element
     *
     * @internal
     *
     * @var string
     */
    public $fieldtype = 'text';

    /**
     * @internal
     *
     * @var string
     */
    public $html = '';

    /**
     * @internal
     *
     * @var string
     */
    public $renderingClass;

    /**
     * @internal
     *
     * @var string
     */
    public $renderingData;

    /**
     * @internal
     *
     * @var bool
     */
    public $border = false;

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     *
     * @return $this
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return string
     */
    public function getRenderingClass()
    {
        return $this->renderingClass;
    }

    /**
     * @param string $renderingClass
     */
    public function setRenderingClass($renderingClass)
    {
        $this->renderingClass = $renderingClass;
    }

    /**
     * @return string
     */
    public function getRenderingData()
    {
        return $this->renderingData;
    }

    /**
     * @param string $renderingData
     */
    public function setRenderingData($renderingData)
    {
        $this->renderingData = $renderingData;
    }

    /**
     * @return bool
     */
    public function getBorder(): bool
    {
        return $this->border;
    }

    /**
     * @param bool $border
     */
    public function setBorder(bool $border): void
    {
        $this->border = $border;
    }

    /**
     * {@inheritdoc}
     */
    public function enrichLayoutDefinition(/*?Concrete */ $object, /**  array */ $context = []) // : self
    {
        $renderer = Model\DataObject\ClassDefinition\Helper\DynamicTextResolver::resolveRenderingClass(
            $this->getRenderingClass()
        );

        $context['fieldname'] = $this->getName();
        $context['layout'] = $this;

        if ($renderer instanceof DynamicTextLabelInterface) {
            $result = $renderer->renderLayoutText($this->renderingData, $object, $context);
            $this->html = $result;
        }

        $twig = \Pimcore::getContainer()->get('twig');
        $template = $twig->createTemplate($this->html);
        $this->html = $template->render(array_merge($context,
            [
                'object' => $object,
            ]
        ));

        return $this;
    }
}
