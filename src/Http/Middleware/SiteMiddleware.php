<?php

namespace HalcyonLaravel\Base\Http\Middleware;

use Closure;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteMiddleware
{
    private $domainRepository;

    public function __construct()
    {
        $this->domainRepository = app(config('base.repositories.domain',
            'App\Repositories\Core\Domain\DomainRepository'));
    }

    /**
     * Handle an incoming request.
     *
     * @param          $request
     * @param  \Closure  $next
     * @param  string  $machineName
     *
     * @return mixed
     * @throws \Throwable
     */
    public function handle($request, Closure $next, string $machineName)
    {
        $site = $this->domainRepository
            ->skipCriteria()
            ->findWhere([
                'machine_name' => $machineName,
            ])->first();

        throw_if(
            is_null($site),
            Exception::class,
            "Domain machine name [$machineName] not found."
        );

        throw_if(
            $site->domain != current_base_url(),
            NotFoundHttpException::class
        );

        return $next($request);
    }
}
