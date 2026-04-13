<?php
if (!defined('ABSPATH')) {
    exit;
}

class PegDash_Ajax {

    public function __construct() {
        add_action('wp_ajax_pegdash_get_data', array($this, 'get_data'));
        add_action('wp_ajax_pegdash_save_data', array($this, 'save_data'));
        add_action('wp_ajax_pegdash_delete_data', array($this, 'delete_data'));
    }

    private function check_permission() {
        check_ajax_referer('pegdash_nonce', 'security');

        if ( current_user_can('manage_options') ) return true;

        $allowed_role = get_option('pegdash_allowed_role');
        if ( is_user_logged_in() && !empty($allowed_role) ) {
            $user = wp_get_current_user();
            if ( in_array( $allowed_role, (array) $user->roles ) ) return true;
        }

        wp_send_json_error("Sin autorización para modificar la Base de Datos");
        wp_die();
    }

    public function get_data() {
        $this->check_permission();
        global $wpdb;

        $entity = sanitize_text_field($_POST['entity']);
        $valid_entities = ['campaigns', 'classifications', 'ticket_types', 'adsets', 'ads', 'media', 'ad_logs', 'sales_logs'];
        
        if (!in_array($entity, $valid_entities)) {
            wp_send_json_error('Entidad invalida');
        }

        $table_name = $wpdb->prefix . "pegdash_" . $entity;
        
        $suppress = $wpdb->suppress_errors();
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC");
        $wpdb->suppress_errors($suppress);

        if (!is_array($results)) {
            wp_send_json_success([]);
        }

        // Formatear al estandar JavaScript del frontend (camelCase & Strings para IDs)
        foreach($results as $res) {
            $res->id = (string)$res->id;
            if(isset($res->log_date)) {
                $res->date = $res->log_date;
                unset($res->log_date);
            }
            if(isset($res->campaignId)) $res->campaignId = (string)$res->campaignId;
            if(isset($res->classificationId)) $res->classificationId = (string)$res->classificationId;
            if(isset($res->adsetId)) $res->adsetId = (string)$res->adsetId;
            if(isset($res->adId)) $res->adId = (string)$res->adId;
        }
        
        wp_send_json_success($results);
    }

    public function save_data() {
        $this->check_permission();
        global $wpdb;
        
        $entity = sanitize_text_field($_POST['entity']);
        $payload = stripslashes_deep($_POST['payload']);
        
        if(is_string($payload)) {
            $payload = json_decode($payload, true);
        }

        $table_name = $wpdb->prefix . "pegdash_" . $entity;

        if (isset($payload['date'])) {
            $payload['log_date'] = $payload['date'];
            unset($payload['date']);
        }
        if (isset($payload['timestamp'])) unset($payload['timestamp']);
        if (isset($payload['createdAt'])) unset($payload['createdAt']);
        
        $payload['created_at'] = current_time('mysql');

        $result = $wpdb->insert($table_name, $payload);
        
        if ($result === false) {
            wp_send_json_error('Error SQL Insert (' . $table_name . '): ' . $wpdb->last_error);
        }

        $insert_id = $wpdb->insert_id;
        
        // Retornar al frontend
        if(isset($payload['log_date'])) {
            $payload['date'] = $payload['log_date'];
            unset($payload['log_date']);
        }
        $payload['id'] = (string)$insert_id;
        
        wp_send_json_success($payload);
    }

    public function delete_data() {
        $this->check_permission();
        global $wpdb;
        
        $entity = sanitize_text_field($_POST['entity']);
        $id = intval($_POST['id']);
        
        $table_name = $wpdb->prefix . "pegdash_" . $entity;
        $wpdb->delete($table_name, array('id' => $id));
        
        wp_send_json_success(array('deleted' => true, 'id' => (string)$id));
    }
}
