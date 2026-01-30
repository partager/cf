<?php
if (isset($_POST['check_by_zap']) && isset($_POST['cf_zap_auth'])) {
	$page = "callback_api";
}
$protected_links = [
	"create_config", "login", "forgot_password", "logout", "all_funnels",
	"membership_funnels", "optins", "members", "create_funnel", "page_builder",
	"autores_dashboard", "autores_records", "smtp_create", "smtp_table", "createlist",
	"listrecords", "plugins", "products", "cod_manage", "sales",
	"media", "compose_mail", "sequence", "sequence_records","sequences", "createmultiuser",
	"multiuser_table", "do_payment|do_payment_execute", "schedule_api|schedule_api_runserver", "mail_send", "mail_track",
	"export_csv", "download", "dashboard", "settings", "app_guide",
	"sentemailsdetails", "do_unsubscribe", "do_redirect", "gdpr", "analysis",
	"api_request", "ajax", "callback_api", "data_requests", "load_static_scripts","aiwriter",
	"mmbr_logout", "show_cf_narand", "install_update_dependencies", "no_permission"
];

function aiwriter($context, string $page){
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$funnel = $context->load->loadFunnel();
	$page_description = t("CloudFunnels AI");
	$createoredit = t("CloudFunnels AI");

	$footer = $context->load->loadAiWriter();
	$footer .= $context->load->loadScript('aiwriter/aiwriter', 1);	
	
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_funnel";
	$context->load->view($createoredit, $header, $page . ".php", $footer, array('page_description' => $page_description,'tutorial_link' => $tutorial_link));
}

function create_config($context, String $page)
{
	$headers = $context->load->loadBootstrap(1);
	$headers .= $context->load->loadVue();
	$headers .= $context->load->loadScript('request');
	$headers .= $context->load->loadStyle('style');
	$headers .= $context->load->loadJSTranslator();
	$footer = $context->load->loadScript('auth_control');
	if (isset($_POST['generate_translation'])) {
		registerTranslation($_POST['generate_translation']);
		$footer .= "<script>
			authcreate.language_selected=true;
			authcreate.current_language=`" . $_POST['generate_translation'] . "`;
			</script>";
	}
	require_once($context->view_dir . "/create_config.php");
}

function login($context, String $page)
{
	$site_tokenforlogin = get_option('site_token');
	if (isset($_SESSION['user' . $site_tokenforlogin])) {
		header('Location: index.php?page=' . $_SESSION['first_page' . get_option('site_token')] . '');
	}
	$security = $context->load->secure();
	$security->manageRate(0);
	$headers = $context->load->loadBootstrap();
	$headers .= $context->load->loadVue();
	$headers .= $context->load->loadScript('request');
	$headers .= $context->load->loadScript('visual_loader');
	$headers .= $context->load->loadStyle('visual-loader');
	$headers .= $context->load->loadStyle('style');
	$headers .= $context->load->loadJSTranslator();
	$footer = $context->load->loadScript('auth_control');
	require_once($context->view_dir . "/login.php");
	if (isset($_GET['autologin'])) {
		echo "<script>authcreate.autologin=1;</script>";
	}
}

function forgot_password($context, String $page)
{
	$security = $context->load->secure();
	$security->manageRate(0);
	$headers = $context->load->loadBootstrap(1);
	$headers .= $context->load->loadVue();
	$headers .= $context->load->loadScript('request');
	$headers .= $context->load->loadScript('visual_loader');
	$headers .= $context->load->loadStyle('style');
	$headers .= $context->load->loadStyle('visual-loader');
	$headers .= $context->load->loadJSTranslator();
	$footer = $context->load->loadScript('auth_control');
	require_once($context->view_dir . "/forgot_password.php");
}

function logout($context, String $page)
{
	session_destroy();
	if (isset($_COOKIE['qfnlreuser' . get_option('site_token')])) {
		setcookie('qfnlreuser' . get_option('site_token'), '', -1);
	}
	header('Location: index.php?page=login');
}

function all_funnels($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$header .= "<script src='assets/js/vv/js/popper.min.js'></script>";
	$funnel = $context->load->loadFunnel();
	$page_description = "Create, edit, manage your funnels and sites";
	$createoredit = "Funnels and Sites";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_funnel";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'funnel' => $funnel, 'tutorial_link' => $tutorial_link));
}

