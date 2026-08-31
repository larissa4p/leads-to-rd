<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class RDS_Settings {

    const OPTION = 'rds_settings';

    public function __construct() {
        add_action( 'admin_menu',            [ $this, 'add_menu' ] );
        add_action( 'admin_init',            [ $this, 'register_settings' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
    }

    public function add_menu(): void {
        add_options_page(
            'RD Station',
            'RD Station',
            'manage_options',
            'rd-station',
            [ $this, 'render_page' ]
        );
    }

    public function register_settings(): void {
        register_setting( 'rds_group', self::OPTION, [
            'sanitize_callback' => [ $this, 'sanitize' ],
        ] );
    }

    public function sanitize( array $input ): array {
        return [
            'token'      => sanitize_text_field( $input['token'] ?? '' ),
            'identifier' => sanitize_text_field( $input['identifier'] ?? 'wordpress-form' ),
            'cf7'        => ! empty( $input['cf7'] ) ? 1 : 0,
            'wpforms'    => ! empty( $input['wpforms'] ) ? 1 : 0,
        ];
    }

    public function enqueue_styles( string $hook ): void {
        if ( $hook !== 'settings_page_rd-station' ) return;
        wp_enqueue_style( 'rds-admin', RDS_URL . 'assets/css/admin.css', [], RDS_VERSION );
    }

    public static function get( string $key, mixed $default = '' ): mixed {
        $opts = get_option( self::OPTION, [] );
        return $opts[ $key ] ?? $default;
    }

    public function render_page(): void {
        $opts  = get_option( self::OPTION, [] );
        $token = $opts['token']      ?? '';
        $id    = $opts['identifier'] ?? 'wordpress-form';
        $cf7   = $opts['cf7']        ?? 1;
        $wpf   = $opts['wpforms']    ?? 1;
        $logs  = RDS_Log::get_recent( 20 );
        ?>
        <div class="wrap rds-wrap">
            <h1>RD Station Integration</h1>

            <form method="post" action="options.php">
                <?php settings_fields( 'rds_group' ); ?>

                <div class="rds-card">
                    <h2>Configurações</h2>

                    <table class="form-table">
                        <tr>
                            <th><label for="rds_token">API Token</label></th>
                            <td>
                                <input type="password" id="rds_token" name="<?= self::OPTION ?>[token]"
                                    value="<?= esc_attr( $token ) ?>" class="regular-text" autocomplete="off" />
                                <p class="description">Encontre em RD Station → Integrações → API Token.</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="rds_id">Identificador padrão</label></th>
                            <td>
                                <input type="text" id="rds_id" name="<?= self::OPTION ?>[identifier]"
                                    value="<?= esc_attr( $id ) ?>" class="regular-text" />
                                <p class="description">Nome da conversão que aparece no RD Station.</p>
                            </td>
                        </tr>
                        <tr>
                            <th>Integrações</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?= self::OPTION ?>[cf7]" value="1" <?= checked( 1, $cf7, false ) ?> />
                                    Contact Form 7
                                </label>
                                <br>
                                <label>
                                    <input type="checkbox" name="<?= self::OPTION ?>[wpforms]" value="1" <?= checked( 1, $wpf, false ) ?> />
                                    WPForms
                                </label>
                            </td>
                        </tr>
                    </table>

                    <?php submit_button( 'Salvar configurações' ); ?>
                </div>
            </form>

            <div class="rds-card rds-log">
                <h2>Últimos envios</h2>

                <?php if ( empty( $logs ) ) : ?>
                    <p class="rds-empty">Nenhum envio registrado ainda.</p>
                <?php else : ?>
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Formulário</th>
                                <th>E-mail</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ( $logs as $row ) : ?>
                                <tr>
                                    <td><?= esc_html( $row['created_at'] ) ?></td>
                                    <td><?= esc_html( $row['form'] ) ?></td>
                                    <td><?= esc_html( $row['email'] ) ?></td>
                                    <td>
                                        <span class="rds-badge <?= $row['success'] ? 'rds-ok' : 'rds-err' ?>">
                                            <?= $row['success'] ? 'OK' : 'Erro ' . esc_html( $row['status'] ) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}
