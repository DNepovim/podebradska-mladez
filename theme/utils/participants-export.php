<?php

function pm_participants_export($eventID) {
	$document = new PHPExcel();
	$document->setActiveSheetIndex(0);
	$list = $document->getActiveSheet();

	$event = get_post($eventID);
	$participants = getEventParticipants($event->ID);
	$additionals = get_post_meta($event->ID, 'pm_additional_fields', true);

	$columns = [
		['name' => 'Datum přihlášení',	'width' => 10, 'id' => 'participant_date'],
		['name' => 'Jméno',							'width' => 20, 'id' => 'participant_name'],
		['name' => 'Příjmení',					'width' => 20, 'id' => 'participant_surname'],
		['name' => 'E-mail',						'width' => 10, 'id' => 'participant_mail'],
	];

	$columns_addtional = [];
	foreach ($additionals as $value) {
		$columns_addtional[] = ['name' => $value['pm_label'], 'width' => 10];
	}

	$col = A;
	$row = 1;
	foreach (array_merge($columns, $columns_addtional) as $column) {
		// Set width of column
		$list->getColumnDimension($col)->setWidth($column['width']);
		// Set column name
		$list->setCellValue( $col . $row, $column['name'] );
		$col++;
	}

	foreach ($participants as $participant) {
		$row++;
		$col = A;

		foreach ($columns as $column) {
			// Add data
			if ( $column['id'] == 'participant_date') {
					$value = date('j. n. y', strtotime(get_post_meta($participant->ID, $column['id'], true)));
			} else {
					$value = get_post_meta($participant->ID, $column['id'], true);
			}
			$list->setCellValue($col++ . $row, $value);
		}

		$additionals = get_post_meta($participant->ID, 'participant_additional', true);
		foreach ($additionals as $key => $field) {
			// Add data
			$value = '';
			$firstValue = $additionals[$key];
			if (is_array($firstValue)) {
				foreach ($firstValue as $item) {
					$value .= $item . "\n";
				}
			} else {
				$value = $firstValue;
			}
			$list->setCellValue($col++ . $row, $value);
		}
	}

	$list->getStyle('J1:Q' . $list->getHighestRow())->getAlignment()->setWrapText(true);

	$list->getStyle('A1:' . $col . '1')->applyFromArray([
		'fill' => [
				'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'color' => ['rgb' => '417505']
		],
		'font'  => [
				'bold'  => true,
				'color' => ['rgb' => 'FFFFFF']
		]
	]);

	$filename = get_the_date('ym', $event) . '_';
	$filename .= $event->post_name . '_';
	$filename .= 'prihlasovani' . '.xlsx';

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="' . $filename . '"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($document, ("Excel2007"));
	$objWriter->save('php://output');
	die();
}
