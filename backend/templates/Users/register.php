<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Form\RegistrationForm $registration
 */
?>
<div class="row">

    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($registration) ?>
            <fieldset>
                <legend><?= __('Register') ?></legend>
                <?php
                echo $this->Form->control('username');
                echo $this->Form->control('password', ['type' => 'password']);
                echo $this->Form->control('confirmPassword', ['type' => 'password']);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
