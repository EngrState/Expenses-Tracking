<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class RequiredRules implements RuleInterface{
    public function validate(array $data, string $field, array $params): bool{
        return !empty($data[$field]);
    }
    public function getMessage(array $data, string $field, array $params): string{
        return "this field is required";
    }

} 