<?php

namespace App\Casts;

use App\Models\Status;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class SerializedArray implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return unserialize($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  array  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        if(isset($value['before']['status_id'])){
            $value['before']['status']=Status::find($value['before']['status_id'])->name;
            $value['after']['status']=Status::find($value['after']['status_id'])->name;
            unset($value['before']['status_id']);
            unset($value['after']['status_id']);
        }
        return serialize($value);
    }
}