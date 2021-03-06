<?php

namespace Persephone\Modules\Acp;

if ( !defined( "INIT_DONE" ) )
{
	die( "Improper access! Exiting now..." );
}

/**
 * Handlers : ACP
 *
 * @package  Audith CMS codename Persephone
 * @author   Shahriyar Imanov <shehi@imanov.name>
 * @version  1.0
 */
class Handler
{
	/**
	 * Registry Reference
	 *
	 * @var \Persephone\Core\Registry
	 */
	private $Registry;

	/**
	 * Main container for retrieved content
	 *
	 * @var mixed
	 */
	private $content;

	/**
	 * Default HTTP header set for the module
	 *
	 * @var array
	 */
	public $http_headers_default = array();

	/**
	 * Module Processor Map [method access]
	 *
	 * @var array
	 */
	public $processor_map = array();

	/**
	 * Module's currently running subroutine
	 *
	 * @var array
	 */
	private $running_subroutine = array();

	/**
	 * Module Structural Map
	 *
	 * @var array
	 */
	public $structural_map = array();


	/**
	 * Constructor - Inits Handler
	 *
	 * @param    \Persephone\Core\Registry Registry object reference
	 */
	public function __construct ( \Persephone\Core\Registry $Registry )
	{
		//-----------
		// Prelim
		//-----------
		$this->Registry = $Registry;
		if ( !defined( "ACCESS_TO_AREA" ) )
		{
			define( "ACCESS_TO_AREA", "admin" );
		}

		//------------------
		// STRUCTURAL MAP
		//------------------

		$this->structural_map = array(
			'm_subroutines' => array(
				'configuration__system'           => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'configuration__system',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'configuration/system-(?P<config_group>[-a-z]+)',
					's_pathinfo_uri_schema_parsed' => 'configuration/system-(?P<config_group>[-a-z]+)',
					's_qstring_parameters'         => array(
						'config_group' => array(
							'request_regex' => '[-a-z]+',
							'_is_mandatory' => true,
						),
					),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(),
				),
				'content_and_data__index'         => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'management_index',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'content_and_data',
					's_pathinfo_uri_schema_parsed' => 'content_and_data',
					's_qstring_parameters'         => array(),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(),
				),
				'content_and_data__content'       => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'content_and_data__content',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'rdbms',
					's_pathinfo_uri_schema'        => 'content_and_data/content-(?P<m_unique_id_clean>[a-z0-9]{32})',
					's_pathinfo_uri_schema_parsed' => 'content_and_data/content-(?P<m_unique_id_clean>[a-z0-9]{32})',
					's_qstring_parameters'         => array(
						'm_unique_id_clean' => array(
							'request_regex' => '[a-z0-9]{32}',
							'_is_mandatory' => true,
						),
					),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(),
				),
				'content-and-data__media-library' => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'content_and_data__media_library',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'content-and-data/media-library',
					's_pathinfo_uri_schema_parsed' => 'content-and-data/media-library',
					's_qstring_parameters'         => array(),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(
						array( 'file' => "/jumploader.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
					),
				),
				'components_modules'              => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'components_modules',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'components/modules',
					's_pathinfo_uri_schema_parsed' => 'components/modules',
					's_qstring_parameters'         => array(),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(
						array( 'file' => "/jquery.tablesorter.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
						array( 'file' => "/jquery.tablesorter.pager.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
						array( 'file' => "/jquery.metadata.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
					),
				),
				'components_viewmodule'           => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'components_viewmodule',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'components/viewmodule-(?P<m_unique_id_clean>[a-z0-9]{32})',
					's_pathinfo_uri_schema_parsed' => 'components/viewmodule-(?P<m_unique_id_clean>[a-z0-9]{32})',
					's_qstring_parameters'         => array(
						'm_unique_id_clean' => array(
							'request_regex' => '[a-z0-9]{32}',
							'_is_mandatory' => true,
						),
					),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(
						array( 'file' => "/jquery.cookie.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
						array( 'file' => "/jquery.tablesorter.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
						array( 'file' => "/jquery.tablesorter.pager.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
						array( 'file' => "/jquery.metadata.js", 'params' => "", 'type' => "js", 'scope' => "global" ),
					),
				),
				'components_viewconnector'        => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'components_viewconnector',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'tpl',
					's_pathinfo_uri_schema'        => 'components/viewconnector-(?P<m_unique_id_clean>[a-z0-9]{32})-(?P<c_name>[a-z][a-z0-9_]+)',
					's_pathinfo_uri_schema_parsed' => 'components/viewconnector-(?P<m_unique_id_clean>[a-z0-9]{32})-(?P<c_name>[a-z][a-z0-9_]+)',
					's_qstring_parameters'         => array(
						'm_unique_id_clean' => array(
							'request_regex' => '[a-z0-9]{32}',
							'_is_mandatory' => true,
						),
						'c_name'            => array(
							'request_regex' => '[a-z][a-z0-9_]+',
							'_is_mandatory' => true,
						),
					),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(),
				),
				'test'                            => array(
					'm_unique_id'                  => "{4F54D297-5867BF9A-17873F81-1A94BEA6}",
					's_name'                       => 'test',
					's_data_source'                => 'rdbms',
					's_data_target'                => 'rdbms',
					's_pathinfo_uri_schema'        => 'test',
					's_pathinfo_uri_schema_parsed' => 'test',
					's_qstring_parameters'         => array(
						'm_unique_id_clean' => array(
							'request_regex' => '[a-z0-9]{32}',
							'_is_mandatory' => true,
						),
					),
					's_fetch_criteria'             => array(),
					's_data_definition'            => array(),
					's_additional_skin_assets'     => array(),
				),
			),
		);
	}


	/**
	 * content__do()
	 */
	public function content__do ( &$running_subroutine, $action )
	{
		$this->running_subroutine = $running_subroutine;

		$this->processor_map = array(
			'configuration__system'           => array(
				'get'    => "settings__do_show",
				'edit'   => "settings__do_edit",
				'revert' => "settings__do_revert",
			),
			'management_index'                => array(
				'get' => "management__do_prepare"
			),
			'management_content'              => array(
				'get'    => "content__do_fetch",
				'edit'   => "content__do_edit",
				'create' => "content__do_create",
				'delete' => "content__do_remove",
			),
			'content-and-data__media-library' => array(
				'get' => "management__media_library__do_list",
			),
			'components_modules'              => array(
				'get'    => "modules__do_list",
				'edit'   => "modules__do_edit",
				'create' => "modules__do_create__master_unit",
				'delete' => "modules__do_remove",
			),
			'components_viewmodule'           => array(
				'get'                                => "modules__do_view",
				'sr_alter__add'                      => "modules__subroutines__do_create",
				'sr_alter__drop'                     => "modules__subroutines__do_remove",
				'ddl_alter__add'                     => "modules__ddl__do_create",
				'ddl_alter__add__mimelist__do_fetch' => "modules__ddl__mimelist__do_fetch",
				'ddl_alter__pre_edit'                => "modules__ddl__do_edit__pre_processing",
				'ddl_alter__edit'                    => "modules__ddl__do_edit",
				'ddl_alter__sort'                    => "modules__ddl__do_sort",
				'ddl_alter__drop'                    => "modules__ddl__do_drop",
				'ddl_alter__restore_backup'          => "modules__ddl__do_restore_backup",
				'ddl_alter__purge_backup'            => "modules__ddl__do_drop_backup",
				'ddl_alter__set_title_column'        => "modules__ddl__do_set_title_column",
				'ddl_alter__link_to_connector_unit'  => "modules__do_create__connector_unit",
			),
			'components_viewconnector'        => array(
				'get'                                => "modules__connector_unit__do_view",
				'ddl_alter__add'                     => "modules__connector_unit__ddl__do_create",
				'ddl_alter__add__mimelist__do_fetch' => "modules__ddl__mimelist__do_fetch",
				'ddl_alter__sort'                    => "modules__connector_unit__ddl__do_sort",
				'ddl_alter__drop'                    => "modules__connector_unit__ddl__do_drop",
				'ddl_alter__restore_backup'          => "modules__connector_unit__ddl__do_restore_backup",
				'ddl_alter__purge_backup'            => "modules__connector_unit__ddl__do_drop_backup",
			),
			'test'                            => array(
				'get' => "modules__do_view",
			),
		);

		if ( isset( $this->processor_map[ $this->running_subroutine[ 's_name' ] ] )
		     and
		     isset( $this->processor_map[ $this->running_subroutine[ 's_name' ] ][ $action ] )
		)
		{
			$_methods = $this->processor_map[ $this->running_subroutine[ 's_name' ] ][ $action ];
			$_methods = explode( "|", $_methods );
			foreach ( $_methods as $_method_name )
			{
				/* Each method alters the content, and the subsequent method uses
				   that altered version (using references) and alters it at the end. */
				$this->content[ 'content' ] = $this->$_method_name();
			}

			return $this->content;
		}
		else
		{
			header( "HTTP/1.1 400 Bad Request" );
		}
	}


	private function content__do_fetch ()
	{
		//---------------------
		// Preliminary stuff
		//---------------------

		if ( !isset( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) or !preg_match( '#^[a-z0-9]{32}$#', $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) )
		{
			return false;
		}
		$m_unique_id = "{" . implode( "-", str_split( strtoupper( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ), 8 ) ) . "}";

		if ( !array_key_exists( $m_unique_id, $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			return false;
		}
		$m = $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $m_unique_id ];

		//-----------------------------
		// Prepare final fetch query
		//-----------------------------

		$_subqueries                   = array();
		$_fields_to_fetch              = array_merge( array( 'id', 'tags', 'timestamp', 'submitted_by', 'status_published', 'status_locked' ), array_keys( $m[ 'm_data_definition' ] ) );
		$this->Registry->Db->cur_query = $this->Registry->Db->sql->select()
			->from( $this->Registry->Db->attach_prefix( "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo" ) )
			->columns( $_fields_to_fetch );

		//---------------------------------------------------------
		// Execute final fetch query and retrieve requested data
		//---------------------------------------------------------

		try
		{
			$statement = $this->Registry->Db->sql->prepareStatementForSqlObject( $this->Registry->Db->cur_query );
			$result = $statement->execute();

			if ( $result instanceof \Zend\Db\Adapter\Driver\ResultInterface and $result->isQueryResult() )
			{
				$resultSet = new \Zend\Db\ResultSet\ResultSet;
				$resultSet->initialize( $result )->toArray();
				$m[ 'running_subroutine' ][ 'content' ][ 'count' ] = $resultSet->count();

				$this->Registry->Db->query_count++;
			}
			else
			{
				return false;
			}
		}
		catch ( \Persephone\Exception $e )
		{
			return false;
		}

		# No content in this page? Redirect to page 1 then...
		if ( !$m[ 'running_subroutine' ][ 'content' ][ 'count' ] )
		{
			return null;
		}

		$m[ 'running_subroutine' ][ 'content' ][ 'raw' ] = $result;

		//---------------------------------------------------------
		// Run fetched data through Data-Processors and parse it
		//---------------------------------------------------------

		// @todo : To be continued...

		return $m;
	}


