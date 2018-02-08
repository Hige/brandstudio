<?php

namespace App\Models;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Traits\AdminBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionUser extends Model
{
    use SoftDeletes, AdminBuilder;

    protected $table = 'subscription_users';
    protected $with = ['event'];
    protected $attributes = [
        'id' => 0,
        'event_id'   => 0,
        'name' => null,
        'email' => null,
        'phone' => null,
        'gender' => null,
        'birthday' => null,
        'ip' => '127.0.0.1',
        'created_at' => 0,
        'updated_at' => 0,
        'deleted_at' => null,
    ];
    protected $fillable = ['event_id', 'name', 'email', 'phone', 'gender', 'birthday', 'ip'];
    protected $casts = [
        'id' => 'integer',
        'event_id' => 'integer',
        'name' => 'string',
        'email' => 'string',
        'phone' => 'string',
        'gender' => 'string',
    ];
    protected $dates = [
        'birthday',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public $rules = [
        'name' => 'required',
        'email' => 'required',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
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
        return $this->name;
    }
}
