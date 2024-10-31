<?php

function one_elements_hover_animations() {

	return [
		'forward' => 'Forward',
		'backward' => 'Backward'
	];

}

add_filter( 'elementor/controls/hover_animations/additional_animations', 'one_elements_hover_animations' );