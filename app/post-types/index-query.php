<?php

namespace Voxel\Post_Types;

if ( ! defined('ABSPATH') ) {
	exit;
}

class Index_Query {

	public
		$post_type,
		$table;

	private
		$select_clauses = [],
		$join_clauses = [],
		$where_clauses = [],
		$orderby_clauses = [],
		$groupby_clauses = [];

	public function __construct( \Voxel\Post_Type $post_type ) {
		$this->post_type = $post_type;
		$this->table = $post_type->get_index_table();
	}

	public function select( string $clause_sql ) {
		$this->select_clauses[] = $clause_sql;
	}

	public function join( string $clause_sql ) {
		$this->join_clauses[] = $clause_sql;
	}

	public function where( string $clause_sql ) {
		$this->where_clauses[] = $clause_sql;
	}

	public function orderby( string $clause_sql ) {
		$this->orderby_clauses[] = $clause_sql;
	}

	public function groupby( string $clause_sql ) {
		$this->groupby_clauses[] = $clause_sql;
	}

	public function get_sql( array $args = [] ) {
		// reset
		$this->select_clauses = [];
		$this->join_clauses = [];
		$this->where_clauses = [];
		$this->orderby_clauses = [];
		$this->groupby_clauses = [];

		// apply filters
		foreach ( $this->post_type->get_filters() as $filter ) {
			$filter->query( $this, $args );
		}

		$limit = '';
		if ( isset( $args['limit'] ) && absint( $args['limit'] ) > 0 ) {
			$limit = sprintf( 'LIMIT %d', absint( $args['limit'] ) );
		}

		$offset = '';
		if ( isset( $args['offset'] ) && absint( $args['offset'] ) > 0 ) {
			$offset = sprintf( 'OFFSET %d', absint( $args['offset'] ) );
		}

		// generate sql string
		$sql = "
			SELECT DISTINCT `{$this->table->get_escaped_name()}`.post_id {$this->_get_select_clauses()}
				FROM `{$this->table->get_escaped_name()}`
			{$this->_get_join_clauses()}
			{$this->_get_where_clauses()}
			{$this->_get_groupby_clauses()}
			{$this->_get_orderby_clauses()}
			{$limit} {$offset}
		";

		return $sql;
	}

	private function _get_where_clauses() {
		if ( empty( $this->where_clauses ) ) {
			return '';
		}

		return sprintf( 'WHERE %s', join( ' AND ', $this->where_clauses ) );
	}

	private function _get_select_clauses() {
		if ( empty( $this->select_clauses ) ) {
			return '';
		}

		return ', '. join( ", ", $this->select_clauses );
	}

	private function _get_join_clauses() {
		if ( empty( $this->join_clauses ) ) {
			return '';
		}

		return join( " \n ", $this->join_clauses );
	}

	private function _get_orderby_clauses() {
		if ( empty( $this->orderby_clauses ) ) {
			return 'ORDER BY post_id DESC';
		}

		return sprintf( 'ORDER BY %s', join( ", ", $this->orderby_clauses ) );
	}

	private function _get_groupby_clauses() {
		if ( empty( $this->groupby_clauses ) ) {
			return '';
		}

		return sprintf( 'GROUP BY %s', join( ", ", array_unique( $this->groupby_clauses ) ) );
	}

	public function get_posts( array $args = [] ) {
		global $wpdb;

		// dump_sql($this->get_sql( $args ));
		$post_ids = $wpdb->get_col( $this->get_sql( $args ) );
		return array_map( 'intval', $post_ids );
	}
}
