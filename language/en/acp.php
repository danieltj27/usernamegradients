<?php

/**
 * @package Username Gradients
 * @copyright (c) 2025 Daniel James
 * @license https://opensource.org/license/gpl-2-0
 */

if ( ! defined( 'IN_PHPBB' ) ) {

	exit;

}

if ( empty( $lang ) || ! is_array( $lang ) ) {

	$lang = [];

}

$lang = array_merge( $lang, [
	'ACP_GROUP_GRADIENT_DIRECTION_LABEL'			=> 'Gradient direction',
	'ACP_GROUP_GRADIENT_DIRECTION_DESCRIPTION'		=> 'Set the direction of the text gradient.',
	'ACP_GROUP_GRADIENT_DIRECTION_VALUE_TOP'		=> 'To top',
	'ACP_GROUP_GRADIENT_DIRECTION_VALUE_RIGHT'		=> 'To right',
	'ACP_GROUP_GRADIENT_DIRECTION_VALUE_BOTTOM'		=> 'To bottom',
	'ACP_GROUP_GRADIENT_DIRECTION_VALUE_LEFT'		=> 'To left',
	'ACP_GROUP_GRADIENT_ONE_LABEL'					=> 'Gradient colour 1',
	'ACP_GROUP_GRADIENT_ONE_DESCRIPTION'			=> 'The hex colour code of the first colour in the gradient. You must supply both colours for a gradient to be applied.',
	'ACP_GROUP_GRADIENT_TWO_LABEL'					=> 'Gradient colour 2',
	'ACP_GROUP_GRADIENT_TWO_DESCRIPTION'			=> 'The hex colour code of the second colour in the gradient. You must supply both colours for a gradient to be applied.'
] );
