<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = array('id', 'category_id', 'comment', 'sum', 'user_id', 'tr_date');
    protected $hidden = array('user_id', 'created_at', 'updated_at');

    public function category()
    {
        return $this->belongsTo('Category', 'category_id');
    }
}