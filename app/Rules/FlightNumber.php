<?php

namespace App\Rules;

use App\Flight;
use App\Transporter;
use Illuminate\Contracts\Validation\Rule;

class FlightNumber implements Rule
{
    protected $_flightNumber = null;
    protected $_transporterCode = null;

    /**
     * @return null|string
     */
    public function getFlightNumber()
    {
        return $this->_flightNumber;
    }

    /**
     * @return null|string
     */
    public function getTransporterCode()
    {
        return $this->_transporterCode;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $transporterCodeLength = Transporter::CODE_LENGTH;
        $flightNumberLength = Flight::NUMBER_LENGTH;

        $pattern = "/^([A-Z]{{$transporterCodeLength}})(?: |-)?([0-9]{{$flightNumberLength}})$/";

        preg_match($pattern, $value, $matches);

        /**
         * Expects exactly 3 matches: full match, transporter code and flight number
         */
        if (count($matches) !== 3) {
            return false;
        }

        $this->_flightNumber = $matches[2];
        $this->_transporterCode = $matches[1];

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Flight number is not valid.';
    }
}
