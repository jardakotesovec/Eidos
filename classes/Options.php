<?php

namespace APP\plugins\themes\eidos\classes;

use APP\core\Application;
use APP\journal\Journal;
use APP\plugins\themes\eidos\EidosTheme;

/**
 * Helper class to add theme options
 */
class Options
{
    public const HEADER_DEFAULT = 'default';
    public const HEADER_BOXED = 'boxed';
    public const HEADER_LINE = 'line';

    public const HOMEPAGE_IMAGE_POSITION_ABOVE = 'above';
    public const HOMEPAGE_IMAGE_POSITION_BEHIND = 'behind';
    public const HOMEPAGE_IMAGE_POSITION_BELOW = 'below';

    /**
     * Primary locale of current context
     */
    protected string $primaryLocale;

    /**
     * Current context
     */
    protected Journal $context;

    public function __construct(
        /**
         * Instance of the theme
         */
        protected EidosTheme $theme,
    ) {
        $request = Application::get()->getRequest();
        $this->context = $request->getContext();
        $this->primaryLocale = $this->context
            ? $this->context->getPrimaryLocale()
            : $request->getSite()->getPrimaryLocale();
    }

    /**
     * Add all theme options
     */
    public function addOptions(): void
    {
        $this->addHeaderOption();
        $this->addTaglineOption();
        $this->addHomepageImageOption();
    }

    /**
     * Add option for the header layout
     */
    protected function addHeaderOption(): void
    {
        $this->theme->addOption('header', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.eidos.option.header.label'),
            'description' => __('plugins.themes.eidos.option.header.description'),
            'options' => [
                [
                    'value' => self::HEADER_DEFAULT,
                    'label' => __('plugins.themes.eidos.option.header.default'),
                ],
                [
                    'value' => self::HEADER_BOXED,
                    'label' => __('plugins.themes.eidos.option.header.default-boxed'),
                ],
                [
                    'value' => self::HEADER_LINE,
                    'label' => __('plugins.themes.eidos.option.header.line'),
                ],
            ],
            'default' => self::HEADER_DEFAULT,
        ]);
    }

    /**
     * Add option for the tagline to display beside the logo
     */
    protected function addTaglineOption(): void
    {
        $this->theme->addOption('tagline', 'FieldText', [
            'label' => __('plugins.themes.eidos.option.tagline.label'),
            'description' => __('plugins.themes.eidos.option.tagline.description'),
            'isMultilingual' => true,
        ]);
    }

    /**
     * Add option for where to display the homepage image
     */
    protected function addHomepageImageOption(): void
    {
        $this->theme->addOption('homepageImagePosition', 'FieldOptions', [
            'type' => 'radio',
            'label' => __('plugins.themes.eidos.option.homepageImagePosition.label'),
            'description' => __('plugins.themes.eidos.option.homepageImagePosition.description'),
            'options' => [
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
                    'label' => __('plugins.themes.eidos.option.homepageImagePosition.above'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BEHIND,
                    'label' => __('plugins.themes.eidos.option.homepageImagePosition.behind'),
                ],
                [
                    'value' => self::HOMEPAGE_IMAGE_POSITION_BELOW,
                    'label' => __('plugins.themes.eidos.option.homepageImagePosition.below'),
                ],
            ],
            'default' => self::HOMEPAGE_IMAGE_POSITION_ABOVE,
        ]);
    }
}
