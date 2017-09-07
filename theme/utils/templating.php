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
	return get_daterange($event);
};

MangoFilters::$set['gpsizes'] = function($url, $width = null, $height = null){
	$url .= '=';
	if ($width) {
		$url .= 'w' . $width . '-';
	}
	if ($height) {
		$url .= 'h' . $height;
	}
	return $url;
};

MangoFilters::$set['hashtags'] = function($string){
	return preg_replace('/#([^\s]+)/', '<span class="hashtag">#\1</span>', $string);
};
