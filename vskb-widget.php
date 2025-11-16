<?php
// disable direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class vskb_widget extends WP_Widget {
	// constructor 
	public function __construct() {
		$widget_ops = array( 'classname' => 'vskb-widget', 'description' => __( 'Display your categories and posts in a widget.', 'very-simple-knowledge-base' ) );
		parent::__construct( 'vskb_widget', __( 'VS Knowledge Base', 'very-simple-knowledge-base' ), $widget_ops );
	}

	// set widget in dashboard
	function form( $instance ) {
		$instance = wp_parse_args( $instance, array(
			'vskb_title' => '',
			'vskb_text' => '',
			'vskb_columns' => '',
			'vskb_attributes' => '',
		) );
		$vskb_title = ! empty( $instance['vskb_title'] ) ? $instance['vskb_title'] : __( 'VS Knowledge Base', 'very-simple-knowledge-base' );
		$vskb_text = $instance['vskb_text'];
		$vskb_columns = $instance['vskb_columns'];
		$vskb_attributes = $instance['vskb_attributes'];
		// widget input fields
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'vskb_title' ) ); ?>"><?php esc_html_e( 'Title', 'very-simple-knowledge-base' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vskb_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vskb_title' ) ); ?>" type="text" value="<?php echo esc_attr( $vskb_title ); ?>"></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'vskb_text' ) ); ?>"><?php esc_html_e( 'Text above knowledge base', 'very-simple-knowledge-base' ); ?>:</label>
		<textarea class="widefat monospace" rows="6" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'vskb_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vskb_text' ) ); ?>"><?php echo wp_kses_post( $vskb_text ); ?></textarea></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'vskb_columns' ) ); ?>"><?php esc_html_e( 'Columns', 'very-simple-knowledge-base' ); ?>:</label>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'vskb_columns' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vskb_columns' ) ); ?>">
			<option value="one" <?php echo ( $vskb_columns == 'one' ) ? 'selected' : ''; ?>><?php esc_html_e( 'One column', 'very-simple-knowledge-base' ); ?></option>
			<option value="two" <?php echo ( $vskb_columns == 'two' ) ? 'selected' : ''; ?>><?php esc_html_e( 'Two columns', 'very-simple-knowledge-base' ); ?></option>
			<option value="three" <?php echo ( $vskb_columns == 'three' ) ? 'selected' : ''; ?>><?php esc_html_e( 'Three columns', 'very-simple-knowledge-base' ); ?></option>
			<option value="four" <?php echo ( $vskb_columns == 'four' ) ? 'selected' : ''; ?>><?php esc_html_e( 'Four columns', 'very-simple-knowledge-base' ); ?></option>
			<option value="disable" <?php echo ( $vskb_columns == 'disable' ) ? 'selected' : ''; ?>><?php esc_html_e( 'Disable', 'very-simple-knowledge-base' ); ?></option>
		</select></p>
		<p><?php /* translators: %s: Disable. */ printf( esc_html__( 'You can disable the columns with option %s.', 'very-simple-knowledge-base' ), esc_html__( 'Disable', 'very-simple-knowledge-base' ) ); ?><br>
		<?php esc_html_e( 'This can be handy if you only want to use your own styling.', 'very-simple-knowledge-base' ); ?></p>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'vskb_attributes' ) ); ?>"><?php esc_html_e( 'Attributes', 'very-simple-knowledge-base' ); ?>:</label>
		<textarea class="widefat monospace" rows="3" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'vskb_attributes' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'vskb_attributes' ) ); ?>" placeholder="<?php esc_attr_e( 'Example', 'very-simple-knowledge-base' ); ?>: posts_per_category=&quot;2&quot;"><?php echo wp_kses_post( $vskb_attributes ); ?></textarea></p>
		<p><?php esc_html_e( 'For info and available attributes', 'very-simple-knowledge-base' ); ?> <?php echo '<a href="https://wordpress.org/plugins/very-simple-knowledge-base" rel="noopener noreferrer" target="_blank">'.esc_html__( 'click here', 'very-simple-knowledge-base' ).'</a>'; ?>.</p>
		<?php
	}

	// update widget
	function update( $new_instance, $old_instance ) {
		$instance = array();
		// sanitize input
		$instance['vskb_title'] = sanitize_text_field( $new_instance['vskb_title'] );
		$instance['vskb_text'] = wp_kses_post( $new_instance['vskb_text'] );
		$instance['vskb_columns'] = sanitize_text_field( $new_instance['vskb_columns'] );
		$instance['vskb_attributes'] = sanitize_text_field( $new_instance['vskb_attributes'] );
		return $instance;
	}

	// display widget with knowledge base in frontend
	function widget( $args, $instance ) {
		echo wp_kses_post( $args['before_widget'] );
		if ( ! empty( $instance['vskb_title'] ) ) {
			echo wp_kses_post( $args['before_title'] ) . esc_html( apply_filters( 'widget_title', $instance['vskb_title'] ) ) . wp_kses_post( $args['after_title'] );
		}
		if ( ! empty( $instance['vskb_text'] ) ) {
			echo '<div class="vskb-widget-text">'.wp_kses_post( wpautop( $instance['vskb_text'] ).'</div>' );
		}
		$widget_columns = '1';
		if ( ! empty( $instance['vskb_columns'] ) ) {
			if ( $instance['vskb_columns'] == 'one' ) {
				$widget_columns = '1';
			} else if ( $instance['vskb_columns'] == 'two' ) {
				$widget_columns = '2';
			} else if ( $instance['vskb_columns'] == 'three' ) {
				$widget_columns = '3';
			} else if ( $instance['vskb_columns'] == 'four' ) {
				$widget_columns = '4';
			} else if ( $instance['vskb_columns'] == 'disable' ) {
				$widget_columns = '0';
			}
		}
		$content = '[knowledgebase';
		if ( ! empty( $instance['vskb_attributes'] ) ) {
			$content .= ' '.wp_strip_all_tags( $instance['vskb_attributes'], true );
		}
		$content .= ' columns="'.esc_attr( $widget_columns ).'"';
		$content .= ']';
		echo do_shortcode( $content );
		echo wp_kses_post( $args['after_widget'] );
	}
}
