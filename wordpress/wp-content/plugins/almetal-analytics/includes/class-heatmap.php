<?php
/**
 * Gestion des Heatmaps
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Heatmap {
    
    /**
     * Enregistrer un clic
     */
    public static function track_click($data) {
        global $wpdb;
        
        $page_url = sanitize_text_field($data['url'] ?? '');
        $page_hash = md5($page_url);
        
        return $wpdb->insert(
            $wpdb->prefix . 'almetal_analytics_heatmap',
            array(
                'page_url' => $page_url,
                'page_hash' => $page_hash,
                'click_x' => intval($data['x'] ?? 0),
                'click_y' => intval($data['y'] ?? 0),
                'viewport_width' => intval($data['viewport_width'] ?? 0),
                'viewport_height' => intval($data['viewport_height'] ?? 0),
                'element_selector' => sanitize_text_field($data['selector'] ?? ''),
                'device_type' => sanitize_text_field($data['device_type'] ?? 'desktop'),
            ),
            array('%s', '%s', '%d', '%d', '%d', '%d', '%s', '%s')
        );
    }
    
    /**
     * Obtenir les données de heatmap pour une page
     */
    public static function get_heatmap_data($page_url, $device_type = 'all', $period = '30days') {
        global $wpdb;
        
        $page_hash = md5($page_url);
        $date_from = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        if ($period === '7days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-7 days'));
        } elseif ($period === '90days') {
            $date_from = date('Y-m-d H:i:s', strtotime('-90 days'));
        }
        
        $where_device = '';
        if ($device_type !== 'all') {
            $where_device = $wpdb->prepare(" AND device_type = %s", $device_type);
        }
        
        // Obtenir les clics groupés par zone
        $clicks = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                ROUND(click_x / 10) * 10 as x,
                ROUND(click_y / 10) * 10 as y,
                COUNT(*) as count,
                viewport_width,
                viewport_height
             FROM {$wpdb->prefix}almetal_analytics_heatmap 
             WHERE page_hash = %s AND created_at >= %s {$where_device}
             GROUP BY ROUND(click_x / 10), ROUND(click_y / 10), viewport_width, viewport_height
             ORDER BY count DESC",
            $page_hash, $date_from
        ), ARRAY_A);
        
        // Obtenir les éléments les plus cliqués
        $elements = $wpdb->get_results($wpdb->prepare(
            "SELECT 
                element_selector,
                COUNT(*) as clicks
             FROM {$wpdb->prefix}almetal_analytics_heatmap 
             WHERE page_hash = %s AND created_at >= %s AND element_selector != '' {$where_device}
             GROUP BY element_selector
             ORDER BY clicks DESC
             LIMIT 20",
            $page_hash, $date_from
        ), ARRAY_A);
        
        return array(
            'page_url' => $page_url,
            'period' => $period,
            'device_type' => $device_type,
            'total_clicks' => array_sum(array_column($clicks, 'count')),
            'clicks' => $clicks,
            'top_elements' => $elements,
        );
    }
    
    /**
     * Obtenir les pages avec le plus de données heatmap
     */
    public static function get_tracked_pages($limit = 20) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT 
                page_url,
                COUNT(*) as total_clicks,
                MAX(created_at) as last_click
             FROM {$wpdb->prefix}almetal_analytics_heatmap 
             GROUP BY page_url
             ORDER BY total_clicks DESC
             LIMIT %d",
            $limit
        ), ARRAY_A);
    }
}
