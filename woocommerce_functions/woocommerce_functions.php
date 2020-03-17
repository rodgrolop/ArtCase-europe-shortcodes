<?php

add_action('admin_post_submit-form', '_handle_form_action'); // If the user is logged in

add_action('admin_post_nopriv_submit-form', '_handle_form_action'); // If the user in not logged in

function _handle_form_action(){

	$objProduct = new WC_Product_Variable();
	$objProduct->set_name($_POST['product_name_es']); //Set product name.
	$objProduct->set_status('draft'); //Set product status.
	$objProduct->set_featured(FALSE); //Set if the product is featured.                          | bool
	$objProduct->set_catalog_visibility('visible'); //Set catalog visibility.                   | string $visibility Options: 'hidden', 'visible', 'search' and 'catalog'.
	$objProduct->set_description($_POST['product_description_es']); //Set product description.
	$objProduct->set_short_description($_POST['product_description_es']); //Set product short description.
	$objProduct->set_sku($_POST['product_sku']); //Set SKUproduct_sku
	$objProduct->set_price($_POST['product_price']); //Set the product's active price.
	$objProduct->set_regular_price($_POST['product_price']); //Set the product's regular price.
	$objProduct->set_manage_stock(FALSE); //Set if product manage stock.                         | bool
	$objProduct->set_stock_status('instock'); //Set stock status.                               | string $status 'instock', 'outofstock' and 'onbackorder'
	$objProduct->set_backorders('no'); //Set backorders.                                        | string $backorders Options: 'yes', 'no' or 'notify'.
	$objProduct->set_sold_individually(FALSE); //Set if should be sold individually.            | bool
	$objProduct->set_reviews_allowed(TRUE); //Set if reviews is allowed.  
	$categories = $_POST['categories'];

	if ($categories) {
		foreach ($categories as $category) {
			$categories_ids[] = $category;
		}
		$objProduct->set_category_ids($categories_ids); //Set the product categories.                   | array $term_ids List of terms IDs.
    }

	if (!function_exists('wp_generate_attachment_metadata')){
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }
    
    if ($_FILES) {
       	foreach ($_FILES as $file => $array) {
           	if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
               	return "upload error : " . $_FILES[$file]['error'];
           	}
           	if($file == 'device-design-input'){
           		$attach_id = media_handle_upload( $file, 0 );
           		$objProduct->set_image_id($attach_id); //Set main image ID. 
           	}

        }   
    }

    $devices = $_POST['devices'];

    if ($devices) {
		foreach ($devices as $device) {
			$devices_names[] = $device;
		}
		$attribute = new WC_Product_Attribute();
    	$attribute->set_id(3);
    	$attribute->set_name('pa_device');
    	$attribute->set_options($devices_names);
    	$attribute->set_visible(true);
    	$attribute->set_variation(true);
    	$objProduct->set_attributes(array($attribute));
    	
    	$product_id = $objProduct->save();

    	foreach ($devices_names as $device_name) {
			$variation = new WC_Product_Variation();
  			$variation->set_parent_id($product_id);
  			$variation->set_attributes(array('pa_device' => sanitize_title($device_name)));
  			$variation->set_sku($_POST['product_sku'] . '-' . sanitize_title($device_name));
  			$variation->set_price($_POST['product_price']);
  			$variation->set_regular_price($_POST['product_price']);
  			$variation->set_status('publish');
  			$variation_id = $variation->save();
  			$parent_product = wc_get_product( $product_id );
  			$parent_product->sync( $variation_id );
  			$parent_product->save();
  			$device_renders = $_POST[sanitize_title($device_name).'-renders'];
  			$variation_attachment_ids = [];
  			if ($device_renders) {
				foreach ($device_renders as $device_render) {
					$variation_attachment_ids[] = $device_render;
				}
				$rest_of_ids = [];
				for ($i=0; $i < count($variation_attachment_ids); $i++) { 
					$attachment_title = get_the_title($variation_attachment_ids[$i]);
					$words = explode(" ", $attachment_title);
					$last_word = end($words);
					if ($last_word == "Back"){						
						$variation_main_image_id = $variation_attachment_ids[$i];
						unset($variation_attachment_ids[$i]);
					}
				}
  				update_post_meta($variation_id, '_thumbnail_id', $variation_main_image_id);
				update_post_meta($variation_id, 'rtwpvg_images', $variation_attachment_ids);
				$parent_product = wc_get_product( $product_id );
  				$parent_product->sync( $variation_id );
  				$parent_product->save();
			}
		}
		$parent_product = wc_get_product( $product_id );
		$parent_product->save();
		
		
    }  

  //   if ($_POST['device_color']) {
		// $attribute = new WC_Product_Attribute();
  //   	$attribute->set_id(1);
  //   	$attribute->set_name('pa_color');
  //   	$attribute->set_options($_POST['device_color']);
  //   	$attribute->set_visible(true);
  //   	$attribute->set_variation(true);
  //   	$objProduct->set_attributes(array($attribute));
    	
  //   	$product_id = $objProduct->save();

  //   	$variation = new WC_Product_Variation();
  // 		$variation->set_parent_id($product_id);
  // 		$variation->set_attributes(array('pa_color' => sanitize_title($_POST['device_color'])));
  // 		$variation->set_price($_POST['product_price']);
  // 		$variation->set_regular_price($_POST['product_price']);
  // 		$variation->set_status('publish');
  // 		$variation_id = $variation->save();
  // 		$parent_product = wc_get_product( $product_id );
  // 		$parent_product->sync( $variation_id );
  // 		$parent_product->save();

    	
		// $parent_product = wc_get_product( $product_id );
		// $parent_product->save();
		
		
  //   }

	do_action( 'woocommerce_update_product',  $product_id ); 
	// $new_product_id = $objProduct->save(); //Saving the data to create new product, it will return product ID.

	wp_safe_redirect( esc_url_raw( add_query_arg( 'status', 'ok', 'https://www.solidmood.com/wp-admin/admin.php?page=create-new-sm-case' ) ) );

}	

