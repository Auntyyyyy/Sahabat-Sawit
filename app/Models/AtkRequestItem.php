<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AtkRequestItem extends Model
{
    protected $fillable = ['atk_request_id', 'atk_item_id', 'jumlah'];

    public function request()
    {
        return $this->belongsTo(AtkRequest::class, 'atk_request_id');
    }

    public function item()
    {
        return $this->belongsTo(AtkItem::class, 'atk_item_id');
    }
}