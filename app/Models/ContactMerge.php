<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMerge extends Model
{
    protected $fillable = [
        'master_contact_id',
        'merged_contact_id',
        'merged_data',
        'conflict_resolutions'
    ];

    protected $casts = [
        'merged_data' => 'array',
        'conflict_resolutions' => 'array'
    ];

    public function masterContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'master_contact_id');
    }

    public function mergedContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'merged_contact_id');
    }
}
