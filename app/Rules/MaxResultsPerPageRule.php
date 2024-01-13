<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

final class MaxResultsPerPageRule implements Rule
{
    private mixed $value;
    private int $fallbackLimit;

    public function __construct($fallbackLimit = 25)
    {
        $this->fallbackLimit = $fallbackLimit;
    }

    public function passes($attribute, $value): bool
    {
        $this->value = $value;

        if (!is_numeric($value)) {
            return false;
        }

        if (!is_int($value)) {
            $value = intval($value);
        }

        if ($value > max_results_per_page()) {
            return false;
        }

        return true;
    }

    public function message(): array|string
    {
        $mrpp = max_results_per_page($this->fallbackLimit);
        return "Value {$this->value} is higher than the configured '$mrpp' max value.";
    }
}
