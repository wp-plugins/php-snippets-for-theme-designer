<?php
/*
Plugin Name: php snippet for theme designers
Plugin URI: http://plugin.php-web.net/wp/tema-dezaina-no-tame-no-phpsunipetto
Description: This plugin diplays a is_page()/is_category()/is_tag() snippet for page/category/tag list. You can go to the page/category/tag list in the admin area, and you can copy one (and paste it to a theme file).
Version: 2.0
Author: Fumito Mizuno
Author URI: http://php-web.net/
License: GPL ver.2 or later
*/

function readonly_textarea( $data, $rows=3, $cols=30 ) {
    $data = esc_html( $data );
    $rows = intval( $rows );
    $cols = intval( $cols );
    return '<textarea readonly rows="' . $rows . '" cols="' . $cols . '" onclick="this.focus();this.select()">' . $data . '</textarea>';
}

function create_snippet_if( $condition, $id, $slug ) {
    $condition = esc_html( $condition );
    $id = esc_html( $id );
    $slug = esc_html( urldecode( $slug ) );
    $snippetformat = '&lt;?php if ( %1$s ( \'%2$s\' ) ): // %3$s  ?&gt; ' . "\n\n" . '&lt;?php endif; // %1$s( \'%2$s\' ) %3$s  ?&gt;';
    return sprintf($snippetformat, $condition, $id, $slug);
}

function create_snippet_url( $func_name, $id, $slug ) {
    $func_name = esc_html( $func_name );
    $id = esc_html( $id );
    $slug = esc_html( urldecode( $slug ) );
    $snippetformat = '&lt;?php echo esc_url( %1$s ( \'%2$s\' ) ); // %3$s  ?&gt; ';
    return sprintf($snippetformat, $func_name, $id, $slug);
}

function add_snippet_column( $defaults ) {
       $defaults['linksnippet'] = __('URL') ;
       $defaults['ifsnippet'] = __('IF') ;
       return $defaults;
}
add_filter('manage_pages_columns', 'add_snippet_column');
function add_categories_snippet_column( $defaults ) {
       $defaults['ifsnippet'] = __('IF') ;
       return $defaults;
}
add_filter('manage_edit-category_columns', 'add_categories_snippet_column');
add_filter('manage_edit-post_tag_columns', 'add_categories_snippet_column');

function add_snippet_text($column_name, $id) {
    if( $column_name == 'ifsnippet' ) {
        $pageinfo = get_page( $id );
        $output = create_snippet_if( 'is_page', $pageinfo->ID, $pageinfo->post_name );
        print readonly_textarea( $output );
    } elseif( $column_name == 'linksnippet' ) {
        $pageinfo = get_page( $id );
        $output = create_snippet_url( 'get_page_link', $pageinfo->ID, $pageinfo->post_name );
        print readonly_textarea( $output );
    }

}
add_action('manage_pages_custom_column', 'add_snippet_text', 10, 2);
function add_categories_snippet_text($null,$column_name, $id) {
        $cat_name = get_cat_name($id);
        $output = create_snippet_if( 'is_category', $id, $cat_name );
        return readonly_textarea($output);
}
add_filter('manage_category_custom_column', 'add_categories_snippet_text', 10, 3);
function add_post_tag_snippet_text($null,$column_name, $id) {
        $tag_array = get_term_by('id',$id,'post_tag');
        $output = create_snippet_if( 'is_tag', $id, $tag_array->name );
        return readonly_textarea($output);
}
add_filter('manage_post_tag_custom_column', 'add_post_tag_snippet_text', 10, 3);
