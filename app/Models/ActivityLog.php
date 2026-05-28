<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'subject', 'description', 'type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log(string $subject, string $description = '', string $type = 'info'): void
    {
        static::create([
            'user_id'     => auth()->id(),
            'subject'     => $subject,
            'description' => $description,
            'type'        => $type,
        ]);
    }
}
