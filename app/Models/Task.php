<?php

namespace App\Models;

use App\Models\User;
use App\Trait\Tenantable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory,Tenantable;
    protected $fillable = ['name', 'status','tenant_id','user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
