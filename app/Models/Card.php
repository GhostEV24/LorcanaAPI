<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        "api_id",
        "set_id",
        "name",
        "number",
        "version",
        "cardIdentifier",
        "description",
        "image",
        "thumbnail",
        "rarity",
        "story",
    ];

    public function set()
    {
        return $this->belongsTo(Set::class);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\CardFactory::new();
    }
}
