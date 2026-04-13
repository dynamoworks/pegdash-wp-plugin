<?php
if (!defined('ABSPATH')) {
    exit;
}

class PegDash_DB {
    public static function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Se requiere para usar la función dbDelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE {$wpdb->prefix}pegdash_campaigns (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            goal mediumint(9) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;
        
        CREATE TABLE {$wpdb->prefix}pegdash_classifications (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            color varchar(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;
        
        CREATE TABLE {$wpdb->prefix}pegdash_ticket_types (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}pegdash_adsets (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            campaignId varchar(255) NOT NULL,
            classificationId varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            audience varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}pegdash_ads (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            adsetId varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}pegdash_media (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            adId varchar(255) NOT NULL,
            name varchar(255) NOT NULL,
            url text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}pegdash_ad_logs (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            adsetId varchar(255) NOT NULL,
            log_date date NOT NULL,
            spend decimal(10,2) NOT NULL,
            leads mediumint(9) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;

        CREATE TABLE {$wpdb->prefix}pegdash_sales_logs (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            campaignId varchar(255) NOT NULL,
            type varchar(255) NOT NULL,
            log_date date NOT NULL,
            qty mediumint(9) NOT NULL,
            complimentaries mediumint(9) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        dbDelta($sql);
    }
}
