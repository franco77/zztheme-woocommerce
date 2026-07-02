<?php
/**
 * Walker de menú con soporte de submenús accesibles.
 * Añade aria-expanded y botones toggle para submenús en mobile.
 */

if (!defined('ABSPATH')) {
    exit;
}

class ZZTheme_Walker_Nav_Menu extends Walker_Nav_Menu {
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent  = str_repeat("\t", $depth);
        $output .= "\n{$indent}<ul class=\"sub-menu depth-{$depth}\">\n";
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes      = empty($item->classes) ? [] : (array) $item->classes;
        $classes[]    = 'menu-item-' . $item->ID;
        $has_children = in_array('menu-item-has-children', $classes, true);

        $class_names = join(' ', array_filter(apply_filters('nav_menu_css_class', $classes, $item, $args, $depth)));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= '<li' . $class_names . '>';

        $atts           = [];
        $atts['href']   = !empty($item->url) ? $item->url : '';
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if ($value !== '') {
                $attributes .= ' ' . $attr . '="' . esc_attr($value) . '"';
            }
        }

        $title  = apply_filters('the_title', $item->title, $item->ID);
        $output .= '<a' . $attributes . '>' . esc_html($title) . '</a>';

        if ($has_children) {
            $output .= '<button class="submenu-toggle" aria-expanded="false" aria-label="' . esc_attr__('Abrir submenú', 'zztheme') . '">+</button>';
        }
    }
}
