<?php
namespace OneElements\Admin\Partials;

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://themexclub.com
 * @since      1.0.0
 *
 * @package    One_Elements
 * @subpackage One_Elements/admin/partials
 */
?>

<div class="app-container">
    <div class="main-container">
        
        <div id="gs-team-shortcode-app">

            <header class="gs-team-header">
                <div class="gs-containeer-f">
                    <div class="gs-roow">
                        <div class="logo-area col-xs-6">
                            <!-- <router-link to="/"><img src="" alt="GS Team Members Logo"></router-link> -->
                        </div>
                        <div class="menu-area col-xs-6 text-right">
                            <ul>
                                <router-link to="/" tag="li"><a><?php _e( 'Shortcodes', 'gsteam' ); ?></a></router-link>
                                <router-link to="/shortcode" tag="li"><a><?php _e( 'Create New', 'gsteam' ); ?></a></router-link>
                                <router-link to="/preferences" tag="li"><a><?php _e( 'Preferences', 'gsteam' ); ?></a></router-link>
                                <router-link to="/demo-data" tag="li"><a><?php _e( 'Demo Data', 'gsteam' ); ?></a></router-link>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="gs-team-app-view-container">
                <router-view :key="$route.fullPath"></router-view>
            </div>

        </div>
        
    </div>
</div>