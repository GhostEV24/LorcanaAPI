<?php

namespace App\Models;

use Database\Factories\SetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    use HasFactory;
    protected $fillable = ['api_id','name', 'code', 'release_date', 'card_number'];

    // Relation for cards
    public function cards()
    {
        return $this->hasMany(Card::class);
    }


    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return SetFactory::new();
    }
}
