<?php

/**
 * @package Username Gradients
 * @copyright (c) 2025 Daniel James
 * @license https://opensource.org/license/gpl-2-0
 */

namespace danieltj\usernamegradients\migrations;

class initial_install extends \phpbb\db\migration\migration {

	/**
	 * Check installation status.
	 */
	public function effectively_installed() {

		return (
			$this->db_tools->sql_column_exists( $this->table_prefix . 'groups', 'group_gradient_dir' ) &&
			$this->db_tools->sql_column_exists( $this->table_prefix . 'groups', 'group_gradient_1' ) &&
			$this->db_tools->sql_column_exists( $this->table_prefix . 'groups', 'group_gradient_2' )
		);

	}

	/**
	 * Requires phpBB 3.3 migration.
	 */
	static public function depends_on() {

		return [ '\phpbb\db\migration\data\v330\v330' ];

	}

	/**
	 * Install
	 */
	public function update_schema() {

		return [
			'add_columns' => [
				$this->table_prefix . 'groups' => [
					'group_gradient_dir'	=> [ 'VCHAR:1', 'l' ], // used for 'to left' value
					'group_gradient_1'		=> [ 'VCHAR:6', '' ],  // first six digit hex code excluding #
					'group_gradient_2'		=> [ 'VCHAR:6', '' ]   // second six digit hex code excluding #
				]
			]
		];

	}

	/**
	 * Uninstall
	 */
	public function revert_schema() {

		return [
			'drop_columns' => [
				$this->table_prefix . 'groups' => [
					'group_gradient_dir', 'group_gradient_1', 'group_gradient_2'
				]
			]
		];

	}

}
