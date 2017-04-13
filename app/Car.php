<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Car
 *
 * @inheritdoc
 * @property int $id
 * @property int $year
 * @property string $make
 * @property string $model
 * @property string $colour
 * @property string $license
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Car extends Model
{
    protected $fillable = ['year', 'make', 'model', 'colour', 'license'];

    public function owner()
    {
      return $this->belongsTo('App\Person', 'person_id');
    }
}
