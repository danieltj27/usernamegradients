<?php

/**
 * @package Username Gradients
 * @copyright (c) 2025 Daniel James
 * @license https://opensource.org/license/gpl-2-0
 */

namespace danieltj\usernamegradients\event;

use phpbb\request\request;
use phpbb\template\template;
use phpbb\language\language;
use danieltj\usernamegradients\includes\functions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface {

	/**
	 * @var request
	 */
	protected $request;

	/**
	 * @var template
	 */
	protected $template;

	/**
	 * @var language
	 */
	protected $language;

	/**
	 * @var functions
	 */
	protected $functions;

	/**
	 * Constructor
	 */
	public function __construct( request $request, template $template, language $language, functions $functions ) {

		$this->request = $request;
		$this->template = $template;
		$this->language = $language;
		$this->functions = $functions;

	}

	/**
	 * Register Events
	 */
	static public function getSubscribedEvents() {

		return [
			'core.user_setup_after'						=> 'add_languages',
			'core.modify_username_string'				=> 'update_username_string',
			'core.acp_manage_group_display_form'		=> 'add_group_settings',
			'core.acp_manage_group_initialise_data'		=> 'init_group_data',
			'core.acp_manage_group_request_data'		=> 'request_group_data',
		];

	}

	/**
	 * Add Languages
	 */
	public function add_languages() {

		$this->language->add_lang( [ 'acp' ], 'danieltj/usernamegradients' );

	}

	/**
	 * includes/fucntions_content
	 */
	public function update_username_string( $event ) {

		$data = $this->functions->get_group_gradient_data( $this->functions->get_group_id_by_user_id( $event[ 'user_id' ] ) );

		if ( ! empty( $data ) && 1 <= strlen( $event[ 'username_string' ] ) ) {

			// Store the filtered username string.
			$html_username_string = $event[ 'username_string' ];

			$dom = new \DOMDocument;
			$dom->loadHTML( $html_username_string, \LIBXML_HTML_NODEFDTD | \LIBXML_HTML_NOIMPLIED );

			if ( str_starts_with( $html_username_string, '<a' ) || str_starts_with( $html_username_string, '<span' ) ) {

				// Using <span> or <a>?
				if ( str_starts_with( $html_username_string, '<a' ) ) {

					$elements = $dom->getElementsByTagName( 'a' );

				} elseif ( str_starts_with( $html_username_string, '<span' ) ) {

					$elements = $dom->getElementsByTagName( 'span' );

				}

				if ( 't' === $data[ 'group_gradient_dir' ] ) {

					$dir = 'to top';

				} elseif ( 'r' === $data[ 'group_gradient_dir' ] ) {

					$dir = 'to right';

				} elseif ( 'b' === $data[ 'group_gradient_dir' ] ) {

					$dir = 'to bottom';

				} elseif ( 'l' === $data[ 'group_gradient_dir' ] ) {

					$dir = 'to left';

				}

				foreach ( $elements as $element ) {

					$element->setAttribute( 'style', 'color: transparent; background: linear-gradient(' . $dir . ', #' . $data[ 'group_gradient_1' ] . ', #' . $data[ 'group_gradient_2' ] . '); background-clip: text;' );

					$event[ 'username_string' ] = $dom->saveHTML();

				}

			}

		}

	}

	/**
	 * @todo
	 */
	public function add_group_settings( $event ) {

		$this->template->assign_vars( [
			'GROUP_GRADIENT_DIRECTION'	=> $event[ 'group_row' ][ 'group_gradient_dir' ],
			'GROUP_GRADIENT_COLOUR_ONE'	=> $event[ 'group_row' ][ 'group_gradient_1' ],
			'GROUP_GRADIENT_COLOUR_TWO'	=> $event[ 'group_row' ][ 'group_gradient_2' ]
		] );

	}

	/**
	 * @todo
	 */
	public function init_group_data( $event ) {

		$event->update_subarray( 'test_variables', 'gradient_dir', 'string' );
		$event->update_subarray( 'test_variables', 'gradient_1', 'string' );
		$event->update_subarray( 'test_variables', 'gradient_2', 'string' );

	}

	/**
	 * @todo
	 */
	public function request_group_data( $event ) {

		$direction = $this->request->variable( 'group_gradient_dir', '' );
		$colour_1 = $this->request->variable( 'group_gradient_1', '' );
		$colour_2 = $this->request->variable( 'group_gradient_2', '' );

		$direction = in_array( $direction, [ 't', 'r', 'b', 'l' ], true ) ? $direction : 'l';
		$colour_1 = ctype_alnum( $colour_1 ) && 6 === (int) strlen( $colour_1 ) ? $colour_1 : '';
		$colour_2 = ctype_alnum( $colour_2 ) && 6 === (int) strlen( $colour_2 ) ? $colour_2 : '';

		$event->update_subarray( 'submit_ary', 'gradient_dir', $direction );
		$event->update_subarray( 'submit_ary', 'gradient_1', $colour_1 );
		$event->update_subarray( 'submit_ary', 'gradient_2', $colour_2 );

	}

}
