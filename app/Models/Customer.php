<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\TenantScope;
use App\Models\Tenant;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $rememberTokenName = false;

    protected $fillable = [
        'username',
        'password',
        'pppoe_password',
        'fullname',
        'address',
        'phonenumber',
        'email',
        'balance',
        'service_type',
        'auto_renewal',
        'last_login',
        'long',
        'lat',
        'ktp',
        'tenant_id',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Tenant::currentId() && !$model->tenant_id) {
                $model->tenant_id = Tenant::currentId();
            }
        });
    }

    public function recharges(): HasMany
    {
        return $this->hasMany(UserRecharge::class);
    }

    public function recharge(): HasOne
    {
        return $this->hasOne(UserRecharge::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
