<?php

namespace App\Models;

use App\Enum\PaymentGatewayStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\TenantScope;
use App\Models\Tenant;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'gateway',
        'gateway_trx_id',
        'plan_id',
        'plan_name',
        'router_id',
        'router_name',
        'price',
        'pg_url_payment',
        'payment_method',
        'payment_channel',
        'pg_request',
        'pg_paid_response',
        'expired_date',
        'paid_date',
        'status',
        'tenant_id',
    ];

    protected $casts = [
        'expired_date' => 'datetime',
        'paid_date' => 'datetime',
        'status' => PaymentGatewayStatus::class,
    ];

    public function router(): BelongsTo
    {
        return $this->belongsTo(Router::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
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
