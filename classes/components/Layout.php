<?php
namespace APP\plugins\themes\eidos\classes\components;

use APP\core\Application;
use APP\plugins\themes\eidos\EidosTheme;
use APP\template\TemplateManager;
use PKP\plugins\ThemePlugin;

class Layout extends \APP\view\components\Layout
{
    /**
     * Add global template data
     */
    protected function addGlobalData(): void
    {
        parent::addGlobalData();
        view()->share('getStringSize', [$this, 'getStringSize']);
        view()->share('eidosUrl', $this->getEidosTheme()->getPluginUrl());
        view()->share('usesCustomFonts', $this->getEidosTheme()->optionsHelper->usesCustomFonts());
    }

    /**
     * Get a short `size` string indicating the length of a string
     *
     * @return string 'xs' | 'sm' | 'md' | 'lg'
     */
    public function getStringSize(string $str): string
    {
        $length = strlen($str);
        return $length <= 40 ? 'xs' : ($length <= 80 ? 'sm' : ($length <= 100 ? 'md' : 'lg'));
    }

    /**
     * Get the root parent theme
     *
     * This is always Eidos, whether or not a child theme has been
     * activated.
     */
    protected function getEidosTheme(): EidosTheme
    {
        $templateMgr = TemplateManager::getManager(Application::get()->getRequest());
        /** @var ThemePlugin $activeTheme */
        $activeTheme = $templateMgr->getTemplateVars('activeTheme');
        return $activeTheme->getRootTheme();
    }
}
