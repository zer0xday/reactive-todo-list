<?php
namespace App\Validation;

use Respect\Validation\Validator as Respect;
use Respect\Validation\Exceptions\NestedValidationException as NVE;

class Validator {
    protected $messages = [];

    public function validate($req, array $rules) {
        foreach($rules as $field => $rule) {
            try {
                $rule->setName(ucfirst($field))->assert($req->getParam($field));
            } catch(NVE $ex) {
                $this->messages[$field] = $ex->getMessages();
            }
        }

        $_SESSION['errors'] = $this->messages;
        return empty($this->messages) ? true : false;
    }
}