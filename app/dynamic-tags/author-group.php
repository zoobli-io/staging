<?php

namespace Voxel\Dynamic_Tags;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Author_Group extends User_Group {

	public $user;

	public function get_key(): string {
		return 'author';
	}

	public function get_title(): string {
		return _x( 'Author', 'groups', 'voxel' );
	}

	protected function editor_init(): void {
		if ( $this->post && ( $author = $this->post->get_author() ) ) {
			$this->user = $author;
		} else {
			$this->user = \Voxel\User::dummy();
		}
	}

	protected function frontend_init(): void {
		if ( $this->post && ( $author = $this->post->get_author() ) ) {
			$this->user = $author;
		} else {
			$this->user = \Voxel\User::dummy();
		}
	}
}
