<?php

namespace awesomemotive\NewBranch;

use Cocur\Slugify\Slugify;

class Create {

	private Slugify $slugify;

	/**
	 * @var false|string
	 */
	private $plugin;

	public function __construct() {
		$this->slugify = new Slugify();
	}

	public function from_title_and_id() {

		global $argv;

		// format:
		// {plugin}/{id}-?{component}-{keywords}

		$arguments = implode( ' ', array_slice( $argv, 1 ) );

		$title_and_id = $this->set_plugin_part( $arguments );

		$title_and_id = explode( '#', $title_and_id );

		if ( count( $title_and_id ) < 2 ) {
			$id = $this->xreadline( 'I did not recognize ID at the end of copied title. Please enter it manually: ' );
		} else {
			$id = trim( array_pop( $title_and_id ) );
		}

		$title   = trim( implode( ' ', $title_and_id ) );

		$component_and_tags = explode( ":", $title );

		if ( count( $component_and_tags ) < 2 ) {
			$component = '';
		} else {
			$component = array_shift( $component_and_tags );
		}

		$component = $this->xreadline( 'Please provide component, feature or field this branch is relevant (leave empty to skip): ', $component );

		$keywords = trim( implode( ' ', $component_and_tags ) );

		$keywords = $this->xreadline( 'Provide some keywords which should be added to branch name: ', $keywords );

		$slug = $this->slugify->slugify( sprintf( '%d %s %s', $id, $component, $keywords ) );

		$words = 4;

		while ( $this->slug_too_long( $slug, $words ) ) {

			$fix_slug = $this->xreadline( sprintf( 'This slug has too many words, please shorten it to have not more than %d words: ', $words ), $slug );
			$slug     = $this->slugify->slugify( $fix_slug );

			$words++;
		}

		$branchname = $this->plugin ? sprintf(
			'%s/%s',
			$this->slugify->slugify( $this->plugin ),
			$slug
		) : $slug;

		print( "I am going to create a branch with the following name. You can edit it and then press enter to accept. Press ctrl+C to exit:" . PHP_EOL );
		$branchname = $this->slugify_preserve_slashes( $this->xreadline( '', $branchname ) );

		shell_exec( sprintf( 'git checkout -b %s > /dev/null 2>&1', $branchname ) );
		print( 'Created branch with name ' . $branchname . PHP_EOL );

		exit(0);
	}

	/**
	 * Checks if string has more words than $length delimiters.
	 *
	 * @param string $slug  Slug to verify.
	 * @param int    $words Number of allowed words, default 3.
	 *
	 * @return bool
	 */
	private function slug_too_long( $slug, $words = 4 ) {

		return substr_count( $slug, '-' ) > $words;
	}

	/**
	 * @param string $arguments
	 *
	 * @return string|void
	 */
	private function set_plugin_part( string $arguments ) {

		$plugin_and_title = explode( "/", $arguments );

		if ( count( $plugin_and_title ) > 1 ) {
			$this->plugin = trim( $plugin_and_title[0] );
			return implode( ' ', array_slice( $plugin_and_title, 1 ) );
		}

		print( 'Select namespace for the branch. Selected namespace will be prepended to the branch name and followed by slash.' . PHP_EOL );
		$namespaces = '';
		foreach ( $this->get_namespaces() as $id => $namespace ) {
			$namespaces .= sprintf( '%d: %s %s', $id, $namespace, PHP_EOL );
		}
		print( shell_exec( 'echo "' . $namespaces . '" | column' ) );
		$plugin_id = $this->xreadline( 'Please provide the number: ', 1 );
		if ( ! is_numeric( $plugin_id ) ) {
			exit( 'Error: Please provide the numeric value for selected namespace' . PHP_EOL );
		}
		if ( (int) $plugin_id === 0 ) {
			$this->plugin = false;
		} else {
			$this->plugin = $this->get_namespaces()[ $plugin_id ];
		}

		return implode( ' ', $plugin_and_title );
	}

	/**
	 * Display bash prompt with prefilled expected value.
	 *
	 * @param string $prompt  The prompt text.
	 * @param string $prefill Prefilled value.
	 *
	 * @return false|string
	 */
	private function xreadline($prompt, $prefill = '' ) {
		return exec ('/bin/bash -c \'read -r -p "'.$prompt.'" -i "'.$prefill.'" -e STRING;echo "$STRING";\'');
	}

	/**
	 * Slugifies the string but keeps slashes.
	 *
	 * @param string $string
	 *
	 * @return string
	 */
	private function slugify_preserve_slashes( string $string ) {

		return str_replace( 'slashplaceholder', '/', $this->slugify->slugify( str_replace( "/", "slashplaceholder", $string ) ) );
	}

	private function get_namespaces() {
		return [
			0 => '(skip the namespace part)',
			1 => 'core',
			2 => 'activecampaign',
			3 => 'authorize-net',
			4 => 'aweber',
			5 => 'campaign-monitor',
			6 => 'captcha',
			7 => 'conversational-forms',
			8 => 'drip',
			9 => 'form-abandonment',
			10 => 'form-locker',
			11 => 'form-pages',
			12 => 'form-templates-pack',
			13 => 'geolocation',
			14 => 'getresponse',
			15 => 'hubspot',
			16 => 'mailchimp',
			17 => 'offline-forms',
			18 => 'paypal-commerce',
			19 => 'paypal-standard',
			20 => 'post-submissions',
			21 => 'salesforce',
			22 => 'save-resume',
			23 => 'sendinblue',
			24 => 'signatures',
			25 => 'square',
			26 => 'stripe',
			27 => 'surveys-polls',
			28 => 'user-journey',
			29 => 'user-registration',
			30 => 'webhooks',
			31 => 'zapier',
		];
	}
}