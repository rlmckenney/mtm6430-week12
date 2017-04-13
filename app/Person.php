<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Person
 *
 * @inheritdoc
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property int $age
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App
 */
class Person extends Model
{
    // The $fillable property is a "white-list" of model properties that are explicitly declared
    // as safe to receive mass assignment updates -- e.g. from form fields.
    protected $fillable = ['first_name', 'last_name', 'age'];

    // The inverse is the $guarded property which acts as a black list of fields that are
    // explicitly prohibited from being updated through a mass assignment operation.
    // Use one or the other - not both!
    // protected $guarded = ['id', 'created_at', 'updated_at'];

    // We can also declare that all properties are safe by setting the $guarded list to an empty array.
    // protected $guarded = [];

    // Also note, the $fillable and $guarded properties should be declared as protected, not public.
    // This keeps them safe from accidental manipulation by other objects in your application.

    public function cars()
    {
      return $this->hasMany('App\Car');
    }
}
