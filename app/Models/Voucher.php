<?php

namespace App\Models;

use App\Enum\PlanType;
use App\Enum\VoucherStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\TenantScope;
use App\Models\Tenant;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'router_id',
        'user',
        'type',
        'code',
        'status',
        'tenant_id',
    ];

    protected $casts = [
        'type' => PlanType::class,
        'status' => VoucherStatus::class,
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

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
