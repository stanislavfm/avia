<?php
/**
 * Flight Searcher API
 * @author Stanislav Chertilin <staschertilin@gmail.com>
 * @copyright 2019 https://github.com/stanislavfm/flight-searcher
 */

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\AuthToken
 */
class AuthToken extends JsonResource
{
    public function toArray($request)
    {
        $data = [
            'permissions' => $this->permissions,
            'expiresAt' => $this->expiresAt->format('Y-m-d H:i:s'),
        ];

        if (!is_null($this->getToken())) {
            $data['token'] = $this->getToken();
        }

        return $data;
    }
}