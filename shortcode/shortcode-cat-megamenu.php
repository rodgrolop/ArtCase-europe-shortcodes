<?php
/**
 * Link shortcode
 *
 * Write [cat_megamenu] in your post editor to render this shortcode.
 */

if ( ! function_exists( 'cat_megamenu_shortcode' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'cat_megamenu', 'cat_megamenu_shortcode' );
	});

	/**
	 * Shortcode Function
	 */
	function cat_megamenu_shortcode( $atts ) {

		// Save $atts.
		$_atts = shortcode_atts( array(
			'taxonomy' => 'product_cat', //Type of taxonomy
			'parent_cat_id'  => 0, // Parent Category ID.
			'col_num'  => 0, // Number of desired columns.
		), $atts );

		$args = array(
    		'parent' => $_atts['parent_cat_id'],
    		'hide_empty' => false
		); 

		$terms = get_terms($_atts['taxonomy'], $args);

		$numberCats = count($terms);

		$colNum = $_atts['col_num'];

		$classColDivider = $colNum + 1;

		$elementsPerCol = (int) ($numberCats / $colNum);

		$elementsExcess = $numberCats % $colNum;

		$parentThumbnail_id = get_woocommerce_term_meta( $_atts['parent_cat_id'], 'thumbnail_id', true );

		$parentThumbnail = wp_get_attachment_url( $parentThumbnail_id );

		ob_start();

		echo '<div class="acs-menu-container columns-'.$classColDivider.'">';

		createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,true,false,0,0,$classColDivider,$html);
		
		echo '<div class="acs-menu-column img-column acs-col-1-'.$classColDivider.'"><img id="acs-img-holder" src="'.$parentThumbnail.'"/></div></div>';
		$output_string = ob_get_contents();
		ob_end_clean();
		return $output_string;

	}

	function createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,$colStart,$colEnd,$elementIndex,$elementIndexInColumn,$classColDivider){

		if ($elementsExcess > 0) {
			$elementCount = $elementsPerCol + 1;
		}
		else{
			$elementCount = $elementsPerCol;
		}

		if ($colStart){			
			echo '<ul class="acs-menu-column acs-col-1-'.$classColDivider.'">';
			createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,false,false,$elementIndex,0,$classColDivider);
		}
		elseif ($colEnd){
			$elementsExcess -= 1;
			echo '</ul>';
			if ($numberCats > $elementIndex) {				
				createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,true,false,$elementIndex,0,$classColDivider);
			}
			else{
				return 'hola';
			}
		}
		else {

			if ($elementIndexInColumn < $elementCount){
				$category = $terms[$elementIndex];
				$thumbnail_id = get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_url( $thumbnail_id );
				$category_link = get_category_link( $category->term_id );
				echo '<li class="acs-list-element"><a class="acs-link-element" href="'.$category_link.'" data-tn="'.$image.'">'.$category->name.'</a></li>';
				$elementIndexInColumn += 1;
				$elementIndex += 1;
				createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,false,false,$elementIndex,$elementIndexInColumn,$classColDivider);

			}
			else{
				createAcsElements($elementsExcess,$elementsPerCol,$terms,$colNum,$numberCats,false,true,$elementIndex,$elementIndexInColumn,$classColDivider);
			}
		
		}
		
	}

} // End if().