add_action('wp_ajax_nopriv_canvasUpload', 'canvasUpload');

add_action('wp_ajax_canvasUpload', 'canvasUpload');

function whatever(){
	$uploads = wp_upload_dir();
	$img = $_POST['uploadImage'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = $uploads['path'].'/'. uniqid() . '.jpg';
	$r = file_put_contents($file, $data);
	echo $r ? ($file . '-'. $attachment_id) : 'Error saving file';
}

/**
 * Save the image on the server.
 */
function canvasUpload() {

	if (!function_exists('wp_generate_attachment_metadata')){
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
    }

	// Upload dir.
	$upload_dir  = wp_upload_dir();
	$upload_path = str_replace( '/', DIRECTORY_SEPARATOR, $upload_dir['path'] ) . DIRECTORY_SEPARATOR;
	$base64_img = $_POST['uploadImage'];
	$img             = str_replace( 'data:image/jpeg;base64,', '', $base64_img );
	$img             = str_replace( ' ', '+', $img );
	$decoded         = base64_decode( $img );
	$filename        = uniqid() . '.jpeg';
	$file_type       = 'image/jpeg';

	// Save the image in the uploads directory.
	$upload_file = file_put_contents( $upload_path . $filename, $decoded );

	$attachment = array(
		'post_mime_type' => $file_type,
		'post_title'     => $_POST['title'],
		'post_content'   => $_POST['title'],
		'post_excerpt'   => $_POST['title'],
		'post_status'    => 'inherit',
		'guid'           => $upload_dir['url'] . '/' . basename( $filename )
	);

	$attachment_id = wp_insert_attachment( $attachment, $upload_dir['path'] . '/' . $filename );
	$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload_path . $filename );
	wp_update_attachment_metadata( $attachment_id, $attachment_data );
	update_post_meta($attachment_id, '_wp_attachment_image_alt', $_POST['title']);
	echo  $attachment_id;
}
