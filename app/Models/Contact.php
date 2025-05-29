<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'additional_file',
        'is_merged',
        'merged_into',
        'additional_emails',
        'additional_phones'
    ];

    protected $casts = [
        'additional_emails' => 'array',
        'additional_phones' => 'array',
        'is_merged' => 'boolean'
    ];

    public function customFields(): HasMany
    {
        return $this->hasMany(ContactCustomField::class);
    }

    public function mergedInto(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'merged_into');
    }

    public function mergedContacts(): HasMany
    {
        return $this->hasMany(Contact::class, 'merged_into');
    }

    public function mergeHistory(): HasMany
    {
        return $this->hasMany(ContactMerge::class, 'master_contact_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_merged', false);
    }

    public function getCustomFieldValue($fieldName)
    {
        $field = $this->customFields()->where('field_name', $fieldName)->first();
        return $field ? $field->field_value : null;
    }

    public function setCustomField($fieldName, $fieldValue, $fieldType = 'text')
    {
        return $this->customFields()->updateOrCreate(
            ['field_name' => $fieldName],
            ['field_value' => $fieldValue, 'field_type' => $fieldType]
        );
    }
}
