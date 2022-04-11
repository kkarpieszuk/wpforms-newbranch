<?php

namespace awesomemotive\NewBranch;

use Cocur\Slugify\Slugify;

class Create {

	public function from_title_and_id() {

		global $argv;

		$arguments = implode( ' ', array_slice( $argv, 1 ) );

		$namespace_and_title = explode( "/", $arguments );

		if ( count( $namespace_and_title ) > 1 ) {
			$namespace = trim( $namespace_and_title[0] );
			$title_and_id = implode( ' ', array_slice( $namespace_and_title, 1 ) );
		} else {
			$namespace = 'core';
			$title_and_id = implode( ' ', $namespace_and_title );
		}

		$title_and_id = explode( '#', $title_and_id );

		if ( count( $title_and_id ) < 2 ) {
			$id = $this->xreadline( 'I did not recognize ID at the end of copied title. Please enter it manually: ' );
		} else {
			$id = trim( array_pop( $title_and_id ) );
		}

		$title   = trim( implode( ' ', $title_and_id ) );
		$slugify = new Slugify();

		$slug = $slugify->slugify( sprintf( '%d %s', $id, $title ) );

		$branchname = sprintf(
			'%s/%s',
			$namespace,
			$slug
		);

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
}