<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['reference', 'full_name', 'email', 'topic', 'message', 'email_verified_at', 'submitted_at', 'status'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'submitted_at' => 'datetime'];
    }
}
