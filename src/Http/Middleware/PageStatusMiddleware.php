<?php

namespace HalcyonLaravel\Base\Http\Middleware;

use Closure;
use HalcyonLaravel\Base\Criterion\Eloquent\ThisEqualThatCriteria;
use HalcyonLaravel\Base\Criterion\Eloquent\ThisHasCurrentDomainCriteria;

class PageStatusMiddleware
{
    private $pageRepository;

    public function __construct()
    {
        $this->pageRepository = app(config('base.repositories.page', 'App\Repositories\Core\Page\PageRepository'));
    }

    /**
     * Handle an incoming request.
     *
     * @param          $request
     * @param  \Closure  $next
     * @param  string  $modelName
     *
     * @return mixed
     */
    public function handle($request, Closure $next, string $modelName)
    {
        $this->pageRepository->pushCriteria(new ThisEqualThatCriteria('pageable_type', $modelName));
        $this->pageRepository->pushCriteria(new ThisHasCurrentDomainCriteria);
        $page = $this->pageRepository->all()->first();

        if (empty($page)) {
            abort(404);
        }
        return $next($request);
    }
}