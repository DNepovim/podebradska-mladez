<?php

MangoPressTemplating::init();

// czech number format
MangoFilters::$set['number'] = function($number, $decimal = 2) {
	if(fmod($number, 1) == 0) {
		$decimal = 0;
	}
	$sep = ',';
	$formatted = number_format($number, $decimal, $sep, "\xC2\xA0");
	if($decimal) {
		$formatted = Strings::replace($formatted, '~,?0$~');
	}

	return $formatted;
};

// czech Kč number format
MangoFilters::$set['czk'] = function($number, $decimal = 2){
	if(fmod($number, 1) == 0) {
		$decimal = 0;
	}
	$sep = ',';
	$formatted = number_format($number, $decimal, $sep, "\xC2\xA0");
	if($decimal) {
		$formatted = Strings::replace($formatted, '~,?0*$~', '');
	}

	return $formatted . (!$decimal ? ',-' : '') . "\xC2\xA0Kč";
};

MangoFilters::$set['daterange'] = function($event){
	$start = strtotime(get_post_meta($event->ID, 'pm_start_date', true));
	$end = strtotime(get_post_meta($event->ID, 'pm_end_date', true));
	if (date('dmy', $start) == date('dmy', $end)) {
			$date = date('j. n. Y', $start);
	} else {
		if(date('m', $start) == date('m', $end)){
			$date = date('j.', $start) . '–' . date('j. n. Y', $end);
		} else {
			$date = date('j. n.', $start) . '–' . date('j. n. Y', $end);
		}
	}

	return $date;
};
