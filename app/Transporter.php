<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Transporter
 *
 * @property int $id
 * @property string $code
 * @property string|null $name
 */
class Transporter extends Model
{
    public $timestamps = false;
}
