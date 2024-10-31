<?php

function one_elements_entrance_animations() {

	return [
		'Fading' => [
			'fadeIn' => 'Fade In',
			'fadeInDown' => 'Fade In Down',
			'fadeInLeft' => 'Fade In Left',
			'fadeInRight' => 'Fade In Right',
			'fadeInUp' => 'Fade In Up',
			'fadeInDownSmall' => 'Fade In Down Small',
			'fadeInLeftSmall' => 'Fade In Left Small',
			'fadeInRightSmall' => 'Fade In Right Small',
			'fadeInUpSmall' => 'Fade In Up Small',
			'fadeInDownLarge' => 'Fade In Down Large',
			'fadeInLeftLarge' => 'Fade In Left Large',
			'fadeInRightLarge' => 'Fade In Right Large',
			'fadeInUpLarge' => 'Fade In Up Large',
		],

		'Sliding' => [
			'slideInDown' => 'Slide In Down',
			'slideInLeft' => 'Slide In Left',
			'slideInRight' => 'Slide In Right',
			'slideInUp' => 'Slide In Up',
			'slideInDownSmall' => 'Slide In Down Small',
			'slideInLeftSmall' => 'Slide In Left Small',
			'slideInRightSmall' => 'Slide In Right Small',
			'slideInUpSmall' => 'Slide In Up Small',
			'slideInDownLarge' => 'Slide In Down Large',
			'slideInLeftLarge' => 'Slide In Left Large',
			'slideInRightLarge' => 'Slide In Right Large',
			'slideInUpLarge' => 'Slide In Up Large',
		]

	];

	// return array_merge( $additional_animations, $oneelements_animations );

}

add_filter( 'elementor/controls/animations/additional_animations', 'one_elements_entrance_animations' );