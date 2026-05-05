<?php
namespace APP\plugins\themes\eidos\classes\components;

use APP\core\Application;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade;

class Layout extends Component
{
    public function __construct(
        public string $title,
        public string $description = '',
        public string $bodyClass = '',
        public string $head = '',
    ) {
        //
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
     * Get the <title> by combining the current page title
     * with the context or site name.
     */
    public function pageTitle() : string
    {
        $context = Application::get()->getRequest()->getContext();
        $page = Application::get()->getRequest()->getRequestedPage();

        if ($page === 'index') {
            return $this->title;
        }

        $contextTitle = $context
            ? $context->getLocalizedName()
            : Application::get()->getRequest()->getSite()->getLocalizedTitle();

        return $this->title . __('common.titleSeparator') . $contextTitle;
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
}