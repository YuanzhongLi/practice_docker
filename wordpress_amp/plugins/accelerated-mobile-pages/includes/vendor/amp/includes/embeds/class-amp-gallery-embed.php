<?php
//namespace AMPforWP\AMPVendor;
require_once( AMP__DIR__ . '/includes/embeds/class-amp-base-embed-handler.php' );

class AMP_Gallery_Embed_Handler extends AMP_Base_Embed_Handler {
	private static $script_slug = 'amp-carousel';
	private static $script_src = 'https://cdn.ampproject.org/v0/amp-carousel-0.1.js';

	public function register_embed() {
		add_shortcode( 'gallery', array( $this, 'shortcode' ) );
	}

	public function unregister_embed() {
		remove_shortcode( 'gallery' );
	}

	public function get_scripts() {
		if ( ! $this->did_convert_elements ) {
			return array();
		}

		return array( self::$script_slug => self::$script_src );
	}

	public function shortcode( $attr ) {
		$post = get_post();

		if ( ! empty( $attr['ids'] ) ) {
			// 'ids' is explicitly ordered, unless you specify otherwise.
			if ( empty( $attr['orderby'] ) ) {
				$attr['orderby'] = 'post__in';
			}
			$attr['include'] = $attr['ids'];
		}

		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'include'    => '',
			'exclude'    => '',
			'size'       => array( $this->args['width'], $this->args['height'] ),
		), $attr, 'gallery' );

		$id = intval( $atts['id'] );

		if ( ! empty( $atts['include'] ) ) {
			$attachments = get_posts( array(
				'include' => $atts['include'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
				'fields' => 'ids',
			) );
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array(
				'post_parent' => $id,
				'exclude' => $atts['exclude'],
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
				'fields' => 'ids',
			) );
		} else {
			$attachments = get_children( array(
				'post_parent' => $id,
				'post_status' => 'inherit',
				'post_type' => 'attachment',
				'post_mime_type' => 'image',
				'order' => $atts['order'],
				'orderby' => $atts['orderby'],
				'fields' => 'ids',
			) );
		}

		if ( empty( $attachments ) ) {
			return '';
		}

		$urls = array();
		foreach ( $attachments as $attachment_id ) {
			list( $url, $width, $height ) = wp_get_attachment_image_src( $attachment_id, $atts['size'], true );

			if ( ! $url ) {
				continue;
			}

			$urls[] = apply_filters('amp_gallery_image_params', array(
				'url' => $url,
				'width' => $width,
				'height' => $height,
			),$attachment_id);
		}
		return $this->render( array(
			'images' => $urls,
		) );
	}

	public function render( $args ) {
		global $redux_builder_amp,$carousel_markup_all;
		$this->did_convert_elements = true;
		
		$args = wp_parse_args( $args, array(
			'images' => false,
		) );
		
		if ( empty( $args['images'] ) ) {
			return '';
		}

		/*Filter*/
		$carousel_markup = '';
		
		$carousel_markup_all = array(
			'1'=>array(
						'main-html'=>'{{with_carousel}}',
						'image-with-caption-html'=>'<figure><div class="ampforwp-gallery-item amp-carousel-container">{{main_images}} </div><figcaption :openbrack:class:closebrack:="expanded? \'expanded\' : \'\'" on="tap:AMP.setState({expanded: !expanded})" tabindex="0" role="button" >{{main_images_caption}}<span :openbrack:text:closebrack:="expanded ? \'less\' : \'more\'">more</span> </figcaption></figure>',
						'image-without-caption-html' =>'<div class="ampforwp-gallery-item amp-carousel-container">{{main_images}} </div>',
						'gallery_css' => '',

						'scripts' => array()
									),
			'2' => array(
						'main-html'=>'{{with_carousel}} {{with_carousel_thumbnail}}',
						'image-with-caption-html'=>'<figure><div class="ampforwp-gallery-item amp-carousel-container">{{main_images}} </div><figcaption :openbrack:class:closebrack:="expanded? \'expanded\' : \'\'" on="tap:AMP.setState({expanded: !expanded})" tabindex="0" role="button" >{{main_images_caption}}<span :openbrack:text:closebrack:="expanded ? \'less\' : \'more\'">more</span> </figcaption></figure>',
						'image-without-caption-html' =>'<div class="ampforwp-gallery-item amp-carousel-container">{{main_images}} </div>',
						'carousel_with_thumbnail_html'=>'<button on="tap:carousel-with-carousel-preview-{{unique_id}}.goToSlide(index={{unique_index}})" class="amp-carousel-slide amp-scrollable-carousel-slide">{{thumbnail}}</button>',
						'gallery_css' => '
							.carousel-preview button{padding:0;}
							.carousel-preview amp-img{height:40px;width:60px;position:relative;}
							.carousel-preview {width: 100%;display: inline-block;text-align: center;margin: 20px 0px;}
							',
						'scripts' => array()
									),
			'3' => array(
						'main-html'=>'<div class="wrapper">{{with_images}}</div>
						<amp-image-lightbox id="gallery-lightbox" layout="nodisplay">
					      <div on="tap:gallery-lightbox.close" role="button"
					          tabindex="0">
					          <button class="cls-btn" on="tap:gallery-lightbox.close"
					            role="button" tabindex="0"></button>
					      </div>
					    </amp-image-lightbox>',
						'image-with-caption-html'=>'',
						'image-without-caption-html' =>'{{main_images}}',
						'gallery_css' => '
							.wrapper{display:inline-block;width:100%;clear:both;}
							.wrapper amp-img{height:134px;width:200px;position: relative;float:left;margin: 0px 30px 30px 0px;}
							.cls-btn{background:#0d0d0d;border:none;position: absolute;right: 10px;}
							.cls-btn:after{content:"X";display:inline-block;color:#fff;font-size:20px;padding:20px;}
							',
						'scripts' => array()
									),
		);


		$carousel_markup_all = apply_filters("ampforwp_manage_gallery_markup", $carousel_markup_all);
		//Default markup
		$markup =  $carousel_markup_all[1];

		if( isset($redux_builder_amp['ampforwp-gallery-design-type']) &&  isset($carousel_markup_all[$redux_builder_amp['ampforwp-gallery-design-type'] ] ) ){
			$markup =  $carousel_markup_all[$redux_builder_amp['ampforwp-gallery-design-type']];
		}
		/*Filter*/

		
		$amp_images = array();
		foreach ( $args['images'] as $key => $image ) {
			$amp_img_arr = array(
					'src' => $image['url'],
					'width' => $image['width'],
					'height' => $image['height'],
					'layout' => 'fill',
					'class'  => 'amp-carousel-img',
				);
			if( isset($redux_builder_amp['ampforwp-gallery-design-type']) && $redux_builder_amp['ampforwp-gallery-design-type'] == 3  ){
				$design3_additional_attr = array('on'=> 'tap:gallery-lightbox', 'role'=>'button', 
                	'tabindex'=>$key);
				$amp_img_arr = array_merge($amp_img_arr, $design3_additional_attr);
			}
			$amp_images[$key] = AMP_HTML_Utils::build_tag(
				'amp-img',
				$amp_img_arr
			);

			//Image markups loading
			$returnHtml = '';
			//Check if the attachment has caption or not
			if(isset($image['caption']) && $image['caption'] != '' && isset($markup['image-with-caption-html']) && $markup['image-with-caption-html'] != ''){
				// To enable the carousel magic
				//add_action('ampforwp_after_header','ampforwp_carousel_class_magic', 999, 1);
				add_filter('ampforwp_modify_the_content','ampforwp_carousel_class_magic');
				//add_action('below_the_header_design_1','ampforwp_carousel_class_magic', 999, 1);
				$caption = $image['caption'];
				// Append the caption with image
				$returnHtml = isset($markup['image-with-caption-html'])? $markup['image-with-caption-html']:'';
				$returnHtml = str_replace('{{main_images}}', $amp_images[$key] , $returnHtml);
				$returnHtml = str_replace('{{main_images_caption}}', wp_kses_data( $caption ), $returnHtml);
			}
			elseif( isset($markup['image-without-caption-html']) ){
				// If there is no caption
				$returnHtml = isset($markup['image-without-caption-html'])? $markup['image-without-caption-html'] :'';
				$returnHtml = str_replace('{{main_images}}', $amp_images[$key] , $returnHtml);
			}

			
			$images[$key] = apply_filters('amp_gallery_images', $returnHtml, $image, $markup);
		}// foreach Closed



		//replacements
			$r = rand(1,100);
			$amp_carousel = AMP_HTML_Utils::build_tag(
							'amp-carousel',
							array(
								'width' => $this->args['width'],
								'height' => $this->args['height'],
								'type' => 'slides',
								'layout' => 'responsive',
								'class'  => 'collapsible-captions',
								'id' => 'carousel-with-carousel-preview-'.$r
							),
							implode( PHP_EOL, $images ));

			$amp_carousel_with_thumbnail_nav = apply_filters('amp_thumbnail_images', $amp_images, $r, $markup);
			$amp_carousel_thumbnail ='';
			if(!empty($amp_carousel_with_thumbnail_nav)){
				$amp_carousel_thumbnail = AMP_HTML_Utils::build_tag(
								'amp-carousel',
								array(
									'width' => 'auto',
									'height' => 48,
									'type' => 'carousel',
									'layout' => 'fixed-height',
									'class'  => 'carousel-preview'
								),
								implode( PHP_EOL, $amp_carousel_with_thumbnail_nav ));
			
			}
		$amp_carousel_thumbnail = apply_filters('amp_gallery_markup', $amp_carousel_thumbnail);

		$returnCompleteHtml = $markup['main-html'];
		//last changes
		$returnCompleteHtml = str_replace('{{with_carousel}}', $amp_carousel, $returnCompleteHtml);
		$returnCompleteHtml = str_replace('{{with_carousel_thumbnail}}', $amp_carousel_thumbnail, $returnCompleteHtml);
		$returnCompleteHtml = str_replace('{{with_images}}', implode( PHP_EOL, $images ), $returnCompleteHtml);
		return $returnCompleteHtml;
	}
}// Class closed


// Add Caption in the Gallery Image
add_filter('amp_gallery_images','ampforwp_new_gallery_images', 10, 3);
function ampforwp_new_gallery_images($images_markup, $image, $markup_arr){
	add_action('amp_post_template_css', 'ampforwp_additional_gallery_style');
	add_filter('amp_post_template_data','ampforwp_carousel_bind_script');
	return $images_markup;
}




if( ! function_exists( 'ampforwp_additional_gallery_style' ) ){
	function ampforwp_additional_gallery_style(){
		global $redux_builder_amp,$carousel_markup_all;
		$design_type = '';
		$design_type = $redux_builder_amp['ampforwp-gallery-design-type'];
		
		if(isset($design_type) && $design_type!==''){
			echo $carousel_markup_all[$design_type]['gallery_css'];
		}
	}
}
// amp-bind for carousel with captions
if( !function_exists('ampforwp_carousel_bind_script')){
	function ampforwp_carousel_bind_script($data){
		global $redux_builder_amp;
		$design_type = '';
		$design_type = $redux_builder_amp['ampforwp-gallery-design-type'];
		if( $design_type == 1 || $design_type == 2 ){
			if ( empty( $data['amp_component_scripts']['amp-bind'] ) ) {
				$data['amp_component_scripts']['amp-bind'] = 'https://cdn.ampproject.org/v0/amp-bind-0.1.js';
			}	
		}elseif( $design_type == 3 ){
			if ( empty( $data['amp_component_scripts']['amp-image-lightbox'] ) ) {
				$data['amp_component_scripts']['amp-image-lightbox'] = 'https://cdn.ampproject.org/v0/amp-image-lightbox-0.1.js';
			}
		}else{
			if ( empty( $data['amp_component_scripts']['amp-bind'] ) ) {
				$data['amp_component_scripts']['amp-bind'] = 'https://cdn.ampproject.org/v0/amp-bind-0.1.js';
			}
		}

		
	return $data;
	}
}

add_filter('amp_thumbnail_images','ampforwp_new_thumbnail_images',10,3);
function ampforwp_new_thumbnail_images($amp_images, $uniqueid, $markup_arr){
	if(!isset($markup_arr['carousel_with_thumbnail_html'])){return '';}
	$amp_thumb_image_buttons = '';
	foreach ($amp_images as $key => $value) {
		$returnHtml = $markup_arr['carousel_with_thumbnail_html'];
		$returnHtml = str_replace('{{thumbnail}}', $value , $returnHtml);
		$returnHtml = str_replace('{{unique_id}}', $uniqueid , $returnHtml);
		$returnHtml = str_replace('{{unique_index}}', $key , $returnHtml);
		$amp_thumb_image_buttons[$key] = $returnHtml;
	}
	return $amp_thumb_image_buttons;
}