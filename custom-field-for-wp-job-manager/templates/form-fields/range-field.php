<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$field = $args['field'];
$key   = $args['key'];
// Ensure class is an array before imploding
$class_attr = '';
if ( ! empty( $field['class'] ) ) {
    $class_attr = is_array( $field['class'] ) ? implode( ' ', $field['class'] ) : $field['class'];
}
?>

    <input
        type="range"
        name="<?php echo esc_attr( $key ); ?>"
        id="<?php echo esc_attr( $key ); ?>"
        value="<?php echo esc_attr( $field['value'] ); ?>"
        class="<?php echo esc_attr( $class_attr ); ?> input-text"
        placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>"
        <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?>
        <?php
        // Support both `attributes` and `custom_attributes` (we set custom_attributes in frontend)
        $attributes = array();
        if ( ! empty( $field['attributes'] ) && is_array( $field['attributes'] ) ) {
            $attributes = $field['attributes'];
        }
        if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {
            $attributes = array_merge( $attributes, $field['custom_attributes'] );
        }
        if ( ! empty( $attributes ) ) {
            foreach ( $attributes as $attr => $value ) {
                echo esc_attr( $attr ) . '="' . esc_attr( $value ) . '" ';
            }
        }
        ?>
    />
    <?php if ( ! empty( $field['description'] ) ) : ?>
        <small class="description"><?php echo esc_html( $field['description'] ); ?></small>
    <?php endif; ?>
