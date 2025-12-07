<?php
/**
 * Export des données (CSV, PDF, API)
 * 
 * @package Almetal_Analytics
 */

if (!defined('ABSPATH')) {
    exit;
}

class Almetal_Analytics_Export {
    
    /**
     * Exporter en CSV
     */
    public static function to_csv($data, $filename = 'export.csv') {
        if (empty($data)) {
            return false;
        }
        
        // Headers
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        
        // BOM pour Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // En-têtes de colonnes
        fputcsv($output, array_keys($data[0]), ';');
        
        // Données
        foreach ($data as $row) {
            fputcsv($output, $row, ';');
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Exporter en JSON
     */
    public static function to_json($data, $filename = 'export.json') {
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        echo wp_json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Exporter le rapport analytics
     */
    public static function export_analytics_report($period = '30days', $format = 'csv') {
        $stats = Almetal_Analytics_Database::get_stats($period);
        $visits_by_day = Almetal_Analytics_Database::get_visits_by_day($period);
        $top_pages = Almetal_Analytics_Database::get_top_pages($period, 50);
        $sources = Almetal_Analytics_Database::get_traffic_sources($period);
        $devices = Almetal_Analytics_Database::get_devices($period);
        
        $data = array(
            'report_info' => array(
                array(
                    'metric' => 'Période',
                    'value' => $period,
                ),
                array(
                    'metric' => 'Date export',
                    'value' => current_time('mysql'),
                ),
                array(
                    'metric' => 'Total visites',
                    'value' => $stats['total_visits'],
                ),
                array(
                    'metric' => 'Visiteurs uniques',
                    'value' => $stats['unique_visitors'],
                ),
                array(
                    'metric' => 'Taux de rebond',
                    'value' => $stats['bounce_rate'] . '%',
                ),
                array(
                    'metric' => 'Durée moyenne',
                    'value' => gmdate('H:i:s', $stats['avg_duration']),
                ),
            ),
        );
        
        $filename = 'analytics-report-' . date('Y-m-d') . '.' . $format;
        
        if ($format === 'json') {
            $data['visits_by_day'] = $visits_by_day;
            $data['top_pages'] = $top_pages;
            $data['traffic_sources'] = $sources;
            $data['devices'] = $devices;
            self::to_json($data, $filename);
        } else {
            // Pour CSV, on exporte les visites par jour
            self::to_csv($visits_by_day, $filename);
        }
    }
    
    /**
     * Webhook vers service externe
     */
    public static function send_webhook($url, $data, $secret = '') {
        $payload = wp_json_encode($data);
        
        $headers = array(
            'Content-Type' => 'application/json',
        );
        
        // Signature HMAC pour sécurité
        if ($secret) {
            $signature = hash_hmac('sha256', $payload, $secret);
            $headers['X-Webhook-Signature'] = $signature;
        }
        
        $response = wp_remote_post($url, array(
            'headers' => $headers,
            'body' => $payload,
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => $response->get_error_message(),
            );
        }
        
        return array(
            'success' => true,
            'status_code' => wp_remote_retrieve_response_code($response),
            'body' => wp_remote_retrieve_body($response),
        );
    }
    
    /**
     * Sync vers Google Sheets (via API)
     */
    public static function sync_to_sheets($spreadsheet_id, $data, $credentials) {
        // Cette fonction nécessite la librairie Google API
        // À implémenter selon les besoins
        return array('success' => false, 'message' => 'Google Sheets sync not implemented');
    }
    
    /**
     * Sync vers Notion
     */
    public static function sync_to_notion($database_id, $data, $api_key) {
        $url = 'https://api.notion.com/v1/pages';
        
        $headers = array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json',
            'Notion-Version' => '2022-06-28',
        );
        
        // Formater les données pour Notion
        $properties = array();
        foreach ($data as $key => $value) {
            $properties[$key] = array(
                'rich_text' => array(
                    array(
                        'text' => array('content' => (string) $value),
                    ),
                ),
            );
        }
        
        $body = array(
            'parent' => array('database_id' => $database_id),
            'properties' => $properties,
        );
        
        $response = wp_remote_post($url, array(
            'headers' => $headers,
            'body' => wp_json_encode($body),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            return array(
                'success' => false,
                'error' => $response->get_error_message(),
            );
        }
        
        return array(
            'success' => wp_remote_retrieve_response_code($response) === 200,
            'response' => json_decode(wp_remote_retrieve_body($response), true),
        );
    }
}
