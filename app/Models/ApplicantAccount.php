<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ApplicantAccount extends Model
{
    protected $fillable=['journey_vetting_submission_id','full_name','email','phone','password','email_verified_at'];
    protected $hidden=['password'];
    protected function casts(): array { return ['password'=>'hashed','email_verified_at'=>'datetime']; }
}
