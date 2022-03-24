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
			exit( 'Incorrect title and id has been pasted. Please provide value copied from issue title above issue description. The correct pattern is "{issue title} #{issue id}"' . PHP_EOL );
		}

		$slugify = new Slugify();

		$id = trim( array_pop( $title_and_id ) );
		$title = trim( implode( ' ', $title_and_id ) );

		$slug = $slugify->slugify( sprintf( '%d %s', $id, $title ) );

		$branchname = sprintf(
			'%s/%s',
			$namespace,
			$slug
		);

		print( 'Creating branch with name ' . $branchname . PHP_EOL );
		exit(0);
	}
}