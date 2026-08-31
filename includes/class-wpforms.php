<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_WPForms {

    public function __construct() {
        add_action( 'wpforms_process_complete', [ $this, 'on_submit' ], 10, 4 );
    }

    /**
     * @param array $fields     Campos preenchidos.
     * @param array $entry      Dados brutos do formulário.
     * @param array $form_data  Configuração do formulário.
     * @param int   $entry_id   ID da entrada salva.
     */
    public function on_submit( array $fields, array $entry, array $form_data, int $entry_id ): void {
        if ( ! RDS_Settings::get( 'wpforms', 1 ) ) return;

        $token = RDS_Settings::get( 'token' );
        if ( ! $token ) return;

        $contact = $this->extract_contact( $fields );
        if ( empty( $contact['email'] ) ) return;

        $identifier = ! empty( $form_data['settings']['form_title'] )
            ? sanitize_title( $form_data['settings']['form_title'] )
            : RDS_Settings::get( 'identifier', 'wordpress-form' );

        $api    = new RDS_API( $token );
        $result = $api->convert( $identifier, $contact );

        RDS_Log::insert( $identifier, $contact['email'], $result );
    }

    private function extract_contact( array $fields ): array {
        $contact = [];

        foreach ( $fields as $field ) {
            $type  = $field['type'] ?? '';
            $value = sanitize_text_field( $field['value'] ?? '' );

            if ( ! $value ) continue;

            if ( $type === 'email' && empty( $contact['email'] ) ) {
                $contact['email'] = sanitize_email( $value );
            } elseif ( in_array( $type, [ 'name', 'text' ], true ) && empty( $contact['name'] ) ) {
                $label = strtolower( $field['name'] ?? '' );
                if ( str_contains( $label, 'nome' ) || str_contains( $label, 'name' ) ) {
                    $contact['name'] = $value;
                }
            } elseif ( $type === 'phone' && empty( $contact['mobile_phone'] ) ) {
                $contact['mobile_phone'] = $value;
            }
        }

        return $contact;
    }
}
