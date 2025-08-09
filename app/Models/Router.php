<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Scopes\TenantScope;
use App\Models\Tenant;

class Router extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'username',
        'password',
        'description',
        'enabled',
        'tenant_id',
    ];

    protected $appends = [
        'status',
    ];

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function getStatusAttribute(): string
    {
        return $this->enabled ? 'Enabled' : 'Disabled';
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
