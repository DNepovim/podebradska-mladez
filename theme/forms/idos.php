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

$form->addText('f', 'Odkud pojedeš?')
	->setRequired('Musíš sem napsat odkud pojedeš.');

$form->addSubmit('send', 'Najít');
return $form;
