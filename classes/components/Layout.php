<?php
namespace APP\plugins\themes\eidos\classes\components;

use APP\core\Application;
use APP\plugins\themes\eidos\EidosTheme;
use APP\template\TemplateManager;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as ViewFacade;
use PKP\context\Context;
use PKP\facades\Locale;
use PKP\i18n\LocaleMetadata;
use PKP\plugins\ThemePlugin;

class Layout extends Component
{
    public function __construct(
        public string $title,
        public string $description = '',
        public string $bodyClass = '',
        public string $head = '',
    ) {
        $this->addGlobalData();
    }

    public function render(): View|Closure|string
    {
        return view(
            ViewFacade::resolvePluginComponentViewPath(
                $this,
                'components.layout'
            )
        );
    }

    /**
     * Get the name of the context or site, depending
     * on what kind of page we're viewing.
     */
    public function contextName() : string
    {
        $context = Application::get()->getRequest()->getContext();
        return $context
            ? $context->getLocalizedName()
            : Application::get()->getRequest()->getSite()->getLocalizedTitle();
    }

    /**
     * Get the <title> by combining the current page title
     * with the context or site name.
     */
    public function pageTitle() : string
    {
        $page = Application::get()->getRequest()->getRequestedPage();

        if ($page === 'index') {
            return $this->title;
        }

        return $this->title . __('common.titleSeparator') . $this->contextName();
    }

    /**
     * Get classes for the <body> tag which indicate the current
     * page and op of the request.
     */
    public function bodyClasses(): string
    {
        $page = Application::get()->getRequest()->getRequestedPage();
        $op = Application::get()->getRequest()->getRequestedOp();

        $classes = [];

        if ($page) {
            $classes[] = "pkp-page-{$page}";
        }

        if ($op) {
            $classes[] = "pkp-op-{$op}";
        }

        return join(' ', $classes);
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
     * Add global template data
     */
    protected function addGlobalData(): void
    {
        view()->share('contextName', $this->contextName());
        view()->share('getStringSize', [$this, 'getStringSize']);
        view()->share('locales', $this->getLocales());
        view()->share('publicationIds', $this->getPublicationIds());
        view()->share('eidosUrl', $this->getEidosTheme()->getPluginUrl());
        view()->share('usesCustomFonts', $this->getEidosTheme()->optionsHelper->usesCustomFonts());
    }

    /**
     * Get an array of all locales supported by the
     * current context or site.
     */
    protected function getLocales(): array
    {
        $request = Application::get()->getRequest();
        $context = $request->getContext();

        $locales = Locale::getFormattedDisplayNames(
            isset($context)
                ? $context->getSupportedLocales()
                : $request->getSite()->getSupportedLocales(),
            Locale::getLocales(),
            LocaleMetadata::LANGUAGE_LOCALE_ONLY
        );

        return $locales;
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
        error_log(get_class($activeTheme->getRootTheme()));
        return $activeTheme->getRootTheme();
    }
}