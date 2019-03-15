<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class FEP_Admin_Settings {

    private $settings_tabs;

    function __construct() {

        $this->settings_tabs = new FEP_Settings_API;

        add_action( 'admin_init', [ $this, 'admin_init' ], 10 );
        add_action( 'admin_menu', [ $this, 'admin_menu' ], 30 );



    }

    function admin_init() {

        //set the settings
        $this->settings_tabs->set_sections( $this->fep_admin_sections() );
        $this->settings_tabs->set_fields( $this->fep_admin_settings() );

        //initialize settings
        $this->settings_tabs->admin_init();

    }

    function admin_menu() {

        $my_page = add_submenu_page(
            'elementor',
            __( 'Flexible Elementor Panel', 'fep' ),
    		__( 'FEP Settings', 'fep' ),
            'manage_options',
            'fep-options',
            [ $this, 'plugin_page' ]

        );

		// Load the JS conditionally
		add_action( 'load-' . $my_page, [ $this, 'load_admin_enqueue' ] );

    }

	// This function is only called when our plugin's page loads!
    function load_admin_enqueue(){

        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_fep_admin' ] );

    }

    function fep_admin_sections() {


        $sections = [

            [

                'id'    => 'fep_informations',
                'title' => esc_html__( 'Informations', 'fep' )

            ],
			[

				'id'    => 'fep_how_to_configure',
				'title' => esc_html__( 'How to configure it?', 'fep' )

			],
			[

				'id'    => 'fep_divers',
				'title' => esc_html__( 'Divers', 'fep' )

			],
        ];

        return apply_filters('fep_admin_sections', array_merge( $sections ) );

    }



    function fep_admin_settings() {



		$settings = FEP_Informations_Tab::section();
		$settings2 = FEP_HowToConfigure_Tab::section();
		$settings3 = FEP_Divers_Tab::section();

        return apply_filters('fep_admin_settings', array_merge( $settings, $settings2, $settings3 ) );

    }





    function plugin_page() {

		$html_fep_title = '<div class="title">';
			$html_fep_title .= '<h1>' . __('Flexible Elementor Panel','fep') . '</h1>';
			$html_fep_title .= '<h2>' . __('The Ultimate Backend Solution for Elementor','fep') . '</h2>';
		$html_fep_title .='</div>';

        echo '<div class="wrap" id="fep-admin">';
            echo $html_fep_title;
            $this->save_message();
            $this->settings_tabs->show_navigation();
            $this->settings_tabs->show_forms();
            //$this->footer_info();
        echo '</div>';

    }

    function save_message() {

        if( isset($_GET['settings-updated']) ) { ?>

            <div class="updated notice is-dismissible">

                <p><strong><?php esc_html_e('Your settings have been saved.', 'fep') ?></strong></p>

            </div>

            <?php

        }

    }


    function footer_info() {

        ?>

        <div class="fep-options-footer-info">

			<p><?php //_e('footer text', 'fep'); ?></p>

        </div>

        <?php

    }


	/**
	* Register and enqueue a custom stylesheet in the WordPress admin.
	*/
	public function enqueue_fep_admin() {

		// style
		wp_enqueue_style( 'flexible-elementor-panel-admin', plugins_url( '/assets/css/flexible-elementor-panel-admin.css', __FILE__ ), array(), constant( 'FEP_VERSION' ), 'all' );

		// scripts
		wp_enqueue_script( 'fep-functions-admin-js', plugins_url( '/assets/js/fep-functions-admin.js', __FILE__ ), false, constant( 'FEP_VERSION' ), true );
		wp_enqueue_script( 'flexible-elementor-panel-admin-js', plugins_url( '/assets/js/flexible-elementor-panel-admin.js', __FILE__ ), false, constant( 'FEP_VERSION' ), true );

	}

}
new FEP_Admin_Settings();
