<?php

namespace App\Http\Middleware;

use App\Models\Tenant as TenantModel;
use App\Support\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = null;

        if ($header = $request->header('X-Tenant')) {
            $tenant = TenantModel::where('name', $header)->first();
        } else {
            $host = $request->getHost();
            $parts = explode('.', $host);
            if (count($parts) > 2) {
                $subdomain = $parts[0];
                $tenant = TenantModel::where('name', $subdomain)->first();
            }
        }

        Tenant::set($tenant);

        return $next($request);
    }
}
