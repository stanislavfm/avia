<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property string|null $name
 * @mixin \Eloquent
 */
class Transporter extends Model
{
    public $timestamps = false;
}
