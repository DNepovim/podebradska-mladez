<?php

// Latte: {$Forms[register]}

use Nette\Forms\Form;

$form = new Form;

$form->addProtection('Detected robot activity.');

$form->addHidden('postID');

$form->addtext('firstname', 'Jméno')
	->setAttribute('placeholder', 'Ruprd')
	->setRequired('Napiš nám sem prosím svoje jméno.');

$form->addtext('surname', 'Příjmení')
	->setAttribute('placeholder', 'Murdoch')
	->setRequired('Napiš nám sem prosím svoje příjmení.');

$form->addText('email', 'E-mail')
	->setAttribute('placeholder', 'ruprde@email.hu')
	->setRequired('Napiš nám sem prosím svůj e-mail, ať ti můžeme když tak poslat další informace.')
	->addRule($form::EMAIL, 'Udělal jsi asi nějakou chybku v e-mailu.');

$form->addSubmit('register', 'Přihlásit se')
	->setAttribute('class', 'button form-button');

if(isFormValid($form, __FILE__)) {
	dump($c->getValues());
}

return $form;
