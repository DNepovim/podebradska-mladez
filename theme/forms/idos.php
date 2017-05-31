<?php

// Latte: {$Forms[idos]}

use Nette\Forms\Form;

$form = new Form;

$form->addProtection('Detected robot activity.');
$form->setAction('http://jizdnirady.idnes.cz/vlakyautobusy/spojeni');

$form->addHidden('date');
$form->addHidden('time');
$form->addHidden('t');
$form->addHidden('byarr');

$form->addText('f', 'Odkud')
	->setAttribute('placeholder', 'Úžice')
	->setRequired('Musíš sem napsat odkud jedeš.');

$form->addSubmit('send', 'Odeslat');

return $form;