	private function management__media_library__do_list ()
	{
		$_page_nr = 1;
		if ( isset( $_GET[ '_page' ][ $this->running_subroutine[ 's_name' ] ] ) and intval( $_GET[ '_page' ][ $this->running_subroutine[ 's_name' ] ] ) > 1 )
		{
			$_page_nr = intval( $_GET[ '_page' ][ $this->running_subroutine[ 's_name' ] ] );
		}

		$this->Registry->Db->cur_query = array(
			'do'         => "select",
			'fields'     => array(
				"f_id",
				"f_hash",
				"f_extension",
				"f_name",
				"f_size",
				"f_dimensions",
				"f_duration",
				"f_mime",
				"f_os_compat",
				"f_version",
				"f_architecture",
				"f_extra",
				"f_timestamp"
			),
			'table'      => array( "media_library" ),
			'order'      => array( "f_timestamp ASC" ),
			'limit_page' => array( $_page_nr, 20 ),
		);
		$result                        = $this->Registry->Db->simple_exec_query();

		foreach ( $result as &$_row )
		{
			$_row[ 'f_name' ] = explode( "\n", $_row[ 'f_name' ] );
		}

		$return[ 'media_library__file_list' ]         = $result;
		$return[ 'media_library__total_nr_of_items' ] = $this->Registry->Cache->cache__do_get( "total_nr_of_attachments" );

		return $return;
	}


	private function management__do_prepare ()
	{
		//-------------------------------
		// Is our cache content empty?
		//-------------------------------

		if ( !is_array( $this->Registry->Cache->cache[ 'modules' ] ) or !count( $this->Registry->Cache->cache[ 'modules' ] ) )
		{
			# Empty cache
			$return = null;
		}
		else
		{
			$return = $this->Registry->Cache->cache[ 'modules' ][ 'by_name' ];
		}

		return $return;
	}


