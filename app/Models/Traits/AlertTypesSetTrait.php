<?php

namespace App\Models\Traits;

trait AlertTypesSetTrait
{
    public function getAlertTypesAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        return explode(',', $value);
    }

    public function setAlertTypesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['alert_types'] = implode(',', $value);
        } else {
            $this->attributes['alert_types'] = $value;
        }
    }

    public function scopeWhereFindInSetOr($query, $column, $values)
    {
        if (!is_array($values)) {
            $values = [$values];
        }
        $query->where(function ($query) use ($column, $values) {
            foreach ($values as $value) {
                $query->orWhereRaw("FIND_IN_SET('$value', `$column`)");
            }
        });
        return $query;
    }
}
