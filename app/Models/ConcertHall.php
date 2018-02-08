<?php

namespace App\Models;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConcertHall extends Model
{
    protected $table = 'concert_halls';
    protected $attributes = [
        'id' => 0,
        'title' => null,
        'slug' => null,
        'description' => null,
        'phone' => null,
        'address' => null,
        'metro' => null,
        'work_time' => null,
        'url' => null,
        'created_at' => 0,
        'updated_at' => 0,
        'deleted_at' => null,
    ];
    protected $fillable = ['title', 'description', 'phone', 'address', 'metro', 'work_time', 'url'];
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'phone' => 'string',
        'address' => 'string',
        'metro' => 'string',
        'work_time' => 'string',
        'url' => 'string',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $rules = [
        'title' => 'required',
    ];

    use SoftDeletes, AdminBuilder;

    public static function grid($callback)
    {
        return new Grid(new static, $callback);
    }

    public static function form($callback)
    {
        return new Form(new static, $callback);
    }

    public function events()
    {
        return $this->hasMany(Event::class, 'concert_hall_id');
    }

    public function __toString()
    {
        return $this->title;
    }
}