	/**
	 * Fetches detailed information on a requested connector-enabled field of unit module
	 *
	 * @return   mixed     Array containing DDL-info on the requested field; FALSE on failure
	 */
	private function modules__connector_unit__do_view ()
	{
		$m_unique_id = "{" . implode( "-", str_split( strtoupper( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ), 8 ) ) . "}";

		if ( array_key_exists( $m_unique_id, $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			if ( isset( $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $m_unique_id ][ 'm_data_definition' ][ $this->running_subroutine[ 'request' ][ 'c_name' ] ] ) )
			{
				$return[ 'm_data__me' ]     = $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $m_unique_id ];
				$return[ 'm_data__others' ] = $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
				unset( $return[ 'm_data__others' ][ $m_unique_id ] ); // Removing 'me'-self from among 'others' :D
				$return[ '_request' ] = $this->running_subroutine[ 'request' ];
				$return[ 'c_data' ]   = $this->Registry->Cache->cache__do_get_part(
					"modules_connectors",
					$return[ 'm_data__me' ][ 'm_data_definition' ][ $this->running_subroutine[ 'request' ][ 'c_name' ] ][ 'connector_linked' ]
				);

				return $return;
			}

			return false;
		}
		else
		{
			# No such module? Redirect back to Components
			$this->Registry->http_redirect( SITE_URL . "/acp/components/viewmodule-" . $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] );
		}
	}


	/**
	 * Creates a new DDL element [a data-field] for a connector-unit.
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__connector_unit__ddl__do_create ()
	{
		$input                    = $this->Registry->Input->post();
		$faults                   = array();
		$_parent_module_cache     =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
		$_connector_modules_cache = $this->Registry->Cache->cache__do_get( "modules_connectors" );
		if ( array_key_exists( $input[ 'connector_linked' ], $_connector_modules_cache ) )
		{
			$m =& $_parent_module_cache[ $input[ 'm_unique_id' ] ];
			$c =& $_connector_modules_cache[ $input[ 'connector_linked' ] ];

			if ( !isset( $m[ 'm_data_definition' ][ $input[ 'connected_field' ] ] ) or $m[ 'm_data_definition' ][ $input[ 'connected_field' ] ][ 'connector_linked' ] != $input[ 'connector_linked' ] )
			{
				return array( array( 'faultCode' => 0, 'faultMessage' => "Fatal error! Inconsistency within the submitted data has been detected..." ) );
			}

			//--------------------------------------------------------------------
			// DDL Validation & Processing : Deploying Data_Processor instance
			//--------------------------------------------------------------------

			# TYPE: Validation...
			if ( ( $_processor_instance = $this->Registry->loader( "Data_Processors__" . ucwords( $input[ 'dft_type' ] ) ) ) === false )
			{
				$faults[ ] = array( 'faultCode' => 703, 'faultMessage' => "Invalid data-type: <em>" . $input[ 'dft_type' ] . "</em>!" );
			}
			if ( $_processor_instance->modules__ddl__do_validate( $input, $c ) !== true )
			{
				// __LANGUAGE__TRANSLATOR__ -> config-2-errorcode();
				return $_processor_instance->faults; // @todo Language support - code-to-meaning translation pending
			}

			# Continue...
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $input[ 'connected_field' ],
				'action'   => "add_column",
				'col_info' => $this->Registry->Db->modules__ddl_column_type_translation( $_processor_instance->ddl_config__validated, true )
			);
			if ( $this->Registry->Db->simple_exec_query() )
			{
				# INSERT - modules_data_definition
				$this->Registry->Db->cur_query = array(
					'do'    => "insert",
					'table' => "modules_data_definition",
					'set'   => $_processor_instance->ddl_config__validated,
				);
				if ( $this->Registry->Db->simple_exec_query() )
				{
					# On SUCCESS, update cache and respond
					$_recache = new \Persephone\Cache\Recache( $this->Registry );
					$_recache->main( "modules" );
					$_recache->main( "modules_connectors" );

					return array( 'responseCode' => 1, 'responseMessage' => "Success! Field-registry successfully added!<br />Refreshing..." );
				}
				else
				{
					return array( array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to create new Data-Definition record for reasons unknown!" ) );
				}
			}
			else
			{
				return array( array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to alter Data-Repo table for reasons unknown!" ) );
			}
		}
		else
		{
			return array( array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" ) );
		}
	}


	/**
	 * Re-orders DDL elements of a Connector-Unit
	 *
	 * @return   array      Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__connector_unit__ddl__do_sort ()
	{
		$_position_information = $this->Registry->Input->post( "position" );
		if ( is_null( $_position_information ) or !is_array( $_position_information ) or empty( $_position_information ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Empty data-set! Request aborted..." );
		}

		//-------------------------------------------
		// Determine m_unique_id of Connector-Unit
		//-------------------------------------------

		if ( isset( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) and !empty( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) )
		{
			$m_unique_id = "{" . implode( "-", str_split( strtoupper( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ), 8 ) ) . "}";
		}
		if ( !array_key_exists( $m_unique_id, $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid parent (master) module provided! Request aborted..." );
		}
		$_m_cache_node =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
		if ( !isset( $_m_cache_node[ $m_unique_id ][ 'm_data_definition' ][ $this->running_subroutine[ 'request' ][ 'c_name' ] ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid connector provided! Request aborted..." );
		}
		$_connector_unit__m_unique_id = $this->Registry->Cache->cache__do_get_part(
			"modules_connectors",
			$_m_cache_node[ $m_unique_id ][ 'm_data_definition' ][ $this->running_subroutine[ 'request' ][ 'c_name' ] ][ 'connector_linked' ] . ",m_unique_id"
		);

		//-------------
		// Continue
		//-------------

		$_rows_affected = 0;
		foreach ( $_position_information as $_position => $_name )
		{
			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => array( 'position' => $_position + 1 ),
				'where'  => array(
					"name=" . $this->Registry->Db->quote( $_name ),
					"m_unique_id=" . $this->Registry->Db->quote( $_connector_unit__m_unique_id ),
				),
			);
			$_rows_affected += $this->Registry->Db->simple_exec_query();
		}
		if ( $_rows_affected )
		{
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules_connectors" );
		}

		return array( 'responseCode' => 1, 'responseMessage' => "Re-order successful! Refreshing in 2 seconds..." );
	}


	/**
	 * Drops a Connector-DDL elements [a data-fields] [with on-demand-backup support].
	 *
	 * @param    string     Module Unique-ID
	 * @param    array      List of fields to drop
	 * @param    boolean    Whether to backup the field being dropped, or not
	 *
	 * @return   array      Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__connector_unit__ddl__do_drop ( $m_unique_id = "", $ddl_checklist = array(), $do_backup_dropped_field = true )
	{
		if ( strlen( $m_unique_id ) == 37 and count( $ddl_checklist ) )
		{
			# FOR INTERNAL USAGE + CODE REUSABILITY
			$input = array(
				'm_unique_id'             => $m_unique_id,
				'ddl_checklist'           => $ddl_checklist,
				'do_backup_dropped_field' => $do_backup_dropped_field,
			);
		}
		else
		{
			# Regular REQUEST
			$input = $this->Registry->Input->post();
		}

		$_parent_module_cache     =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
		$_connector_modules_cache = $this->Registry->Cache->cache__do_get( "modules_connectors" );
		if ( array_key_exists( $input[ 'connector_linked' ], $_connector_modules_cache ) )
		{
			$m =& $_parent_module_cache[ $input[ 'm_unique_id' ] ];
			$c =& $_connector_modules_cache[ $input[ 'connector_linked' ] ];

			if ( !isset( $m[ 'm_data_definition' ][ $input[ 'connected_field' ] ] ) or $m[ 'm_data_definition' ][ $input[ 'connected_field' ] ][ 'connector_linked' ] != $input[ 'connector_linked' ] )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Fatal error! Inconsistency within the submitted data has been detected..." );
			}

			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "Select something..." );
				}
			}

			if ( in_array( $input[ 'connected_field' ], $input[ 'ddl_checklist' ] ) )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Cannot delete Primary connector-field! Instead, you might wish to delete the whole connector-unit itself..." );
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_drop )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_drop, $c[ 'm_data_definition' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_drop . "</i>') exists withim DDL Repository!" );
				}

				# Is is 'used' by some subroutine
				if ( isset( $c[ 'm_data_definition' ][ $column_to_drop ][ 'used_in' ] ) and !empty( $c[ 'm_data_definition' ][ $column_to_drop ][ 'used_in' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "The selected data-field cannot be removed, as it's being used by one or more subroutines!" );
				}
			}

			//-------------------------
			// Modules Cache Cleanup
			//-------------------------

			$this->modules__do_cleanup( $m );

			//---------------------------------------
			// ALTER : Module _conn_repo -tables
			//---------------------------------------

			# Prepare necessary SQL statement parameters
			$list_of_columns_to_process__translated = array();
			$list_of_c_unique_ids_to_process        = array(); // Connector-Unit IDs, for future reference
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ] = $this->Registry->Db->modules__ddl_column_type_translation( $c[ 'm_data_definition' ][ $name ], true );

				# We add "__" prefix to field names which are being "backup'ed"
				if ( $input[ 'do_backup_dropped_field' ] )
				{
					$list_of_columns_to_process__translated[ $name ][ 'old_name' ] = $name;
					$list_of_columns_to_process__translated[ $name ][ 'name' ]     = "__" . $name; // Flagging the backup'ed column name with double-underscore prefix
				}
			}

			# Execute...
			if ( $input[ 'do_backup_dropped_field' ] )
			{
				$this->Registry->Db->cur_query = array(
					'do'       => "alter",
					'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $input[ 'connected_field' ],
					'action'   => "change_column",
					'col_info' => $list_of_columns_to_process__translated
				);
			}
			else
			{
				$this->Registry->Db->cur_query = array(
					'do'       => "alter",
					'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $input[ 'connected_field' ],
					'action'   => "drop_column",
					'col_info' => $list_of_columns_to_process__translated
				);
			}
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (ALTER-MODULE-DDL) failed!" );
			}
			unset( $list_of_columns_to_process__translated );

			//---------------------------------------------
			// UPDATE/DELETE : modules_data_definition
			//---------------------------------------------

			if ( $input[ 'do_backup_dropped_field' ] )
			{
				$this->Registry->Db->cur_query = array(
					'do'     => "update",
					'tables' => "modules_data_definition",
					'set'    => array( 'is_backup' => 1 ),
					'where'  => array(
						"m_unique_id=" . $this->Registry->Db->quote( $input[ 'connector_linked' ] ),
						"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
					),
				);
			}
			else
			{
				$this->Registry->Db->cur_query = array(
					'do'    => "delete",
					'table' => "modules_data_definition",
					'where' => array(
						"m_unique_id=" . $this->Registry->Db->quote( $input[ 'connector_linked' ] ),
						"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
					),
				);
			}
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-DDL-RECORD) failed!" );
			}

			//-----------------------------------------
			// SUCCESS : Still here :) Update cache
			//-----------------------------------------

			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );
			$_recache->main( "modules_connectors" );

			return array( 'responseCode' => 1, 'responseMessage' => "Success! Field-registry successfully dropped!<br />Refreshing..." );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Drops a DDL element [a data-field] of a connector-unit from DDL Backup Repo
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__connector_unit__ddl__do_drop_backup ()
	{
		$input                    = $this->Registry->Input->post();
		$_parent_module_cache     =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
		$_connector_modules_cache = $this->Registry->Cache->cache__do_get( "modules_connectors" );
		if ( array_key_exists( $input[ 'connector_linked' ], $_connector_modules_cache ) )
		{
			$m =& $_parent_module_cache[ $input[ 'm_unique_id' ] ];
			$c =& $_connector_modules_cache[ $input[ 'connector_linked' ] ];

			# Continue...
			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No field selected! Please select one..." );
				}
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_drop )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_drop, $c[ 'm_data_definition_bak' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_drop . "</i>') exists withim DDL Backup Repository!" );
				}
			}

			//---------------------------------------
			// ALTER : Module _master_repo -tables
			//---------------------------------------

			# Prepare necessary SQL statement parameters
			$list_of_columns_to_process__translated = array();
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ]           = $this->Registry->Db->modules__ddl_column_type_translation( $c[ 'm_data_definition_bak' ][ $name ], true );
				$list_of_columns_to_process__translated[ $name ][ 'name' ] = "__" . $name; // Backup fields have __ prefix
			}

			# Execute...
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $input[ 'connected_field' ],
				'action'   => "drop_column",
				'col_info' => $list_of_columns_to_process__translated
			);

			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (DROP-CONNECTOR-FIELD) failed!" );
			}
			unset( $list_of_columns_to_process__translated );

			//-------------------------------------
			// DELETE: modules_data_definition
			//-------------------------------------

			$this->Registry->Db->cur_query = array(
				'do'    => "delete",
				'table' => "modules_data_definition",
				'where' => array(
					"m_unique_id=" . $this->Registry->Db->quote( $input[ 'connector_linked' ] ),
					"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
				),
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (DELETE-DDL-RECORD) failed!" );
			}

			//--------------------------------------
			// SUCCESS : Update cache and respond
			//--------------------------------------

			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );
			$_recache->main( "modules_connectors" );

			return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully dropped!<br />Refreshing...' );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Restores DDL elements [data-fields] of a connector-unit from DDL Backup Repo
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__connector_unit__ddl__do_restore_backup ()
	{
		$input                    = $this->Registry->Input->post();
		$_parent_module_cache     =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
		$_connector_modules_cache = $this->Registry->Cache->cache__do_get( "modules_connectors" );
		if ( array_key_exists( $input[ 'connector_linked' ], $_connector_modules_cache ) )
		{
			$m =& $_parent_module_cache[ $input[ 'm_unique_id' ] ];
			$c =& $_connector_modules_cache[ $input[ 'connector_linked' ] ];

			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No field selected! Please select one..." );
				}
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_restore )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_restore, $c[ 'm_data_definition_bak' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_restore . "</i>') exists within DDL Backup Repository!" );
				}
			}

			//-------------------------------------
			// ALTER : Module _master_repo -tables
			//-------------------------------------

			$list_of_columns_to_process__translated = array();
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ]               = $this->Registry->Db->modules__ddl_column_type_translation( $c[ 'm_data_definition_bak' ][ $name ], true );
				$list_of_columns_to_process__translated[ $name ][ 'old_name' ] = "__" . $name;
				$list_of_columns_to_process__translated[ $name ][ 'name' ]     = $name;
			}

			# CHANGEs
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $input[ 'connected_field' ],
				'action'   => "change_column",
				'col_info' => $list_of_columns_to_process__translated
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (RESTORE-MODULE-FIELD) failed!" );
			}

			//------------------------------------
			// UPDATE :  module_data_definition
			//------------------------------------

			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => array( 'is_backup' => 0 ),
				'where'  => array(
					"m_unique_id=" . $this->Registry->Db->quote( $input[ 'connector_linked' ] ),
					"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
				),
			);

			if ( $this->Registry->Db->simple_exec_query() )
			{
				# On SUCCESS, update cache and respond
				$_recache = new \Persephone\Cache\Recache( $this->Registry );
				$_recache->main( "modules" );
				$_recache->main( "modules_connectors" );

				return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully restored!<br />Refreshing...' );
			}
			else
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Error! Database query (UPDATE-MODULE-RECORD) failed!" );
			}
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Runs diagnostics on provided module configuration information, determining whether it is good to be deployed
	 * [RECOMMENDED FOR INTERNAL USE ONLY!]
	 *
	 * @param    array      Module information
	 *
	 * @return   boolean    TRUE on success, FALSE otherwise
	 */
	private function modules__integrity_diagnostics__do_run ( &$m )
	{
		//---------------------------------------------------------
		// Connector-enabled DDL : Are we ALL connector_linked?
		//---------------------------------------------------------

		foreach ( $m[ 'm_data_definition' ] as $_f )
		{
			if ( $_f[ 'connector_enabled' ] and !$_f[ 'connector_linked' ] )
			{
				return false;
			}
		}

		//---------------------------------------------
		// Still here? Then the module is good to go
		//---------------------------------------------

		return true;
	}


	/**
	 * Cleans up Modules Cache, preparing it for INSERT/UPDATE into Modules-DB-Table
	 *
	 * @param   array   Module container
	 *
	 * @return  void
	 */
	private function modules__do_cleanup ( &$m )
	{
		//--------------
		// Actual DDL
		//--------------

		# DDL
		foreach ( $m[ 'm_data_definition' ] as $_field_name => &$_field_data )
		{
			if ( isset( $_field_data[ 'used_in' ] ) )
			{
				unset( $_field_data[ 'used_in' ] );
			}

			# Do the same for connector-DDL
			if ( isset( $_field_data[ 'c_data_definition' ] ) and count( $_field_data[ 'c_data_definition' ] ) )
			{
				foreach ( $_field_data[ 'c_data_definition' ] as $__field_name => &$__field_data )
				{
					if ( isset( $__field_data[ 'used_in' ] ) )
					{
						unset( $__field_data[ 'used_in' ] );
					}
				}
			}
		}

		//--------------
		// Backup DDL
		//--------------

		# DDL
		foreach ( $m[ 'm_data_definition_bak' ] as $_field_name => &$_field_data )
		{
			if ( isset( $_field_data[ 'used_in' ] ) )
			{
				unset( $_field_data[ 'used_in' ] );
			}

			# Do the same for connector-DDL
			if ( isset( $_field_data[ 'c_data_definition' ] ) and count( $_field_data[ 'c_data_definition' ] ) )
			{
				foreach ( $_field_data[ 'c_data_definition' ] as $__field_name => &$__field_data )
				{
					if ( isset( $__field_data[ 'used_in' ] ) )
					{
						unset( $__field_data[ 'used_in' ] );
					}
				}
			}
		}
	}


	/**
	 * Fetches the list of modules from Module Cache
	 *
	 * @return   mixed   Array of list of modules; NULL on failure
	 */
	private function modules__do_list ()
	{
		if ( !is_array( $this->Registry->Cache->cache[ 'modules' ] ) or !count( $this->Registry->Cache->cache[ 'modules' ] ) )
		{
			# Empty cache
			return null;
		}
		else
		{
			return $this->Registry->Cache->cache[ 'modules' ][ 'by_name' ];
		}
	}


	/**
	 * Creates a new Module (Main Unit)
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__do_create__master_unit ()
	{
		# Input
		$input  = $this->Registry->Input->post();
		$faults = array();
		$struct = array();

		# Clean-up
		$m_name = $this->Registry->Input->clean__makesafe_alphanumerical( $input[ 'm_name' ] );
		if ( empty( $m_name ) )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> is a required field!" );
		}
		if ( strlen( $m_name ) > 32 )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> cannot exceed 32-characters!" );
		}

		# Check availability of module name
		if ( !$this->modules__do_create__check_availability( $m_name ) )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> is not available!" );
		}

		# Continue...
		if ( empty( $input[ 'm_description' ] ) )
		{
			$faults[ ] = array( 'faultCode' => 702, 'faultMessage' => "<em>Module Description</em> is a required field!" );
		}

		# Any errors?
		if ( count( $faults ) )
		{
			return $faults;
		}

		# None... Good, go on.
		$m_description = $input[ 'm_description' ];
		$m_extras      = array();
		$m_data_target = $input[ 'm_data_target' ];

		if ( isset( $input[ 'm_extras' ] ) and count( $input[ 'm_extras' ] ) )
		{
			foreach ( $input[ 'm_extras' ] as $_extra )
			{
				if ( in_array( $_extra, array( "tags", "comments" ) ) )
				{
					$m_extras[ ] = $_extra;
				}
			}
		}

		# Calculate module Unique-Id
		$m_unique_id = "{" .
		               implode(
			               "-",
			               str_split(
				               strtoupper(
					               md5(
						               md5(
							               md5( md5( md5( $m_name ) . $this->Registry->config[ 'general' ][ 'admin_email' ] . UNIX_TIME_NOW ) . $this->Registry->config[ 'general' ][ 'admin_name' ] ) .
							               $this->Registry->config[ 'url' ][ 'hostname' ][ 'http' ][ 'full' ]
						               )
					               )
				               ),
				               8
			               )
		               ) . "}";

		# Insert
		$this->Registry->Db->cur_query = array(
			"do"    => "insert",
			"table" => "modules",
			"set"   => array(
				'm_name'           => ( $this->Registry->config[ 'modules' ][ 'm_names_strtolower' ] )
					? strtolower( $m_name )
					: $m_name,
				'm_unique_id'      => $m_unique_id,
				'm_description'    => $m_description,
				'm_type'           => "master",
				'm_enforce_ssl'    => $input[ 'm_enforce_ssl' ]
					? 1
					: 0,
				'm_extras'         => serialize( $m_extras ),
				'm_cache_array'    => "a:0:{}",
				'm_enable_caching' => $input[ 'm_enable_caching' ]
					? 1
					: 0,
				'm_is_enabled'     => 0
			)
		);

		if ( $this->Registry->Db->simple_exec_query() )
		{
			$_m_unique_id_clean                                                               = preg_replace( '#[^a-z0-9]#', "", strtolower( $m_unique_id ) );
			$struct[ 'tables' ][ 'mod_' . $_m_unique_id_clean . '_master_repo' ]              = $this->Registry->Db->modules__default_table_structure( "master_repo" );
			$struct[ 'tables' ][ 'mod_' . $_m_unique_id_clean . '_master_repo' ][ 'comment' ] = $m_description;

			# Features : COMMENTS
			if ( in_array( "comments", $m_extras ) )
			{
				// @todo
			}

			# CREATE TABLEs
			$this->Registry->Db->simple_exec_create_table_struct( $struct );

			# On SUCCESS, update cache and respond
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );

			return array( 'responseCode' => 1, 'responseMessage' => "Module successfully created!", 'responseAction' => "refresh" );
		}
		else
		{
			return array( array( 'faultCode' => 0, 'faultMessage' => "Unexpected problem occured! Possible DB-connection problems?!" ) );
		}
	}


	/**
	 * Creates a new Connector container (Connector Unit)
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__do_create__connector_unit ()
	{
		# Prelim
		$input  = $this->Registry->Input->post();
		$faults = array();
		$struct = array();

		//---------------
		// Continue...
		//---------------

		if ( !array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}

		if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
		{
			if ( !empty( $input[ 'ddl_checklist' ] ) )
			{
				$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
			}
			else
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Select something..." );
			}
		}

		$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

		# Calculate module Unique-Id
		foreach ( $input[ 'ddl_checklist' ] as $_f )
		{
			$m_unique_id = "{" . implode( "-", str_split( strtoupper( md5( $m[ 'm_unique_id_clean' ] . $_f ) ), 8 ) ) . "}";
			if ( isset( $m[ 'm_data_definition' ][ $_f ] ) )
			{
				$_connector_field =& $m[ 'm_data_definition' ][ $_f ];
				if ( !$_connector_field[ 'connector_enabled' ] )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "The selected field is not Connector-enabled!" );
				}
				if ( $_connector_field[ 'connector_linked' ] )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "The selected field has already been Connect'ed!" );
				}
			}
			else
			{
				return array( 'faultCode' => 0, 'faultMessage' => "No such field exists in our records!" );
			}

			//--------------------------------------------------------
			// INSERT Connector-Unit data and accompanying DDL info
			//--------------------------------------------------------

			$this->Registry->Db->cur_query = array(
				'do'    => "insert",
				'table' => "modules",
				'set'   => array(
					'm_unique_id'   => $m_unique_id,
					'm_type'        => "connector",
					'm_enforce_ssl' => null,
					'm_can_disable' => null,
					'm_can_remove'  => null,
					'm_is_enabled'  => null,
				),
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (INSERT-CONNECTOR-DATA) failed!" );
			}

			$this->Registry->Db->cur_query = array(
				'do'    => "insert",
				'table' => "modules_data_definition",
				'set'   => array(
					'm_unique_id'          => $m_unique_id,
					'name'                 => $_connector_field[ 'name' ],
					'label'                => $_connector_field[ 'label' ],
					'type'                 => $_connector_field[ 'type' ],
					'subtype'              => $_connector_field[ 'subtype' ],
					'maxlength'            => $_connector_field[ 'maxlength' ],
					'allowed_filetypes'    => $_connector_field[ 'allowed_filetypes' ],
					'connector_length_cap' => null, // for data-fields belonging to a Connector-unit, 'connector_length_cap' = NULL
					'input_regex'          => $_connector_field[ 'input_regex' ],
					'request_regex'        => $_connector_field[ 'request_regex' ],
					'default_options'      => serialize( $_connector_field[ 'default_options' ] ),
					'default_value'        => $_connector_field[ 'default_value' ],
					'connector_enabled'    => null, // for data-fields belonging to a Connector-unit, 'connector_enabled' = NULL
					'connector_linked'     => null, // for data-fields belonging to a Connector-unit, 'connector_linked' = NULL
					'is_html_allowed'      => $_connector_field[ 'is_html_allowed' ],
					'is_required'          => $_connector_field[ 'is_required' ],
					'is_unique'            => $_connector_field[ 'is_unique' ],
					'is_numeric'           => $_connector_field[ 'is_numeric' ],
					'is_backup'            => $_connector_field[ 'is_backup' ],
				),
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (INSERT-CONNECTOR-DDL) failed!" );
			}

			# Module Unique Id - Cleaned up
			$_parent_m_unique_id_clean = preg_replace( '#[^a-z0-9]#', "", strtolower( $input[ 'm_unique_id' ] ) );

			# _conn_repo table default structure
			$struct[ 'tables' ][ 'mod_' . $_parent_m_unique_id_clean . '_conn_repo__' . $_connector_field[ 'name' ] ] = $this->Registry->Db->modules__default_table_structure( "connector_repo" );

			# Attaching connector-enabled field's data-definition to _conn_repo table structire
			$struct[ 'tables' ][ 'mod_' . $_parent_m_unique_id_clean . '_conn_repo__' . $_connector_field[ 'name' ] ][ 'col_info' ][ $_connector_field[ 'name' ] ] =
				$this->Registry->Db->modules__ddl_column_type_translation( $_connector_field );

			# CREATE TABLEs
			$this->Registry->Db->simple_exec_create_table_struct( $struct );

			# Set connector_linked value
			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => array( 'connector_linked' => $m_unique_id ),
				'where'  => array(
					"name=" . $this->Registry->Db->quote( $_connector_field[ 'name' ] ),
					"m_unique_id=" . $this->Registry->Db->quote( $_connector_field[ 'm_unique_id' ] ),
				),
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-MODULE-DDL-CONNECTOR-LINKED) failed!" ) );
			}
		}

		# Update cache and respond
		$_recache = new \Persephone\Cache\Recache( $this->Registry );
		$_recache->main( "modules" );
		$_recache->main( "modules_connectors" );

		return array( 'responseCode' => 1, 'responseMessage' => "Connector-Unit successfully created!<br />Refreshing in 2 seconds...", 'responseAction' => "refresh" );
	}


	/**
	 * Checks whether requested module name is available or not
	 *
	 * @param    string    Module name
	 * @param    string    Module ID to exclude from this check
	 *
	 * @return   boolean   TRUE if module-name is available, FALSE otherwise
	 */
	private function modules__do_create__check_availability ( $m_name, $m_unique_id = null )
	{
		if ( array_key_exists( strtolower( $m_name ), $this->Registry->Cache->cache[ 'modules' ][ 'by_name' ] ) )
		{
			# Was module name changed at all? Maybe not... Continue in that case.
			if ( !is_null( $m_unique_id ) and $this->Registry->Cache->cache[ 'modules' ][ 'by_name' ][ $m_name ][ 'm_unique_id' ] == $m_unique_id )
			{
				return true;
			}

			# Otherwise, give error
			return false;
		}
		else
		{
			return true;
		}
	}


	/**
	 * Edits a module
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__do_edit ()
	{
		# Input
		$input  = $this->Registry->Input->post();
		$faults = array();

		# Is it a valid module?
		if ( !array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$faults[ ] = array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
		$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

		# Clean-up
		$m_name = $this->Registry->Input->clean__makesafe_alphanumerical( $input[ 'm_name' ] );
		if ( empty( $m_name ) )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> is a required field!" );
		}
		if ( strlen( $m_name ) > 32 )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> cannot exceed 32-characters!" );
		}

		# Check availability of module name
		if ( !$this->modules__do_create__check_availability( $m_name, $input[ 'm_unique_id' ] ) )
		{
			$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "<em>Module Name</em> is not available!" );
		}

		# Continue...
		if ( empty( $input[ 'm_description' ] ) )
		{
			$faults[ ] = array( 'faultCode' => 702, 'faultMessage' => "<em>Module Description</em> is a required field!" );
		}

		# Any errors?
		if ( count( $faults ) )
		{
			return $faults;
		}

		# None... Good, go on.
		$m_description = $input[ 'm_description' ];
		$m_extras_new  = array();

		$_list_of_added_extras   = array();
		$_list_of_removed_extras = $m[ 'm_extras' ];
		if ( isset( $input[ 'm_extras' ] ) and count( $input[ 'm_extras' ] ) )
		{
			foreach ( $input[ 'm_extras' ] as $_extra )
			{
				if ( in_array( $_extra, array( "tags", "comments" ) ) )
				{
					$m_extras_new[ ] = $_extra;
					if ( !in_array( $_extra, $_list_of_removed_extras ) )
					{
						$_list_of_added_extras[ ] = $_extra;
					}
					else
					{
						unset( $_list_of_removed_extras[ array_search( $_extra, $_list_of_removed_extras ) ] );
					}
				}
			}
		}

		# UPDATE
		$this->Registry->Db->cur_query = array(
			'do'     => "update",
			'tables' => "modules",
			'set'    => array(
				'm_name'           => ( $this->Registry->config[ 'modules' ][ 'm_names_strtolower' ] )
					? strtolower( $m_name )
					: $m_name,
				'm_description'    => $m_description,
				'm_enforce_ssl'    => $input[ 'm_enforce_ssl' ]
					? 1
					: 0,
				'm_extras'         => serialize( $m_extras_new ),
				'm_enable_caching' => $input[ 'm_enable_caching' ]
					? 1
					: 0,
				'm_is_enabled'     => 0
			),
			'where'  => "m_unique_id = " . $this->Registry->Db->quote( $m[ 'm_unique_id' ] )
		);

		if ( $this->Registry->Db->simple_exec_query() )
		{
			# Updating table COMMENT
			$this->Registry->Db->cur_query = array(
				'do'      => "alter",
				'table'   => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
				'action'  => "comment",
				'comment' => $m_description,
			);
			$this->Registry->Db->simple_exec_query();

			# Dropping tables of removed extras
			$tables = array();
			foreach ( $_list_of_removed_extras as $_extra )
			{
				if ( $_extra == 'tags' )
				{
					$tables[ ] = "mod_" . $m[ 'm_unique_id_clean' ] . "_tags";
				}
				if ( $_extra == 'comments' )
				{
					// @todo : Needs revising once Comments feature is implemented
					$tables[ ] = "mod_" . $m[ 'm_unique_id_clean' ] . "_comments";
				}
			}
			if ( count( $tables ) )
			{
				$this->Registry->Db->simple_exec_drop_table( $tables );
			}

			# Newly added features : TAGS
			$struct = array();
			if ( in_array( "tags", $_list_of_added_extras ) )
			{
				$struct[ 'tables' ][ 'mod_' . $m[ 'm_unique_id_clean' ] . '_tags' ] = $this->Registry->Db->modules__default_table_structure( "tags" );
			}

			# Features : COMMENTS
			if ( in_array( "comments", $_list_of_added_extras ) )
			{
				// @todo
			}

			# CREATE TABLEs
			if ( count( $struct ) )
			{
				$this->Registry->Db->simple_exec_create_table_struct( $struct );
			}

			# On SUCCESS, update cache and respond
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );

			return array(
				'responseCode'    => 1,
				'responseMessage' => "Module successfully modified! Please note that the module also has been disabled!<br />Refreshing...",
				'responseAction'  => "refresh"
			);
		}
		else
		{
			return array( array( 'faultCode' => 0, 'faultMessage' => "No changes were made!" ) );
		}
	}


	/**
	 * Removes a module
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__do_remove ()
	{
		//----------
		// Prelim
		//----------

		$input = $this->Registry->Input->post();

		//-----------
		// Cleanup
		//-----------

		if ( empty( $input[ 'm_unique_id' ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "No module was selected! Please select one..." );
		}
		if ( !array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
		$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

		//----------------------------------------------------------------------------------------------------
		// Let's determine the list of m_unique_id's and c_unique_id's, and the tables associated with them
		//----------------------------------------------------------------------------------------------------

		$_list_of_tables_to_drop        = array(
			"mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
		);
		$_list_of_unique_ids_to_process = array( $input[ 'm_unique_id' ] );
		$_data_definitions_merged       = array_merge( $m[ 'm_data_definition' ], $m[ 'm_data_definition_bak' ] );
		foreach ( $_data_definitions_merged as $_f )
		{
			if ( !empty( $_f[ 'connector_linked' ] ) )
			{
				$_list_of_tables_to_drop[ ]        = "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $_f[ 'name' ];
				$_list_of_unique_ids_to_process[ ] = $_f[ 'connector_linked' ];
			}
		}
		// Debug
		//print_r($_list_of_unique_ids_to_process); print_r($_list_of_tables_to_drop); exit;

		# Quoted $_list_of_unique_ids_to_process
		$_list_of_unique_ids_to_process = array_map( array( $this->Registry->Db->db, "quote" ), $_list_of_unique_ids_to_process );

		//---------------------------
		// Remove Module Record(s)
		//---------------------------

		$this->Registry->Db->cur_query = array(
			'do'    => "delete",
			'table' => "modules",
			'where' => "m_unique_id IN (" . implode( ",", $_list_of_unique_ids_to_process ) . ")",
		);
		$this->Registry->Db->simple_exec_query();

		//------------------------------
		// ... and all related tables
		//------------------------------

		if ( count( $_list_of_tables_to_drop ) )
		{
			$this->Registry->Db->simple_exec_drop_table( $_list_of_tables_to_drop );
		}

		//---------------------------------------------------------------------------
		// ... and all related data-definitions [incl. those of container-units'
		//---------------------------------------------------------------------------

		$this->Registry->Db->cur_query = array(
			'do'    => "delete",
			'table' => "modules_data_definition",
			'where' => "m_unique_id IN (" . implode( ",", $_list_of_unique_ids_to_process ) . ")",
		);
		$this->Registry->Db->simple_exec_query();

		//---------------------------
		// ... and all subroutines
		//---------------------------

		$this->Registry->Db->cur_query = array(
			'do'    => "delete",
			'table' => "modules_subroutines",
			'where' => array( "m_unique_id = " . $this->Registry->Db->quote( $m[ 'm_unique_id' ] ) ),
		);
		$this->Registry->Db->simple_exec_query();

		//--------------------
		// ... and all tags
		//--------------------

		$this->Registry->Db->cur_query = array(
			'do'    => "delete",
			'table' => "data_repos__tags",
			'where' => "m_unique_id IN (" . implode( ",", $_list_of_unique_ids_to_process ) . ")",
		);
		$this->Registry->Db->simple_exec_query();

		//------------
		// Return
		//------------

		# Update cache
		$_recache = new \Persephone\Cache\Recache( $this->Registry );
		$_recache->main( "modules" );
		$_recache->main( "modules_connectors" );

		return array( 'responseCode' => 1, 'responseMessage' => "Module (and its subroutines) successfully removed! Skin templates are still remaining in system!", 'responseAction' => "refresh" );
	}


	/**
	 * Fetches detailed information on a requested module
	 *
	 * @return   mixed     Array containing info re the requested module; FALSE on failure
	 */
	private function modules__do_view ( $m_unique_id = "" )
	{
		# 'm_unique_id'
		if ( empty( $m_unique_id ) and  isset( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) and !empty( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ) )
		{
			$m_unique_id = "{" . implode( "-", str_split( strtoupper( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ), 8 ) ) . "}";
		}

		# Still empty?
		if ( empty( $m_unique_id ) )
		{
			return false;
		}

		if ( array_key_exists( $m_unique_id, $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$return[ 'me' ]     = $this->Registry->Modules->modules__do_load( $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $m_unique_id ] );
			$return[ 'others' ] = $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ];
			unset( $return[ 'others' ][ $m_unique_id ] ); // Removing 'me'-self from among 'others' :D
		}
		else
		{
			# No such module? Redirect back to Components
			$this->Registry->http_redirect( SITE_URL . "/acp/components" );
		}

		return $return;
	}


	/**
	 * Creates a new DDL-record [module data-fields].
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_create ()
	{
		$input  = $this->Registry->Input->post();
		$faults = array();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			//-------------------------
			// Modules Cache Cleanup
			//-------------------------

			$this->modules__do_cleanup( $m );

			//-----------------------------------------------
			// DDL Validation & Processing : Primary data
			//-----------------------------------------------

			$_list_of_reserved_names = array( "id", "tags", "timestamp", "submitted_by", "status_published", "status_locked" );
			$ddl_config__validated   = array(
				'name'        => $this->Registry->config[ 'modules' ][ 'm_names_strtolower' ]
					? strtolower( $input[ 'name' ] )
					: $input[ 'name' ],
				'label'       => $input[ 'label' ],
				'is_required' => $input[ 'is_required' ]
					? 1
					: 0,
				'position'    => count( $m[ 'm_data_definition' ] ) + 1,
			);

			# NAME: Validation...
			if ( empty( $ddl_config__validated[ 'name' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_REQUIRED" );
				// "<em>Field Name</em> is a required field!"
			}
			elseif ( array_key_exists( $input[ 'name' ], $m[ 'm_data_definition' ] ) or array_key_exists( $input[ 'name' ], $m[ 'm_data_definition_bak' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__NOT_AVAILABLE" );
				// "<em>Field Name</em> not available; either already registered, or exists in backups!"
			}
			elseif ( in_array( $ddl_config__validated[ 'name' ], $_list_of_reserved_names ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_A_RESERVED_KEYWORD" );
				// "<em>Field Name</em> cannot have one of the following reserved values - change your entry:<br />&nbsp;&nbsp;&nbsp;<em>" . implode( ", " , $_list_of_reserved_names ) .  "</em>"
			}
			elseif ( !preg_match( "#^[a-z][a-z0-9_]+$#", $ddl_config__validated[ 'name' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_INVALID" );
				// "<em>Field Name</em> must contain only a lowercase alphanumeric and underscore characters, and must not start with any numerical!"
			}

			# LABEL: Validation...
			if ( empty( $ddl_config__validated[ 'label' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 702, 'faultMessage' => "LABEL__IS_REQUIRED" );
				// "<em>Field Label</em> is a required field!"
			}

			//--------------------------------------------------------------------
			// DDL Validation & Processing : Deploying Data_Processor instance
			//--------------------------------------------------------------------

			# TYPE: Validation...
			if ( ( $_processor_instance = $this->Registry->loader( "Data_Processors__" . ucwords( $input[ 'type' ] ) ) ) === false )
			{
				$faults[ ] = array( 'faultCode' => 703, 'faultMessage' => "Invalid data-type: <em>" . $input[ 'type' ] . "</em>!" );
			}
			$_processor_instance->modules__ddl__do_validate( $input, $m, $ddl_config__validated, $faults );

			# Any "faults"?
			if ( count( $faults ) )
			{
				return $faults;
			}

			# Continue...
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
				'action'   => "add_column",
				'col_info' => $this->Registry->Db->modules__ddl_column_type_translation( $ddl_config__validated, true )
			);

			if ( $this->Registry->Db->simple_exec_query() )
			{
				# INSERT - modules_data_definition
				$this->Registry->Db->cur_query = array(
					'do'    => "insert",
					'table' => "modules_data_definition",
					'set'   => $ddl_config__validated,
				);
				if ( $this->Registry->Db->simple_exec_query() )
				{
					# On SUCCESS, update cache and respond
					$_recache = new \Persephone\Cache\Recache( $this->Registry );
					$_recache->main( "modules" );

					return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully added!<br />Refreshing...', 'responseAction' => 'refresh' );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to create new Data-Definition record for reasons unknown!" );
				}
			}
			else
			{
				return array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to alter Data-Repo table for reasons unknown!" );
			}
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Populates DDL information into an array which in return is used to populate DDL-Edit form in ACP front-end.
	 *
	 * @return   mixed   Array containing DDL-information if such actual DDL exists; NULL otherwise
	 */
	private function modules__ddl__do_edit__pre_processing ()
	{
		if ( !is_null( $this->Registry->Input->post( "m_unique_id" ) ) and $m = $this->modules__do_view( $this->Registry->Input->post( "m_unique_id" ) ) )
		{
			if ( $m[ 'me' ][ 'm_data_definition' ][ $this->Registry->Input->post( "ddl_checklist" ) ][ 'is_backup' ] )
			{
				return null;
			}
			$_field =& $m[ 'me' ][ 'm_data_definition' ][ $this->Registry->Input->post( "ddl_checklist" ) ]; // Just for convenience...
			$return = array(
				'name'                          => $_field[ 'name' ],
				'label'                         => $_field[ 'label' ],
				'type'                          => $_field[ 'type' ],
				'subtype'                       => $_field[ 'subtype' ],
				'links_with'                    => $_field[ 'e_data_definition' ][ 'm_unique_id' ],
				'links_with__e_data_definition' => $_field[ 'e_data_definition' ][ 'm_data_definition' ],
				'allowed_filetypes'             => $_field[ 'allowed_filetypes' ],
				'maxlength'                     => $_field[ 'maxlength' ],
				'default_value'                 => $_field[ 'default_value' ],
				'connector_enabled'             => $_field[ 'connector_enabled' ],
				'connector_length_cap'          => $_field[ 'connector_length_cap' ],
				'is_html_allowed'               => $_field[ 'is_html_allowed' ],
				'is_required'                   => $_field[ 'is_required' ],
				'is_unique'                     => $_field[ 'is_unique' ],
			);
			while ( !empty( $_field[ 'default_options' ] ) and list( $key, $value ) = each( $_field[ 'default_options' ] ) )
			{
				$return[ 'default_options' ] .= $key . "=" . $value . "\n";
			}
			unset( $_field, $m );

			return $return;
		}

		return null;
	}


	/**
	 * Alters a DDL-record [module data-field].
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_edit ()
	{
		$input  = $this->Registry->Input->post();
		$faults = array();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			//-------------------------
			// Modules Cache Cleanup
			//-------------------------

			$this->modules__do_cleanup( $m );

			//-----------------------------------------------
			// DDL Validation & Processing : Primary data
			//-----------------------------------------------

			$_list_of_reserved_names = array( "id", "tags", "timestamp", "submitted_by", "status_published", "status_locked" );
			$ddl_config__validated   = array(
				'name'  => $this->Registry->config[ 'modules' ][ 'm_names_strtolower' ]
					? strtolower( $input[ 'name__old' ] )
					: $input[ 'name__old' ],
				'label' => $input[ 'label' ],
			);

			# NAME: Validation...
			if ( empty( $ddl_config__validated[ 'name' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_REQUIRED" );
				// "<em>Field Name</em> is a required field!"
			}
			elseif ( in_array( $ddl_config__validated[ 'name' ], $_list_of_reserved_names ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_A_RESERVED_KEYWORD" );
				// "<em>Field Name</em> cannot have one of the following reserved values - change your entry:<br />&nbsp;&nbsp;&nbsp;<em>" . implode( ", " , $_list_of_reserved_names ) .  "</em>"
			}
			elseif ( !preg_match( "#^[a-z][a-z0-9_]+$#", $ddl_config__validated[ 'name' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__IS_INVALID" );
				// "<em>Field Name</em> must contain only a lowercase alphanumeric and underscore characters, and must not start with any numerical!"
			}
			elseif ( !array_key_exists( $ddl_config__validated[ 'name' ], $m[ 'm_data_definition' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 701, 'faultMessage' => "NAME__NOT_FOUND" );
				// "No such <em>Field Name</em> exists!"
			}

			# LABEL: Validation...
			if ( empty( $ddl_config__validated[ 'label' ] ) )
			{
				$faults[ ] = array( 'faultCode' => 702, 'faultMessage' => "LABEL__IS_REQUIRED" );
				// "<em>Field Label</em> is a required field!"
			}

			# Any "faults"?
			if ( count( $faults ) )
			{
				return $faults;
			}

			//--------------------------------------------------------------------
			// DDL Validation & Processing : Deploying Data_Processor instance
			//--------------------------------------------------------------------

			# TYPE: Validation...
			if ( ( $_processor_instance = $this->Registry->loader( "Data_Processors__" . ucwords( $input[ 'type' ] ) ) ) === false )
			{
				$faults[ ] = array( 'faultCode' => 703, 'faultMessage' => "Invalid data-type: <em>" . $input[ 'type' ] . "</em>!" );
			}
			$_processor_instance->modules__ddl__do_validate( $input, $m, $ddl_config__validated, $faults );

			//-----------------------------------------------------
			// Make sure protected properties are not altered!
			//-----------------------------------------------------

			$_list_of_protected_properties = array( "name", "type", "subtype", "links_with", "default_options", "maxlength", "connector_enabled" );
			foreach ( $_list_of_protected_properties as $key )
			{
				if ( !isset( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ $key ] ) or !isset( $ddl_config__validated[ $key ] ) )
				{
					continue;
				}
				if ( is_array( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ $key ] ) ) // Arrays are serialized, we need unserialize them to compare
				{
					if ( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ $key ] != unserialize( $ddl_config__validated[ $key ] ) )
					{
						$faults[ ] = array( 'faultCode' => 0, 'faultMessage' => "DDL__INCONSISTENCY_DETECTED__PROTECTED_PROPERTIES" ); // "Fatal error occured during DDL editing!"
						break;
					}
				}
				else
				{
					if ( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ $key ] != $ddl_config__validated[ $key ] )
					{
						$faults[ ] = array( 'faultCode' => 0, 'faultMessage' => "DDL__INCONSISTENCY_DETECTED__PROTECTED_PROPERTIES" ); // "Fatal error occured during DDL editing!"
						break;
					}
				}
			}

			# Any "faults"?
			if ( count( $faults ) )
			{
				return $faults;
			}

			//-----------------------------------------
			// Database : ALTER *_master_repo table
			//-----------------------------------------

			$ddl_config__validated__translated               = $this->Registry->Db->modules__ddl_column_type_translation( $ddl_config__validated, true );
			$ddl_config__validated__translated[ 'old_name' ] = $ddl_config__validated__translated[ 'name' ]; // For SQL 'ALTER CHANGE' operation

			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
				'action'   => "change_column",
				'col_info' => array( $ddl_config__validated__translated[ 'name' ] => $ddl_config__validated__translated )
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to alter Master-Repo table for reasons unknown!" );
			}

			//-----------------------------------------
			// Database : ALTER *_conn_repo__* table
			//-----------------------------------------

			if (
				$m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ 'connector_enabled' ] and !empty( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ 'connector_linked' ] )
			)
			{
				$ddl_config__validated__translated_for_conn               = $this->Registry->Db->modules__ddl_column_type_translation( $ddl_config__validated );
				$ddl_config__validated__translated_for_conn[ 'old_name' ] = $ddl_config__validated__translated_for_conn[ 'name' ]; // For SQL 'ALTER CHANGE' operation

				$this->Registry->Db->cur_query = array(
					'do'       => "alter",
					'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . strtolower( $ddl_config__validated[ 'name' ] ),
					'action'   => "change_column",
					'col_info' => array( $ddl_config__validated__translated_for_conn[ 'name' ] => $ddl_config__validated__translated_for_conn )
				);
				if ( !$this->Registry->Db->simple_exec_query() )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "DB-handler Failed to alter Conn-Repo table for reasons unknown!" );
				}

				# Update DDL-record of Title-field for corresponding connector unit
				$this->Registry->Db->cur_query = array(
					'do'     => "update",
					'tables' => "modules_data_definition",
					'set'    => array_merge( // Updating ...
						$ddl_config__validated,
						array(
						     'm_unique_id'          => $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ 'connector_linked' ],
						     // ... 'm_unique_id' with that of Connector-unit
						     'connector_enabled'    => null,
						     // for data-fields belonging to a Connector-unit, 'connector_enabled' = NULL
						     'connector_linked'     => null,
						     // for data-fields belonging to a Connector-unit, 'connector_linked' = NULL
						     'connector_length_cap' => null,
						     // for data-fields belonging to a Connector-unit, 'connector_length_cap' = NULL
						)
					),
					'where'  => array(
						"m_unique_id=" . $this->Registry->Db->quote( $m[ 'm_data_definition' ][ $ddl_config__validated[ 'name' ] ][ 'connector_linked' ] ),
						"name=" . $this->Registry->Db->quote( $ddl_config__validated[ 'name' ] ),
					),
				);
				$this->Registry->Db->simple_exec_query();

				$_recache = new \Persephone\Cache\Recache( $this->Registry );
				$_recache->main( "modules_connectors" );
			}

			//------------------------------------------------
			// Database : INSERT - modules_data_definition
			//------------------------------------------------

			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => $ddl_config__validated,
				'where'  => array(
					"m_unique_id=" . $this->Registry->Db->quote( $ddl_config__validated[ 'm_unique_id' ] ),
					"name=" . $this->Registry->Db->quote( $ddl_config__validated[ 'name' ] ),
				),
			);
			$this->Registry->Db->simple_exec_query();

			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );

			return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully altered!<br />Refreshing...', 'responseAction' => 'refresh' );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Fetches MIME list (lite weight, without signature information)
	 *
	 * @return   array   MIME list
	 */
	private function modules__ddl__mimelist__do_fetch ()
	{
		# Fetch MIMELIST cache
		$_mimelist_cache = $this->Registry->Cache->cache__do_get( "mimelist" );

		# Fetch what we need
		$_mimelist_cache__subset = ( !is_null( $this->Registry->Input->request( "mimetype" ) ) and in_array(
			$this->Registry->Input->request( "mimetype" ),
			array(
			     "image",
			     "audio",
			     "video"
			)
		) )
			? array_values( $_mimelist_cache[ 'by_type' ][ $this->Registry->Input->request( "mimetype" ) ] )
			: array_values( $_mimelist_cache[ 'by_ext' ] );

		# ... and parse it
		$_return = array();
		foreach ( $_mimelist_cache__subset as $_data )
		{
			$_return[ $_data[ 'type_extension' ] ] = $_data[ 'type_description' ];
		}

		return $_return;
	}


	/**
	 * Re-orders DDL elements
	 *
	 * @return   array      Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_sort ()
	{
		$m_unique_id = "{" . implode( "-", str_split( strtoupper( $this->running_subroutine[ 'request' ][ 'm_unique_id_clean' ] ), 8 ) ) . "}";

		$_position_information = $this->Registry->Input->post( "position" );
		/**
		 * If we don't have position-information, we guess it, based on DDL-information, since DDL-info is fetched with sorting.
		 *
		 * @see Cache__Recache->modules__do_recache()
		 */
		if ( is_null( $_position_information ) or !is_array( $_position_information ) or empty( $_position_information ) )
		{
			$_position_information = array();
			$_i                    = 0;
			foreach ( $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $m_unique_id ][ 'm_data_definition' ] as $_field_info )
			{
				$_position_information[ $_i ] = $_field_info[ 'name' ];
				$_i++;
			}
		}

		$_rows_affected = 0;
		foreach ( $_position_information as $_position => $_name )
		{
			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => array( 'position' => $_position + 1 ),
				'where'  => array(
					"name=" . $this->Registry->Db->quote( $_name ),
					"m_unique_id=" . $this->Registry->Db->quote( $m_unique_id ),
				),
			);
			$_rows_affected += $this->Registry->Db->simple_exec_query();
		}
		if ( $_rows_affected )
		{
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );
		}

		return array( 'responseCode' => 1, 'responseMessage' => "Re-order successful! Refreshing in 2 seconds..." );
	}


	/**
	 * Drops DDL elements [module data-fields] [with on-demand-backup support].
	 *
	 * @param    string     Module Unique-ID
	 * @param    array      List of fields to drop
	 * @param    boolean    Whether to backup the field being dropped, or not
	 *
	 * @return   array      Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_drop ( $m_unique_id = "", $ddl_checklist = array(), $do_backup_dropped_field = true )
	{
		if ( strlen( $m_unique_id ) == 37 and count( $ddl_checklist ) )
		{
			# FOR INTERNAL USAGE + CODE REUSABILITY
			$input = array(
				'm_unique_id'             => $m_unique_id,
				'ddl_checklist'           => $ddl_checklist,
				'do_backup_dropped_field' => $do_backup_dropped_field,
			);
		}
		else
		{
			# Regular REQUEST
			$input = $this->Registry->Input->post();
		}

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			# Continue...
			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "Select something..." );
				}
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_drop )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_drop, $m[ 'm_data_definition' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_drop . "</i>') exists withim DDL Repository!" );
				}

				# Is is 'used' by some subroutine
				if ( isset( $m[ 'm_data_definition' ][ $column_to_drop ][ 'used_in' ] ) and !empty( $m[ 'm_data_definition' ][ $column_to_drop ][ 'used_in' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "The selected data-field cannot be removed, as it's being used by one or more subroutines!" );
				}
			}

			# Little cleanup - Cache-wise
			$this->modules__do_cleanup( $m );

			//---------------------------------------
			// ALTER : Module _master_repo -tables
			//---------------------------------------

			# Prepare necessary SQL statement parameters
			$list_of_columns_to_process__translated = array();
			$list_of_c_unique_ids_to_process        = array(); // Connector-Unit IDs, for future reference
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ] = $this->Registry->Db->modules__ddl_column_type_translation( $m[ 'm_data_definition' ][ $name ], true );

				if ( $m[ 'm_data_definition' ][ $name ][ 'connector_enabled' ] and !empty( $m[ 'm_data_definition' ][ $name ][ 'connector_linked' ] ) )
				{
					$list_of_c_unique_ids_to_process[ $name ] =& $m[ 'm_data_definition' ][ $name ][ 'connector_linked' ];
				}

				# We add "__" prefix to field names which are being "backup'ed"
				if ( $input[ 'do_backup_dropped_field' ] )
				{
					$list_of_columns_to_process__translated[ $name ][ 'old_name' ] = $name;
					$list_of_columns_to_process__translated[ $name ][ 'name' ]     = "__" . $name; // Flagging the backup'ed column name with double-underscore prefix
				}
			}

			# Execute...
			if ( $input[ 'do_backup_dropped_field' ] )
			{
				$this->Registry->Db->cur_query = array(
					'do'       => "alter",
					'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
					'action'   => "change_column",
					'col_info' => $list_of_columns_to_process__translated
				);
			}
			else
			{
				$this->Registry->Db->cur_query = array(
					'do'       => "alter",
					'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
					'action'   => "drop_column",
					'col_info' => $list_of_columns_to_process__translated
				);
			}
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (ALTER-MODULE-DDL) failed!" );
			}
			unset( $list_of_columns_to_process__translated );

			//---------------------------------------------
			// UPDATE/DELETE : modules_data_definition
			//---------------------------------------------

			if ( $input[ 'do_backup_dropped_field' ] )
			{
				$this->Registry->Db->cur_query = array(
					'do'     => "update",
					'tables' => "modules_data_definition",
					'set'    => array( 'is_backup' => 1 ),
					'where'  => array(
						"m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ),
						"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
					),
				);
			}
			else
			{
				if ( count( $list_of_c_unique_ids_to_process ) )
				{
					$_where_clause = "m_unique_id IN (" . implode(
						",",
						array_map(
							array(
							     $this->Registry->Db->db,
							     "quote"
							),
							$list_of_c_unique_ids_to_process
						)
					) . ")" . " OR " . "(" . "m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ) . " AND " . "name IN (" . implode(
						                 ",",
						                 array_map(
							                 array(
							                      $this->Registry->Db->db,
							                      "quote"
							                 ),
							                 $list_of_columns_to_process
						                 )
					                 ) . ")" . ")";
				}
				else
				{
					$_where_clause = array(
						"m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ),
						"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
					);
				}
				$this->Registry->Db->cur_query = array(
					'do'    => "delete",
					'table' => "modules_data_definition",
					'where' => $_where_clause,
				);
			}
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-DDL-RECORD) failed!" );
			}

			//----------------------------------------------
			// DELETE : Connector-Unit record and table
			//----------------------------------------------

			if ( !$input[ 'do_backup_dropped_field' ] and count( $list_of_c_unique_ids_to_process ) )
			{
				# Connector-Unit data removal
				$this->Registry->Db->cur_query = array(
					'do'    => "delete",
					'table' => "modules",
					'where' => array(
						"m_unique_id IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_c_unique_ids_to_process ) ) . ")",
					),
				);
				$this->Registry->Db->simple_exec_query();

				# Connector-Unit table(s) removal
				$_list_of_tables_to_drop = array(); // List of Connector-Unit tables to drop
				while ( list( $_field_name, $_c_unique_id ) = each( $list_of_c_unique_ids_to_process ) )
				{
					$_list_of_tables_to_drop[ ] = "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $_field_name;
				}
				if ( count( $_list_of_tables_to_drop ) )
				{
					if ( !$this->Registry->Db->simple_exec_drop_table( $_list_of_tables_to_drop ) )
					{
						return array( 'faultCode' => 0, 'faultMessage' => "Database query (DROP-CONNECTOR-TABLES) failed!" );
					}
				}
				unset( $_list_of_tables_to_drop );
			}

			//-------------------------------------------------------------------------
			// UPDATE : modules [in case current 'm_title_column' is being deleted
			//-------------------------------------------------------------------------

			if ( in_array( $m[ 'm_title_column' ], $list_of_columns_to_process ) )
			{
				$this->Registry->Db->cur_query = array(
					'do'     => "update",
					'tables' => "modules",
					'set'    => array( 'm_title_column' => null ),
					'where'  => "m_unique_id = " . $this->Registry->Db->quote( $input[ 'm_unique_id' ] )
				);
				if ( !$this->Registry->Db->simple_exec_query() )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-MODULES-TITLE-FIELD-RECORD) failed!" );
				}
			}

			//------------------------------------------
			// SUCCESS : Update cache and reorder DDL
			//------------------------------------------

			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );
			$_recache->main( "modules_connectors" );
			$this->modules__ddl__do_sort();

			return array( 'responseCode' => 1, 'responseMessage' => "Success! Field-registry successfully dropped!<br />Refreshing...", "responseAction" => "refresh" );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Drops DDL elements [module data-fields] from DDL Backup Repo
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_drop_backup ()
	{
		$input = $this->Registry->Input->post();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			# Little cleanup - Cache-wise
			$this->modules__do_cleanup( $m );

			# Continue...
			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No field selected! Please select one..." );
				}
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_drop )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_drop, $m[ 'm_data_definition_bak' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_drop . "</i>') exists withim DDL Backup Repository!" );
				}
			}

			//---------------------------------------
			// ALTER : Module _master_repo -tables
			//---------------------------------------

			# Prepare necessary SQL statement parameters
			$list_of_columns_to_process__translated = array();
			$list_of_c_unique_ids_to_process        = array(); // Connector-Unit IDs, for future reference
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ]           = $this->Registry->Db->modules__ddl_column_type_translation( $m[ 'm_data_definition_bak' ][ $name ], true );
				$list_of_columns_to_process__translated[ $name ][ 'name' ] = "__" . $name; // Backup fields have __ prefix

				if ( $m[ 'm_data_definition_bak' ][ $name ][ 'connector_enabled' ] and !empty( $m[ 'm_data_definition_bak' ][ $name ][ 'connector_linked' ] ) )
				{
					$list_of_c_unique_ids_to_process[ $name ] =& $m[ 'm_data_definition_bak' ][ $name ][ 'connector_linked' ];
				}
			}

			# Execute...
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
				'action'   => "drop_column",
				'col_info' => $list_of_columns_to_process__translated
			);

			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (DROP-MODULE-FIELD) failed!" );
			}
			unset( $list_of_columns_to_process__translated );

			//-------------------------------------
			// DELETE: modules_data_definition
			//-------------------------------------

			if ( count( $list_of_c_unique_ids_to_process ) )
			{
				$_where_clause = "m_unique_id IN (" . implode(
					",",
					array_map(
						array(
						     $this->Registry->Db->db,
						     "quote"
						),
						$list_of_c_unique_ids_to_process
					)
				) . ")" . " OR " . "(" . "m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ) . " AND " . "name IN (" . implode(
					                 ",",
					                 array_map(
						                 array(
						                      $this->Registry->Db->db,
						                      "quote"
						                 ),
						                 $list_of_columns_to_process
					                 )
				                 ) . ")" . ")";
			}
			else
			{
				$_where_clause = array(
					"m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ),
					"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
				);
			}
			$this->Registry->Db->cur_query = array(
				'do'    => "delete",
				'table' => "modules_data_definition",
				'where' => $_where_clause,
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-DDL-RECORD) failed!" );
			}

			//--------------------------------------------
			// DELETE : Connector-Unit record and table
			//--------------------------------------------

			if ( count( $list_of_c_unique_ids_to_process ) )
			{
				# Connector-Unit data removal
				$this->Registry->Db->cur_query = array(
					'do'    => "delete",
					'table' => "modules",
					'where' => array(
						"m_unique_id IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_c_unique_ids_to_process ) ) . ")",
					),
				);
				$this->Registry->Db->simple_exec_query();

				# Connector-Unit table(s) removal
				$_list_of_tables_to_drop = array(); // List of Connector-Unit tables to drop
				while ( list( $_field_name, $_c_unique_id ) = each( $list_of_c_unique_ids_to_process ) )
				{
					$_list_of_tables_to_drop[ ] = "mod_" . $m[ 'm_unique_id_clean' ] . "_conn_repo__" . $_field_name;
				}
				if ( count( $_list_of_tables_to_drop ) )
				{
					if ( !$this->Registry->Db->simple_exec_drop_table( $_list_of_tables_to_drop ) )
					{
						return array( 'faultCode' => 0, 'faultMessage' => "Database query (DROP-CONNECTOR-TABLES) failed!" );
					}
				}
				unset( $_list_of_tables_to_drop );
			}

			//--------------------------------------
			// SUCCESS : Update cache and respond
			//--------------------------------------

			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );
			$_recache->main( "modules_connectors" );

			return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully dropped!<br />Refreshing...', 'responseAction' => "refresh" );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Restores DDL elements [module data-fields] from DDL Backup Repo
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_restore_backup ()
	{
		$input = $this->Registry->Input->post();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			# Little cleanup - Cache-wise
			$this->modules__do_cleanup( $m );

			# Continue...
			if ( !isset( $input[ 'ddl_checklist' ] ) or !is_array( $input[ 'ddl_checklist' ] ) or !count( $input[ 'ddl_checklist' ] ) )
			{
				if ( !empty( $input[ 'ddl_checklist' ] ) )
				{
					$input[ 'ddl_checklist' ] = array( $input[ 'ddl_checklist' ] );
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No field selected! Please select one..." );
				}
			}

			$list_of_columns_to_process = $input[ 'ddl_checklist' ];
			foreach ( $list_of_columns_to_process as $column_to_restore )
			{
				# Is it a valid field-name?
				if ( !array_key_exists( $column_to_restore, $m[ 'm_data_definition_bak' ] ) )
				{
					return array( 'faultCode' => 0, 'faultMessage' => "No such data-field ('<i>" . $column_to_restore . "</i>') exists within DDL Backup Repository!" );
				}
			}

			//-------------------------------------
			// ALTER : Module _master_repo -tables
			//-------------------------------------

			$list_of_columns_to_process__translated = array();
			foreach ( $list_of_columns_to_process as $name )
			{
				$list_of_columns_to_process__translated[ $name ]               = $this->Registry->Db->modules__ddl_column_type_translation( $m[ 'm_data_definition_bak' ][ $name ], true );
				$list_of_columns_to_process__translated[ $name ][ 'old_name' ] = "__" . $name;
				$list_of_columns_to_process__translated[ $name ][ 'name' ]     = $name;
			}

			# CHANGEs
			$this->Registry->Db->cur_query = array(
				'do'       => "alter",
				'table'    => "mod_" . $m[ 'm_unique_id_clean' ] . "_master_repo",
				'action'   => "change_column",
				'col_info' => $list_of_columns_to_process__translated
			);
			if ( !$this->Registry->Db->simple_exec_query() )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Database query (RESTORE-MODULE-FIELD) failed!" );
			}

			//------------------------------------
			// UPDATE :  module_data_definition
			//------------------------------------

			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => "modules_data_definition",
				'set'    => array( 'is_backup' => 0 ),
				'where'  => array(
					"m_unique_id=" . $this->Registry->Db->quote( $input[ 'm_unique_id' ] ),
					"name IN (" . implode( ",", array_map( array( $this->Registry->Db->db, "quote" ), $list_of_columns_to_process ) ) . ")",
				),
			);

			if ( $this->Registry->Db->simple_exec_query() )
			{
				# On SUCCESS, update cache and respond
				$_recache = new \Persephone\Cache\Recache( $this->Registry );
				$_recache->main( "modules" );
				$_recache->main( "modules_connectors" );

				return array( 'responseCode' => 1, 'responseMessage' => 'Success! Field-registry successfully restored!<br />Refreshing...', 'responseAction' => "refresh" );
			}
			else
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Error! Database query (UPDATE-MODULE-RECORD) failed!" );
			}
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Sets DDL-column as Title-column
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__ddl__do_set_title_column ()
	{
		$input = $this->Registry->Input->post();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

			if ( empty( $input[ 'ddl_checklist' ] ) )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "Select something..." );
			}
			else
			{
				if ( array_key_exists( $input[ 'ddl_checklist' ], $m[ 'm_data_definition' ] ) )
				{
					if ( $m[ 'm_title_column' ] == $input[ 'ddl_checklist' ] )
					{
						return array( 'faultCode' => 0, 'faultMessage' => "Selected field has already been set as a Title-field!" );
					}

					//----------------------------------------------------------
					// VALIDATION : Is this data-field eligible to be a TITLE?
					//----------------------------------------------------------

					$_ddl_information    =& $m[ 'm_data_definition' ][ $input[ 'ddl_checklist' ] ];
					$_processor_instance = $this->Registry->loader( "Data_Processors__" . ucwords( $_ddl_information[ 'type' ] ) );
					if ( $_processor_instance->modules__ddl__is_eligible_for_title( $_ddl_information ) )
					{
						$this->Registry->Db->cur_query = array(
							'do'     => "update",
							'tables' => "modules",
							'set'    => array( 'm_title_column' => $input[ 'ddl_checklist' ] ),
							'where'  => "m_unique_id = " . $this->Registry->Db->quote( $input[ 'm_unique_id' ] )
						);
						if ( $this->Registry->Db->simple_exec_query() )
						{
							# On SUCCESS, update cache and respond
							$_recache = new \Persephone\Cache\Recache( $this->Registry );
							$_recache->main( "modules" );

							return array( 'responseCode' => 1, 'responseMessage' => "Success! Field successfully set as Title!<br />Refreshing...", 'responseAction' => "refresh" );
						}
						else
						{
							return array( 'faultCode' => 0, 'faultMessage' => "Database query (UPDATE-MODULE-RECORD) failed!" );
						}
					}
					else
					{
						return array( 'faultCode' => 0, 'faultMessage' => "Selected data-field is not eligible to be a Title-field!" );
					}
				}
				else
				{
					return array( 'faultCode' => 0, 'faultMessage' => "Invalid data-field provided!" );
				}
			}
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}
	}


	/**
	 * Creates a new Module Subroutine
	 *
	 * @param    array   Custom subroutine-configuration
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__subroutines__do_create ( $input = null )
	{
		if ( is_null( $input ) )
		{
			$input = $this->Registry->Input->post();
		}
		$faults = array();

		if ( array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			//------------------
			// Main container
			//------------------

			$subroutine = array();

			//------------------------------------------
			// Data-source, Data-binding, Data-target
			//------------------------------------------

			$subroutine[ 's_data_source' ] = $input[ 's_data_source' ];
			$subroutine[ 's_data_target' ] = $input[ 's_data_target' ];

			$faults = $this->Registry->loader( "Data_Sources__" . ucwords( $subroutine[ 's_data_source' ] ) )->modules__subroutines__do_validate( $subroutine, $input );
		}
		else
		{
			$faults[ ] = array( 'faultCode' => 0, 'faultMessage' => "Error! Invalid module-unique-id provided!" );
		}

		//-------------------------------------------------------------------------
		// Any errors so far? If so, return them and halt processing the request
		//-------------------------------------------------------------------------

		if ( count( $faults ) )
		{
			return $faults;
		}

		//-----------------------------------------------
		// Still here? Continue processing the request
		//-----------------------------------------------

		$subroutine[ 's_data_definition' ]    = serialize( $subroutine[ 's_data_definition' ] );
		$subroutine[ 's_qstring_parameters' ] = serialize( $subroutine[ 's_qstring_parameters' ] );
		$subroutine[ 's_fetch_criteria' ]     = serialize( $subroutine[ 's_fetch_criteria' ] );
		$subroutine[ 'm_unique_id' ]          = $input[ 'm_unique_id' ];

		$this->Registry->Db->cur_query = array(
			'do'    => "insert",
			'table' => "modules_subroutines",
			'set'   => $subroutine
		);

		if ( $this->Registry->Db->simple_exec_query() )
		{
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );

			return array( 'responseCode' => 1, 'responseMessage' => 'Subroutine successfully created!<br />Refreshing...', 'responseAction' => 'refresh' );
		}
		else
		{
			return array( array( 'faultCode' => 0, 'faultMessage' => "Database error occured! Possible connection problems with Db-Server?!" ) );
		}
	}


	/**
	 * Removes a Module Subroutine
	 *
	 * @return   array   Array containing status code pairs (either responseCode-responseMessage (on SUCCESS); or faultCodes-faultMessages (otherwise) )
	 */
	private function modules__subroutines__do_remove ()
	{
		$input =& $this->Registry->Input->request();

		if ( !array_key_exists( $input[ 'm_unique_id' ], $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ] ) )
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid module-unique-id provided!" );
		}

		$m =& $this->Registry->Cache->cache[ 'modules' ][ 'by_unique_id' ][ $input[ 'm_unique_id' ] ];

		if ( isset( $input[ 's_name' ] ) and !empty( $input[ 's_name' ] ) )
		{
			if ( !array_key_exists( $input[ 's_name' ], $m[ 'm_subroutines' ] ) )
			{
				return array( 'faultCode' => 0, 'faultMessage' => "No such subroutine available!" );
			}
			if ( !$m[ 'm_subroutines' ][ $input[ 's_name' ] ][ 's_can_remove' ] )
			{
				return array(
					'faultCode'    => 0,
					'faultMessage' => "The selected subroutine is not removable! Some of possible reasons are:<ul><li>It's a system-subroutine (such as &#039;insert&#039; or &#039;update&#039; subroutines;</li><li>It's locked by system administrator;</li></ul>"
				);
			}
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Select something..." );
		}

		//-----------------------------------------------
		// Still here? Continue processing the request
		//-----------------------------------------------

		$this->Registry->Db->cur_query = array(
			'do'    => "delete",
			'table' => "modules_subroutines",
			'where' => array( "s_name = '" . $input[ 's_name' ] . "'", "m_unique_id = '" . $input[ 'm_unique_id' ] . "'" )
		);

		if ( $this->Registry->Db->simple_exec_query() )
		{
			$_recache = new \Persephone\Cache\Recache( $this->Registry );
			$_recache->main( "modules" );

			return array( 'responseCode' => 1, 'responseMessage' => "Success! Subroutine successfully removed!<br />Refreshing...", 'responseAction' => "refresh" );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Database error occured! Possible connection problems with Db-Server?!" );
		}
	}


	/**
	 * Retrieves the list of configuration setting groups
	 *
	 * @return   mixed   Array containing the list of groups on SUCCESS, null otherwise
	 */
	private function settings__do_show ()
	{
		$data = array();
		if ( isset( $this->Registry->Cache->cache[ 'settings' ] ) and count( $this->Registry->Cache->cache[ 'settings' ] ) )
		{
			$_group_data = $this->Registry->Cache->cache[ 'settings' ][ 'by_key' ][ $this->running_subroutine[ 'request' ][ 'config_group' ] ];
			if ( $_group_data[ 'conf_group_noshow' ] )
			{
				continue;
			}

			$_group_data__items = $_group_data;
			foreach ( $_group_data__items as $_k => &$_v )
			{
				if ( !is_array( $_v ) or $_v[ 'conf_show' ] == 0 )
				{
					unset( $_group_data__items[ $_k ] );
					continue; // "continue" is a MUST here, otherwise after key deletion we will lose a character, due to pointer shifting ahead for 1-byte.
				}

				$_conf_extra = array();
				if ( $_v[ 'conf_type' ] == 'dropdown' or $_v[ 'conf_type' ] == 'multi' )
				{
					if ( !empty( $_v[ 'conf_extra' ] ) )
					{
						if ( $_v[ 'conf_extra' ] == '#show_groups#' )
						{
							foreach ( $this->Registry->Cache->cache[ 'member_groups' ] as $__k => $__v )
							{
								$_conf_extra[ $__v[ 'g_id' ] ] = $__v[ 'g_title' ];
							}
						}
						elseif ( $_v[ 'conf_extra' ] == '#show_skins#' )
						{
							foreach ( $this->Registry->Cache->cache[ 'skins' ] as $__k => $__v )
							{
								$_conf_extra[ $__v[ 'set_id' ] ] = $__v[ 'set_name' ];
							}
						}
						else
						{
							$_conf_extra_lines = explode( "\n", preg_replace( '#\r?\n#m', "\n", $_v[ 'conf_extra' ] ) );
							foreach ( $_conf_extra_lines as $__v )
							{
								$_tmp_conf_extra                      = explode( "=", $__v );
								$_conf_extra[ $_tmp_conf_extra[ 0 ] ] = $_tmp_conf_extra[ 1 ];
							}
						}
					}
					$_v[ 'conf_extra' ] = $_conf_extra;
				}

				switch ( $_v[ 'conf_type' ] )
				{
					case 'yes_no':
						if ( $_v[ 'conf_value' ] == '1' )
						{
							(int) $_conf_real_value = 1;
						}
						elseif ( $_v[ 'conf_value' ] == '0' )
						{
							(int) $_conf_real_value = 0;
						}
						else
						{
							(int) $_conf_real_value = $_v[ 'conf_default' ];
						}
						break;

					case 'input':
					case 'textarea':
					case 'dropdown':
						if ( strval( $_v[ 'conf_value' ] ) == '' )
						{
							(string) $_conf_real_value = $_v[ 'conf_default' ];
						}
						else
						{
							(string) $_conf_real_value = $_v[ 'conf_value' ];
						}
						break;

					case 'multi':
						$_v[ 'conf_default' ] = $this->Registry->Input->clean__excessive_separators( $_v[ 'conf_default' ], "," );
						$_v[ 'conf_default' ] = explode( ",", $_v[ 'conf_default' ] );
						if ( strval( $_v[ 'conf_value' ] ) == '' )
						{
							$_conf_real_value = $_v[ 'conf_default' ];
						}
						else
						{
							$_v[ 'conf_value' ] = $this->Registry->Input->clean__excessive_separators( $_v[ 'conf_value' ], "," );
							$_conf_real_value   = explode( ",", $_v[ 'conf_value' ] );
						}
						break;
				}
				$_v[ 'conf_real_value' ] = $_conf_real_value;
			}

			$data[ $_group_data[ 'conf_group_id' ] ] = array(
				'conf_group_id'      => $_group_data[ 'conf_group_id' ],
				'conf_group_title'   => $_group_data[ 'conf_group_title' ],
				'conf_group_desc'    => $_group_data[ 'conf_group_desc' ],
				'conf_group_count'   => $_group_data[ 'conf_group_count' ],
				'conf_group_noshow'  => $_group_data[ 'conf_group_noshow' ],
				'conf_group_keyword' => $_group_data[ 'conf_group_keyword' ],
				'_items'             => $_group_data__items,
			);

			return $data;
		}
		else
		{
			return null;
		}
	}


	/**
	 * ACP :: System :: Settings :: Edit Settings
	 *
	 * @return   array   Response CODE and MESSAGE
	 */
	private function settings__do_edit ()
	{
		$input          = $this->Registry->Input->post();
		$_rows_affected = 0;

		foreach ( $input[ 'conf_key' ] as $_k => $_v )
		{
			$_v                            = is_array( $_v )
				? implode( ",", $_v )
				: $_v;
			$this->Registry->Db->cur_query = array(
				'do'     => "update",
				'tables' => array( "conf_settings" ),
				'set'    => array( "conf_value" => $_v ),
				'where'  => "conf_key = '" . $_k . "'"
			);
			$_rows_affected += $this->Registry->Db->simple_exec_query();
		}
		if ( $_rows_affected )
		{
			$this->Registry->loader( "Cache__Recache" )->main( "settings" );

			return array( 'responseCode' => 1, 'responseMessage' => "Settings successfully updated! Refreshing in 2 seconds...", 'responseAction' => "refresh:2000" );
		}

		return array( 'responseCode' => 1, 'responseMessage' => "Nothing changed..." );
	}


	/**
	 * ACP :: System :: Settings :: Revert Setting to Default value
	 *
	 * @return   array   Response CODE and MESSAGE
	 */
	private function settings__do_revert ()
	{
		$input = $this->Registry->Input->post();
		if ( !empty( $input[ 'revert__for' ] ) )
		{
			$setting_id                    = intval( $input[ 'revert__for' ] );
			$this->Registry->Db->cur_query = array(
				"do"     => "update",
				"tables" => array( "conf_settings" ),
				"set"    => array( "conf_value" => new Zend_Db_Expr( "NULL" ) ),
				"where"  => "conf_id = " . $this->Registry->Db->quote( $setting_id, "INTEGER" ),
			);
			$this->Registry->Db->simple_exec_query();

			$this->Registry->loader( "Cache__Recache" )->main( "settings" );

			return array( 'responseCode' => 1, 'responseMessage' => "Setting successfully reverted to its default value! Refreshing in 2 seconds...", 'responseAction' => "refresh:2000" );
		}
		else
		{
			return array( 'faultCode' => 0, 'faultMessage' => "Invalid setting-id provided! Request denied..." );
		}
	}
}
