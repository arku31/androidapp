<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [];

    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    protected $primaryKey = 'id';

    public function transactions()
    {
        return $this->hasMany('Operation', 'category_id');
    }
}