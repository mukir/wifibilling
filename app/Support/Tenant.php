<?php

namespace App\Support;

use App\Models\Tenant as TenantModel;

class Tenant
{
    protected static ?TenantModel $tenant = null;

    public static function set(?TenantModel $tenant): void
    {
        self::$tenant = $tenant;
    }

    public static function current(): ?TenantModel
    {
        return self::$tenant;
    }

    public static function currentId(): ?int
    {
        return self::$tenant?->id;
    }
}
