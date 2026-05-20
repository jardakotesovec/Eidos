<?php
namespace APP\plugins\themes\eidos\classes\components;

use APP\core\Application;
use APP\plugins\themes\eidos\EidosTheme;
use APP\template\TemplateManager;
use Illuminate\Support\Collection;
use PKP\context\Context;
use PKP\plugins\ThemePlugin;

class Layout extends \APP\view\components\Layout
{
    public function __construct(
        public string $title,
        public string $description = '',
        public string $bodyClass = '',
        public string $head = '',
    ) {
        parent::__construct($title, $description, $bodyClass, $head);
    }

    /**
     * Add global template data
     */
    protected function addGlobalData(): void
    {
        parent::addGlobalData();
        view()->share('contextName', $this->contextName());
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
     * Get an array of ISSNs and other publication IDs
     *
     * @return Collection
     */
    protected function getPublicationIds(): Collection
    {
        $context = Application::get()->getRequest()->getContext();

        $ids = new Collection();

        if ($context->getData('printIssn')) {
            $ids->add([
                'name' => __('journal.issn'),
                'value' => $context->getData('printIssn'),
            ]);
        }

        if ($context->getData('onlineIssn')) {
            $ids->add([
                'name' => __('metadata.property.displayName.eissn'),
                'value' => $context->getData('onlineIssn'),
            ]);
        }

        if ($context->getData(Context::SETTING_DOI_PREFIX)) {
            $ids->add([
                'name' => __('manager.dois.title'),
                'value' => $context->getData(Context::SETTING_DOI_PREFIX),
            ]);
        }

        return $ids;
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