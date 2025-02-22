<?php

namespace Sportlery\Library\Models;

use Carbon\Carbon;
use Model;

/**
 * Model
 */
class Event extends Model
{
    use \October\Rain\Database\Traits\Validation;

    const TYPE_PAID = 0;
    const TYPE_FREE = 1;

    public $implement = [
        'RainLab.Translate.Behaviors.TranslatableModel',
        'Sportlery.Library.Behaviors.HashIdsModel',
    ];

    public $translatable = ['name', 'description'];

    /*
     * Validation
     */
    public $rules = [
        'slug' => 'required|unique:spr_events,slug',
        'starts_at' => 'required|date_format:"Y-m-d H:i:s"',
        'ends_at' => 'required|date_format:"Y-m-d H:i:s"',
        'description' => 'required',
        'price' => 'required|numeric',
        'location_id' => 'required|exists:spr_locations,id',
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spr_events';

    /* Relations */
    public $attachOne = [
        'avatar' => 'System\Models\File'
    ];

    public $attachMany = [
        'event_gallery' => 'System\Models\File'
    ];

    public $belongsTo = [
        'location' => Location::class,
        'user' => \RainLab\User\Models\User::class,
    ];

    public $belongsToMany = [
        'sports' => [
            Sport::class,
            'table' => 'spr_event_sport',
            'order' => 'name',
        ],
        'categories' => [
            Category::class,
            'table' => 'spr_category_event',
            'order' => 'name',
            'scope' => 'forEvents',
        ],
    ];

    protected $with = ['location'];
    protected $appends = ['latitude', 'longitude'];
    protected $dates = ['starts_at', 'ends_at', 'booking_ends_at'];

    public function scopeSearch($query, array $params)
    {
        if (!isset($params['past']) || $params['past'] === '0') {
            $query->where('ends_at', '>=', Carbon::now());
        }

        if (isset($params['q']) && $q = trim($params['q'])) {
            $q = '%'.$q.'%';

            $query->where(function($query) use ($q) {
                $query->orWhere('name', 'like', $q)->orWhere('description', 'like', $q);
            });
        }

        if (isset($params['sport']) && $params['sport'] !== '') {
            $query->whereHas('sports', function($query) use ($params) {
                return $query->where('id', $params['sport']);
            });
        }

        if (isset($params['event_type']) && $params['event_type'] !== '') {
            if ($params['event_type'] == self::TYPE_FREE) {
                $query->where('price', 0);
            } else {
                $query->where('price', '>', 0);
            }
        }

        if (isset($params['city']) && $city = trim($params['city'])) {
            $query->whereHas('location', function($query) use ($city) {
                return $query->where('city', $city);
            });
        }

        return $query;
    }

    /**
     * Get an array of category names attached to the event.
     *
     * @return array
     */
    public function getCategoryNamesAttribute()
    {
        return $this->categories->lists('name');
    }

    public function getLatitudeAttribute()
    {
        return $this->location ? $this->location->latitude : null;
    }

    public function getLongitudeAttribute()
    {
        return $this->location ? $this->location->longitude : null;
    }

    public function isFull()
    {
        return $this->max_attendees > 0 && $this->current_attendees >= $this->max_attendees;
    }

    public function isBookingEnded()
    {
        if (is_null($this->booking_ends_at) || $this->booking_ends_at <= Carbon::minValue()) {
            // No booking ends at date set.
            return false;
        }

        return $this->booking_ends_at->isPast();
    }
}


