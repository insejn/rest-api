<?php

namespace AKPRO\CaseStudies;
use Micropackage\DocHooks\HookAnnotations;
use StoutLogic\AcfBuilder\FieldsBuilder;

class Fields extends HookAnnotations {
	/**
	 * @action acf/init
	 */
	public function build() {
		$crm_details = new FieldsBuilder('crm_details');
		$crm_details
			->addRepeater('zakres_prac', ['min' => 1, 'layout' => 'block'])
				->addText('nazwa')
				->addWysiwyg('opis')
				->endRepeater()
			->addRepeater('parametry_ekranow', ['min' => 1, 'layout' => 'block'])
				->addText('nazwa')
				->addWysiwyg('opis')
				->endRepeater()
			->addGallery('zdjecia')
			->addOembed('film')
			->addDatePicker('data_realizacji')
			->addText('miejsce_wykonania')
			->addText('nazwa_klienta')
			->addText('imienazwisko_referencje')
			->addText('cytat_referencje')
			->addNumber('crm_id')
			->setLocation('post_type', '==', 'case_study');

		acf_add_local_field_group($crm_details->build());
	}
}
