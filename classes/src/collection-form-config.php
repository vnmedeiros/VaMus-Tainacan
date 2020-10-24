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
			if ( isset( $post->vamus_collection_identifier ) )
				update_post_meta( $object->get_id(), 'vamus_collection_identifier', $post->vamus_collection_identifier);

			if ( isset( $post->vamus_collection_name ) )
				update_post_meta( $object->get_id(), 'vamus_collection_name', $post->vamus_collection_name);

			if ( isset( $post->vamus_collection_lat ) )
				update_post_meta( $object->get_id(), 'vamus_collection_lat', $post->vamus_collection_lat);

			if ( isset( $post->vamus_collection_long ) )
				update_post_meta( $object->get_id(), 'vamus_collection_long', $post->vamus_collection_long);
		}
	}

	function add_meta_to_response( $extra_meta, $request ) {
		$extra_meta = array(
			'vamus_collection_identifier',
			'vamus_collection_name',
			'vamus_collection_lat',
			'vamus_collection_long'
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
						<input type="text" name="vamus_collection_identifier" >
					</div>
				</div>

				<div class="field">
					<label class="label">Nome:</label>
					<div class="control is-clearfix"> 
						<input type="text" name="vamus_collection_name" >
					</div>
				</div>

				<div class="field">
					<label class="label">lat:</label>
					<div class="control is-clearfix"> 
						<input type="number" name="vamus_collection_lat" >
					</div>
				</div>

				<div class="field">
					<label class="label">long:</label>
					<div class="control is-clearfix"> 
						<input type="number" name="vamus_collection_long" >
					</div>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

}

CollectionFormConfig::get_instance();