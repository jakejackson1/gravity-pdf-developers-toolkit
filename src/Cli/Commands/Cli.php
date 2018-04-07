<?php

namespace GFPDF\Plugins\DeveloperToolkit\Cli\Commands;

use WP_CLI;

/**
 * @package     Gravity PDF Developer Toolkit
 * @copyright   Copyright (c) 2018, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
    This file is part of Gravity PDF Developer Toolkit.

    Copyright (c) 2018, Blue Liquid Designs

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/**
 * Class Cli
 *
 * @package GFPDF\Plugins\DeveloperToolkit\Cli\Commands
 *
 * @since   1.0
 */
class Cli implements InterfaceCli {

	/**
	 * Logs a message
	 *
	 * @param string $text
	 *
	 * @since 1.0
	 */
	public function log( $text ) {
		WP_CLI::log( $text );
	}

	/**
	 * Logs a warning message
	 *
	 * @param string $text
	 *
	 * @since 1.0
	 */
	public function warning( $text ) {
		WP_CLI::warning( $text );
	}

	/**
	 * Logs a success message
	 *
	 * @param string $text
	 *
	 * @since 1.0
	 */
	public function success( $text ) {
		WP_CLI::success( $text );
	}

	/**
	 * Logs an error
	 *
	 * @param string $text
	 * @param bool   $exit
	 *
	 * @since 1.0
	 * @throws WP_CLI\ExitException
	 */
	public function error( $text, $exit = true ) {
		WP_CLI::error( $text, $exit );
	}

	/**
	 * Ask the CLI user a question and return their response
	 *
	 * @param string $question
	 *
	 * @return string
	 *
	 * @since 1.0
	 */
	public function getResponse( $question ) {
		fwrite( STDOUT, $question );
		return trim( fgets( STDIN ) );
	}
}