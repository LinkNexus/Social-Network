<?php

class Validator
{

    protected array $data = [];

    protected array $errors = [];

    public function __construct(array $data)
    {

        $this->data = $data;

    }

    protected function getField(string $field): ?string
    {

        if (!isset($this->data[$field])){
            return null;
        }

        return $this->data[$field];

    }

    public function isPosted(): bool
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    public function isValuePosted(string $field): bool
    {
        return !empty($this->getField($field));
    }

    public function isAlphanumeric(string $field, string $errorMsg): void
    {

        if(!$this->isValuePosted($field) || !preg_match('/^[a-z0-9A-Z_]+$/', $this->getField($field))){
            $this->errors[$field] = $errorMsg;
        }

    }

    public function isEmail(string $field, string $errorMsg): void
    {

        if (!$this->isValuePosted($field) || !filter_var($this->getField($field), FILTER_VALIDATE_EMAIL)){
            $this->errors[$field] = $errorMsg;
        }

    }

    public function isUnique(string $field, Database $link, User $user, array $errorMessages): void
    {

        $result = $link->query('SELECT * FROM users WHERE '. $field .'= :'. $field, [$field => $this->getField($field)])->fetch();
        if ($result){
            if ($user->banPeriod($result)){
                if (!in_array($errorMessages[0], $this->errors)) {
                    if ($user->banPeriod($result) > 0) {
                        $this->errors[$field] = $errorMessages[0] . $user->banPeriod($result) . ' days';
                    }
                }
            } else {
                $this->errors[$field] = $errorMessages[1];
            }
        }

    }

    public function isConfirmed(array $fields, array $errorMessages): void
    {

        if (!$this->isValuePosted($fields[0]) || strlen($this->getField($fields[0])) < 5){
            $this->errors[$fields[0]] = $errorMessages[0];
        }

        if (!$this->isValuePosted($fields[1]) || $this->getField($fields[0]) != $this->getField($fields[1])){
            $this->errors[$fields[1]] = $errorMessages[1];
        }

    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isTooLong(string $field, int $length, string $errorMsg): void
    {
        if (strlen($this->data[$field]) > $length){
            $this->errors['field'] = $errorMsg;
        }
    }

}