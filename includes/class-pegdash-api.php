<?php
if (!defined('ABSPATH')) {
    exit;
}

class PegDash_API {
    
    private $tables = [
        'campaigns', 'classifications', 'ticket_types', 
        'adsets', 'ads', 'media', 'ad_logs', 'sales_logs'
    ];

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        foreach ($this->tables as $entity) {
            // Obtener todos los registros GET
            register_rest_route('pegdash/v1', '/' . $entity, array(
                array(
                    'methods'  => WP_REST_Server::READABLE,
                    'callback' => array($this, 'get_items'),
                    'permission_callback' => array($this, 'check_permission'),
                    'args'     => array('entity' => array('default' => $entity))
                ),
                // Crear un registro POST
                array(
                    'methods'  => WP_REST_Server::CREATABLE,
                    'callback' => array($this, 'create_item'),
                    'permission_callback' => array($this, 'check_permission'),
                    'args'     => array('entity' => array('default' => $entity))
                )
            ));

            // Eliminar registro por ID
            register_rest_route('pegdash/v1', '/' . $entity . '/(?P<id>\d+)', array(
                array(
                    'methods'  => WP_REST_Server::DELETABLE,
                    'callback' => array($this, 'delete_item'),
                    'permission_callback' => array($this, 'check_permission'),
                    'args'     => array('entity' => array('default' => $entity))
                )
            ));
        }
    }

    public function check_permission() {
        if ( current_user_can('manage_options') ) return true;
        
        $allowed_role = get_option('pegdash_allowed_role');
        if ( is_user_logged_in() && !empty($allowed_role) ) {
            $user = wp_get_current_user();
            if ( in_array( $allowed_role, (array) $user->roles ) ) return true;
        }
        return false;
    }

    public function get_items($request) {
        global $wpdb;
        $entity = $request->get_default('entity');
        $table_name = $wpdb->prefix . "pegdash_" . $entity;
        
        $results = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC");
        
        if (!is_array($results)) {
            return rest_ensure_response([]);
        }

        // Mapear al estandar JavaScript del frontend (camelCase & Strings para IDs)
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
        
        return rest_ensure_response($results);
    }

    public function create_item($request) {
        global $wpdb;
        $entity = $request->get_default('entity');
        $table_name = $wpdb->prefix . "pegdash_" . $entity;
        
        $params = $request->get_json_params();
        if (!$params) $params = $request->get_params(); // Fallback to raw params
        
        unset($params['entity']);
        unset($params['id']); // Por seguridad
        
        // Adaptación Firebase -> MySQL
        if (isset($params['date'])) {
            $params['log_date'] = $params['date'];
            unset($params['date']);
        }
        if (isset($params['timestamp'])) {
            unset($params['timestamp']); 
        }
        if (isset($params['createdAt'])) {
            unset($params['createdAt']);
        }

        $result = $wpdb->insert($table_name, $params);
        if ($result === false) {
            return new WP_Error('db_insert_error', 'Error insertando en la Base de Datos', array('status' => 500));
        }

        $insert_id = $wpdb->insert_id;
        
        // Retornar al objeto recien insertado para que actualice la vista sin recargar
        // Mapeo inverso de log_date a date
        if(isset($params['log_date'])) {
            $params['date'] = $params['log_date'];
            unset($params['log_date']);
        }
        $params['id'] = (string)$insert_id;
        return rest_ensure_response($params);
    }

    public function delete_item($request) {
        global $wpdb;
        $entity = $request->get_default('entity');
        $id = $request->get_param('id');
        $table_name = $wpdb->prefix . "pegdash_" . $entity;
        
        $wpdb->delete($table_name, array('id' => $id));
        return rest_ensure_response(array('deleted' => true, 'id' => $id));
    }
}
