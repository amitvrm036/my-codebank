<?php
/*********
* Author: 
* Date  : 
* Modified By: 
* Modified Date:
* 
* Purpose:
*  Controller For ## Management
* 
* @package 
* @subpackage 
* 
* @link InfController.php 
* @link Base_Controller.php
* @link model/##.php
* @link views/##
*/

class Profit_report extends Admin_base_Controller
{

	function __construct()
	 {
		try
		{
		    parent::__construct();
			parent::_check_admin_login();
            # loading reqired model & helpers...
			$this->load->model('cart_model');
			$this->load->model('order_model');
            $this->data['navclass']	= "1";
		    $this->load->model('customer_model');
			
		}
        catch(Exception $err_obj)
        {
            show_error($err_obj->getMessage());
        }  

	}

	function index() 
	{
		try
		 {
		    
			$data = $this->data;
            
            # adjusting header & footer sections [Start]...
			parent::_set_title('::: Randapharma :::');
			parent::_set_meta_desc("::: Randapharma :::");
			parent::_set_meta_keywords("::: Randapharma :::");
			
		   // parent::_add_js_arr( array(''=>'header') );										
			parent::_add_css_arr( array('css/admin.css') );
			
			parent::_add_js_arr( array(  'js/jquery-1.9.1.js',
										'js/jquery-migrate-1.2.1.min.js',
										'js/jquery-ui.js',
										'js/jquery.form.js',
										'js/json2.js', 
										'js/ModalDialog.js',
										'js/jquery.blockUI.js',
										'js/utilities.js',
										'js/jquery.autofill.js',
									  ));
			parent::_add_css_arr( array('css/jquery-ui-1.8.2.custom.css') );
			
			$data['navclass']	= "4";
			$data['subclass']	= "1";
			$data['heading'] = 'New Orders';
			# adjusting header & footer sections [End]...
			
			if(count($_POST)>0)
				$yr	= $this->input->post('year');
			else
				$yr	= date('Y');
			for($month=1;$month<13;$month++)
            {
				$data['result'][$month] = $this->order_model->get_all_order_by_month($month,$yr);
			}
			#pr($data['result']);
			$data['postyr']	= $yr;
			
			# rendering the view file...
			/*ob_start();
            $this->show_ajax_orders();#$current_page
            $data['result_content'] = ob_get_contents(); //pr($data['result_content']);
            ob_end_clean();*/
			
			
            $view_file = "admin/order/profit_report.phtml";
            parent::_render($data, $view_file);
		}
        catch(Exception $err_obj)
        {
            show_error($err_obj->getMessage());
        } 
		
	} 
	
	public function show_ajax_orders()
	{
		try
        {
			$order_by	= "";
			//$wh	= " AND sub.i_admin_customer_payment_status_update=0 ";
			//OR sub.i_admin_supplier_payment_status_update=0
			$wh	= " AND (cd.i_payment_status < 2 ) AND c.i_is_quick_order=0";
			$result = $this->order_model->get_new_orders($wh,'','',' cd.i_order_id DESC');
			
			$resultCount = count($result);
		
			$total_rows = $this->order_model->get_count_new_orders($wh);
            
            
            #Jquery Pagination Starts
            $this->load->library('jquery_pagination');
            $config['base_url'] = base_url()."order/order/show_ajax_orders/";
            $config['total_rows'] = $total_rows;
            $config['per_page'] = $this->pagination_per_page;
            $config['uri_segment'] = 4;
            $config['num_links'] = 9;
            $config['page_query_string'] = false;
            $config['prev_link'] = '&laquo;';
            $config['next_link'] = '&raquo;';

            $config['cur_tag_open'] = '<li><a href="javascript:void(0)" class="select">';
            $config['cur_tag_close'] = '</a></li>';

            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';

            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';

            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $config['div'] = '#table_content'; /* Here #content is the CSS selector for target DIV */
            $config['js_bind'] = "showBusyScreen(); "; /* if you want to bind extra js code */
            $config['js_rebind'] = "hideBusyScreen(); "; /* if you want to rebind extra js code */

            $this->jquery_pagination->initialize($config);
            $data['page_links'] = $this->jquery_pagination->create_links();

            // getting   listing...
            $data['info_arr'] = $result;
            $data['no_of_result'] = $total_rows;
            $data['current_page'] = $page;
          	$data['datepicker_min_date']	    = date('d/m/Y', mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));
            # loading the view-part...
          	echo  $this->load->view('admin/order/ajax/order_list_ajax.phtml', $data,TRUE);
         }
        catch(Exception $err_obj)
        {
            show_error($err_obj->getMessage());
        } 
        
	}
	function detail($month,$yr) 
	{
		try
		 {
		   
			$data = $this->data;
            
            # adjusting header & footer sections [Start]...
			parent::_set_title('::: Randapharma :::');
			parent::_set_meta_desc("::: Randapharma :::");
			parent::_set_meta_keywords("::: Randapharma :::");
			
		   // parent::_add_js_arr( array(''=>'header') );										
			parent::_add_css_arr( array('css/admin.css') );
			
			parent::_add_js_arr( array(  'js/jquery-1.9.1.js',
										'js/jquery-migrate-1.2.1.min.js',
										'js/jquery-ui.js',
										'js/jquery.form.js',
										'js/json2.js', 
										'js/ModalDialog.js',
										'js/jquery.blockUI.js',
										'js/utilities.js',
										'js/jquery.autofill.js',
									  ));
			parent::_add_css_arr( array('css/jquery-ui-1.8.2.custom.css') );
			
			$data['navclass']	= "4";
			$data['subclass']	= "1";
			$data['heading'] = 'New Orders';
			# adjusting header & footer sections [End]...
			$nmonth = date("m", strtotime($month));
			$data['result'] = $this->order_model->get_all_order_by_date($nmonth,$yr);
			$data['year']	= $yr;
			$view_file = "admin/order/profit_report_detail.phtml";
            parent::_render($data, $view_file);
		}
        catch(Exception $err_obj)
        {
            show_error($err_obj->getMessage());
        } 
		
	} 
}   // end of controller...
