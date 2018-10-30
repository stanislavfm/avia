<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @property-read \App\Flight[] $flights
 * @mixin \Eloquent
 */
class Transporter extends Model
{
    const CODE_LENGTH = 2;

    public $timestamps = false;

    public function flights()
    {
        return $this->hasMany('App\Flight', 'transporterId');
    }
}
