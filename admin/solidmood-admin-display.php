<div class="wrap">
  <h1>New Solid Mood Case</h1>
  <?php
  // Let see if we have a caching notice to show
  $admin_notice = get_option('custom_wordpress_plugin_admin_notice');
  if($admin_notice) {
    // We have the notice from the DB, let's remove it.
    delete_option( 'custom_wordpress_plugin_admin_notice' );
    // Call the notice message
    $this->admin_notice($admin_notice);
  }
  if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
    $this->admin_notice("Your settings have been updated!");
  }
  ?>
 <form method="POST" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" enctype="multipart/form-data">
 	<input type='hidden' name='action' value='submit-form' />
      <h1>Case Info</h1>
      <div class="form-section name">
        <h3 class="section-title">Case Name</h3>
        <div class="section-inputs">
          <label for="product_name_es">Español</label>
          <input type="text" name="product_name_es" id="product_name_es" class="large-text" value="">
          <label for="product_name_en">English</label>
          <input type="text" name="product_name_en" id="product_name_en" class="large-text" value="">
        </div>
      </div>
      <div class="form-section price">
        <h3 class="section-title">Case Price</h3>
        <div class="section-inputs">
          <label for="product_price">Price</label>
          <input type="number" name="product_price" id="product_price" class="large-text" value="">           
        </div>
      </div>
      <div class="form-section sku">
        <h3 class="section-title">Case SKU</h3>
        <div class="section-inputs">
          <label for="product_sku">SKU</label>
          <input type="text" name="product_sku" id="product_sku" class="large-text" value="">
        </div>
      </div>
      <div class="form-section description">
        <h3 class="section-title">Case Description</h3>
        <div class="section-inputs">
          <label for="product_description_es">Español</label>                
                <?php
                  $content = '';
                  $args = array(
                    'tinymce'       => array(
                    'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                    ),
                  );
                  wp_editor( $content, 'product_description_es', $args );
                ?>     
          <label for="product_description_en">English</label>
                
                <?php
                  $content = '';
                  $args = array(
                    'tinymce'       => array(
                    'toolbar1'      => 'bold,italic,underline,separator,alignleft,aligncenter,alignright,separator,link,unlink,undo,redo',
                    ),
                  );
                  wp_editor( $content, 'product_description_en', $args );
                ?>
        </div>
      </div>
      <div class="form-section category">
        <h3 class="section-title">Case Category</h3>
        <div class="section-inputs">
          <label for="product_description_es">Select Categories</label>
                <?php
                $args = array(
                       'taxonomy'     => 'product_cat',
                       'orderby'      => 'name',
                       'show_count'   => 0,
                       'pad_counts'   => 0,
                       'hierarchical' => 1,
                       'title_li'     => '',
                       'hide_empty'   => 0
                );
                  $all_categories = get_categories( $args );
                  echo '<ul id="product_catchecklist" class="categorychecklist form-no-clear">';
                  foreach($all_categories as $cat) {
                    if($cat->category_parent == 0) {
                      $category_id = $cat->term_id;
                      echo '<li id="product_cat-' . $category_id . '"><label class="selectit"><input value="' . $category_id . '" type="checkbox"   name="categories[]" id="in-product_cat-' . $category_id . '">' . $cat->name . '</label></li>';
                      $args2 = array(
                              'taxonomy'     => 'product_cat',
                              'child_of'     => 0,
                              'parent'       => $category_id,
                              'orderby'      => 'name',
                              'show_count'   => 0,
                              'pad_counts'   => 0,
                              'hierarchical' => 1,
                              'title_li'     => '',
                              'hide_empty'   => 0
                      );
                      $sub_cats = get_categories( $args2 );
                      if($sub_cats) {
                          foreach($sub_cats as $sub_category) {
                              echo '<li style="margin-left:10px;" id="product_cat-' . $sub_category->term_id . '"><label class="selectit"><input value="' . $sub_category->term_id . '" type="checkbox" name="categories[]" id="in-product_cat-' . $sub_category->term_id . '">' . $sub_category->name . '</label></li>';
                          }   
                      }
                    }
  
                  }
                  echo '</ul>';
                ?>
        </div>
      </div>
      <div class="form-section color">
        <h3 class="section-title">Case Colors</h3>
        <div class="section-inputs">
          <label>Color</label>
          <input type="text" name="device_color" id="device_color" class="large-text" value="">
        </div>
      </div>
      <div class="form-section images">
        <h3 class="section-title">Case Images</h3>
        <div class="images-generator-container">
          <div class="images-generator-input">
            <div class="design-preview">
              <img id="device-design-img" class="device-design-img" src="https://via.placeholder.com/1024" alt="Device design" />
              <input type='file' id="device-design-input" class="device-design-input" name="device-design-input" style="display:none;"/>
            </div>
            <div class="design-preview-buttons">
              <button type="button" id="generate-renders" class="button generate-renders button-primary">Generate Renders</button>
              <button type="button" id="upload-renders" class="button upload-renders button-primary">Upload Renders</button>
            </div>
            <p class="loading-renders" style="color:green;"></p>
          </div>
          <?php
      
          $options = array('hide_empty' => false);
      
          $terms = get_terms('pa_device', $options);
      
          foreach ($terms as $each_device) {
            echo '<div class="single-device-container"><h4>' . $each_device->name . '</h4><div id="generated-gallery-' . $each_device->slug . '"  class="single-device-images"><input value="' . $each_device->name . '" type="hidden" name="devices[]">';
  
            $path_images =  plugin_dir_path( __DIR__ ) . 'assets/devices/' . $each_device->slug;
  
            $url_images = plugin_dir_url( dirname( __FILE__ ) ) . 'assets/devices/' . $each_device->slug . '/';
  
            $files = [];
  
            if ($handle = opendir($path_images)) {
  
              while (false !== ($entry = readdir($handle))) {
  
                $files[] = $entry;
  
              }
  
              $images = preg_grep('/\.(jpg|jpeg|png|gif)(?:[\?\#].*)?$/i', $files);
              
              sort($images); 
  
              foreach($images as $image) {
  
                $is_front = substr($image, -9) == 'front.png' ? true : false;
  
                $img_id = substr($image, 0, -4);
  
                $img_title = str_replace('-', ' ', $img_id);
  
                $img_title = ucwords($img_title);
  
                echo '<div class="single-mockup-container ' . ($is_front ? 'device-front-img' : '')  . '"><canvas id="' . $img_id . '" class="device-mockup"  data-img-src="' . $url_images . $image . '" data-img-name="' . $img_title . '" style="display:none;"></canvas><input value="" type="number"  name="' . $each_device->slug . '-renders[]" id="' . $img_id . '-input" style="display:none;"/></div>';
  
              }
  
              closedir($handle);
              
            }
  
            echo '</div></div>';
          }     
          
          ?>
        </div>
      </div>
      <p class="submit"><input type="submit" value="Create Case" class="button-primary" name="Submit"></p>
</form>
</div>