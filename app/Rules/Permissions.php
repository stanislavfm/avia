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
        $permissions = explode(',', $value);
        $permissions = array_filter($permissions);

        if (empty($permissions)) {
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
        return 'Permissions should be comma-separated string with next values: ' . implode(', ', AuthToken::PERMISSIONS) . '.';
    }
}
