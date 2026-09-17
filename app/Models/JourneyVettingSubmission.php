<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JourneyVettingSubmission extends Model
{
    protected $fillable = [
        'reference',
        'access_slug',
        'access_code',
        'access_code_hash',
        'access_slug_created_at',
        'access_enabled_at',
        'access_invited_at',
        'full_name',
        'email',
        'phone',
        'identity_type',
        'identity_number',
        'education_level',
        'employment_status',
        'monthly_income_range',
        'preferred_language',
        'court_experience',
        'separation_status',
        'separation_duration',
        'divorce_stage',
        'papers_filed',
        'legal_document_confidence',
        'support_needs',
        'agreement_status',
        'selected_topics',
        'email_verified_at',
        'submitted_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'identity_number' => 'encrypted',
            'selected_topics' => 'array',
            'email_verified_at' => 'datetime',
            'submitted_at' => 'datetime',
            'access_slug_created_at' => 'datetime',
            'access_enabled_at' => 'datetime',
            'access_invited_at' => 'datetime',
            'access_code' => 'encrypted',
        ];
    }

    public function getMaskedIdentityNumberAttribute(): string
    {
        $number = (string) $this->identity_number;
        $suffix = substr(preg_replace('/[^A-Z0-9]/i', '', $number), -4);

        return $this->identity_type === 'nric'
            ? '••••••-••-'.$suffix
            : '••••••'.$suffix;
    }
}
