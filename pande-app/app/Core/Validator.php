<?php
/**
 * Server-Side Form and Input Validation
 */

declare(strict_types=1);

namespace App\Core;

class Validator {
    private array $data;
    private array $rules;
    private array $errors = [];

    public function __construct(array $data, array $rules) {
        $this->data = $data;
        $this->rules = $rules;
        $this->validate();
    }

    private function validate(): void {
        foreach ($this->rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $ruleList = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;

            foreach ($ruleList as $rule) {
                $params = [];
                if (str_contains($rule, ':')) {
                    [$ruleName, $paramStr] = explode(':', $rule, 2);
                    $params = explode(',', $paramStr);
                } else {
                    $ruleName = $rule;
                }

                $this->applyRule($field, $value, $ruleName, $params);
            }
        }
    }

    private function applyRule(string $field, mixed $value, string $rule, array $params): void {
        $fieldName = ucfirst(str_replace('_', ' ', $field));

        switch ($rule) {
            case 'required':
                if ($value === null || trim((string)$value) === '') {
                    $this->errors[$field][] = "{$fieldName} is required.";
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field][] = "{$fieldName} must be a valid email address.";
                }
                break;

            case 'min':
                $min = (int)($params[0] ?? 0);
                if (is_numeric($value)) {
                    if ((float)$value < $min) {
                        $this->errors[$field][] = "{$fieldName} must be at least {$min}.";
                    }
                } elseif (is_string($value) && mb_strlen($value) < $min) {
                    $this->errors[$field][] = "{$fieldName} must be at least {$min} characters.";
                }
                break;

            case 'max':
                $max = (int)($params[0] ?? 0);
                if (is_string($value) && mb_strlen($value) > $max) {
                    $this->errors[$field][] = "{$fieldName} cannot exceed {$max} characters.";
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field][] = "{$fieldName} must be a number.";
                }
                break;

            case 'in':
                if (!empty($value) && !in_array($value, $params, true)) {
                    $this->errors[$field][] = "{$fieldName} contains an invalid value.";
                }
                break;

            case 'matches':
                $matchField = $params[0] ?? '';
                if ($value !== ($this->data[$matchField] ?? null)) {
                    $this->errors[$field][] = "{$fieldName} does not match " . ucfirst(str_replace('_', ' ', $matchField)) . ".";
                }
                break;
        }
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function fails(): bool {
        return !$this->passes();
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(): ?string {
        if (empty($this->errors)) {
            return null;
        }
        $firstField = reset($this->errors);
        return $firstField[0] ?? null;
    }
}
