<?php declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class RegistrationForm extends Form
{
    protected $Users;

    public function __construct()
    {
        parent::__construct();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('username', 'string')
            ->addField('password', ['type' => 'string'])
            ->addField('confirmPassword', ['type' => 'string']);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->minLength('password', 8)
            ->sameAs('password', 'confirmPassword', 'Password and Confirm Password must match.')
            ->regex('password', '/^(?=.*[A-Z])(?=.*\d).+$/', 'Password must contain at least one number and one uppercase character.')
            ->add('username', 'unique', [
                'rule' => [$this, 'isUniqueUsername'],
                'message' => 'This username is already taken.'
            ]);

        return $validator;
    }

    public function isUniqueUsername($value, $context)
    {
        $count = $this->Users->find()
            ->where(['username' => $value])
            ->count();

        return $count === 0;
    }

    protected function _execute(array $data): bool
    {
        return true;
    }
}
