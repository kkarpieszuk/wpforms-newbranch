<?php

namespace awesomemotive\NewBranch;

use Cocur\Slugify\Slugify;
use Diversen\ParseArgv;

class Create {

	public function from_title_and_id() {

		$p = new ParseArgv();

		var_dump( $p->flags );

		$config = [
			'title' => [
				'alias'  => 't',
				'help'   => '.Title and ID copied from issue title, just above issue description. The correct pattern is "{issue title} #{issue id}"',
				'filter' => 'string',
			],
			'addon' => [
				'alias' => 'a',
				'default' => 'core',
				'help'  => 'Addon slug.',
				'filter' => 'string',
			]
		];



		$title_and_id = explode( '#', implode( ' ', $p->values ) );

		var_dump( $title_and_id );

		if ( count( $title_and_id ) < 2 ) {
			exit( 'Incorrect title and id has been pasted. Please provide value copied from issue title above issue description. The correct pattern is "{issue title} #{issue id}"' . PHP_EOL );
		}

		$slugify = new Slugify();

		$id = array_pop( $title_and_id );
		$title = implode( ' ', $title_and_id );

		$slug = $slugify->slugify( sprintf( '%d %s', trim( $id ), trim( $title ) ) );

		$branchname = sprintf(
			'%s/%s',
			$p->flags['a'],
			$slug
		);

		print( $branchname . PHP_EOL );
		exit(0);
	}
}