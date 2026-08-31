<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_Log {

    const TABLE = 'rdstation_log';

    public static function create_table(): void {
        global $wpdb;

        $table   = $wpdb->prefix . self::TABLE;
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$table} (
            id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            form        VARCHAR(200)    NOT NULL,
            email       VARCHAR(200)    NOT NULL DEFAULT '',
            status      SMALLINT        NOT NULL DEFAULT 0,
            success     TINYINT(1)      NOT NULL DEFAULT 0,
            response    TEXT,
            created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset};";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta( $sql );
    }

    public static function insert( string $form, string $email, array $result ): void {
        global $wpdb;

        $wpdb->insert(
            $wpdb->prefix . self::TABLE,
            [
                'form'      => $form,
                'email'     => $email,
                'status'    => $result['status'],
                'success'   => $result['success'] ? 1 : 0,
                'response'  => wp_json_encode( $result['body'] ),
            ],
            [ '%s', '%s', '%d', '%d', '%s' ]
        );
    }

    public static function get_recent( int $limit = 50 ): array {
        global $wpdb;
        $table = $wpdb->prefix . self::TABLE;
        return $wpdb->get_results(
            $wpdb->prepare( "SELECT * FROM {$table} ORDER BY created_at DESC LIMIT %d", $limit ),
            ARRAY_A
        );
    }
}
