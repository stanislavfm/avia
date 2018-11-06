<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $hash
 * @property array $permissions
 * @property \Illuminate\Support\Carbon|null $expiresAt
 * @property \Illuminate\Support\Carbon|null $createdAt
 * @property \Illuminate\Support\Carbon|null $updatedAt
 * @mixin \Eloquent
 */
class AuthToken extends Model
{
    const TOKEN_LENGTH = 64;

    const GET_PERMISSION = 'get';
    const CREATE_PERMISSION = 'create';
    const UPDATE_PERMISSION = 'update';
    const DELETE_PERMISSION = 'delete';

    const PERMISSIONS = [
        self::GET_PERMISSION,
        self::CREATE_PERMISSION,
        self::UPDATE_PERMISSION,
        self::DELETE_PERMISSION,
    ];

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    protected $token = null;
    protected $dates = ['expiresAt', 'createdAt', 'updatedAt'];
    protected $fillable = ['hash', 'permissions', 'expiresAt'];
    protected $casts = ['permissions' => 'array'];

    /**
     * @param string $value
     * @return string
     */
    public static function createHash(string $value): string
    {
        return hash('sha256', $value);
    }

    /**
     * @return string|null
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasGetPermission()
    {
        return in_array(self::GET_PERMISSION, $this->permissions);
    }

    /**
     * @return bool
     */
    public function hasCreatePermission()
    {
        return in_array(self::CREATE_PERMISSION, $this->permissions);
    }

    /**
     * @return bool
     */
    public function hasUpdatePermission()
    {
        return in_array(self::UPDATE_PERMISSION, $this->permissions);
    }

    /**
     * @return bool
     */
    public function hasDeletePermission()
    {
        return in_array(self::DELETE_PERMISSION, $this->permissions);
    }
}
