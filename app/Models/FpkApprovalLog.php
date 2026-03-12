<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FpkApprovalLog extends Model
{
    use HasFactory;

    protected $table = 'fpk_approval_logs';

    protected $fillable = [
        'fpk_id',
        'user_id',
        'action',
        'from_status',
        'to_status',
        'notes',
    ];

    public function fpk()
    {
        return $this->belongsTo(Fpk::class, 'fpk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
