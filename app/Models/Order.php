<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     *  List of statuses.
     */
    const STATUS_CREATED = 1;
    const STATUS_PREPARING = 2;
    const STATUS_PREPARED = 3;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'status',
        'remaining_time'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status_id'    => 'integer',
        'cooking_time' => 'time',
        'cooked_at'    => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'table_number',
        'dish_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    /**
     * Retrieve user relation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * "An order will be cooked" time.
     *
     * @return string
     */
    public function getCookedAtAttribute()
    {
        if ($this->cooking_time) {
            $cookingTime = Carbon::createFromFormat('H:i:s', $this->cooking_time);

            $time = $this->updated_at;
            $time = $time->addHours($cookingTime->format('H'));
            $time = $time->addMinutes($cookingTime->format('i'));
            $time = $time->addSeconds($cookingTime->format('s'));

            return $time->__toString();
        }
    }

    /**
     * Format a cooking time.
     *
     * @param $value
     */
    public function setCookingTimeAttribute($value)
    {
        $time = Carbon::createFromFormat('H:i', $value)->toTimeString();

        $this->attributes['cooking_time'] = $time;
    }

    /**
     * Get a remaining time of order.
     *
     * @return string
     */
    public function getRemainingTimeAttribute()
    {
        if ($this->cooked_at) {
            if (Carbon::parse($this->cooked_at) > Carbon::now()) {
                return Carbon::parse($this->cooked_at)->diffInSeconds(Carbon::now());
            }
        }
    }

    /**
     * Get a status name.
     *
     * @return mixed
     */
    public function getStatusAttribute()
    {
        return $this->statuses()[$this->status_id];
    }

    /**
     * Status list.
     *
     * @return array
     */
    public static function statuses()
    {
        return [
            self::STATUS_CREATED   => 'Created',
            self::STATUS_PREPARING => 'Preparing',
            self::STATUS_PREPARED  => 'Prepared'
        ];
    }
}