function membership_funnels($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$funnel = $context->load->loadFunnel();
	$page_description = "Manage your membership areas";
	$createoredit = "Members";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_membership";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'funnel' => $funnel, 'tutorial_link' => $tutorial_link));
}

function optins($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$funnel = $context->load->loadFunnel();
	$page_description = "Manage optins collected from funnel(s)";
	$createoredit = "Optins";
	$optinsob = $context->load->loadOptin();
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_funnel";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'funnel' => $funnel, 'optinob' => $optinsob, 'tutorial_link' => $tutorial_link));
}

function members($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$funnel = $context->load->loadFunnel();
	$page_description = "Manage your members";
	$createoredit = "Members";
	$optinsob = $context->load->loadMember();
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_membership";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'funnel' => $funnel, 'optinob' => $optinsob, 'tutorial_link' => $tutorial_link));
}

function create_funnel($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('jszip.min');
	$header .= $context->load->loadScript('jszip_utils');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$header .= $context->load->loadStyle('animate');
	$header .= $context->load->loadScript('html2canvas.min');
	$footer = $context->load->loadScript('funnel_control');
	require_once('common_templates.php');
	$labelbuttons = "";
	$pages = false;
	$flodername = "";
	$createoredit = "<span id='uniquefunnelheader'>Create Funnel</span>";
	$smtps_ob = $context->load->loadSMTP();
	$smtps = $smtps_ob->getSMTP('', 1);
	$lists_ob = $context->load->createlist();
	$lists = $lists_ob->getList('', 1);
	$products_ob = $context->load->loadSell();
	$products = $products_ob->getProductIdTitle();
	$membership_ob = $context->load->loadMember();
	$registrationpages = $membership_ob->getAllMembershipRegistrationPages();
	$protocol = $context->load->getProtocol();
	$integrations = $context->load->loadIntegrations();
	$funnel_data_ob = $context->load->loadFunnel();
	$total_funnels = $funnel_data_ob->totalSumCountsFunel();
	if (isset($_GET['id'])) {
		$funnel_data = $funnel_data_ob->getFunnel($_GET['id']);
		$pages = $funnel_data_ob->getPageHTML($_GET['id']);
		if ($funnel_data) {
			$createoredit = "<span id='uniquefunnelheader'>Edit Funnel</span>";
			$labelbuttons = $funnel_data->labelhtml;

			$funnel_data->baseurl = str_replace($funnel_data_ob->routed_url, get_option('install_url'), $funnel_data->baseurl);

			$footer .= "<script>
			funnel.funnel_name='" . $funnel_data->name . "';
			funnel.funnel_url='" . $funnel_data->baseurl . "';
			funnel.funnel_type='" . $funnel_data->type . "';
			funnel.current_funnel=" . $funnel_data->id . ";
			funnel.current_step='step_2';
			funnel.common_inputs_for_current_funnel='" . $funnel_data->validinputs . "';
			setTimeout(function(){
				try {
					funnel.headerAdder();
					var thisfirstbtn=document.querySelectorAll('button[lbl=\"1\"]')[0];
					thisfirstbtn.dispatchEvent(new Event('mousedown'));
					thisfirstbtn.dispatchEvent(new Event('mouseup'));
					funnel.movable_element=0;
					funnel.createIndicator();
					funnel.headerAdder();
				} catch(err) {
					console.log(err.message);
				}
			},2000);
			settingDivOpenClose();
			</script>";
		}
	}
	$page_description = "Create, edit and manage your funnels and sites";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_funnel";
	$context->load->view($createoredit, $header, $page . ".php", $footer, array('labelbtn' => $labelbuttons, 'pages' => $pages, 'page_description' => $page_description, 'smtps' => $smtps, 'lists' => $lists, 'products' => $products, 'registrationpages' => $registrationpages, 'protocol' => $protocol, 'integrations' => $integrations, 'tutorial_link' => $tutorial_link, "flodername" => $flodername, 'total_funnels' => $total_funnels));
	if (isset($_COOKIE['templateedited'])) {
		unset($_COOKIE['templateedited']);
	}
}

