<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnostic extends Model
{
    protected $fillable = ['question'];

    public function options() {
        return $this->hasMany(Option::class);
    }
}
