<?php

namespace Listings\Jobs\Admin;

use Listings\Jobs\Admin\Writepanels\JobDetails;

class Admin
{
    public function __construct()
    {
        $this->jobdetails = new JobDetails();
        $this->cpt = new Cpt();

        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style( 'listings-jobs-admin', LISTINGS_JOBS_PLUGIN_URL . '/assets/css/admin.css' );
    }
}