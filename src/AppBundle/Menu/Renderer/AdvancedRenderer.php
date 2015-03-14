<?php

namespace AppBundle\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

class AdvancedRenderer extends ListRenderer
{
    /**
     * @param MatcherInterface $matcher
     * @param array $defaultOptions
     * @param null|string $charset
     */
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], $charset = null)
    {
        // Initialize default options
        $defaultOptions = array_merge(['icon' => true, 'itemAttributes' => [], 'itemElement' => 'li', 'listAttributes' => [], 'listElement' => 'ul'], $defaultOptions);

        parent::__construct($matcher, $defaultOptions, $charset);
    }

    /**
     * @param ItemInterface $item
     * @param array $attributes
     * @param array $options
     *
     * @return string
     */
    protected function renderList(ItemInterface $item, array $attributes, array $options)
    {
        /**
         * Return an empty string if any of the following are true:
         *   a) The menu has no children eligible to be displayed
         *   b) The depth is 0
         *   c) This menu item has been explicitly set to hide its children
         */
        if (0 === $options['depth'] || !$item->hasChildren() || !$item->getDisplayChildren()) {
            return '';
        }

        // Option: listAttributes
        if (array_key_exists('listAttributes', $options) && is_array($options['listAttributes'])) {
            $attributes = $this->merge($attributes, $options['listAttributes']);
        }

        // Option: listElement
        $listElement = $this->defaultOptions['listElement'];
        if (array_key_exists('listElement', $options)) {
            $listElement = $options['listElement'];
        }

        // Render list
        $html = '';
        if ($listElement) {
            $html = $this->format('<' . $listElement . $this->renderHtmlAttributes($attributes) . '>', $listElement, $item->getLevel(), $options);
        }

        $html .= $this->renderChildren($item, $options);

        if ($listElement) {
            $html .= $this->format('</' . $listElement . '>', $listElement, $item->getLevel(), $options);
        }

        return $html;
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderItem(ItemInterface $item, array $options)
    {
        /**
         * Return an empty string if any of the following are true:
         *   a) We don't have access or this item is marked to not be shown
         */
        if (!$item->isDisplayed()) {
            return '';
        }

        // Option: currentAsLink
        $currentAsLink = $this->defaultOptions['currentAsLink'];
        if (array_key_exists('currentAsLink', $options)) {
            $currentAsLink = $options['currentAsLink'];
        }

        // Option: itemAttributes
        $attributes = $item->getAttributes();
        if (array_key_exists('itemAttributes', $options) && is_array($options['itemAttributes'])) {
            $attributes = $this->merge($attributes, $options['itemAttributes']);
        }

        // Option: itemElement
        $itemElement = $this->defaultOptions['itemElement'];
        if (array_key_exists('itemElement', $options)) {
            $itemElement = $options['itemElement'];
        }

        // Create and populate class array
        $class = array_key_exists('class', $attributes) ? explode(' ', $attributes['class']) : [];

        $isCurrent = $this->matcher->isCurrent($item);
        if ($isCurrent) {
            $class[] = $options['currentClass'];
        } elseif ($this->matcher->isAncestor($item, $options['matchingDepth'])) {
            $class[] = $options['ancestorClass'];
        }
        if ($item->actsLikeFirst()) {
            $class[] = $options['firstClass'];
        }
        if ($item->actsLikeLast()) {
            $class[] = $options['lastClass'];
        }
        if ($options['depth'] !== 0 && $item->hasChildren()) {
            if (null !== $options['branch_class'] && $item->getDisplayChildren()) {
                $class[] = $options['branch_class'];
            }
        } elseif (null !== $options['leaf_class']) {
            $class[] = $options['leaf_class'];
        }

        // Implode class array and write back
        if (!empty($class)) {
            $attributes['class'] = implode(' ', $class);
        }

        // Render item
        if ($itemElement) {
            // Render the text/link with wrapper tag
            $html = $this->format('<' . $itemElement . $this->renderHtmlAttributes($attributes) . '>', $itemElement, $item->getLevel(), $options);
            $html .= $this->renderLink($item, $options);
        } else {
            // Render the text/link without wrapper tag
            if ($item->getUri() && (!$isCurrent || $currentAsLink)) {
                $attributes = array_merge($item->getLinkAttributes(), $attributes);
                $text = sprintf('<a href="%s"%s>%s</a>', $this->escape($item->getUri()), $this->renderHtmlAttributes($attributes), $this->renderLabel($item, $options));
            } else {
                $attributes = array_merge($item->getLabelAttributes(), $attributes);
                $text = sprintf('<span%s>%s</span>', $this->renderHtmlAttributes($attributes), $this->renderLabel($item, $options));
            }

            $html = $this->format($text, 'link', $item->getLevel(), $options);
        }

        // Render children
        $childrenClass = (array)$item->getChildrenAttribute('class');
        $childrenClass[] = 'menu_level_' . $item->getLevel();

        $childrenAttributes = $item->getChildrenAttributes();
        $childrenAttributes['class'] = implode(' ', $childrenClass);

        $html .= $this->renderList($item, $childrenAttributes, $options);

        if ($itemElement) {
            $html .= $this->format('</' . $itemElement . '>', $itemElement, $item->getLevel(), $options);
        }

        return $html;
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderLink(ItemInterface $item, array $options = array())
    {
        // Option: currentAsLink
        $currentAsLink = $this->defaultOptions['currentAsLink'];
        if (array_key_exists('currentAsLink', $options)) {
            $currentAsLink = $options['currentAsLink'];
        }

        if ($item->getUri() && (!$this->matcher->isCurrent($item) || $currentAsLink)) {
            $text = $this->renderLinkElement($item, $options);
        } else {
            $text = $this->renderSpanElement($item, $options);
        }

        return $this->format($text, 'link', $item->getLevel(), $options);
    }

    /**
     * @param ItemInterface $item
     * @param array $options
     *
     * @return string
     */
    protected function renderLabel(ItemInterface $item, array $options)
    {
        // Option: icon
        $icon = $this->defaultOptions['icon'];
        if (array_key_exists('icon', $options)) {
            $icon = $options['icon'];
        }
        $icon = $icon ? $item->getExtra('icon', $icon) : false;
        $icon = $icon ? sprintf('<i class="%s"></i>', $icon) : false;

        $label = $options['allow_safe_labels'] && $item->getExtra('safe_label', false) ? $item->getLabel() : $this->escape($item->getLabel());

        if (!empty($icon)) {
            return !empty($label) ? $icon . '&nbsp;'. $label : $icon;
        }

        return $label;
    }

    /**
     * @param string $html
     * @param string $type
     * @param int $level
     * @param array $options
     *
     * @return string
     */
    protected function format($html, $type, $level, array $options)
    {
        return $options['compressed'] ? $html : str_repeat(' ', $level * 4) . $html . "\n";
    }

    /**
     * Merges two attribute lists while concatenating class string.
     *
     * @param array $attributes1
     * @param array $attributes2
     *
     * @return array
     */
    protected function merge(array $attributes1, array $attributes2)
    {
        foreach ($attributes2 as $attributeName => $attributeValue) {
            // Append value in case of 'class'
            if ($attributeName === 'class' && array_key_exists($attributeName, $attributes1) && is_string($attributes1[$attributeName])
            ) {
                $attributes1[$attributeName] .= ' ' . $attributeValue;
            } // Override for everything else
            else {
                $attributes1[$attributeName] = $attributeValue;
            }
        }

        return $attributes1;
    }
} 