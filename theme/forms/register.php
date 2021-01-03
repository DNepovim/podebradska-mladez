<?php

// Latte: {$Forms[register]}

use Nette\Forms\Form;

$form = new Form;

$form->addProtection('Detected robot activity.');

$form->addHidden('postID');

$form->addtext('firstname', 'Jméno')
	->setRequired('Napiš nám sem prosím svoje jméno.');

$form->addtext('surname', 'Příjmení')
	->setRequired('Napiš nám sem prosím svoje příjmení.');

$form->addText('email', 'E-mail')
	->setRequired('Napiš nám sem prosím svůj e-mail, ať ti můžeme když tak poslat další informace.')
	->addRule($form::EMAIL, 'Udělal jsi asi nějakou chybu v e-mailu.');

$form->addSubmit('register', 'Přihlásit se');

if(isFormValid($form, __FILE__)) {
	$values = $form->getValues();
	$values['additional'] = $form->getHttpData()['additional'];

	try {
		process_registration_form($values);
		wp_redirect(add_query_arg('success', true, remove_query_arg('do')));
		die;
	} catch (SendException $e) {
		wp_redirect(add_query_arg('success', false, remove_query_arg('do')));
	}
}

return $form;
