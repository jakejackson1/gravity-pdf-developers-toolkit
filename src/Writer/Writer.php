<?php

namespace GFPDF\Plugins\DeveloperToolkit\Writer;

use BadMethodCallException;

/**
 * @package     Gravity PDF Developer Toolkit
 * @copyright   Copyright (c) 2020, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.0
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This class acts as a router for all public methods found in the GFPDF\Plugins\DeveloperToolkit\Writer namespace.
 * Objects that impliment InterfaceWriter are registered when this object is first created. When methods that don't exist
 * are called they get routed through the magic method __call(), which searches through each registered object for a matching
 * method name. If found, it passes the arguments directly to the method. This allows a simple API for the
 * Toolkit-enabled template files, while still making our code easily maintainable and testible.
 *
 * ## Examples
 *
 * Writer is automatically injected into your Toolkit-enabled templates and is accessible via `$w`. To enable this feature,
 * add `Toolkit: true` to the PDF template header information (where `Template Name` and `Group` goes). Alternatively,
 * use the WP CLI command `wp gpdf create-template "My Custom Template" --enable-toolkit`. To get more information about
 * the WP CLI command use `wp gpdf create-template --help`.
 *
 *      // Load our custom CSS styles that'll apply to the PDF template
 *      $w->beginStyles();
 *      ?>
 *      <style>
 *          body {
 *              color: #999;
 *          }
 *      </style>
 *      <?php
 *      $w->endStyles();
 *
 *      // Load our PDF we want to overlay content onto
 *      $w->addPdf( __DIR__ . '/pdfs/path-to-document.pdf' );
 *
 *      // Load page 1 which we can then overlay content to
 *      $w->addPage( 1 );
 *
 *      // Add the text 'My Content' at 50mm from the left, 100mm from the top with a width of 30mm and a height of 5mm
 *      $w->add( 'My content', [ 50, 100, 30, 5 ] );
 *
 *      // Add a multi-line text snippet (gives you better line-height)
 *      $w->addMulti( 'Content<br>Content<br>Content', [ 20, 80, 100, 50 ] );
 *
 *      // Load pages 2 thru 4
 *      $w->addPage( [ 2, 4 ] );
 *
 *      // Add a Checkbox to page 4 at 100mm from the left and 30mm from the top
 *      $w->tick( [ 100, 30 ] );
 *
 *      // Add an Ellipse to page 4 at 120mm from the left and 50mm from the top (this will mark the center of the ellipse), with a 5mm width and 3mm height
 *      $w->ellipse( [ 120, 50, 5, 3 ] );
 *
 * For more examples, view the individual class documentation in GFPDF\Plugins\DeveloperToolkit\Writer\Processes
 *
 * @package GFPDF\Plugins\DeveloperToolkit\Writer
 *
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Import
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Single
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Multi
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Ellipse
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Tick
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Html
 * @mixin \GFPDF\Plugins\DeveloperToolkit\Writer\Processes\Styles
 *
 * @since   1.0
 */
class Writer extends AbstractWriter {

	/**
	 * @var InterfaceWriter[]
	 * @since 1.0
	 */
	protected $classes = [];

	/**
	 * Register all classes on initialisation
	 *
	 * @param InterfaceWriter[] $classes
	 *
	 * @since 1.0
	 */
	public function __construct( $classes = [] ) {
		foreach ( $classes as $class ) {
			$this->registerClass( $class );
		}
	}

	/**
	 * Register the class with the Writer
	 *
	 * @param InterfaceWriter $class
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	public function registerClass( InterfaceWriter $class ) {
		$this->classes[] = $class;
	}

	/**
	 * Search through the registered classes for a public method match and call it with the passed arguments
	 *
	 * @param string $name      The method being called
	 * @param array  $arguments The method arguments
	 *
	 * @return mixed
	 *
	 * @throws BadMethodCallException
	 */
	public function __call( $name, $arguments ) {
		foreach ( $this->classes as $class ) {
			if ( is_callable( [ $class, $name ] ) ) {
				$this->maybeInjectMpdf( $class );

				return call_user_func_array( [ $class, $name ], $arguments );
			}
		}

		throw new BadMethodCallException( sprintf( 'The method "%s" could not be found.', $name ) );
	}

	/**
	 * Inject the Mpdf object to our registered classes (if required) right before calling the chosen method
	 *
	 * @param InterfaceWriter $class
	 *
	 * @since 1.0
	 */
	protected function maybeInjectMpdf( $class ) {
		if ( ! $class->isMpdfSet() ) {
			$class->setMpdf( $this->mpdf );
		}
	}
}
