<?php

namespace App\Models;

use App\Enum\PlanType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\TenantScope;
use App\Models\Tenant;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice',
        'username',
        'plan_name',
        'price',
        'recharged_at',
        'expired_at',
        'method',
        'routers',
        'type',
        'tenant_id',
    ];

    protected $casts = [
        'type' => PlanType::class,
        'recharged_at' => 'datetime',
        'expired_at' => 'datetime',
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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
