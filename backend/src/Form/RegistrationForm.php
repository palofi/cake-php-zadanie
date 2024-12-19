<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class RegistrationForm extends Form
{
    protected Table $Users;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @param \Cake\Form\Schema $schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema->addField('username', 'string')
            ->addField('password', ['type' => 'string'])
            ->addField('confirmPassword', ['type' => 'string']);
    }

    /**
     * @param \Cake\Validation\Validator $validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->minLength('password', 8)
            ->sameAs('password', 'confirmPassword', 'Password and Confirm Password must match.')
            ->regex(
                'password',
                '/^(?=.*[A-Z])(?=.*\d).+$/',
                'Password must contain at least one number and one uppercase character.'
            )
            ->add('username', 'unique', [
                'rule' => [$this, 'isUniqueUsername'],
                'message' => 'This username is already taken.',
            ]);

        return $validator;
    }

    /**
     * @param string $value
     * @param array<string> $context
     * @return bool
     */
    public function isUniqueUsername(string $value, array $context): bool
    {
        $count = $this->Users->find()
            ->where(['username' => $value])
            ->count();

        return $count === 0;
    }
}
