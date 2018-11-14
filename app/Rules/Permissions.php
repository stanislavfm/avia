<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\AuthToken;

class Permissions implements Rule
{
    /**
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $permissions = $value;

        if (!is_array($permissions)) {
            return false;
        }

        $uniquePermissions = array_unique($permissions);

        if (count($permissions) !== count($uniquePermissions)) {
            return false;
        }

        $permissions = $uniquePermissions;

        if (count($permissions) !== count(array_intersect($permissions, AuthToken::PERMISSIONS))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('api.permissions_validation', ['values' => implode(', ', AuthToken::PERMISSIONS)]);
    }
}
