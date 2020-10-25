<?php
namespace Tainacan\VaMus;

class CollectionFormConfig {

	protected static $instance;

	final public static function get_instance() {
		return isset(static::$instance)
			? static::$instance
			: static::$instance = new static;
	}

	final private function __construct() {
		$this->init();
	}

	protected function init() {
		add_action( 'tainacan-register-admin-hooks', array( $this, 'register_hook' ) );
		add_action( 'tainacan-insert-tainacan-collection', array( $this, 'save_data' ) );
		add_filter( 'tainacan-api-response-collection-meta', array( $this, 'add_meta_to_response' ), 10, 2 );
		add_filter( 'tainacan-api-items-prepare-for-response', array( $this, 'add_vamus_info_to_response_item' ), 10, 2 );
	}

	function register_hook() {
		if ( function_exists( 'tainacan_register_admin_hook' ) ) {
				tainacan_register_admin_hook( 'collection', array( $this, 'form' ) );
		}
	}

	function save_data( $object ) {
		if ( ! function_exists( 'tainacan_get_api_postdata' ) ) {
			return;
		}
		$post = tainacan_get_api_postdata();
		if ( $object->can_edit() ) {
			if ( isset( $post->vamus_institute_identifier_collection ) )
				update_post_meta( $object->get_id(), 'vamus_institute_identifier_collection', $post->vamus_institute_identifier_collection);

			if ( isset( $post->vamus_institute_name_collection ) )
				update_post_meta( $object->get_id(), 'vamus_institute_name_collection', $post->vamus_institute_name_collection);

			if ( isset( $post->vamus_institute_lat_collection ) )
				update_post_meta( $object->get_id(), 'vamus_institute_lat_collection', $post->vamus_institute_lat_collection);

			if ( isset( $post->vamus_institute_long_collection ) )
				update_post_meta( $object->get_id(), 'vamus_institute_long_collection', $post->vamus_institute_long_collection);
		}
	}

	function add_vamus_info_to_response_item($item_arr, $item) {
		$item_arr['vamus_institute_identifier_collection'] = get_post_meta($item_arr['collection_id'],'vamus_institute_identifier_collection', true);
		$item_arr['vamus_institute_name_collection'] = get_post_meta($item_arr['collection_id'], 'vamus_institute_name_collection', true);
		$item_arr['vamus_institute_lat_collection'] = get_post_meta($item_arr['collection_id'], 'vamus_institute_lat_collection', true);
		$item_arr['vamus_institute_long_collection'] = get_post_meta($item_arr['collection_id'], 'vamus_institute_long_collection', true);
		return $item_arr;
	}

	function add_meta_to_response( $extra_meta, $request ) {
		$extra_meta = array(
			'vamus_institute_identifier_collection',
			'vamus_institute_name_collection',
			'vamus_institute_lat_collection',
			'vamus_institute_long_collection'
		);
		return $extra_meta;
	}

	function form() {
		if ( ! function_exists( 'tainacan_get_api_postdata' ) ) {
				return '';
		}

		$categories = get_categories();
		ob_start();
		?>
			<div class="field tainacan-collection--change-text-color">
				<div class="field">
					<label class="label">Identificador:</label>
					<div class="control is-clearfix"> 
						<input type="text" name="vamus_institute_identifier_collection" >
					</div>
				</div>

				<div class="field">
					<label class="label">Nome:</label>
					<div class="control is-clearfix"> 
						<input type="text" name="vamus_institute_name_collection" >
					</div>
				</div>

				<div class="field">
					<label class="label">lat:</label>
					<div class="control is-clearfix"> 
						<input type="number" name="vamus_institute_lat_collection" >
					</div>
				</div>

				<div class="field">
					<label class="label">long:</label>
					<div class="control is-clearfix"> 
						<input type="number" name="vamus_institute_long_collection" >
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

}

CollectionFormConfig::get_instance();