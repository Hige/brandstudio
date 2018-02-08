<?php

namespace App\Models;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes, AdminBuilder;

    protected $table = 'events';
    protected $with = ['concertHall'];
    protected $attributes = [
        'id' => 0,
        'concert_hall_id'   => 0,
        'title' => null,
        'slug' => null,
        'description' => null,
        'start' => 0,
        'end' => null,
        'rate' => 0,
        'released' => false,
        'created_at' => 0,
        'updated_at' => 0,
        'deleted_at' => null,
    ];
    protected $fillable = ['title', 'description', 'rate', 'released'];
    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'rate' => 'integer',
        'released' => 'boolean',
    ];
    protected $dates = [
        'start',
        'end',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $rules = [
        'title' => 'required',
        'start' => 'required',
    ];

    public function concertHall()
    {
        return $this->belongsTo(ConcertHall::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Event::class, 'event_id');
    }

    public static function grid($callback)
    {
        return new Grid(new static, $callback);
    }

    public static function form($callback)
    {
        return new Form(new static, $callback);
    }

    public function __toString()
    {
        return $this->title;
    }
}
