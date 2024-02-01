<?php
/**
 * MC4W Sync Products Manually
 *
 * @package       MC4W
 * @author        Abid R.
 *
 * @wordpress-plugin
 * Plugin Name:       MC4W Sync Products Manually
 * Plugin URI:        https://www.hztech.biz
 * Description:       Sync your products to Mailchimp manually
 * Author:            Abid R.
 */

function render_mailchimp_sync_manually_metabox() {
	add_meta_box( 'mailchimp-sync-manually', __( 'Sync Mailchimp Product', 'textdomain' ), 'render_mailchimp_sync_manually', 'product', 'side' );
}
add_action( 'add_meta_boxes', 'render_mailchimp_sync_manually_metabox' );

function render_mailchimp_sync_manually($post) {
	echo '<p>Make sure you have saved the product before hitting the sync button</p>';
	echo '<p><button class="button button-primary" id="msp-button-sync-now">Sync Now</button></p>';
	echo '<script>jQuery(function($) { $("#msp-button-sync-now").on("click", function() { $("#msp-button-sync-now").attr("disabled", true); $.get("/wp-admin/admin-ajax.php?action=mailchimp_sync_manually&resync='.$post->ID.'", function() { $("#msp-button-sync-now").removeAttr("disabled"); }) }); });</script>';
}

add_action('wp_ajax_mailchimp_sync_manually', 'ajax_action_mailchimp_sync_manually');

function ajax_action_mailchimp_sync_manually() {
	if(isset($_GET['resync']) && class_exists('MailChimp_WooCommerce_Single_Product')) {
		$id = $_GET['resync'];
		$msp = new MailChimp_WooCommerce_Single_Product($id, "");
		$msp->process();
		echo json_encode(['status' => 1]);
		die;
	}
}