function page_builder($context, String $page)
{
	$funnelob = $context->load->loadFunnel();
	if (isset($_GET['fid'])) {
		$content = $funnelob->readContent($_GET['fid'], $_GET['lbl'], $_GET['abtype']);
		$bootstrapFlag = $funnelob->readPage($_GET['fid'], $_GET['lbl'], $_GET['abtype']);
		$this_page_data = $funnelob->getPageFunnel($_GET['fid'], $_GET['abtype'], $_GET['lbl']);
		if ($this_page_data) {
			$_GET['folder'] = $this_page_data->filename;
		}
	}
	$header = $context->load->loadJSTranslator();
	$header .= $context->load->loadScript('request');
	$footer = $context->load->loadScript('compose_mail', 1);
	$footer = $context->load->loadMediaBox();
	$footer .= $context->load->loadAiWriter();
	if ($bootstrapFlag == 'B4') require_once($context->view_dir . "/editor.php");
	else require_once($context->view_dir . "/editor2.php");
}

function smtp_create($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$smtp    = $context->load->loadSMTPData();
	if (isset($_POST['save'])) {
		if (isset($_POST['save']) && !isset($_GET['id'])) {
			$title = $_POST['title'];
			$fromemail = $_POST['fromemail'];
			$hostname = $_POST['hostname'];
			$port = $_POST['port'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$encryption = $_POST['encryption'];
			$fromname = $_POST['fromname'];
			$replyname = $_POST['replyname'];
			$replyemail = $_POST['replyemail'];
			$smtp->insert($title, $fromemail, $hostname, $port, $username, $password, $encryption, $fromname, $replyname, $replyemail);
			$lastid = $smtp->getLastId();
			echo "<script>window.location='index.php?page=smtp_table';</script>";
		}

		if (isset($_POST['save']) && isset($_GET['id'])) {
			$id = $_GET['id'];
			$title = $_POST['title'];
			$fromemail = $_POST['fromemail'];
			$hostname = $_POST['hostname'];
			$port = $_POST['port'];
			$username = $_POST['username'];
			$password = $_POST['password'];
			$encryption = $_POST['encryption'];
			$fromname = $_POST['fromname'];
			$replyname = $_POST['replyname'];
			$replyemail = $_POST['replyemail'];
			$smtp->edit($id, $title, $fromemail, $hostname, $port, $username, $password, $encryption, $fromname, $replyname, $replyemail);
		}
	}
	$page_description = "Create, edit and Manage SMTP settings";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_smtp";
	$context->load->view("SMTP", $header, $page . ".php", "", array('page_description' => $page_description, 'smtp' => $smtp, 'tutorial_link' => $tutorial_link));
}

function smtp_table($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "Create, edit and manage SMTP settings";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_smtp";
	$context->load->view("SMTP Records", $header, $page . ".php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function createlist($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadScript('list');
	$list = $context->load->createlist();
	$total = $list->getTotalCount();
	$total_lists = $list->totalList();
	$page_description = "Create, edit and manage list";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_list";
	$context->load->view("Lists", $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link, 'total_leads' => $total, 'total_lists' => $total_lists));
}

function listrecords($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = "";
	$page_description = "Create, edit and manage and export all your lists";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_list";
	$context->load->view("Lists", $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function plugins($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadScript('plugins_control');
	$footer .= "<script>plugin_control.cf_version=`" . get_option('qfnl_current_version') . "`;</script>";
	if (isset($_GET['ins_remote_plugin'])) {
		$footer .= "<script>plugin_control.doInstallRemotePlugin(`" . $_GET['ins_remote_plugin'] . "`);</script>";
	}
	$page_description = "Create, edit and manage your plugins";
	$plugins_ob = $context->load->loadPlugins();
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_plugins";
	$context->load->view("Plugins", $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link, 'plugins_ob' => $plugins_ob));
}

function products($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadStyle('style');
	$sell = $context->load->loadSell();
	$page_description = "Create, edit and manage your products.";
	$createoredit = "Products";
	$footer = $context->load->loadScript('products');
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sales";
	$context->load->view($createoredit, $header, $page . ".php", $footer, array('page_description' => $page_description, 'sell' => $sell, 'tutorial_link' => $tutorial_link));
}

function cod_manage($context, String $page)
{
	if (isset($_GET['sell_id'])) {
		$sell_id = cf_enc($_GET['sell_id'], 'decode');
		$header = $context->load->loadBootstrap(1);
		$header .= $context->load->loadVue();
		$header .= $context->load->loadScript('request');
		$header .= $context->load->loadScript('visual_loader');
		$header .= $context->load->loadStyle('visual-loader');
		$header .= $context->load->loadStyle('style');
		$sale_ob = $context->load->loadSell();
		$page_description = "See and manage COD detail";
		$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sales";
		$context->load->view("Product Sales", $header, $page . ".php", '', array('page_description' => $page_description, 'sales_ob' => $sale_ob, 'tutorial_link' => $tutorial_link));
	}
}

function sales($context, String $page)
{
	$page_description = "See and manage all your sales.";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sales";
	$header = $context->load->loadBootstrap(1);
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$sale_ob = $context->load->loadSell();
	$context->load->view("Product Sales", $header, $page . ".php", '', array('page_description' => $page_description, 'sales_ob' => $sale_ob, 'tutorial_link' => $tutorial_link));
}

function media($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('vuex');
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadScript('media_storage');
	$footer .= $context->load->loadScript('media_components/app');
	$footer .= $context->load->loadScript('media_components/plupload.full.min');
	$footer .= $context->load->loadScript('media');
	$page_description = "Manage all your media files";
	$tutorial_link = "";
	$context->load->view("Media", $header, $page . ".php", $footer, array(
		'page_description' => $page_description,
		'list_ob' => $context->load->createList(),
		'smtp_ob' => $context->load->loadSMTP(),
		'compose_ob' => $context->load->loadMailComposer(),
		'tutorial_link' => $tutorial_link
	));
}

function compose_mail($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('vuex');
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('sweetalert2_js');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadScript('compose_mail', 1);
	$footer .= $context->load->loadMediaBox();
	$footer .= $context->load->loadAiWriter();
	$page_description = "Compose mail to your subscribers";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_compose_mail";
	$context->load->view("Compose Mail", $header, $page . ".php", $footer, array(
		'page_description' => $page_description,
		'list_ob' => $context->load->createList(),
		'smtp_ob' => $context->load->loadSMTP(),
		'compose_ob' => $context->load->loadMailComposer(),
		'tutorial_link' => $tutorial_link
	));
}

function sequence($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadScript('sequence');
	$footer .= $context->load->loadMediaBox();
	$page_description = "Create edit and manage email sequence";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sequence";
	$context->load->view("Sequence", $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function sequence_records($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "Create, edit and manage all your emails";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sequence";
	$context->load->view("Emails Records", $header, $page . ".php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function sequences($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "Create, edit and manage all your sequences";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_sequence";
	$context->load->view("Sequence Records", $header, $page . ".php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function createmultiuser($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$multiuser = $context->load->loadmultiuser();
	if (isset($_POST['save'])) {
		if (isset($_POST['save']) && !isset($_GET['id'])) {

			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$permission = (isset($_POST['permission'])) ? implode(',', $_POST['permission']) : "";

			if ($multiuser->addUser($name, $email, $password, $permission, $_POST['current_user_current_pass'])) {
				$lastid = $multiuser->getLastId();
				echo "<script>window.location='index.php?page=multiuser_table';</script>";
			} else {
				$GLOBALS['user_addorupdate_err'] = 1;
			}
		}

		if (isset($_POST['save']) && isset($_GET['id'])) {
			$id = $_GET['id'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$permission = (isset($_POST['permission'])) ? implode(',', $_POST['permission']) : "";
			if (!$multiuser->editUser($id, $name, $email, $password, $permission, $_POST['current_user_current_pass'])) {
				$GLOBALS['user_addorupdate_err'] = 1;
			}
		}
	}
	$page_description = "Create, edit and manage user details";

	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_user";
	$context->load->view("Users", $header, $page . ".php", "", array('page_description' => $page_description, 'multiuser' => $multiuser, 'tutorial_link' => $tutorial_link));
}

function multiuser_table($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$multiuser = $context->load->loadmultiuser();
	$page_description = "Create,edit and manage user details";
	$createoredit = "Users";

	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_user";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'multiuser' => $multiuser, 'tutorial_link' => $tutorial_link));
}

function do_payment($context, String $page)
{
	if ($page == "do_payment_execute") {
		$_GET['page'] = 'do_payment';
		$_GET['execute'] = 1;
	}

	if ((isset($_SESSION['order_form_data' . get_option('site_token')])) || (isset($_GET['qfnl_is_ipn']))) {
		if (isset($_GET['qfnl_is_ipn'])) {
			$funnel_ob = $context->load->loadFunnel();
			$exists = $funnel_ob->isOrderform($_GET['qfunnel_id'], $_GET['qfolder'], 'a', $_POST, 0);

			if (!$exists || (get_option('ipn_token') != $_GET['qfnl_is_ipn'])) {
				goto lbl;
			}
		}

		$data = $_SESSION['order_form_data' . get_option('site_token')];
		$sell = $context->load->loadSell();
		if (isset($data['data']['payment_method']) && !empty($data['data']['payment_method'])) {
			$payment_method = $data['data']['payment_method'];
		} else {
			$payment_method = $data['payment_method'];
		}
		$sell->doPayment($data['funnel_id'], $data['folder'], $data['page_id'], $data['ab_type'], $data['product_id'], $payment_method, $data['membership'], $data['lists'], $data['optional_products'], $data['confirmation_url'], $data['cancel_url'], $data['data']);
	}
	lbl:
	exit;
}

function schedule_api($context, String $page)
{
	if ($page == 'schedule_api_runserver') {
		$_GET['runserver'] = 1;
	}
	$schedule_api_ob = $context->load->loadScheduler();
	$schedule_api_ob->reqControl();
}

function mail_send($context, String $page)
{
	$context->load->loadCbMailer();
}

function mail_track($context, String $page)
{
	header('content-type: image/png');
	readfile($context->base_dir . '/assets/img/mail.png');
	$sequence_ob = $context->load->loadSequence();
	$sequence_ob->sequenceTrack(base64_decode($_GET['token']), base64_decode($_GET['card']));
}

function export_csv($context, String $page)
{
	if (isset($_GET['type'])) {
		$type = $_GET['type'];
		if ($type == "list") {
			$listob = $context->load->createlist();
			$listob->exportToCsv($_POST['listid']);
		}
	}
	if (isset($_POST['optinto_csv'])) {
		$optin_ob = $context->load->loadOptin();
		$funnelpage = explode("@", str_replace("page@", "", $_POST['optinto_csv']));
		$optin_ob->optinToCsv($funnelpage[0], $funnelpage[1]);
	}
	if (isset($_POST["membersto_csv"])) {
		$member_ob = $context->load->loadMember();
		$member_ob->exportMemberToCsv($_POST["membersto_csv"]);
	}
	if (isset($_POST['salesto_csv'])) {
		$sell_ob = $context->load->loadSell();
		$sell_ob->exportToCSV($_POST['salesto_csv']);
	}
}

function download($context, String $page)
{
	if (isset($_GET['type']) && $_GET['type'] == "data_request" && isset($_POST['gdpr_req_id'])) {
		$gdpr_ob = $context->load->loadGdpr();
		$data = $gdpr_ob->downloadGdprData($_POST['gdpr_req_id'], 1);
		if ($data === 0) {
			$data = "No Data Found";
		}
		header('content-type: text/html');
		header('content-disposition: attachment; filename=data_request.html');
		echo $data;
	}
}

function dashboard($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$header .= $context->load->loadChartJs();
	$page_description = "Start here";
	$context->load->view("Dashboard", $header, $page . ".php", "", array('page_description' => $page_description, 'load' => $context->load));
}

function settings($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$footer = $context->load->loadMediaBox();
	$page_description = "Configure your app.";
	$createoredit = "Settings";
	$setting_ob = $context->load->loadSettings();
	if (isset($_POST['save_settings'])) {
		$setting_ob->saveSettings();
	}
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_settings";
	$context->load->view($createoredit, $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function app_guide($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadScript('prism');
	$header .= $context->load->loadStyle('prism');
	$header .= $context->load->loadStyle('style');
	$page_description = "Guidance to maintain and setup the app.";
	$createoredit = "Help";
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description));
}

function sentemailsdetails($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "View your complete mail sending details";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_mail";
	$context->load->view("Mailing History", $header, $page . ".php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function do_unsubscribe($context, String $page)
{
	echo $context->load->loadBootstrap(1);
	echo $context->load->loadVue();
	echo $context->load->loadScript('request');
	echo $context->load->loadStyle('style');
	$context->load->loadUnsubscribePage();
}

function do_redirect($context, String $page)
{
	if ((isset($_GET['qfnlemlcard'])) && (isset($_GET['qfnldetectcard']))) {
		$sequence_ob = $context->load->loadSequence();
		$sequence_ob->storeLinksVisits($_GET['qfnldetectcard'], $_GET['qfnlemlcard']);
	}
}

function gdpr($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "Setup and manage GDPR requirements and requests.";
	$createoredit = "GDPR Settings";
	$cookie_ob = $context->load->loadGdpr();
	if (isset($_POST['save_settings'])) {
		$cookie_ob->saveSettings();
	}
	$footer = $context->load->loadMediaBox();
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_gdpr";
	$context->load->view($createoredit, $header, $page . ".php", $footer, array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function analysis($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadVue();
	$header .= $context->load->loadScript('request');
	$header .= $context->load->loadScript('visual_loader');
	$header .= $context->load->loadStyle('visual-loader');
	$header .= $context->load->loadStyle('style');
	$page_description = "Make Analysis Of Your Funnels";
	$createoredit = "Analysis Of Your Funnels";
	$tutorial_link = "https://cloudfunnels.in/membership/members#tutorials_analysis";

	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link));
}

function api_request($context, String $page)
{
	if (isset($_GET['cf_cookie'])) {
		header('content-type:text/javascript');
		$gdpr_ob = $context->load->loadGdpr();
		echo $gdpr_ob->gdprJsScript($_GET['cf_cookie']);
	} elseif (isset($_GET['cf_load_cookie'])) {
		$gdpr_ob = $context->load->loadGdpr();
		echo $gdpr_ob->displayCookie(1, $_GET['cf_load_cookie']);
	} elseif (isset($_GET['mail_api_auth'])) {
		echo hash_hmac('sha1', get_option('site_token'), $_GET['mail_api_auth']);
	} elseif (isset($_POST['copy_funnel'])) {
		$token = $_POST['copy_funnel'];
		$clone_ob = $context->load->loadFunnelCloner();
		$data = $clone_ob->createMap($token);
		echo $data;
		die();
	}
}

function ajax($context, String $page)
{
	//ajax request for integrated apps
	if (isset($_REQUEST['action'])) {
		$plugin_loader = $GLOBALS['plugin_loader'];
		$plugin_loader->processAjax($_REQUEST['action']);
	}
	die();
}

function callback_api($context, String $page)
{
	//handle api requests for integrated apps
	if (isset($_REQUEST['action'])) {
		$plugin_loader = $GLOBALS['plugin_loader'];
		$plugin_loader->processApi($_REQUEST['action']);
	}
	die();
}

function data_requests($context, String $page)
{
	$header = $context->load->loadBootstrap();
	$header .= $context->load->loadStyle('style');
	$err = 0;
	$secure_ob = $context->load->secure();
	if (isset($_POST['submitgdprrequest'])) {
		if (isset($_POST['csrf_token']) && ($secure_ob->matchToken($_POST['csrf_token']))) {
			$gdpr_ob = $context->load->loadGdpr();
			$err = $gdpr_ob->storeDataRequests($_POST['name'], $_POST['email'], $_POST['description'], $_POST['data_type']);
		} else {
			$err = "Please Refresh The Page And Try Again.";
		}
	}
	$csrf_token = $secure_ob->setToken();

	require_once($context->view_dir . "/" . $page . ".php");
	die();
}

function load_static_scripts($context, String $page)
{
	$create_cache = (isset($_GET['create_cache']) && $_GET['create_cache'] == '1') ? 1 : 0;
	if (isset($_GET['script_type'])) {
		$type = $_GET['script_type'];
		if ($type == "type.js") {
			header('content-type: application/javascript');
		} elseif ($type == "type.css") {
			header("content-type: text/css");
		}
	}
	$script = base64_decode($_GET['script']);
	$funnel_ob = $context->load->loadFunnel();
	echo $funnel_ob->cssJsScriptView($script, $create_cache);
}

function mmbr_lgout($context, String $page)
{
	echo "<script>window.location='http://cloudfunnels.in';</script>";
}

function show_cf_narand($context, String $page)
{
	$logo = (function_exists("getBsSixtyFourLogos")) ? getBsSixtyFourLogos("logo") : "assets/theme-assets/assets/images/logo.png";

	$logo_text = (function_exists("getBsSixtyFourLogos")) ? getBsSixtyFourLogos("logo-text") : "assets/theme-assets/assets/images/logo-text.png";

	$class = substr(str_shuffle("asdfghjklwertyuioxcvbnm"), 0, 5);
	echo "<a target='_PARENT' href='http://getcloudfunnels.in' style='text-decoration:none;'><div class='" . $class . "'>
	<div class='img'><img src='" . $logo . "'/></div>
	<div class='txt'><span>Powered&nbsp;By&nbsp;</span> <span><img src='" . $logo_text . "'/></span></div>
	</div></a>";
	echo "<style>
	." . $class . "
	{
		display:flex;
		flex-direction:row;
		justify-content:flex-end;
		height:28px !important;
		background-color:rgb(0, 51, 102, 0.9);
		width:100%;
		padding:5px;
		border-radius:5px 0px 0px 0px;
	}
	." . $class . " div
	{
		border:1px transparent;
		max-height:100% !important;
	}
	." . $class . " div.img
	{
		flex-grow:1;
	}
	." . $class . " div.txt
	{
		flex-grow:3;
		display:flex;
		flex-direction:row;
		align-items:stretch;
	}
	." . $class . " div.txt span:nth-child(1)
	{
		color:white;
		margin-top:auto;
		margin-bottom:auto;
		margin-right:2px;
		opacity:0.5;
		font-size:14px;
	}
	." . $class . " div.txt span:nth-child(2)
	{
		margin-top:auto;
		margin-bottom:auto;
	}
	." . $class . " div.txt img
	{
		max-height:14px;
		animation: brandani;
		animation-duration: 1s;
		animation-delay: 1s;
		animation-iteration-count: 2;
		animation-timing-function:ease;
	}
	@keyframes brandani
	{
		from {max-height:8px;} 
		to{max-height:14px;}
	}
	." . $class . " div.img img
	{
		max-height:25px !important;
		max-width:100%;
		vertical-align:middle;
		margin-right:4px;
	}
	</style>";
}

function install_update_dependencies($context, String $page)
{
	set_time_limit(0);
	global $current_app_version;
	if (isset($current_app_version)) {
		$autoupdater = $context->load->loadAutoUpdater();
		$added = $autoupdater->installDependencies($current_app_version);
		if (isset($_GET['after_update_redirect'])) {
			$url = $_GET['after_update_redirect'];

			if (filter_var($url, FILTER_VALIDATE_URL)) {
				header('Location: ' . $url);
			} elseif (filter_var(base64_decode($url), FILTER_VALIDATE_URL)) {
				$url = base64_decode($url);
				header('Location: ' . $url);
			}
		} else {
			if (!$added) {
				echo "Try again";
			} else {
				echo "done";
			}
		}
	} else {
		echo "Version missing";
	}
}

function no_permission($context, String $page)
{
	$GLOBALS['inside_administration_page'] = false;
	$header = $context->load->loadBootstrap();
	$page_description = "";
	$createoredit = "No Permission";
	$header .= $context->load->loadStyle('style');
	$context->load->view($createoredit, $header, $page . ".php", "", array('page_description' => $page_description));
}

function last_page($context, String $page)
{
	//manage plugins
	$has_a_integrated_page = false;
	if (isset($GLOBALS['plugin_loader'])) {
		$plugin_loader = $GLOBALS['plugin_loader'];
		$header = $context->load->loadBootstrap();
		$header .= $context->load->loadVue();
		$header .= $context->load->loadStyle('style');
		$createoredit = "";
		$page_description = "";
		$tutorial_link = "";
		$plugin_data = $plugin_loader->processAdminMenu($page);
		if ($plugin_data) {
			$has_a_integrated_page = true;
			if (isset($plugin_data[0]['page_title'])) {
				$createoredit = $plugin_data[0]['page_title'] . " ";
			}
			$context->load->view($createoredit, $header, "plugin_view.php", "", array('page_description' => $page_description, 'tutorial_link' => $tutorial_link), $plugin_data);
		}
	}

	if (!$has_a_integrated_page) {
		header('Location: index.php?page=no_permission');
		die();
	}
}

$currentPage = false;
foreach ($protected_links as $key => $link) {
	$function_name = $link;
	$explode_link = explode('|', $link);
	if (count($explode_link) === 2) {
		$function_name = function_exists($explode_link[1]) ? $explode_link[1] : $explode_link[0];
	}

	if ($function_name == $page && function_exists($function_name)) {
		$currentPage = $function_name;
		break;
	} else {
		$currentPage = false;
	}
}

if ($currentPage) {
	call_user_func($currentPage, $this, $currentPage);
} else {
	last_page($this, $page);
}