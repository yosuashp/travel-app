<?php
if (!defined('BASEPATH'))
    exit ('No direct script access allowed');

class Flight_Bookings extends MX_Controller {
    function __construct() {
        modules :: load('Admin');
        $this->load->model('Admin/Flights_Bookings_model');
        $chkadmin = modules :: run('Admin/validadmin');
        if (!$chkadmin) {
            $this->session->set_userdata('prevURL', current_url());
            redirect('admin');
        }
        $this->data['app_settings'] = $this->Settings_model->get_settings_data();
        $checkingadmin = $this->session->userdata('pt_logged_admin');
        if (!empty ($checkingadmin)) {
            $this->data['userloggedin'] = $this->session->userdata('pt_logged_admin');
        }
        else {
            $this->data['userloggedin'] = $this->session->userdata('pt_logged_supplier');
        }
        if (!empty ($checkingadmin)) {
            $this->data['adminsegment'] = "admin";
        }
        else {
            $this->data['adminsegment'] = "supplier";
        }
        $this->load->model('Admin/Bookings_model');
        $this->data['userloggedin'] = $this->session->userdata('pt_logged_admin');
        $this->data['isadmin'] = $this->session->userdata('pt_logged_admin');
        $this->data['isSuperAdmin'] = $this->session->userdata('pt_logged_super_admin');
        $this->role = $this->session->userdata('pt_role');
        $this->data['role'] = $this->role;
        $this->data['addpermission'] = true;
        if($this->role == "admin"){
        $this->editpermission = pt_permissions("editbooking", $this->data['userloggedin']);
        $this->deletepermission = pt_permissions("deletebooking", $this->data['userloggedin']);
        $this->data['addpermission'] = pt_permissions("addbooking", $this->data['userloggedin']);
        }

        $this->lang->load("back", "en");
        }

   function index() {
    $booking_status = '';
    $booking_payment_status = '';
    if ($this->uri->segment(4) == 'pending' || $this->uri->segment(4) == 'confirmed'||$this->uri->segment(4) == 'cancelled') {
        $booking_status = $this->uri->segment(4);
    }elseif ($this->uri->segment(4) == 'unpaid' || $this->uri->segment(4) == 'paid'||$this->uri->segment(4) == 'disputed') {
        $booking_payment_status = $this->uri->segment(4);
    }
    $this->data['all_booking'] = $this->Flights_Bookings_model->admin_get_flights_bookings($booking_status,$booking_payment_status);
        $this->data['main_content'] = 'bookings';
        $this->data['page_title'] = 'Flights Bookings View';
        $this->load->view('template', $this->data);
    }

}