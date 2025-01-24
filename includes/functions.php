<?php

/**
 * @package Username Gradients
 * @copyright (c) 2025 Daniel James
 * @license https://opensource.org/license/gpl-2-0
 */

namespace danieltj\usernamegradients\includes;

use phpbb\auth\auth;
use phpbb\db\driver\driver_interface as database;

final class functions {

	/**
	 * @var driver_interface
	 */
	protected $database;

	/**
	 * Constructor
	 */
	public function __construct( database $database ) {

		$this->database = $database;

	}

	/**
	 * @todo
	 * 
	 * @since 1.0.0
	 * 
	 * @param  integer $user_id  
	 * @return integer $group_id 
	 */
	public function get_group_id_by_user_id( $user_id ) {

		$user_id = (int) $user_id;

		$sql = 'SELECT * FROM ' . USERS_TABLE . ' WHERE ' . $this->database->sql_build_array( 'SELECT', [
			'user_id' => $user_id
		] );

		$result = $this->database->sql_query( $sql );
		$user = $this->database->sql_fetchrow( $result );
		$this->database->sql_freeresult( $result );

		if ( empty( $user ) ) {

			return 0;

		}

		return (int) $user[ 'group_id' ];

	}

	/**
	 * @todo
	 * 
	 * @since 1.0.0
	 * 
	 * @param  integer $group_id The group ID used to fetch the gradient data.
	 * @return array             An array that contains gradient data. The array will be
	 *                           empty if there is a problem fetching the complete data.
	 */
	public function get_group_gradient_data( $group_id ) {

		$group_id = (int) $group_id;

		if ( 0 === $group_id ) {

			return [];

		}

		$sql = 'SELECT * FROM ' . GROUPS_TABLE . ' WHERE ' . $this->database->sql_build_array( 'SELECT', [
			'group_id' => $group_id
		] );

		$result = $this->database->sql_query( $sql );
		$group = $this->database->sql_fetchrow( $result );
		$this->database->sql_freeresult( $result );

		if ( empty( $group ) ) {

			return [];

		}

		if (
			in_array( $group[ 'group_gradient_dir' ], [ 't', 'r', 'b', 'l' ], true ) &&
			ctype_alnum( $group[ 'group_gradient_1' ] ) && 6 === (int) strlen( $group[ 'group_gradient_1' ] ) &&
			ctype_alnum( $group[ 'group_gradient_2' ] ) && 6 === (int) strlen( $group[ 'group_gradient_2' ] )
		) {

			return [
				'group_gradient_dir'	=> $group[ 'group_gradient_dir' ],
				'group_gradient_1'		=> $group[ 'group_gradient_1' ],
				'group_gradient_2'		=> $group[ 'group_gradient_2' ]
			];

		}

		return [];

	}

}
