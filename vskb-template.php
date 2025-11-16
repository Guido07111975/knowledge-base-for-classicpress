<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// category container
$output .= '<li class="vskb-cat-list '.esc_attr( $vskb_term->slug ).'">';
	// category title
	if ( $vskb_atts['post_count'] == 'true' ) {
		$vskb_post_count = '<span class="vskb-post-count">('.$vskb_term->count.')</span>';
	} else {
		$vskb_post_count = '';
	}
	$output .= '<div class="vskb-cat-name"><a href="'.esc_url( get_term_link( $vskb_term->term_id ) ).'" title="'.esc_attr( $vskb_term->name ).'" >'.esc_html( $vskb_term->name ).'</a> '.$vskb_post_count.'</div>';
	// woocommerce product category image
	if ( class_exists( 'woocommerce' ) && ( $vskb_atts['woo_image'] == 'true' ) ) {
		$vskb_thumbnail_id_woo = get_term_meta( $vskb_term->term_id, 'thumbnail_id', true );
		$vskb_thumbnail_woo = wp_get_attachment_url( $vskb_thumbnail_id_woo );
		if ( $vskb_thumbnail_woo ) :
			$output .= '<img class="vskb-cat-image" src="'.esc_url( $vskb_thumbnail_woo ).'" alt="'.esc_attr( $vskb_term->name ).'" />';
		endif;
	}
	// category description
	if ( $vskb_atts['category_description'] == 'true' ) {
		$vskb_description = term_description( $vskb_term->term_id );
		if ( ! empty( $vskb_description ) ) :
			$output .= '<div class="vskb-cat-description">'.wp_kses_post( $vskb_description ).'</div>';
		endif;
	}
	// post list
	$output .= '<ul class="vskb-post-list">';
		// posts query
		$vskb_post_args = array(
			'post_type' => $vskb_atts['post_type'],
			'tax_query' => array(
				array(
					'taxonomy' => $vskb_atts['taxonomy'],
					'field' => 'term_id',
					'terms' => $vskb_term->term_id,
					'include_children' => false,
				),
			),
			'posts_per_page' => $vskb_atts['posts_per_category'],
			'order' => $vskb_atts['order'],
			'orderby' => $vskb_atts['orderby'],
		);
		$vskb_posts = get_posts( $vskb_post_args );
		foreach( $vskb_posts as $vskb_post ) :
			// post title
			if ( get_the_title( $vskb_post->ID ) == false ) {
				$post_title = $vskb_atts['no_post_title_label'];
			} else {
				$post_title = get_the_title( $vskb_post->ID );
			}
			$post_slug = str_replace( ' ', '-', strtolower( $post_title ) );
			$output .= '<li class="vskb-post '.esc_attr( $post_slug ).'">';
			$output .= '<div class="vskb-post-name"><a href="'.get_permalink( $vskb_post->ID ).'" rel="bookmark" title="'.esc_attr( $post_title ).'">'.esc_html( $post_title ).'</a></div>';
			// post meta
			if ( $vskb_atts['post_meta'] == 'true' ) {
				$output .= '<div class="vskb-post-meta">';
				$output .= '<span class="vskb-post-meta-date"><a href="'.esc_url( get_permalink( $vskb_post->ID ) ).'">'.esc_html( get_the_date(get_option( 'date_format' ), $vskb_post->ID) ).'</a></span>';
				$output .= '<span class="vskb-post-meta-sep"> | </span>';
				$output .= '<span class="vskb-post-meta-author">'.sprintf( '<a href="%1$s">%2$s</a>', esc_url( get_author_posts_url( $vskb_post->post_author ) ), esc_html( get_the_author_meta( 'display_name', $vskb_post->post_author ) ) ).'</span>';
				$output .= '</div>';
			}
			$output .= '</li>';
		endforeach;
	$output .= '</ul>';
	// view all link
	if ( $vskb_atts['view_all_link'] == 'true' ) {
		$output .= '<div class="vskb-view-all-link">';
		$output .= '<a href="'.get_term_link( $vskb_term->term_id ).'" title="'.esc_attr( $vskb_term->name ).'">'.esc_html( $vskb_atts['view_all_link_label'] ).'</a>';
		$output .= '</div>';
	}
$output .= '</li>';
