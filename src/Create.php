<?php

namespace awesomemotive\NewBranch;

use Cocur\Slugify\Slugify;

class Create {

	public function from_title_and_id() {

		global $argv;

		// format:
		// {plugin}/{id}-?{component}-{keywords}

		$arguments = implode( ' ', array_slice( $argv, 1 ) );

		$plugin_and_title = explode( "/", $arguments );

		if ( count( $plugin_and_title ) > 1 ) {
			$plugin = trim( $plugin_and_title[0] );
			$title_and_id = implode( ' ', array_slice( $plugin_and_title, 1 ) );
		} else {
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
				$plugin = false;
			} else {
				$plugin = $this->get_namespaces()[ $plugin_id ];
			}
			$title_and_id = implode( ' ', $plugin_and_title );
		}

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

		$slugify = new Slugify();

		$slug = $slugify->slugify( sprintf( '%d %s %s', $id, $component, $keywords ) );

		$branchname = $plugin ? sprintf(
			'%s/%s',
			$slugify->slugify( $plugin ),
			$slug
		) : $slug;

		print( "I am going to create a branch with the following name. You can edit it and then press enter to accept. Press ctrl+C to exit:" . PHP_EOL );
		$branchname = $this->xreadline( '', $branchname );

		$branchname = str_replace( 'slashplaceholder', '/', $slugify->slugify( str_replace( "/", "slashplaceholder", $branchname ) ) );

		shell_exec( sprintf( 'git checkout -b %s > /dev/null 2>&1', $branchname ) );
		print( 'Created branch with name ' . $branchname . PHP_EOL );

		exit(0);
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
			18 => 'paypal-standard',
			19 => 'post-submissions',
			20 => 'salesforce',
			21 => 'save-resume',
			22 => 'sendinblue',
			23 => 'signatures',
			24 => 'square',
			25 => 'stripe',
			26 => 'surveys-polls',
			27 => 'user-journey',
			28 => 'user-registration',
			29 => 'webhooks',
			30 => 'zapier',
		];
	}
}