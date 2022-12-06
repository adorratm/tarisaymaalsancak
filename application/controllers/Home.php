<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! =============================== VARIABLES =============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * Variables
     */
    public $viewFolder = "";
    public $viewData = "";
    // PAYTR ÜYE İŞ YERİ BİLGİLERİ
    const merchant_id                                 = ''; // Mağaza numarası
    const merchant_key                                 = ''; // Mağaza Parolası
    const merchant_salt                             = ''; // Mağaza Gizli Anahtarı
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! =============================== VARIABLES =============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! ============================== CONSTRUCTOR ============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "home_v";
        $this->viewData = new stdClass();
        /**
         * Sitemap
         */
        $this->viewData->page_urls = [];
        $this->viewData->lang = (!empty($this->uri->segment(1) && mb_strlen($this->uri->segment(1)) == 2) ? $this->uri->segment(1) : (!empty(get_cookie("lang")) ? get_cookie("lang") : "tr"));
        $this->viewData->settings = $this->general_model->get("settings", null, ["isActive" => 1, "lang" => $this->viewData->lang]);
        $languages = $this->general_model->get_all("settings", "lang", "rank ASC", ["isActive" => 1]);
        setlocale(LC_ALL, $this->viewData->lang . "_" . (strto("lower|ucwords", ($this->viewData->lang == "en" ? "us" : $this->viewData->lang))));
        $currency = $this->general_model->get("countries", null, ["code" => strto("lower|ucwords", ($this->viewData->lang == "en" ? "us" : $this->viewData->lang))]);
        $this->viewData->currency = $currency->currency_code;
        $this->viewData->formatter = new NumberFormatter($this->viewData->lang, NumberFormatter::CURRENCY);
        $formattedValue = $this->viewData->formatter->formatCurrency(0, $this->viewData->currency);
        $this->viewData->symbol = trim(@str_replace('0,00', '', @str_replace('0.00', '', $formattedValue)));
        $this->viewData->menus = $this->show_tree('HEADER', $this->viewData->lang);
        //$this->viewData->mobileMenus = $this->show_tree('MOBILE', $this->viewData->lang);
        //$this->viewData->rightMenus = $this->show_tree('HEADER_RIGHT', $this->viewData->lang);
        $this->viewData->footer_menus = $this->show_tree('FOOTER', $this->viewData->lang);
        $this->viewData->languages = $languages;
        /**
         * Menu Categories
         */
        $this->viewData->menuCategories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang, "top_id" => 0], [], [], []);
        /**
         * Footer Blogs
         */
        $this->viewData->footerBlogs = $this->general_model->get_all("blogs", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [7]);
        /**
         * Get User Data
         */
        $this->viewData->user = get_active_user() ?? [];
        $this->viewData->pages = $this->general_model->get_all("pages", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        /**
         * User Wishlists
         */
        $userWishlists = (get_active_user() ? $this->general_model->get_all("product_wishlists", null, "id DESC", ["user_id" => get_active_user()->id]) : []);
        $wishlistsArray = [];
        if (!empty($userWishlists)) :
            foreach ($userWishlists as $key => $value) :
                if (!in_array($value->product_id, $wishlistsArray)) :
                    array_push($wishlistsArray, $value->product_id);
                endif;
            endforeach;
        endif;
        $this->viewData->userWishlists = $wishlistsArray;
        $this->cart->product_name_safe = FALSE;
        $this->ci_minifier->set_domparser(2);
        $this->ci_minifier->init(1);
    }
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! ============================== CONSTRUCTOR ============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * ----------------------------------------------------------------------------------------------
     * ...:::!!! ================================= RENDER ================================= !!!:::...
     * ----------------------------------------------------------------------------------------------
     */
    /**
     * Render
     */
    public function render()
    {
        //$this->benchmark->mark('head_start');
        $this->load->view("includes/head", (array)$this->viewData);
        //$this->benchmark->mark('head_end');
        //$this->benchmark->mark('header_start');
        $this->load->view("includes/header");
        //$this->benchmark->mark('header_end');
        //$this->benchmark->mark('content_start');
        $this->load->view($this->viewFolder);
        //$this->benchmark->mark('content_end');
        //$this->benchmark->mark('footer_start');
        $this->load->view("includes/footer");
        //$this->benchmark->mark('footer_end');
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================= RENDER =====?============================ !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== ERROR ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Error 
     */
    public function error()
    {
        $this->viewFolder = "404_v/index";
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== ERROR ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== INDEX ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Index
     */
    public function index()
    {

        /**
         * Slides
         */
        $this->viewData->slides = $this->general_model->get_all("slides", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);

        $wheres["p.isActive"] = 1;
        $wheres["pi.isCover"] = 1;
        $wheres["p.lang"] = $this->viewData->lang;
        $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_categories pc" => ["pwc.category_id = pc.id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
        $select = "p.visits visits,GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isDiscount isDiscount,p.sharedAt";
        $distinct = true;
        $groupBy = ["p.id", "pwc.product_id", "pvg.id"];
        /**
         * Get Suggested Products
         */
        $wheres["p.isSuggested"] = 1;
        $this->viewData->suggestedProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isSuggested"]);
        /**
         * Get New Products
         */
        $wheres["p.isNew"] = 1;
        $this->viewData->newProducts = $this->general_model->get_all("products p", $select, "p.id DESC", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isNew"]);
        /**
         * Get Discount Products
         */
        $wheres["p.isDiscount"] = 1;
        $this->viewData->discountProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isDiscount"]);
        /**
         * Get Mostly Viewed Products
         */
        $this->viewData->mostlyViewedProducts = $this->general_model->get_all("products p", $select, "visits DESC", $wheres, [], $joins, [12], [], $distinct, $groupBy);

        $this->viewData->blogs = $this->general_model->get_all("blogs", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [3]);
        $this->viewData->blog_categories = $this->general_model->get_all("blog_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);

        /**
         * Home Items
         */
        $this->viewData->homeitems = $this->general_model->get_all("home_items", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]);
        $this->viewData->homeitemsFooter = $this->general_model->get_all("home_items", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5, 5]);

        $this->viewData->meta_title = clean(strto("lower|ucwords", lang("home"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url());
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "website";
        $this->viewData->og_title           = clean(strto("lower|ucwords", lang("home"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->viewFolder = "home_v/index";
        $this->render();
        //$this->output->enable_profiler(TRUE);
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== INDEX ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== MENU =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Show Tree
     */
    public function show_tree($position = 'HEADER', $lang = 'tr')
    {
        // create array to store all menus ids
        $store_all_id = array();
        // get all parent menus ids by using isactive
        $id_result = $this->general_model->get_all("menus", "top_id", "rank ASC", ["position" => $position, "isActive" => 1, "lang" => $lang]);
        // loop through all menus to save parent ids $store_all_id array
        foreach ($id_result as $menu_id) {
            array_push($store_all_id, $menu_id->top_id);
        }
        // return all hierarchical tree data from in_parent by sending
        //  initiate parameters 0 is the main parent,news id, all parent ids
        return $this->in_parent(0, $position, $lang, $store_all_id);
    }
    /**
     * recursive function to loop
     * through all comments and retrieve it
     */
    public function in_parent($in_parent = null, $position = null, $lang = null, $store_all_id = null)
    {
        // this variable to save all concatenated html
        $html = "";
        // build hierarchy  html structure based on ul li (parent-child) nodes
        if (in_array($in_parent, $store_all_id)) :
            $result = $this->general_model->get_all("menus", "url,title,id,top_id,page_id,target,showProductCategories", "rank ASC", ["position" => $position, "top_id" => $in_parent, "isActive" => 1, "lang" => $lang]);
            $html .=  '<ul class="' . ($position == "HEADER" ? ($in_parent == 0 ? "navbar-nav justify-content-start flex-grow-1 pe_5" : "dropdown-menu dropdown-submenu") : ($position == "HEADER_RIGHT" ? "user-link" : ($position == "MOBILE" ? ($in_parent == 0 ? "dropdown-menu dropdown-submenu" : "dropdown-menu dropdown-submenu") : ""))) . '">';
            foreach ($result as $key => $value) :
                $page = $this->general_model->get("pages", "url,title", ["isActive" => 1, "id" => $value->page_id, "lang" => $lang]);
                if ($value->page_id !== 0) :
                    if (!empty($page)) :
                        $page->url = (!empty($page->url) ? $page->url : null);
                    endif;
                endif;
                $value->title = (!empty($value->title) ? $value->title : null);
                if (!empty($value->url)) :
                    $value->url = (!empty($value->url) ? $value->url : null);
                endif;
                $html .= '<li ' . (($position == "HEADER") && in_array($value->id, $store_all_id) ? ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) || ($this->uri->segment(2) === null && $value->url === '/') ? (($position == "HEADER") ? "class='dropdown nav-item active'" : "class='nav-item'") : (($position == "HEADER") ? "class='dropdown nav-item'" : null)) : ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || ($this->uri->segment(2) === null && $value->url === '/') || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) ? ($position == "HEADER" ? "class='nav-item active" . ($value->showProductCategories ? " megamenu " : null) . "'" : null) : ($position == "HEADER" ? "class='nav-item " . ($value->showProductCategories ? " megamenu " : null) . "'" : null))) . '>';
                if (empty($value->url)) :

                    if (!empty($page->url)) :

                        $html .= '<a rel="dofollow" ' . (($position == "MOBILE" || $position == "HEADER") && in_array($value->id, $store_all_id) ? ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) || ($this->uri->segment(2) === null && $value->url === '/') ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null)) : ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || ($this->uri->segment(2) === null && $value->url === '/') || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null))) . ' href="' . base_url(lang("routes_page") . "/" . (!empty($page->url) ? $page->url : null)) . '" target="' . $value->target . '" title="' . $value->title . '">' . $value->title  . ((in_array($value->id, $store_all_id) || $value->showProductCategories) && $position != "MOBILE" ? " <i class='dropdown-toggle'  aria-expanded='false' role='button'></i>" : null) . '</a>';
                        array_push($this->viewData->page_urls, base_url(lang("routes_page") . "/" . (!empty($page->url) ? $page->url : null)));
                    else :
                        $html .= '<a rel="dofollow" ' . (($position == "MOBILE" || $position == "HEADER") && in_array($value->id, $store_all_id) ? ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) || ($this->uri->segment(2) === null && $value->url === '/') ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null)) : ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || ($this->uri->segment(2) === null && $value->url === '/') || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null))) . ' href="' . base_url(seo(strto("lower", $value->title))) . '" target="' . $value->target . '" title="' . $value->title . '">' . $value->title . ((in_array($value->id, $store_all_id) || $value->showProductCategories) && $position != "MOBILE" ? " <i class='dropdown-toggle' aria-expanded='false' role='button'></i>" : null) .  '</a>';
                        array_push($this->viewData->page_urls, base_url(seo(strto("lower", $value->title))));
                    endif;
                else :
                    $url = parse_url($value->url, PHP_URL_SCHEME);
                    if ($url === "http" || $url === "https") :
                        $html .= '<a rel="dofollow" ' . (($position == "MOBILE" || $position == "HEADER") && in_array($value->id, $store_all_id) ? ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) || ($this->uri->segment(2) === null && $value->url === '/') ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null)) : ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || ($this->uri->segment(2) === null && $value->url === '/') || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null))) . ' href="' . $value->url . '" target="' . $value->target . '" title="' . $value->title . '">' . $value->title . ((in_array($value->id, $store_all_id) || $value->showProductCategories) && $position != "MOBILE" ? " <i class='dropdown-toggle' aria-expanded='false' role='button'></i>" : null) . '</a>';
                        array_push($this->viewData->page_urls, $value->url);
                    else :
                        $html .= '<a rel="dofollow" ' . (($position == "MOBILE" || $position == "HEADER") && in_array($value->id, $store_all_id) ? ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) || ($this->uri->segment(2) === null && $value->url === '/') ? ($position != "FOOTER" && $position != "FOOTER2" ?  "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null)) : ((!empty($page->url) && ($this->uri->segment(2) == strto("lower", seo($page->url)) || $this->uri->segment(3) == strto("lower", seo($page->url)))) || ($this->uri->segment(2) === null && $value->url === '/') || $this->uri->segment(2) == strto("lower", seo($value->title)) || $this->uri->segment(3) == strto("lower", seo($value->title)) ? ($position != "FOOTER" && $position != "FOOTER2" ? "class='active nav-link'" : null) : ($position != "FOOTER" && $position != "FOOTER2" ? "class='nav-link'" : null))) . ' href="' . base_url($value->url) . '" target="' . $value->target . '" title="' . $value->title . '">' . $value->title . ((in_array($value->id, $store_all_id) || $value->showProductCategories) && $position != "MOBILE" ? " <i class='dropdown-toggle' aria-expanded='false' role='button'></i>" : null) . '</a>';
                        array_push($this->viewData->page_urls, base_url($value->url));
                    endif;
                endif;
                $html .= $this->in_parent($value->id, $position, $lang, $store_all_id);
                $html .= "</li>";
            endforeach;
            $html .=  "</ul>";
        endif;

        return $html;
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== MENU =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== PAGES ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Pages
     */
    public function page()
    {
        $seo_url = $this->uri->segment(3);
        $this->viewData->item = $this->general_model->get("pages", null, ["isActive" => 1, "lang" => $this->viewData->lang, 'url' =>  $seo_url]);
        $this->viewData->meta_title = strto("lower|ucwords", @$this->viewData->item->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = clean(@str_replace("”", "\"", @stripslashes($this->viewData->item->content)));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_page") . "/" . $seo_url));
        $this->viewData->og_image           = clean(get_picture("pages_v", @$this->viewData->item->img_url));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = strto("lower|ucwords", @$this->viewData->item->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean(@str_replace("”", "\"", @stripslashes($this->viewData->item->content)));
        if (empty($this->viewData->item)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "page_v/index";
        endif;
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== PAGES ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== BLOGS =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Blogs
     */
    public function blogs()
    {
        $seo_url = $this->uri->segment(3);
        $search = null;
        if (!empty(clean($this->input->get("search")))) :
            $search = clean($this->input->get("search"));
        endif;
        $category_id = null;
        $category = null;
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $category = $this->general_model->get("blog_categories", null, ["isActive" => 1, "lang" => $this->viewData->lang, "seo_url" => $seo_url]);
            if (!empty($category)) :
                $category_id = $category->id;
                $category->seo_url = (!empty($category->seo_url) ? $category->seo_url : null);
                $category->title = (!empty($category->title) ? $category->title : null);
            endif;
        endif;
        $this->load->library("pagination");
        $config = [];
        $config['base_url'] = (!empty($seo_url) && !is_numeric($seo_url) ? base_url(lang("routes_blog") . "/{$seo_url}/") : base_url(lang("routes_blog") . "/"));
        $config['uri_segment'] = (!empty($seo_url) && !is_numeric($seo_url) && !empty($this->uri->segment(4)) ? 4 : (is_numeric($this->uri->segment(3)) ? 3 : 2));
        $config['use_page_numbers'] = TRUE;
        $config["full_tag_open"] = "<ul class='pagination justify-content-center'>";
        $config["first_link"] = "<i class='fa fa-angles-left fa-1x'></i>";
        $config["first_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "<i class='fa fa-angle-left fa-1x'></i>";
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link active' title='" . $this->viewData->settings->company_name . "' rel='dofollow' href='" . str_replace($this->viewData->lang . "/index.php/", "", current_url()) . "'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config["next_link"] = "<i class='fa fa-angle-right fa-1x'></i>";
        $config["next_tag_open"] = "<li class='page-item'>";
        $config["next_tag_close"] = "</li>";
        $config["last_link"] = "<i class='fa fa-angles-right fa-1x'></i>";
        $config["last_tag_open"] = "<li class='page-item'>";
        $config["last_tag_close"] = "</li>";
        $config["full_tag_close"] = "</ul>";
        $config['attributes'] = array('class' => 'page-link');
        $config['total_rows'] = (!empty($seo_url) && !is_numeric($seo_url) ? (!empty($search) ? $this->general_model->rowCount("blogs", ["isActive" => 1, "category_id" => $category_id, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" => $search, "updatedAt" =>  $search]) : $this->general_model->rowCount("blogs", ["isActive" => 1, "category_id" => $category_id, "lang" => $this->viewData->lang])) : (!empty($search) ? $this->general_model->rowCount("blogs", ["isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" => $search, "createdAt" =>  $search, "updatedAt" =>  $search]) : $this->general_model->rowCount("blogs", ["isActive" => 1, "lang" => $this->viewData->lang])));
        $config['per_page'] = 8;
        $config["num_links"] = 5;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(4);
        elseif (!empty($this->uri->segment(3)) && is_numeric($this->uri->segment(3))) :
            $uri_segment = $this->uri->segment(3);
        else :
            $uri_segment = $this->uri->segment(3);
        endif;
        if (empty($uri_segment)) :
            $uri_segment = 1;
        endif;
        $offset = (!empty($uri_segment) ? $uri_segment - 1 : 0) * $config['per_page'];
        $this->viewData->offset = $offset;
        $this->viewData->per_page = $config['per_page'];
        $this->viewData->total_rows = $config['total_rows'];
        $this->viewData->category = $category;
        $this->viewData->blogs = (!empty($seo_url) && !is_numeric($seo_url) ? (!empty($search) ? $this->general_model->get_all("blogs", null, null, ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" => $search, "updatedAt" =>  $search], [], [$config["per_page"], $offset]) : $this->general_model->get_all("blogs", null, null, ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset])) : (!empty($search) ? $this->general_model->get_all("blogs", null, null, ["isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" =>  $search, "updatedAt" =>  $search], [], [$config["per_page"], $offset]) : $this->general_model->get_all("blogs", null, null, ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset])));
        $this->viewData->categories = $this->general_model->get_all("blog_categories", null, "id DESC", ["isActive" => 1]);
        $this->viewData->latestBlogs = (!empty($seo_url) && !is_numeric($seo_url) ? $this->general_model->get_all("blogs", null, "id DESC", ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]) : $this->general_model->get_all("blogs", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]));

        $this->viewData->meta_title = clean(strto("lower|upper", lang("routes_blog"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url(lang("routes_blog")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = clean(strto("lower|upper", lang("routes_blog"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->viewData->links = $this->pagination->create_links();
        if (empty($this->viewData->blogs)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "blogs_v/index";
        endif;
        $this->render();
    }
    /**
     * Blog Detail
     */
    public function blog_detail($seo_url)
    {
        $this->viewData->blog = $this->general_model->get("blogs", null, ["isActive" => 1, "lang" => $this->viewData->lang, 'seo_url' => $seo_url]);
        if (!empty($this->viewData->blog->category_id)) :
            $this->viewData->category = $this->general_model->get("blog_categories", null, ["id" => $this->viewData->blog->category_id, "isActive" => 1, "lang" => $this->viewData->lang]);
        endif;
        $this->viewData->categories = $this->general_model->get_all("blog_categories", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        $this->viewData->latestBlog = (!empty($this->viewData->blog->category_id) ? $this->general_model->get_all("blogs", null, "id DESC", ['category_id' => $this->viewData->blog->category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]) : $this->general_model->get_all("blogs", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]));

        $this->viewData->meta_title = strto("lower|upper", $this->viewData->blog->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = clean(str_replace("”", "\"", stripslashes($this->viewData->blog->content)));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_blog") . "/" . lang("routes_blog_detail") . "/" . $seo_url));
        $this->viewData->og_image           = clean(get_picture("blogs_v", $this->viewData->blog->img_url));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = strto("lower|upper", $this->viewData->blog->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean(str_replace("”", "\"", stripslashes($this->viewData->blog->content)));
        if (empty($this->viewData->blog)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "blog_detail_v/index";
        endif;
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== BLOGS =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== NEWS =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * News
     */
    public function news()
    {
        $seo_url = $this->uri->segment(3);
        $search = null;
        if (!empty(clean($this->input->get("search")))) :
            $search = clean($this->input->get("search"));
        endif;
        $category_id = null;
        $category = null;
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $category = $this->general_model->get("news_categories", null, ["isActive" => 1, "lang" => $this->viewData->lang, "seo_url" => $seo_url]);
            if (!empty($category)) :
                $category_id = $category->id;
                $category->seo_url = (!empty($category->seo_url) ? $category->seo_url : null);
                $category->title = (!empty($category->title) ? $category->title : null);
            endif;
        endif;
        $this->load->library("pagination");
        $config = [];
        $config['base_url'] = (!empty($seo_url) && !is_numeric($seo_url) ? base_url(lang("routes_galleries") . "/{$seo_url}") : base_url(lang("routes_galleries")));
        $config['uri_segment'] = (!empty($seo_url) && !is_numeric($seo_url) && !empty($this->uri->segment(4)) ? 4 : (!is_numeric($this->uri->segment(3)) ? 3 : 2));
        $config['use_page_numbers'] = TRUE;
        $config["full_tag_open"] = "<ul class='pagination justify-content-center'>";
        $config["first_link"] = "<i class='fa fa-chevrons-left'></i>";
        $config["first_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "<i class='fa fa-chevron-left'></i>";
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link active' title='" . $this->viewData->settings->company_name . "' rel='dofollow' href='" . @str_replace($this->viewData->lang . "/index.php/", "", current_url()) . "'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config["next_link"] = "<i class='fa fa-chevron-right'></i>";
        $config["next_tag_open"] = "<li class='page-item'>";
        $config["next_tag_close"] = "</li>";
        $config["last_link"] = "<i class='fa fa-chevrons-right'></i>";
        $config["last_tag_open"] = "<li class='page-item'>";
        $config["last_tag_close"] = "</li>";
        $config["full_tag_close"] = "</ul>";
        $config['attributes'] = array('class' => 'page-link');
        $config['total_rows'] = (!empty($seo_url) && !is_numeric($seo_url) ? (!empty($search) ? $this->general_model->rowCount("news", ["isActive" => 1, "category_id" => $category_id, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" => $search, "updatedAt" =>  $search]) : $this->general_model->rowCount("news", ["isActive" => 1, "category_id" => $category_id, "lang" => $this->viewData->lang])) : (!empty($search) ? $this->general_model->rowCount("news", ["isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" => $search, "createdAt" =>  $search, "updatedAt" =>  $search]) : $this->general_model->rowCount("news", ["isActive" => 1, "lang" => $this->viewData->lang])));
        $config['per_page'] = 8;
        $config["num_links"] = 5;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(4);
        elseif (!empty($seo_url) && is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(3);
        else :
            $uri_segment = $this->uri->segment(3);
        endif;
        $offset = (!empty($uri_segment) ? $uri_segment - 1 : 0) * $config['per_page'];
        $this->viewData->offset = $offset;
        $this->viewData->per_page = $config['per_page'];
        $this->viewData->total_rows = $config['total_rows'];
        $this->viewData->news_category = $category;
        $this->viewData->news = (!empty($seo_url) && !is_numeric($seo_url) ? (!empty($search) ? $this->general_model->get_all("news", null, null, ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" => $search, "updatedAt" =>  $search], [], [$config["per_page"], $offset]) : $this->general_model->get_all("news", null, null, ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset])) : (!empty($search) ? $this->general_model->get_all("news", null, null, ["isActive" => 1, "lang" => $this->viewData->lang], ["title" =>  $search, "content" =>  $search, "createdAt" =>  $search, "updatedAt" =>  $search], [], [$config["per_page"], $offset]) : $this->general_model->get_all("news", null, null, ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset])));
        $this->viewData->categories = $this->general_model->get_all("news_categories", null, "id DESC", ["isActive" => 1]);
        $this->viewData->latestNews = (!empty($seo_url) && !is_numeric($seo_url) ? $this->general_model->get_all("news", null, "id DESC", ['category_id' => $category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]) : $this->general_model->get_all("news", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]));

        $this->viewData->meta_title = clean(strto("lower|ucwords", lang("routes_news"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url(lang("routes_news")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = clean(strto("lower|ucwords", lang("routes_news"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->viewData->links = $this->pagination->create_links();
        if (empty($this->viewData->news)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "news_v/index";
        endif;
        $this->render();
    }
    /**
     * News Detail
     */
    public function news_detail($seo_url)
    {
        $this->viewData->news = $this->general_model->get("news", null, ["isActive" => 1, "lang" => $this->viewData->lang, 'seo_url' => $seo_url]);
        if (!empty($this->viewData->news->category_id)) :
            $this->viewData->category = $this->general_model->get("news_categories", null, ["id" => $this->viewData->news->category_id, "isActive" => 1, "lang" => $this->viewData->lang]);
        endif;
        $this->viewData->categories = $this->general_model->get_all("news_categories", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        $this->viewData->latestNews = (!empty($this->viewData->news->category_id) ? $this->general_model->get_all("news", null, "id DESC", ['category_id' => $this->viewData->news->category_id, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]) : $this->general_model->get_all("news", null, "id DESC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [5]));

        $this->viewData->meta_title = strto("lower|ucwords", $this->viewData->news->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = clean(@str_replace("”", "\"", @stripslashes($this->viewData->news->content)));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_news") . "/" . lang("routes_newss") . "/" . $seo_url));
        $this->viewData->og_image           = clean(get_picture("news_v", $this->viewData->news->img_url));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = strto("lower|ucwords", $this->viewData->news->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean(@str_replace("”", "\"", @stripslashes($this->viewData->news->content)));
        if (empty($this->viewData->news)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "news_detail_v/index";
        endif;
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================== NEWS =================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ PRODUCTS ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Products
     */
    public function products()
    {
        $search = null;
        if (!empty(clean($this->input->get("search")))) :
            $search = clean($this->input->get("search"));
        endif;
        $seo_url = $this->uri->segment(3);
        $category_id = null;
        $category = null;
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $category = $this->general_model->get("product_categories", null, ["isActive" => 1, "lang" => $this->viewData->lang, "seo_url" => $seo_url]);
            if (!empty($category)) :
                $category_id = $category->id;
                $category->seo_url = (!empty($category->seo_url) ? $category->seo_url : null);
                $category->title = (!empty($category->title) ? $category->title : null);
            endif;
        endif;
        $minPrice = !empty($_GET["amountMin"]) ? floatVal($_GET["amountMin"]) : 0;
        $maxPrice = !empty($_GET["amountMax"]) ? floatVal($_GET["amountMax"]) : null;
        /**
         * Order
         */
        $order = !empty($_GET["orderBy"]) ? clean($_GET["orderBy"]) : "p.id DESC";
        /**
         * Likes
         */
        $likes = [];
        if (!empty($search)) :
            $likes["p.title"] = $search;
            $likes["p.content"] = $search;
            $likes["p.createdAt"] = $search;
            $likes["p.updatedAt"] = $search;
            $likes["p.description"] = $search;
            $likes["p.features"] = $search;
        endif;
        $wheres = [];
        if (!empty($category_id)) :
            $wheres["pwc.category_id"] = $category_id;
        endif;
        $selectedVariations = [];
        if (!empty($_GET["selectedVariations"])) :
            if (is_array($_GET["selectedVariations"])) :
                $selectedVariations = rClean($_GET["selectedVariations"]);
            else :
                $selectedVariations = [clean($_GET["selectedVariations"])];
            endif;
        endif;
        $selected_product_variation_groups = [];
        $selected_product_variation_groups_products = [];
        if (!empty($selectedVariations)) :
            $selected_product_variation_groups1 = $this->general_model->get_all("product_variation_groups", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
            foreach ($selected_product_variation_groups1 as $key => $value) :
                $variation_ids = explode(",", $value->variation_id);

                foreach ($selectedVariations as $k => $v) :

                    if (in_array($v, $variation_ids) && !in_array($value, $selected_product_variation_groups)) :
                        array_push($selected_product_variation_groups, $value);
                    endif;
                endforeach;
            endforeach;

            foreach ($selected_product_variation_groups as $key => $value) :
                if (!in_array($value->id, $selected_product_variation_groups_products)) :
                    array_push($selected_product_variation_groups_products, $value->id);
                endif;
            endforeach;
            if (!empty($selected_product_variation_groups_products)) :
                $selected_product_variation_groups_products = ["pvg.id" => $selected_product_variation_groups_products];
            endif;
        endif;
        /**
         * Wheres
         */
        $wheres["p.isActive"] = 1;
        $wheres["pi.isCover"] = 1;
        if (!empty($_GET["isNew"])) :
            $wheres["p.isNew"] = 1;
        endif;
        if (!empty($_GET["isSuggested"])) :
            $wheres["p.isSuggested"] = 1;
        endif;
        if (!empty($_GET["isDiscount"])) :
            $wheres["p.isDiscount"] = 1;
        endif;

        $wheres["p.lang"] = $this->viewData->lang;
        $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_categories pc" => ["pwc.category_id = pc.id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
        $select = "GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,p.price price,p.discount discount,p.stock stock,p.stockStatus stockStatus,p.isActive isActive,p.isDiscount isDiscount,p.sharedAt,p.price AS newPrice,(CASE when p.isDiscount = 1 then p.price - (p.price * p.discount / 100) else p.price end) AS discountedPrice";
        $distinct = true;
        $groupBy = ["p.id", "pwc.product_id"];
        if (!empty($selected_product_variation_groups_products)) :
            $joins["product_variation_groups pvg"] = ["p.id = pvg.product_id", "left"];
            $wheres = ["p.isActive" => 1, "pi.isCover" => 1, "p.lang" => $this->viewData->lang];
            if (!empty($category_id)) :
                $wheres["pwc.category_id"] = $category_id;
            endif;
            $order = !empty($_GET["orderBy"]) ? clean($_GET["orderBy"]) : "p.id DESC";
            $distinct = true;
            $groupBy = ["p.id", "pwc.product_id", "pvg.id"];
        endif;
        if (!empty($_GET["isViewed"])) :
            $order = !empty($_GET["orderBy"]) ? clean($_GET["orderBy"]) : "p.visits DESC";
        endif;
        if (!empty($minPrice)) :
            $wheres["(CASE
                WHEN p.isDiscount = 1 AND p.discount > 0 THEN (p.price - (p.price * p.discount / 100)) else (p.price) end)>="] = $minPrice;
        endif;
        if (!empty($maxPrice) && $maxPrice > 0) :
            $wheres["p.price<="] = $maxPrice;
        endif;
        /**
         * Min Price
         */
        $this->viewData->minPrice = $this->general_model->get("products p", "MIN((CASE WHEN p.isDiscount = 1 AND p.discount > 0 THEN (p.price - (p.price * p.discount / 100)) else (p.price) end)) AS newPrice", ["p.lang" => $this->viewData->lang, "p.isActive" => 1]);
        if (!empty($minPrice)) :
            $this->viewData->minPrice = $minPrice;
        else :
            $this->viewData->minPrice = @$this->viewData->minPrice->newPrice;
        endif;
        /**
         * Max Price
         */
        $this->viewData->maxPrice = $this->general_model->get("products p", "MAX(p.price) AS newPrice", ["p.lang" => $this->viewData->lang, "p.isActive" => 1]);
        if (!empty($maxPrice)) :
            $this->viewData->maxPrice = $maxPrice;
        else :
            $this->viewData->maxPrice = @$this->viewData->maxPrice->newPrice;
        endif;
        /**
         * Pagination
         */
        $this->load->library("pagination");
        $config = [];
        $config['base_url'] = (!empty($seo_url) && !is_numeric($seo_url) ? base_url(lang("routes_products") . "/{$seo_url}/") : base_url(lang("routes_products") . "/"));
        $config['uri_segment'] = (!empty($seo_url) && !is_numeric($seo_url) && !empty($this->uri->segment(4)) ? 4 : (is_numeric($this->uri->segment(3)) ? 3 : 2));
        $config['use_page_numbers'] = TRUE;
        $config["full_tag_open"] = "<ul class='pagination justify-content-center'>";
        $config["first_link"] = "<i class='fa fa-chevrons-left fa-1x'></i>";
        $config["first_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "<i class='fa fa-chevron-left fa-1x'></i>";
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link active' title='" . $this->viewData->settings->company_name . "' rel='dofollow' href='" . str_replace("tr/index.php/", "", current_url()) . "'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config["next_link"] = "<i class='fa fa-chevron-right fa-1x'></i>";
        $config["next_tag_open"] = "<li class='page-item'>";
        $config["next_tag_close"] = "</li>";
        $config["last_link"] = "<i class='fa fa-chevrons-right fa-1x'></i>";
        $config["last_tag_open"] = "<li class='page-item'>";
        $config["last_tag_close"] = "</li>";
        $config["full_tag_close"] = "</ul>";
        $config['attributes'] = array('class' => 'page-link');
        $config['total_rows'] = $this->general_model->rowCount("products p", $wheres, $likes, $joins, $selected_product_variation_groups_products, $distinct, $groupBy, "p.id");
        $config['per_page'] = 24;
        $config["num_links"] = 5;
        $config['reuse_query_string'] = true;
        $this->pagination->initialize($config);
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(4);
        elseif (!empty($this->uri->segment(3)) && is_numeric($this->uri->segment(3))) :
            $uri_segment = $this->uri->segment(3);
        else :
            $uri_segment = $this->uri->segment(3);
        endif;
        if (empty($uri_segment)) :
            $uri_segment = 1;
        endif;
        $offset = (!empty($uri_segment) ? $uri_segment - 1 : 0) * $config['per_page'];
        $this->viewData->offset = $offset;
        $this->viewData->per_page = $config['per_page'];
        $this->viewData->total_rows = $config['total_rows'];
        $this->viewData->products_category = $category;
        /**
         * Get All Categories
         */
        $this->viewData->categories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        /** 
         * Get Products
         */
        $this->viewData->products = $this->general_model->get_all("products p", $select, $order, $wheres, $likes, $joins, [$config["per_page"], $offset], $selected_product_variation_groups_products, $distinct, $groupBy);
        $wheres = ["p.isActive" => 1];
        $wheres["pi.isCover"] = 1;
        $wheres["p.lang"] = $this->viewData->lang;
        if (!empty($category_id)) :
            $wheres["pwc.category_id"] = $category_id;
        endif;
        $select = "GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,p.price price,p.discount discount,p.stock stock,p.stockStatus stockStatus,p.isDiscount isDiscount,p.sharedAt";
        /**
         * Get Suggested Products
         */
        $wheres["p.isSuggested"] = 1;
        $this->viewData->suggestedProducts = $this->general_model->get_all("products p", $select, "rand()", $wheres, [], $joins, [12], $selected_product_variation_groups_products, $distinct, $groupBy);
        unset($wheres["p.isSuggested"]);
        /**
         * Get New Products
         */
        $wheres["p.isNew"] = 1;
        $this->viewData->newProducts = $this->general_model->get_all("products p", $select, "p.id DESC", $wheres, [], $joins, [12], $selected_product_variation_groups_products, $distinct, $groupBy);
        unset($wheres["p.isNew"]);
        /**
         * Get Discount Products
         */
        $wheres["p.isDiscount"] = 1;
        $this->viewData->discountProducts = $this->general_model->get_all("products p", $select, "rand()", $wheres, [], $joins, [12], $selected_product_variation_groups_products, $distinct, $groupBy);
        unset($wheres["p.isDiscount"]);

        /**
         * Get Mostly Viewed Products
         */
        $wherein = [];
        if (!empty($category->id)) :
            $wherein = ["pc.id" => explode(",", $category->id)];
        endif;
        $this->viewData->mostlyViewedProducts = $this->general_model->get_all("products p", $select, "p.visits DESC", $wheres, [], $joins, [12], $wherein, $distinct, $groupBy);

        /**
         * Meta
         */
        $this->viewData->page_title = (!empty($category) ? $category->title : lang("products"));
        $this->viewData->meta_title = strto("lower|ucwords", (!empty($category) ? $category->title : lang("products"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_products")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "product";
        $this->viewData->og_title           = strto("lower|ucwords", (!empty($category) ? $category->title : lang("products"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->viewData->links = $this->pagination->create_links();
        $this->viewFolder = "products_v/index";
        $this->render();
        //$this->output->enable_profiler(true); // OPEN FOR PERFORMANCE
        //$this->benchmark->mark('code_end');
        //echo $this->benchmark->elapsed_time('code_start','code_end');
    }
    /**
     * Product Detail
     */
    public function product_detail($seo_url)
    {
        $wheres["p.isActive"] = 1;
        $wheres["pi.isCover"] = 1;
        $wheres["p.lang"] = $this->viewData->lang;
        $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_categories pc" => ["pwc.category_id = pc.id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
        $select = "p.visits visits,GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,p.description,p.content,p.features,p.external_url,IFNULL(pvg.price,p.price) price,p.vat vat,p.vatRate vatRate,IFNULL(pvg.discount,p.discount) discount,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
        $distinct = true;
        $groupBy = ["p.id", "pwc.product_id", "pvg.id"];
        $wheres['p.url'] =  $seo_url;
        /**
         * Get Product Detail
         */
        $this->viewData->product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
        if (!empty($this->viewData->product)) :
            increaseViewer($this->viewData->product->id);
        endif;
        /**
         * Product Parts
         */
        $this->viewData->productParts = $this->general_model->get_all("product_parts", null, "rank ASC", ["isActive" => 1, "product_id" => $this->viewData->product->id, "lang" => $this->viewData->lang]);
        /**
         * Product Dimensions
         */
        $this->viewData->productDimensions = $this->general_model->get_all("product_dimensions", null, "rank ASC", ["isActive" => 1, "product_id" => $this->viewData->product->id, "lang" => $this->viewData->lang]);
        /**
         * Product Variation Groups
         */
        $this->viewData->productVariationGroups = $this->general_model->get_all("product_variation_groups pvg", null, "pvg.id ASC", ["pvg.isActive" => 1, "pvg.product_id" => $this->viewData->product->id, "pvg.lang" => $this->viewData->lang]);

        $category_ids = [];
        foreach ($this->viewData->productVariationGroups as $key => $value) :
            foreach (explode(",", $value->category_id) as $ck => $cv) :
                if (!in_array($cv, $category_ids)) :
                    array_push($category_ids, $cv);
                endif;
            endforeach;
        endforeach;
        $wherein = [];
        if (!empty($category_ids)) :
            /**
             * Product Variation Categories
             */
            $wherein = ["id" => $category_ids];
            $this->viewData->productVariationCategories = $this->general_model->get_all("product_variation_categories", null, "id ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [], $wherein);
            /**
             * Product Variations
             */
            $wherein = ["category_id" => $category_ids];
            $this->viewData->productVariations = $this->general_model->get_all("product_variations", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], [], $wherein);
        endif;
        /**
         * Get All Categories
         */
        $this->viewData->categories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        /**
         * Get Product Images
         */
        $this->viewData->product_own_images = $this->general_model->get_all("product_images", null, "isCover DESC,rank ASC", ["isActive" => 1, "product_id" => $this->viewData->product->id, "lang" => $this->viewData->lang]);
        $imgURL = null;
        if (!empty($this->viewData->product_own_images)) :
            foreach ($this->viewData->product_own_images as $key => $value) :
                if ($value->isCover) :
                    $imgURL = $value->url;
                endif;
            endforeach;
        endif;
        /**
         * Get All Cover Product Images
         */
        $this->viewData->product_images = $this->general_model->get_all("product_images", null, "rank ASC", ["isActive" => 1, "isCover" => 1, "lang" => $this->viewData->lang]);
        /**
         * Selected Categories 
         */
        $pselecteds = [];
        $pselectedCategories = $this->general_model->get_all("products_w_categories", null, null, ["product_id" => $this->viewData->product->id]);
        if (!empty($pselectedCategories)) :
            foreach ($pselectedCategories as $key => $value) :
                if (!in_array($value->category_id, $pselecteds)) :
                    array_push($pselecteds, $value->category_id);
                endif;
            endforeach;
        endif;
        $this->viewData->pselecteds = $pselecteds;
        $select = "p.visits visits,GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isDiscount isDiscount,p.sharedAt";
        $wheres = ["p.isActive" => 1, "p.id!=" => $this->viewData->product->id, "p.lang" => $this->viewData->lang, "pi.isCover" => 1];
        /**
         * Get Suggested Products
         */
        $wheres["p.isSuggested"] = 1;
        $this->viewData->suggestedProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isSuggested"]);
        /**
         * Get New Products
         */
        $wheres["p.isNew"] = 1;
        $this->viewData->newProducts = $this->general_model->get_all("products p", $select, "p.id DESC", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isNew"]);
        /**
         * Get Discount Products
         */
        $wheres["p.isDiscount"] = 1;
        $this->viewData->discountProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [12], [], $distinct, $groupBy);
        unset($wheres["p.isDiscount"]);
        /**
         * Get Similar Products
         */
        $this->viewData->sameProducts = $this->general_model->get_all("products p", $select, "RAND()", $wheres, [], $joins, [5], [], $distinct, $groupBy);
        /**
         * Get Mostly Viewed Products
         */
        $this->viewData->mostlyViewedProducts = $this->general_model->get_all("products p", $select, "visits DESC", $wheres, [], $joins, [12], ["pc.id" => explode(",", $this->viewData->product->category_ids)], $distinct, $groupBy);
        /**
         * Meta
         */
        $this->viewData->merchant_id = self::merchant_id;
        $this->viewData->merchant_key = self::merchant_key;
        $this->viewData->merchant_salt = self::merchant_salt;
        $this->viewData->meta_title = strto("lower|ucwords", $this->viewData->product->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = !empty($this->viewData->product->content) ? @str_replace("”", "\"", @stripslashes($this->viewData->product->content)) : @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_products") . "/" . lang("routes_product") . "/" . $seo_url));
        $this->viewData->og_image           = clean(get_picture("products_v", $imgURL));
        $this->viewData->og_type          = "product.item";
        $this->viewData->og_title           = strto("lower|ucwords", $this->viewData->product->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean(@str_replace("”", "\"", @stripslashes($this->viewData->product->content)));
        if (empty($this->viewData->product)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "product_detail_v/index";
        endif;
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ PRODUCTS ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! =============================== GALLERIES ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Galleries
     */
    public function galleries()
    {
        $seo_url = $this->uri->segment(3);
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $gallery_id = $this->general_model->get("galleries", null, ["isActive" => 1, "isCover" => 0, "lang" => $this->viewData->lang, "url" =>  $seo_url])->id;
        endif;
        $this->load->library("pagination");
        $config = [];
        $config['base_url'] = (!empty($seo_url) && !is_numeric($seo_url) ? base_url(lang("routes_galleries") . "/{$seo_url}") : base_url(lang("routes_galleries")));
        $config['uri_segment'] = (!empty($seo_url) && !is_numeric($seo_url) && !empty($this->uri->segment(4)) ? 4 : (!is_numeric($this->uri->segment(3)) ? 3 : 2));
        $config['use_page_numbers'] = TRUE;
        $config["full_tag_open"] = "<ul class='pagination justify-content-center'>";
        $config["first_link"] = "<i class='fa fa-chevrons-left'></i>";
        $config["first_tag_open"] = "<li class='page-item'>";
        $config["first_tag_close"] = "</li>";
        $config["prev_link"] = "<i class='fa fa-chevron-left'></i>";
        $config["prev_tag_open"] = "<li class='page-item'>";
        $config["prev_tag_close"] = "</li>";
        $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link active' title='" . $this->viewData->settings->company_name . "' rel='dofollow' href='" . @str_replace($this->viewData->lang . "/index.php/", "", current_url()) . "'>";
        $config["cur_tag_close"] = "</a></li>";
        $config["num_tag_open"] = "<li class='page-item'>";
        $config["num_tag_close"] = "</li>";
        $config["next_link"] = "<i class='fa fa-chevron-right'></i>";
        $config["next_tag_open"] = "<li class='page-item'>";
        $config["next_tag_close"] = "</li>";
        $config["last_link"] = "<i class='fa fa-chevrons-right'></i>";
        $config["last_tag_open"] = "<li class='page-item'>";
        $config["last_tag_close"] = "</li>";
        $config["full_tag_close"] = "</ul>";
        $config['attributes'] = array('class' => 'page-link');
        $config['total_rows'] = (!empty($seo_url) && !is_numeric($seo_url) && !empty($gallery_id) ? $this->general_model->rowCount("galleries", ["isActive" => 1, "isCover" => 0, "gallery_id" => $gallery_id, "lang" => $this->viewData->lang]) : $this->general_model->rowCount("galleries", ["isActive" => 1, "lang" => $this->viewData->lang]));
        $config['per_page'] = 8;
        $config["num_links"] = 5;
        $this->pagination->initialize($config);
        if (!empty($seo_url) && !is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(4);
        elseif (!empty($seo_url) && is_numeric($seo_url)) :
            $uri_segment = $this->uri->segment(3);
        else :
            $uri_segment = $this->uri->segment(3);
        endif;
        $offset = (!empty($uri_segment) ? $uri_segment - 1 : 0) * $config['per_page'];
        $this->viewData->offset = $offset;
        $this->viewData->per_page = $config['per_page'];
        $this->viewData->total_rows = $config['total_rows'];
        $this->viewData->galleries = (!empty($seo_url) && !is_numeric($seo_url) && !empty($gallery_id) ? $this->general_model->get_all("galleries", null, null, ['gallery_id' => $gallery_id, "isCover" => 0, "isActive" => 1, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset]) : $this->general_model->get_all("galleries", null, null, ["isActive" => 1, "isCover" => 0, "lang" => $this->viewData->lang], [], [], [$config["per_page"], $offset]));

        $this->viewData->links = $this->pagination->create_links();
        $this->viewData->meta_title = clean(strto("lower|ucwords", lang("galleries"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url(lang("routes_galleries")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = clean(strto("lower|ucwords", lang("galleries"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        if (empty($this->viewData->galleries)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "galleries_v/index";
        endif;
        $this->render();
    }
    /**
     * Gallery Detail
     */
    public function gallery_detail($seo_url)
    {
        $this->viewData->gallery = $this->general_model->get("galleries", null, ["isActive" => 1, "lang" => $this->viewData->lang, 'url' =>  $seo_url]);
        $gallery_type = !empty($this->viewData->gallery->gallery_type) ? $this->viewData->gallery->gallery_type : null;

        $this->viewData->meta_title = strto("lower|ucwords", $this->viewData->gallery->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/" . $seo_url));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = strto("lower|ucwords", $this->viewData->gallery->title) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->viewData->gallery_items = !empty($gallery_type) ? $this->general_model->get_all("{$gallery_type}", null, "rank ASC", ["gallery_id" => $this->viewData->gallery->id, "isActive" => 1, "lang" => $this->viewData->lang]) : [];
        if (empty($this->viewData->gallery_items)) :
            $this->viewFolder = "404_v/index";
        else :
            $this->viewFolder = "gallery_detail_v/index";
        endif;
        $this->render();
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! =============================== GALLERIES ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ CONTACT ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Contact
     */
    public function contact()
    {
        $this->viewFolder = "contact_v/index";

        $this->viewData->meta_title = clean(strto("lower|ucwords", lang("contact"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);

        $this->viewData->og_url                 = clean(base_url(lang("routes_contact")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = clean(strto("lower|ucwords", lang("contact"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->render();
    }
    /**
     * Contact Form
     */
    public function contact_form()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => lang("errorMessageTitleText"), "message" => lang("errorMessageText") . " \"{$key}\" " . lang("emptyMessageText")]);
            die();
        else :
            $message = "\"" . $data['full_name'] . "\" İsimli ziyaretçi yeni mesaj gönderdi. \n <b>Ad Soyad : </b> " . $data["full_name"] . "\n <b>Telefon : </b> " . $data["phone"] . "\n <b>E-mail : </b> " . $data["email"] . "\n <b>Konu : </b>" . $data["subject"] . "\n <b>Mesaj : </b>" . $data["comment"];
            $message = $this->load->view("includes/simple_mail_template", ["settings" => get_settings(), "subject" => "Yeni Bir Mesajınız Var! | " . $this->viewData->settings->company_name, "message" => $message, "lang" => $this->viewData->lang], true);
            if (send_emailv2(null, "Yeni Bir Mesajınız Var! | " . $this->viewData->settings->company_name, $message, [], $this->viewData->lang)) :
                echo json_encode(["success" => true, "title" => lang("successMessageTitleText"), "message" => lang("successMessageText")]);
                die();
            else :
                echo json_encode(["success" => false, "title" => lang("errorMessageTitleText"), "message" => lang("errorEmailMessageText")]);
                die();
            endif;
        endif;
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ CONTACT ================================== !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ LANGUAGE ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * Change Language
     */
    public function switchLanguage($lang = "tr")
    {
        if (!empty(get_active_user())) :
            $this->general_model->update("users", ["id" => get_active_user()->id], ["lang" => $lang]);
        endif;
        set_cookie("lang", $lang, strtotime("+1 year"));
        redirect(asset_url($lang));
    }
    /**
     * -----------------------------------------------------------------------------------------------
     * ...:::!!! ================================ LANGUAGE ================================= !!!:::...
     * -----------------------------------------------------------------------------------------------
     */
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! ============================== USER MODULE ============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * Login Form
     */
    public function loginForm()
    {
        if (!get_active_user()) :
            $this->viewFolder = "login_v/index";

            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("login"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url                 = clean(base_url(lang("routes_login")));
            $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type          = "article";
            $this->viewData->og_title           = clean(strto("lower|ucwords", lang("login"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
            $this->render();
        else :
            redirect(base_url());
        endif;
    }
    /**
     * Login
     */
    public function Login()
    {
        if (!get_active_user()) :
            $this->load->library("form_validation");
            $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email");
            $this->form_validation->set_rules("password", lang("password"), "required|trim|min_length[6]");
            $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"),]);
            if ($this->form_validation->run()) :
                $data = rClean($_POST);
                // Create md5 password
                $data["password"] = mb_substr(sha1(md5($data["password"])), 0, 32);
                $user = $this->general_model->get("users", null, ["email" => $data["email"], "password" => $data["password"]]);
                if (!empty($user)) :
                    if ($user->isActive) :
                        $this->session->set_userdata("user", $user);
                        userRole();
                        $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("loginSuccessfully")]);
                        redirect(base_url(lang("routes_login")));
                    else :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnLoginActivation")]);
                        redirect(base_url(lang("routes_login")));
                    endif;
                else :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnLogin")]);
                    redirect(base_url(lang("routes_login")));
                endif;
            else :
                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emptyFields")]);
                redirect(base_url(lang("routes_login")));
            endif;
        else :
            redirect(base_url(lang("routes_login")));
        endif;
    }
    /**
     * Register Form
     */
    public function registerForm()
    {
        if (!get_active_user()) :
            $this->viewFolder = "register_v/index";

            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("register"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url                 = clean(base_url(lang("routes_register")));
            $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type          = "article";
            $this->viewData->og_title           = clean(strto("lower|ucwords", lang("register"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
            $this->render();
        //$this->output->enable_profiler(TRUE);
        else :
            redirect(base_url());
        endif;
    }
    /**
     * Register
     */
    public function register()
    {
        if (!get_active_user()) :
            $this->load->library("form_validation");
            $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email");
            $this->form_validation->set_rules("password", lang("password"), "required|trim|min_length[6]");
            $this->form_validation->set_rules("passwordRepeat", lang("passwordRepeat"), "required|trim|min_length[6]");
            $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"),]);
            if ($this->form_validation->run()) :
                $data = rClean($_POST);
                if ($data["password"] !== $data["passwordRepeat"]) :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("samePassword")]);
                    redirect(base_url(lang("routes_register")));
                else :
                    // Unset passwordRepeat && Terms Policy
                    unset($data["passwordRepeat"]);
                    unset($data["termsPolicy"]);
                    // Create md5 password
                    $data["password"] = mb_substr(sha1(md5($data["password"])), 0, 32);
                    $data["token"] = random_string('alnum', 255);
                    $data["phone"] = @str_replace(" ", "", $data["phone"]);
                    if (!empty($this->general_model->get("users", null, ["email" => $data["email"]]))) :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emailExists")]);
                        redirect(base_url(lang("register")));
                    else :
                        if (!empty($this->general_model->get("users", null, ["phone" => $data["phone"]]))) :
                            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("phoneExists")]);
                            redirect(base_url(lang("register")));
                        else :
                            unset($data[$this->security->get_csrf_token_name()]);
                            if ($this->general_model->add("users", $data)) :
                                $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("registerSuccessfully")]);
                                $activationLink = "<a href='" . base_url(lang("routes_activation") . "/?email=" . $data["email"] . "&phone=" . $data["phone"] . "&token=" . $data["token"]) . "' rel='dofollow' target='_blank'>" . lang("activationLinkText") . "</a>";
                                $message = lang("emailMessage") . $activationLink;
                                $message = $this->load->view("includes/simple_mail_template", ["settings" => get_settings(), "subject" => lang("registerMailTitle"), "message" => $message, "lang" => $this->viewData->lang], true);
                                if (send_emailv2([$data["email"]], lang("registerMailTitle"), $message, [], $this->viewData->lang)) :
                                    redirect(base_url(lang("register")));
                                else :
                                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnRegister")]);
                                    redirect(base_url(lang("register")));
                                endif;
                            else :
                                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnRegister")]);
                                redirect(base_url(lang("register")));
                            endif;
                        endif;
                    endif;
                endif;
            else :
                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emptyFields")]);
                redirect(base_url(lang("routes_register")));
            endif;
        else :
            redirect(base_url(lang("routes_register")));
        endif;
    }
    /**
     * Forgot Password Form
     */
    public function forgotPasswordForm()
    {
        if (!get_active_user()) :
            $this->viewFolder = "forgot_password_v/index";

            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("forgotPassword"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url                 = clean(base_url(lang("routes_forgot-password")));
            $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type          = "article";
            $this->viewData->og_title           = clean(strto("lower|ucwords", lang("forgotPassword"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
            $this->render();
        else :
            redirect(base_url());
        endif;
    }
    /**
     * Forgot Password
     */
    public function forgotPassword()
    {
        if (!get_active_user()) :
            if (!empty($_POST["email"]) && empty($_POST["token"])) :
                $this->load->library("form_validation");
                $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email");
                $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"),]);
                if ($this->form_validation->run() === FALSE) :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emptyFields")]);
                    redirect(base_url(lang("routes_forgot-password")));
                else :
                    $data = rClean($_POST);
                    $user = $this->general_model->get("users", null, ["isActive" => 1, "email" => $data["email"]]);
                    if (!empty($user)) :
                        $data["token"] = random_string('alnum', 255);
                        $message = "<a href='" . base_url(lang("routes_forgot-password-reset") . "?email=" . $user->email . "&phone=" . $user->phone . "&token=" . $data["token"]) . "' rel='dofollow' target='_blank'>" . lang("resetLinkText") . "</a>";
                        $message = $this->load->view("includes/simple_mail_template", ["settings" => get_settings(), "subject" => lang("forgotMailTitle"), "message" => $message, "lang" => $this->viewData->lang], true);
                        if (send_emailv2([$data["email"]], lang("forgotMailTitle"), $message, [], $this->viewData->lang)) :
                            $this->general_model->update("users", ["email" => $data["email"]], ["token" => $data["token"]]);
                            $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("resetMailSuccessfully")]);
                            redirect(base_url(lang("routes_login")));
                        else :
                            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnForgotPassword")]);
                            redirect(base_url(lang("routes_forgot-password")));
                        endif;
                    else :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnForgotPassword")]);
                        redirect(base_url(lang("routes_forgot-password")));
                    endif;
                endif;
            elseif (!empty($_POST["token"]) && !empty($_POST["password"] && !empty($_POST["email"]) && !empty($_POST["passwordRepeat"]) && !empty($_POST["phone"]))) :
                $data = rClean($_POST);
                $user = $this->general_model->get("users", null, ["isActive" => 1, "email" => $data["email"], "token" => $_POST["token"]]);
                if (!empty($user)) :
                    if ($data["password"] == $data["passwordRepeat"]) :
                        $data["password"] = mb_substr(sha1(md5($data["password"])), 0, 32);
                        if ($this->general_model->update("users", ["token" => $data["token"], "email" => $data["email"], "phone" => $data["phone"]], ["password" => $data["password"], "token" => random_string("alnum", 255)])) :
                            $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("resetSuccessfully")]);
                            redirect(base_url(lang("routes_login")));
                        else :
                            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnForgotPassword")]);
                            redirect(base_url(lang("routes_forgot-password")));
                        endif;
                    else :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("samePassword")]);
                        redirect(base_url(lang("routes_forgot-password-reset") . "?email=" . $data["email"] . "&phone=" . $data["phone"] . "&token=" . $data["token"]));
                    endif;
                else :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnForgotPassword")]);
                    redirect(base_url(lang("routes_forgot-password")));
                endif;
            else :
                $this->viewFolder = "forgot_password_reset_v/index";
                $this->viewData->meta_title = clean(strto("lower|ucwords", lang("forgotPassword"))) . " - " . $this->viewData->settings->company_name;
                $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
                $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
                $this->viewData->og_url                 = clean(base_url(lang("routes_forgot-password")));
                $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
                $this->viewData->og_type          = "article";
                $this->viewData->og_title           = clean(strto("lower|ucwords", lang("forgotPassword"))) . " - " . $this->viewData->settings->company_name;
                $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
                $this->render();
            endif;
        else :
            redirect(base_url());
        endif;
    }
    /**
     * Activation
     */
    public function activation()
    {
        if (!get_active_user()) :
            if (!empty($_GET["email"]) && !empty($_GET["phone"]) && !empty($_GET["token"])) :
                $getUser = $this->general_model->get("users", null, ["isActive" => 0, "email" => $_GET["email"], "phone" => $_GET["phone"], "token" => $_GET["token"]]);
                if (!empty($getUser)) :
                    if ($this->general_model->update("users", ["id" => $getUser->id], ["isActive" => 1, "token" => random_string("alnum", 255)])) :
                        $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("activatedSuccessfully")]);
                        redirect(base_url(lang("routes_login")));
                    else :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnActivation")]);
                        redirect(base_url(lang("routes_register")));
                    endif;
                else :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("errorOnActivationLink")]);
                    redirect(base_url(lang("routes_register")));
                endif;
            else :
                redirect(base_url());
            endif;
        else :
            redirect(base_url());
        endif;
    }
    /**
     * Logout
     */
    public function logout()
    {
        $this->session->unset_userdata("user");
        $this->session->unset_userdata("user_roles");
        $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("logoutSuccessfully")]);
        redirect(base_url());
    }
    /**
     * Account
     */
    public function account()
    {
        if (!get_active_user()) :
            redirect(base_url(lang("routes_login")));
        else :
            $this->load->library("form_validation");
            $this->viewFolder = "users_v/index";
            $this->viewData->orders = $this->general_model->get_all("orders", null, "id DESC", ["user_id" => get_active_user()->id, "isActive" => 1]);
            $this->viewData->address_informations = $this->general_model->get_all("user_addresses", null, "id DESC", ["user_id" => get_active_user()->id]);

            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("account"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url                 = clean(base_url(lang("routes_account")));
            $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type          = "article";
            $this->viewData->og_title           = clean(strto("lower|ucwords", lang("account"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
            $this->render();
        endif;
    }
    /**
     * Update Account
     */
    public function accountUpdate()
    {
        if (!get_active_user()) :
            redirect(base_url(lang("routes_login")));
        else :
            $this->load->library("form_validation");
            $this->form_validation->set_rules("email", lang("email"), "required|trim|valid_email");
            if (!empty(clean($_POST["password"]))) :
                $this->form_validation->set_rules("password", lang("password"), "required|trim|min_length[6]");
                $this->form_validation->set_rules("passwordRepeat", lang("passwordRepeat"), "required|trim|min_length[6]");
            endif;
            $this->form_validation->set_message(["required"  => lang("required"), "valid_email" => lang("valid_email"), "min_length" => lang("min_length"),]);
            if ($this->form_validation->run()) :
                $data = rClean($_POST);
                if (!empty(clean($_POST["password"])) && !empty(clean($_POST["passwordRepeat"])) && $data["password"] !== $data["passwordRepeat"]) :
                    $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("samePassword")]);
                    redirect(base_url(lang("routes_account")));
                else :
                    if (!empty(clean($_POST["password"]))) :
                        // Create md5 password
                        $data["password"] = mb_substr(sha1(md5($data["password"])), 0, 32);
                        unset($data["passwordRepeat"]);
                    else :
                        // Unset passwordRepeat && Terms Policy
                        unset($data["password"]);
                        unset($data["passwordRepeat"]);
                    endif;
                    $data["token"] = random_string('alnum', 255);
                    $data["phone"] = @str_replace(" ", "", $data["phone"]);
                    if (!empty($this->general_model->get("users", null, ["email" => $data["email"], "id!=" => get_active_user()->id]))) :
                        $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emailExists")]);
                        redirect(base_url(lang("routes_account")));
                    else :
                        if (!empty($this->general_model->get("users", null, ["phone" => $data["phone"], "id!=" => get_active_user()->id]))) :
                            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("phoneExists")]);
                            redirect(base_url(lang("routes_account")));
                        else :
                            unset($data[$this->security->get_csrf_token_name()]);
                            if ($this->general_model->update("users", ["id" => get_active_user()->id], $data)) :
                                $this->session->set_flashdata("alert", ["success" => true, "title" => lang("success"), "msg" => lang("updatedSuccessfully")]);
                                $user = $this->general_model->get("users", null, ["email" => $data["email"], "phone" => $data["phone"]]);
                                $this->session->set_userdata("user", $user);
                                userRole();
                                redirect(base_url(lang("routes_account")));
                            else :
                                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("success"), "msg" => lang("errorOnUpdate")]);
                                redirect(base_url(lang("routes_account")));
                            endif;
                        endif;
                    endif;
                endif;
            else :
                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("emptyFields")]);
                redirect(base_url(lang("routes_account")));
            endif;
        endif;
    }
    /**
     * Get Order Detail
     */
    public function order_detail($order_code)
    {
        if (get_active_user()) :
            $item = $this->general_model->get(
                "orders",
                "orders.id id,
                users.full_name full_name,
                users.email email,
                users.lang lang,
                orders.symbol symbol,
                orders.phone phone,
                orders.country country,
                orders.city city,
                orders.district district,
                orders.neighborhood neighborhood,
                orders.quarter quarter,
                orders.postalCode postalCode,
                orders.address address,
                orders.address_title address_title,
                orders.shippingPrice shippingPrice,
                orders.vat vat,
                orders.subTotal subTotal,
                orders.total total,
                orders.couponName,
                orders.couponDiscount,
                orders.isActive,
                orders.createdAt,
                orders.updatedAt,
                orders.status,
                orders.shippingCode,
                orders.shippingCompany,
                orders.shippingStatus,
                orders.order_data,
                orders.paymentType",
                ["orders.order_code" => $order_code, "users.id" => get_active_user()->id],
                ["users" => ["users.id = orders.user_id", "left"]]
            );
            $this->viewData->order_data = json_decode(base64_decode($item->order_data), true);
            $this->viewData->item = $item;
            $this->load->view("includes/orderDetail", (array)$this->viewData);
        else :
            die();
        endif;
    }
    /**
     * Get Address
     */
    public function get_address($chooseable = null)
    {

        if (get_active_user()) :
            /**
             * User Address İnformation
             */
            $this->viewData->address_informations = $this->general_model->get_all("user_addresses", null, "id DESC", ["user_id" => get_active_user()->id]);
            if (!empty($chooseable)) :
                $this->load->view("includes/addressChooseable", (array)$this->viewData);
            else :
                $this->load->view("includes/address", (array)$this->viewData);
            endif;
        else :
            die();
        endif;
    }

    /**
     * User Address Form
     */
    public function newAddressForm()
    {
        $this->viewData->allCountries = $this->general_model->get_all("countries");
        $this->viewData->allCities = $this->general_model->get_all("cities");
        $this->viewFolder = 'users_v/address';
        $this->load->view($this->viewFolder, $this->viewData);
    }

    /**
     * User Create New Address
     */
    public function new_address()
    {
        if (get_active_user()) :
            $data = rClean($this->input->post());
            if (checkEmpty($data)["error"]) :
                $key = checkEmpty($data)["key"];
                echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("addressEmpty")]);
            else :
                $data["user_id"] = get_active_user()->id;
                unset($data[$this->security->get_csrf_token_name()]);
                $insert = $this->general_model->add("user_addresses", $data);
                if ($insert) :
                    echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("addressSuccessfully")]);
                else :
                    echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("addressError")]);
                endif;
            endif;
        else :
            die();
        endif;
    }

    /**
     * User Edit Address Form
     */
    public function editAddressForm($id = null)
    {
        if (get_active_user()) :
            if (!empty($id)) :
                $item = $this->general_model->get("user_addresses", null, ["id" => $id, "user_id" => get_active_user()->id]);
                if (!empty($item)) :
                    $this->viewData->allCountries = $this->general_model->get_all("countries");
                    $this->viewData->allCities = $this->general_model->get_all("cities");
                    $this->viewData->allDistricts = $this->general_model->get_all("districts");
                    $this->viewData->allNeighborhoods = $this->general_model->get_all("neighborhoods");
                    $this->viewData->allQuarters = $this->general_model->get_all("quarters");
                    $this->viewData->item = $item;
                    $this->viewFolder = 'users_v/editaddress';
                    $this->load->view($this->viewFolder, $this->viewData);
                endif;
            endif;
        else :
            die();
        endif;
    }

    /**
     * Update Address
     */
    public function update_address($id = null)
    {

        if (get_active_user()) :
            if (!empty($id)) :
                $data = rClean($_POST);
                unset($data[$this->security->get_csrf_token_name()]);
                if ($this->general_model->update("user_addresses", ["id" => $id, "user_id" => get_active_user()->id], $data)) :
                    echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("addressUpdate")]);
                else :
                    echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("addressUpdateError")]);
                endif;
            endif;
        else :
            die();
        endif;
    }

    /**
     * Delete Address
     */
    public function delete_address($id = null)
    {
        if (get_active_user()) :
            if (!empty($id)) :
                if ($this->general_model->delete("user_addresses", ["id" => $id])) :
                    echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("addressDeleted")]);
                else :
                    echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("addressDeleteError")]);
                endif;
            endif;
        else :
            die();
        endif;
    }

    /**
     * Get Districts
     */
    public function getDistricts($district = null)
    {
        if (empty($district)) :
            $data = rClean($_POST);
            $city = $this->general_model->get("cities", null, ["city" => $data["city"]]);
            $districts = $this->general_model->get_all("districts", null, null, ["cities_id" => $city->city_id]);
            echo json_encode($districts);
        else :
            $neighborhood = $this->general_model->get("districts", null, ["district" => trim(urldecode($district))]);
            echo json_encode($neighborhood);
        endif;
    }

    /**
     * Get Neigborhoods
     */
    public function getNeighborhoods($neighborhood = null)
    {
        if (empty($neighborhood)) :
            $data = rClean($_POST);
            $district = $this->general_model->get("districts", null, ["district" => $data["district"]]);
            $neighborhoods = $this->general_model->get_all("neighborhoods", null, null, ["districts_id" => $district->district_id]);
            echo json_encode($neighborhoods);
        else :
            $neighborhood = $this->general_model->get("neighborhoods", null, ["neighborhood" => trim(urldecode($neighborhood))]);
            echo json_encode($neighborhood);
        endif;
    }

    /**
     * Get Quarters
     */
    public function getQuarters($quarter = null)
    {
        if (empty($quarter)) :
            $data = rClean($_POST);
            $neighborhood = $this->general_model->get("neighborhoods", null, ["neighborhood" => $data["neighborhood"]]);
            $quarters = $this->general_model->get_all("quarters", null, null, ["neighborhoods_id" => $neighborhood->neighborhood_id]);
            echo json_encode($quarters);
        else :
            $quarter = $this->general_model->get("quarters", null, ["quarter" => trim(urldecode($quarter))]);
            echo json_encode($quarter);
        endif;
    }
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! ============================== USER MODULE ============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * ---------------------------------------------------------------------------------------------
     * ...:::!!! ============================== CART MODULE ============================== !!!:::...
     * ---------------------------------------------------------------------------------------------
     */
    /**
     * My Cart
     */
    public function myCart()
    {
        if (!empty($_GET["removecoupon"])) :
            unset($_SESSION["couponName"]);
            unset($_SESSION["couponDiscount"]);
            $this->general_model->delete("orders", ["user_id" => (!empty(get_active_user()->id) ? get_active_user()->id : null), "paymentType" => null]);
            redirect(base_url(lang("routes_cart")));
        endif;
        $this->viewFolder = "cart_v/index";
        $this->viewData->meta_title = clean(strto("lower|ucwords", lang("myCart"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
        $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
        $this->viewData->og_url                 = clean(base_url(lang("routes_cart")));
        $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
        $this->viewData->og_type          = "article";
        $this->viewData->og_title           = clean(strto("lower|ucwords", lang("myCart"))) . " - " . $this->viewData->settings->company_name;
        $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
        $this->render();
    }
    /**
     * Cart
     */
    public function cart($count = null)
    {
        if (empty($count)) :
            $this->load->view("includes/cart", $this->viewData);
        else :
            echo count($this->cart->contents());
        endif;
    }
    /**
     * Header Cart
     */
    public function headerCart($count = null)
    {
        if (empty($count)) :
            $this->load->view("includes/headerCart", $this->viewData);
        else :
            echo count($this->cart->contents());
        endif;
    }
    /**
     * Add To Cart
     */
    public function addToCart()
    {

        unset($_POST[$this->security->get_csrf_token_name()]);
        $data = rClean($_POST);
        if (!empty($data) && !empty($data["id"])) :
            /**
             * Get Product 
             */
            if (!empty($data["pvgId"])) :
                $wheres["pvg.id"] = $data["pvgId"];
            endif;
            $wheres["p.isActive"] = 1;
            $wheres["pi.isCover"] = 1;
            $wheres["p.id"] = $data["id"];
            $wheres["p.lang"] = $this->viewData->lang;
            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
            $select = "p.id,IFNULL(pvg.id, NULL) pvgId,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,IFNULL(pvg.stock,p.stock) stock,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
            $distinct = true;
            $groupBy = ["pwc.product_id", "pvg.id"];
            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
            if (!empty($product)) :
                $counterArr = [];
                $product->newPrice = ((int)$product->vat ?  ($product->newPrice + $product->newPrice * ($product->vatRate / 100)) : $product->newPrice);
                $price = ($product->isDiscount ? ($product->newPrice - ($product->newPrice * ((float)$product->discount / 100))) : $product->newPrice);
                $options = ["partArray" => []];
                if (!empty($data["partArray"])) :

                    $j = 0;
                    foreach ($data["partArray"] as $ppKey => $ppValue) :
                        if ($ppValue["quantity"] > 0 && (float)$ppValue["price"] > 0) :
                            array_push($counterArr, $ppValue["id"]);
                        endif;
                    endforeach;
                    foreach ($data["partArray"] as $ppKey => $ppValue) :
                        if ($ppValue["quantity"] > 0 && (float)$ppValue["price"] > 0) :
                            $j++;
                            if ($j == 1) :
                                $product->title .= " (" . lang("modules") . ": ";
                            endif;
                            array_push($options["partArray"], ["title" => $ppValue["title"], "quantity" => $ppValue["quantity"], "price" => $ppValue["price"]]);
                            $addToTotalPrice =  $ppValue["price"] * $ppValue["quantity"];
                            $price += $addToTotalPrice;
                            $product->title .= $ppValue["quantity"] . " " . lang("quantity") . " " . stripslashes($ppValue["title"]) . ($j == count($counterArr) ? "" : ", ");
                            if ($j == count($counterArr)) :
                                $product->title .= ") ";
                            endif;
                        endif;
                    endforeach;
                    if (empty($counterArr) && !empty($options["partArray"])) :
                        $price = 0;
                        $_POST["quantity"] = -1;
                    endif;
                endif;
                if (empty($_POST["quantity"]) || (float)$_POST["quantity"] == 0) :
                    $options["mainQuantity"] = FALSE;
                    $product->title .= lang("onlyPartsIncluded");
                else :
                    $options["mainQuantity"] = TRUE;
                endif;
                $cartData = ["id" => $product->id, "qty" => (empty($_POST["quantity"]) ? 1 : $_POST["quantity"]), "price" => $price, "name" => clean(stripslashes(trim($product->title)))];
                if ((float)$cartData["price"] <= 0) :
                    echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                    die();
                endif;
                if (!empty($data["pvgId"])) :
                    $options["pvgId"] = $data["pvgId"];
                endif;
                $cartData["options"] = $options;
                $stockStatus = $product->stockStatus;
                /**
                 * IF NOT EMPTY THE CART
                 */
                if ($cartData["qty"] <= 0 && empty($counterArr) && !empty($options["partArray"])) :
                    $cartData = [];
                endif;
                if (empty($cartData)) :
                    echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                    die();
                endif;
                if (!empty($this->cart->contents())) :
                    $contentIds = [];
                    foreach ($this->cart->contents() as $items) :
                        if (!in_array($items["id"], $contentIds)) :
                            array_push($contentIds, $items["id"]);
                        endif;
                        if ($items["id"] === $cartData["id"] && !empty($product->pvgId) && $product->pvgId === $items["options"]["pvgId"]) :
                            if ((float)$items["qty"] >= (float)$product->stock || !$stockStatus) :
                                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                                die();
                            else :
                                $this->cart->insert($cartData);
                                echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToCartSuccessfully"), "cartData" => $cartData]);
                                die();
                            endif;
                        elseif ($items["id"] === $cartData["id"] && empty($product->pvgId)) :
                            if ((float)$items["qty"] >= (float)$product->stock || !$stockStatus) :
                                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                                die();
                            else :
                                $this->cart->insert($cartData);
                                echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToCartSuccessfully"), "cartData" => $cartData]);
                                die();
                            endif;
                        endif;
                    endforeach;
                    if (!in_array($cartData["id"], $contentIds) && $stockStatus && (float)$cartData["qty"] <= (float)$product->stock) :
                        $this->cart->insert($cartData);
                        echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToCartSuccessfully"), "cartData" => $cartData]);
                        die();
                    elseif (in_array($cartData["id"], $contentIds) && $stockStatus && (float)$cartData["qty"] <= (float)$product->stock && $product->pvgId !== $items["options"]["pvgId"]) :
                        $this->cart->insert($cartData);
                        echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToCartSuccessfully"), "cartData" => $cartData]);
                        die();
                    else :
                        echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                        die();
                    endif;
                else :
                    /**
                     * ELSE EMPTY CART
                     */
                    if ($stockStatus && (float)$cartData["qty"] <= (float)$product->stock) :
                        $this->cart->insert($cartData);
                        echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToCartSuccessfully"), "cartData" => $cartData]);
                        die();
                    else :
                        echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                        die();
                    endif;
                endif;
            else :
                die();
            endif;
        else :
            die();
        endif;
    }
    /**
     * Update Cart
     */
    public function updateCart()
    {
        $data = rClean($_POST);
        if (!empty($data) && !empty($data["rowid"]) && !empty($data["qty"])) :
            /**
             * Get Product 
             */
            $cartData = $this->cart->get_item($data["rowid"]);
            /**
             * Get Product 
             */
            if (!empty($cartData["options"]["pvgId"])) :
                $wheres["pvg.id"] = $cartData["options"]["pvgId"];
            endif;
            $wheres["p.isActive"] = 1;
            $wheres["pi.isCover"] = 1;
            $wheres["p.id"] = $cartData["id"];
            $wheres["p.lang"] = $this->viewData->lang;
            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
            $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,IFNULL(pvg.stock,p.stock) stock,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
            $distinct = true;
            $groupBy = ["pwc.product_id", "pvg.id"];
            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
            if (!empty($product)) :
                $stockStatus = $product->stockStatus;
                if ($data["qty"] > $product->stock || !$stockStatus) :
                    echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("youCannotAddThisItemMoreToCart")]);
                    die();
                else :
                    unset($data[$this->security->get_csrf_token_name()]);
                    $this->cart->update($data);
                    echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("updatedCartSuccessfully")]);
                endif;
            else :
                die();
            endif;
        else :
            die();
        endif;
    }
    /**
     * Remove From Cart
     */
    public function removeFromCart()
    {
        $data = rClean($_POST);
        if (!empty($data) && !empty($data["rowid"])) :
            /**
             * Remove Product 
             */
            $this->cart->remove($data["rowid"]);
            echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("removedFromCartSuccessfully")]);
        else :
            die();
        endif;
    }
    /**
     * Empty Cart
     */
    public function emptyCart()
    {
        /**
         * Remove Product 
         */
        $this->cart->destroy();
        echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("emptyCartSuccessfully")]);
    }
    /**
     * Apply Coupon
     */
    public function applyCoupon()
    {
        $data = rClean($_POST);
        if (!empty($data) && !empty($data["coupon"])) :
            if (get_active_user()) :
                if (count($this->cart->contents()) > 0) :
                    $now = date("Y-m-d H:i:s");
                    $coupon = $this->general_model->get("coupons", null, ["isActive" => 1, "lang" => $this->viewData->lang], [], ["title" => $data["coupon"]]);
                    if (!empty($coupon)) :
                        if (strtotime($now) >= strtotime($coupon->startedAt) && strtotime($now) <= strtotime($coupon->finishedAt)) :
                            if (!empty($coupon->user_id) && get_active_user()->id !== $coupon->user_id) :
                                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("couponCodeNotOwned")]);
                                die();
                            endif;
                            $couponUsage = $this->general_model->get("orders", null, ["isActive" => 1, "status!=" => "Ödeme Bekleniyor.", "status!=" => "İptal Edildi."], [], ["couponName" => $data["coupon"], "user_id" => get_active_user()->id]);
                            if (!empty($couponUsage)) :
                                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("couponCodeExpired")]);
                                die();
                            endif;
                            $couponMinPrice = (float)$coupon->minPrice;
                            if ($this->cart->total() >= $couponMinPrice) :
                                $_SESSION["couponName"] = $coupon->title;
                                $_SESSION["couponDiscount"] = $coupon->discount;
                                echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("couponCodeApplied")]);
                                die();
                            else :
                                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("couponMinPrice") . ": " . $couponMinPrice . $this->viewData->symbol]);
                            endif;
                        else :
                            echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("couponCodeExpired")]);
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("invalidCoupon")]);
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("mustOneItemInCart")]);
                endif;
            else :
                echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("loginBeforeApplyCoupon")]);
            endif;
        else :
            echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("couponEmpty")]);
        endif;
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ================================ CART MODULE =============================== !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    /**
     * Choose Address
     */
    public function chooseAddress()
    {
        if (get_active_user()) :
            $this->viewFolder = "order_v/address";
            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("chooseAddress"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url = clean(base_url(lang("routes_choose-address")));
            $this->viewData->og_image = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type = "article";
            $this->viewData->og_title = clean(strto("lower|ucwords", lang("chooseAddress"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description = clean($this->viewData->settings->meta_description);
            $this->viewData->address_informations = $this->general_model->get_all("user_addresses", null, "id DESC", ["user_id" => get_active_user()->id], [], [], []);
            $this->render();
        else :
            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("loginToContinue")]);
            redirect(base_url(lang("routes_login")));
        endif;
    }
    /**
     * Change Selected Address
     */
    public function changeSelectedAddress($selected = null)
    {
        if (get_active_user()) :
            if (!empty($selected)) :
                $this->session->set_userdata("choosedAddress", $selected);
                echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("addressChangedSuccessFully"), "choosedAddress" => $this->session->choosedAddress]);
            else :
                echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("youCannotChangeAddress")]);
                die();
            endif;
        else :
            echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("youCannotChangeAddress")]);
            die();
        endif;
    }
    /**
     * Choose Payment
     */
    public function choosePayment()
    {
        if (get_active_user()) :

            // GET DELIVERY AND INVOICE ADDRESS
            $get_address                    = $this->general_model->get("user_addresses", null, ["id" => $this->session->choosedAddress, "user_id" => get_active_user()->id]);
            if (empty($get_address)) :
                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("chooseAddressToContinue")]);
                redirect(base_url(lang("routes_choose-address")));
            endif;


            // Müşterinin sepet/sipariş içeriği	
            $user_basket                     = $this->session->checkout;
            if (empty($user_basket["cart"])) :
                $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("yourCartIsEmpty")]);
                redirect(base_url(lang("routes_products")));
            endif;

            // PAYTR DATA
            $paytr_data =
                [
                    'order_id'       => hash('sha256', microtime() . get_active_user()->id),
                    'name_surname'   => get_active_user()->full_name,
                    'invoice_adress' => $get_address->address . ' ' . $get_address->quarter . ' ' . $get_address->neighborhood . ' ' . $get_address->district . '/' . $get_address->city . ', ' . $get_address->country . ' ' . $get_address->postalCode,
                    'phone'          => $get_address->phone,
                    'email'          => get_active_user()->email,
                    'ok_url'         => base_url(lang("routes_payment") . "?payment=success"),
                    'fail_url'       => base_url(lang("routes_payment") . "?payment=error"),
                ];

            ########################################################################################
            # PAYTR DATA
            ########################################################################################
            $payment_amount                    = (float)@str_replace(",", "", $user_basket["total"]) * 100; // $price* 100 Required
            $email                             = $paytr_data['email'];
            $merchant_oid                     = $paytr_data['order_id'];
            $user_name                         = $paytr_data['name_surname'];
            $user_address                     = $paytr_data['invoice_adress'];
            $user_phone                     = $paytr_data['phone'];
            $merchant_ok_url                 = $paytr_data['ok_url'];
            $merchant_fail_url                 = $paytr_data['fail_url'];
            $user_basket_paytr = [];
            if (!empty($user_basket["cart"])) :
                foreach ($user_basket["cart"] as $items) :
                    /**
                     * Cart & Wishlist Products
                     */
                    $wheres["p.isActive"] = 1;
                    $wheres["pi.isCover"] = 1;
                    $wheres["p.id"] = $items["id"];
                    $wheres["p.lang"] = $this->viewData->lang;
                    if (!empty($items["options"]) && !empty($items["options"]["pvgId"])) :
                        $wheres["pvg.id"] = $items["options"]["pvgId"];
                    else :
                        if (array_key_exists("pvg.id", $wheres)) :
                            unset($wheres["pvg.id"]);
                        endif;
                    endif;
                    $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                    $select = "p.id,p.title,p.url,pi.url img_url,IFNULL(pvg.price,p.price) price,IFNULL(pvg.discount,p.discount) discount,p.vat vat,p.vatRate vatRate,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,IFNULL(pvg.price,p.price) AS newPrice";
                    $distinct = null;
                    $groupBy = ["pwc.product_id"];
                    $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                    ((bool)$product->vat ? $vat =  ((float)$items['price'] * (float)$items["qty"]) - ((float)$items["qty"] * (float)$product->newPrice) : 0);
                    if (!empty($items["options"]["pvgId"])) :
                        $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $items["options"]["pvgId"], "lang" => $this->viewData->lang]);
                        $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                        $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                        $product_variations = [];
                        $product_variation_categories = [];
                        if (!empty($product_variation_in_group)) :
                            foreach ($product_variation_in_group as $key => $value) :
                                $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value, "lang" => $this->viewData->lang]);
                                $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key], "lang" => $this->viewData->lang]);
                                array_push($product_variation_categories, $product_variation_group->title);
                                array_push($product_variations, $product_variation->title);
                            endforeach;
                        endif;
                    endif;
                    $productName = $product->title;
                    if (!empty($items["options"]["pvgId"])) :
                        if (!empty($product_variation_categories) && !empty($product_variations)) :
                            $count = count($product_variation_categories);
                            $i = 1;
                            $productName .= " ( ";
                            foreach ($product_variation_categories as $key => $value) :
                                if ($i < $count) :
                                    $productName .= $value . ":" . $product_variations[$key] . ", ";
                                else :
                                    $productName .= $value  . ":" . $product_variations[$key];
                                endif;
                                $i++;
                            endforeach;
                            $productName .= " ) ";
                        endif;
                    endif;
                    array_push($user_basket_paytr, [$productName, number_format(($product->newPrice + ((bool)$product->vat ? $vat : 0)), 2), $items["qty"]]);
                endforeach;
            endif;


            $user_basket_paytr = base64_encode(json_encode($user_basket_paytr));
            $user_basket                     = base64_encode(json_encode($user_basket));

            // Müşterinin IP adresi
            if (isset($_SERVER["HTTP_CLIENT_IP"])) :
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) :
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            else :
                $ip = $_SERVER["REMOTE_ADDR"];
            endif;

            // Eğer sunucuda değil local makinanızda çalıştırıyorsanız
            // buraya dış ip adresinizi (https://www.whatismyip.com/) yazmalısınız. Aksi halde geçersiz paytr_token hatası alırsınız.
            $user_ip                         = $ip;

            // İşlem zaman aşımı süresi - dakika cinsinden
            $timeout_limit                     = "60";

            // Hata mesajlarının ekrana basılması için entegrasyon ve test sürecinde 1 olarak bırakın. Daha sonra 0 yapabilirsiniz.
            $debug_on                         = 0;

            // Mağaza canlı modda iken test işlem yapmak için 1 olarak gönderilebilir.
            $test_mode                         = 0;

            // Taksit yapılmasını istemiyorsanız, sadece tek çekim sunacaksanız 1 yapın
            $no_installment                    = 0;

            // Sayfada görüntülenecek taksit adedini sınırlamak istiyorsanız uygun şekilde değiştirin.
            // Sıfır (0) gönderilmesi durumunda yürürlükteki en fazla izin verilen taksit geçerli olur.
            $max_installment                 = 0;

            // PARA BİRİMİ
            $currency                         = ($this->viewData->currency == "TRY" ? "TL" : $this->viewData->currency);

            // Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur.
            $hash_str = self::merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket_paytr . $no_installment . $max_installment . $currency . $test_mode;
            $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . self::merchant_salt, self::merchant_key, true));
            $post_vals = [
                'merchant_id'            => self::merchant_id,
                'user_ip'                => $user_ip,
                'merchant_oid'             => $merchant_oid,
                'email'                    => $email,
                'payment_amount'        => $payment_amount,
                'paytr_token'            => $paytr_token,
                'user_basket'            => $user_basket_paytr,
                'debug_on'                => $debug_on,
                'no_installment'        => $no_installment,
                'max_installment'         => $max_installment,
                'user_name'             => $user_name,
                'user_address'             => $user_address,
                'user_phone'             => $user_phone,
                'merchant_ok_url'         => $merchant_ok_url,
                'merchant_fail_url'     => $merchant_fail_url,
                'timeout_limit'         => $timeout_limit,
                'currency'                 => $currency,
                'test_mode'             => $test_mode
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            $result = @curl_exec($ch);
            if (curl_errno($ch))
                die("PAYTR IFRAME connection error. err:" . curl_error($ch));
            curl_close($ch);
            $result = json_decode($result, 1);
            $token = null;
            if (@$result['status'] === 'success') :
                $token = $result['token'];
            endif;

            $user_basket = json_decode(base64_decode($user_basket), true);

            // Create Order

            if (!empty($user_basket)) :
                $this->general_model->delete("orders", ["user_id" => (!empty(get_active_user()->id) ? get_active_user()->id : null), "paymentType" => null]);
                $this->general_model->add(
                    "orders",
                    [
                        "user_id" => (!empty(get_active_user()->id) ? get_active_user()->id : null),
                        "phone" => (!empty($get_address->phone) ? $get_address->phone : null),
                        "country" => (!empty($get_address->country) ? $get_address->country : null),
                        "city" => (!empty($get_address->city) ? $get_address->city : null),
                        "district" => (!empty($get_address->district) ? $get_address->district : null),
                        "neighborhood" => (!empty($get_address->neighborhood) ? $get_address->neighborhood : null),
                        "quarter" => (!empty($get_address->quarter) ? $get_address->quarter : null),
                        "postalCode" => (!empty($get_address->postalCode) ? $get_address->postalCode : null),
                        "address" => (!empty($get_address->address) ? $get_address->address : null),
                        "address_title" => (!empty($get_address->title) ? $get_address->title : null),
                        "vat" => (!empty($user_basket["vat"]) ? $user_basket["vat"] : 0),
                        "subTotal" => (!empty($user_basket["subTotal"]) ? $user_basket["subTotal"] : 0),
                        "shippingPrice" => (!empty($user_basket["shipping"]) ? $user_basket["shipping"] : 0),
                        "total" => (!empty($user_basket["total"]) ? $user_basket["total"] : 0),
                        "couponName" => (!empty($user_basket["couponName"]) ? $user_basket["couponName"] : null),
                        "couponDiscount" => (!empty($user_basket["couponDiscount"]) ? $user_basket["couponDiscount"] : 0),
                        "order_code" => $merchant_oid,
                        "order_data" => base64_encode(json_encode($user_basket)),
                    ]
                );
            endif;
            $this->viewData->banks = $this->general_model->get_all("banks", null, null, ["isActive" => 1, "lang" => $this->viewData->lang]);

            /**
             * Render View
             */
            $this->viewFolder = "order_v/payment_type";
            $this->viewData->merchant_oid = $merchant_oid;
            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("choosePayment"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url = clean(base_url(lang("routes_choose-payment-type")));
            $this->viewData->og_image = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type = "article";
            $this->viewData->og_title = clean(strto("lower|ucwords", lang("choosePayment"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description = clean($this->viewData->settings->meta_description);
            $this->viewData->token = $token;
            $this->render();
        else :
            $this->session->set_flashdata("alert", ["success" => false, "title" => lang("error"), "msg" => lang("loginToContinue")]);
            redirect(base_url(lang("routes_login")));
        endif;
    }
    /**
     * Make Payment
     */
    public function payment()
    {
        /**
         * Pay With PayTR
         */
        // BURADA YAPILMASI GEREKENLER
        // 1) Siparişin durumunu $this -> input -> post('merchant_oid') değerini kullanarak veri tabanınızdan sorgulayın.
        // 2) Eğer sipariş zaten daha önceden onaylandıysa veya iptal edildiyse  echo "OK"; yaparak sonlandırın.
        if (!empty($_POST['status']) && $_POST['status'] === 'success') :
            $hash = base64_encode(hash_hmac('sha256', $this->input->post('merchant_oid') . self::merchant_salt . $this->input->post('status') . $this->input->post('total_amount'), self::merchant_key, true));
            if ($hash === $this->input->post('hash') && $_POST['status'] === 'success') :
                $order_code = $this->input->post('merchant_oid');
                // ÖDEMEYİ AL VE VERİTABANINA SİPARİŞ OLUŞTUR
                $order = $this->general_model->get("orders", null, ["order_code" => $order_code]);
                if (!empty($order)) :
                    if ($this->general_model->update("orders", ["id" => $order->id], ["paymentType" => "Kredi Kartı", "status" => 'Ödeme Alındı.'])) :
                        /**
                         * SET EMAIL DATA
                         */
                        $this->viewData->user = $this->general_model->get("users", null, ["id" => $order->user_id]);
                        $this->viewData->subject = lang("orderConfirmed");
                        $this->viewData->address_title = $order->address_title;
                        $this->viewData->order = $order;
                        $this->viewData->order_data = json_decode(base64_decode($order->order_data), true);
                        /**
                         * Stock Decrease
                         */
                        if (!empty($this->viewData->order_data["cart"])) :
                            foreach ($this->viewData->order_data["cart"] as $cart_key => $cart_value) :
                                /**
                                 * Cart & Wishlist Products
                                 */
                                $wheres["p.isActive"] = 1;
                                $wheres["pi.isCover"] = 1;
                                $wheres["p.id"] = $cart_value["id"];
                                $wheres["p.lang"] = $this->viewData->lang;
                                if (!empty($cart_value["options"]) && !empty($cart_value["options"]["pvgId"])) :
                                    $wheres["pvg.id"] = $cart_value["options"]["pvgId"];
                                else :
                                    if (array_key_exists("pvg.id", $wheres)) :
                                        unset($wheres["pvg.id"]);
                                    endif;
                                endif;
                                $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                                $select = "p.id,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus";
                                $distinct = true;
                                $groupBy = ["pwc.product_id"];
                                $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                                if (!empty($cart_value["options"]["pvgId"])) :
                                    $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"], "lang" => $this->viewData->lang]);
                                    $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                    $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                    $product_variations = [];
                                    $product_variation_categories = [];
                                    if (!empty($product_variation_in_group)) :
                                        foreach ($product_variation_in_group as $key => $value) :
                                            $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value, "lang" => $this->viewData->lang]);
                                            $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key], "lang" => $this->viewData->lang]);
                                            array_push($product_variation_categories, $product_variation_group->title);
                                            array_push($product_variations, $product_variation->title);
                                        endforeach;
                                    endif;
                                endif;

                                if (!empty($product)) :
                                    $product->stock = (float)$product->stock - (float)$cart_value["qty"];
                                    if ($product->stockStatus) :
                                        if (!empty($cart_value["options"]["pvgId"])) :
                                            $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"], "lang" => $this->viewData->lang], ["stock" => $product->stock]);
                                        else :
                                            $this->general_model->update("products", ["id" => $cart_value["id"], "lang" => $this->viewData->lang], ["stock" => $product->stock]);
                                        endif;
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        /**
                         * Stock Decrease
                         */
                        $this->viewData->address = $order->address . " " . $order->quarter . " " . $order->neighborhood . " " . $order->district . " " . $order->city . " / " . $order->country;
                        $this->viewData->message = $this->viewData->settings->company_name . ' ' . lang("orderConfirmMessage");
                        /**
                         * Send To User
                         */
                        $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                        if (send_emailv2([$this->viewData->user->email], $this->viewData->subject, $mailViewData)) :
                            /**
                             * Send To Admin
                             */
                            $this->viewData->subject = $this->viewData->settings->company_name . " - Yeni Bir Sipariş Var!";
                            $this->viewData->message = 'Merhaba Sayın Yetkili, "<b>' . $this->viewData->user->full_name . ' (Email: <a href="mailto:' . $this->viewData->user->email . '">' . $this->viewData->user->email . '</a> - Telefon: <a href="tel:' . $this->viewData->user->phone . '">' . $this->viewData->user->phone . '</a>)</b> İsimli Müşteri Websiteniz Üzerinden <b>' . $this->viewData->order->total . " " . $this->viewData->symbol . '</b> Tutarında Sipariş Oluşturdu.';

                            $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                            send_emailv2(null, $this->viewData->subject, $mailViewData);
                            echo "OK";
                        endif;
                    endif;
                endif;
            endif;
        endif;
        if (!empty($_POST["payWithBankTransfer"])) :
            $order_code = $this->input->post('merchant_oid');
            // ÖDEMEYİ AL VE VERİTABANINA SİPARİŞ OLUŞTUR
            $order = $this->general_model->get("orders", null, ["order_code" => $order_code]);
            if (!empty($order)) :
                if ($this->general_model->update("orders", ["id" => $order->id], ["paymentType" => "Banka Havalesi", "status" => 'Ödeme Bekleniyor.'])) :
                    /**
                     * SET EMAIL DATA
                     */
                    $this->viewData->user = $this->general_model->get("users", null, ["id" => $order->user_id]);
                    $this->viewData->subject = lang("orderConfirmed");
                    $this->viewData->address_title = $order->address_title;
                    $this->viewData->order = $order;
                    $this->viewData->order_data = json_decode(base64_decode($order->order_data), true);
                    /**
                     * Stock Decrease
                     */
                    if (!empty($this->viewData->order_data["cart"])) :
                        foreach ($this->viewData->order_data["cart"] as $cart_key => $cart_value) :
                            /**
                             * Cart & Wishlist Products
                             */
                            $wheres["p.isActive"] = 1;
                            $wheres["pi.isCover"] = 1;
                            $wheres["p.id"] = $cart_value["id"];
                            $wheres["p.lang"] = $this->viewData->lang;
                            if (!empty($cart_value["options"]) && !empty($cart_value["options"]["pvgId"])) :
                                $wheres["pvg.id"] = $cart_value["options"]["pvgId"];
                            else :
                                if (array_key_exists("pvg.id", $wheres)) :
                                    unset($wheres["pvg.id"]);
                                endif;
                            endif;
                            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                            $select = "p.id,IFNULL(pvg.stock,p.stock) stock,IFNULL(pvg.stockStatus,p.stockStatus) stockStatus";
                            $distinct = true;
                            $groupBy = ["pwc.product_id"];
                            $product = $this->general_model->get("products p", $select, $wheres, $joins, [], [], $distinct, $groupBy);
                            if (!empty($cart_value["options"]["pvgId"])) :
                                $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"], "lang" => $this->viewData->lang]);
                                $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                $product_variations = [];
                                $product_variation_categories = [];
                                if (!empty($product_variation_in_group)) :
                                    foreach ($product_variation_in_group as $key => $value) :
                                        $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value, "lang" => $this->viewData->lang]);
                                        $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key], "lang" => $this->viewData->lang]);
                                        array_push($product_variation_categories, $product_variation_group->title);
                                        array_push($product_variations, $product_variation->title);
                                    endforeach;
                                endif;
                            endif;

                            if (!empty($product)) :
                                $product->stock = (float)$product->stock - (float)$cart_value["qty"];
                                if ($product->stockStatus) :
                                    if (!empty($cart_value["options"]["pvgId"])) :
                                        $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"], "lang" => $this->viewData->lang], ["stock" => $product->stock]);
                                    else :
                                        $this->general_model->update("products", ["id" => $cart_value["id"], "lang" => $this->viewData->lang], ["stock" => $product->stock]);
                                    endif;
                                endif;
                            endif;
                        endforeach;
                    endif;
                    /**
                     * Stock Decrease
                     */
                    $this->viewData->address = $order->address . " " . $order->quarter . " " . $order->neighborhood . " " . $order->district . " " . $order->city . " / " . $order->country;
                    $this->viewData->message = $this->viewData->settings->company_name . ' ' . lang("orderConfirmMessage");
                    /**
                     * Send To User
                     */
                    $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                    if (send_emailv2([$this->viewData->user->email], $this->viewData->subject, $mailViewData)) :
                        /**
                         * Send To Admin
                         */
                        $this->viewData->subject = $this->viewData->settings->company_name . " - Yeni Bir Sipariş Var!";
                        $this->viewData->message = 'Merhaba Sayın Yetkili, "<b>' . $this->viewData->user->full_name . ' (Email: <a href="mailto:' . $this->viewData->user->email . '">' . $this->viewData->user->email . '</a> - Telefon: <a href="tel:' . $this->viewData->user->phone . '">' . $this->viewData->user->phone . '</a>)</b> İsimli Müşteri Websiteniz Üzerinden <b>' . $this->viewData->order->total . " " . $this->viewData->symbol . '</b> Tutarında Sipariş Oluşturdu.';

                        $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                        if (send_emailv2(null, $this->viewData->subject, $mailViewData)) :
                            echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("paymentSuccessMessage"), "url" => base_url(lang("routes_payment") . "?payment=success")]);
                        else :
                            if ($this->general_model->update("orders", ["id" => $order->id], ["paymentType" => null, "status" => 'Ödeme Bekleniyor.'])) :
                                echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("paymentErrorMsg"), "url" => base_url(lang("routes_payment") . "?payment=error")]);
                            endif;
                        endif;
                    else :
                        if ($this->general_model->update("orders", ["id" => $order->id], ["paymentType" => null, "status" => 'Ödeme Bekleniyor.'])) :
                            echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("paymentErrorMsg"), "url" => base_url(lang("routes_payment") . "?payment=error")]);
                        endif;
                    endif;

                endif;
            endif;
        endif;
        /**
         * Render View
         */
        if (!empty($_GET["payment"])) :
            if ($_GET["payment"] === "success") :
                $this->cart->destroy();
            endif;
            $this->viewFolder = "order_v/payment";
            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("payment"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url = clean(base_url(lang("routes_payment")));
            $this->viewData->og_image = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type = "article";
            $this->viewData->og_title = clean(strto("lower|ucwords", lang("payment"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description = clean($this->viewData->settings->meta_description);
            $this->render();
        endif;
        if (empty($_GET["payment"]) && empty($_POST)) :
            redirect(base_url());
        endif;
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ============================= Wishlist MODULE ============================== !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    /**
     * Wishlist
     */
    public function wishlist($count = null)
    {
        if (get_active_user()) :
            $this->viewFolder = "wishlist_v/index";
            $this->viewData->meta_title = clean(strto("lower|ucwords", lang("wishlist"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->meta_desc  = @str_replace("”", "\"", @stripslashes($this->viewData->settings->meta_description));
            $this->viewData->meta_keyw  = clean($this->viewData->settings->meta_keywords);
            $this->viewData->og_url                 = clean(base_url(lang("routes_wishlist")));
            $this->viewData->og_image           = clean(get_picture("settings_v", $this->viewData->settings->logo));
            $this->viewData->og_type          = "article";
            $this->viewData->og_title           = clean(strto("lower|ucwords", lang("wishlist"))) . " - " . $this->viewData->settings->company_name;
            $this->viewData->og_description           = clean($this->viewData->settings->meta_description);
            if (empty($count) && $count !== "render") :
                $this->render();
            elseif ($count === "render") :
                $this->load->view("includes/wishlist", $this->viewData);
            else :
                echo $this->general_model->rowCount("product_wishlists", ["user_id" => get_active_user()->id]);
            endif;
        else :
            echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("loginBeforeAddingToWishlist")]);
            redirect(base_url(lang("routes_login")));
        endif;
    }
    /**
     * Header Wishlist
     */
    public function headerWishlist($count = null)
    {
        if (empty($count)) :
            $this->load->view("includes/headerWishlist", $this->viewData);
        else :
            if (get_active_user()) :
                echo $this->general_model->rowCount("product_wishlists", ["user_id" => get_active_user()->id]);
            else :
                echo "0";
            endif;
        endif;
    }
    /**
     * Add To Cart
     */
    public function addToWishlist()
    {
        if (get_active_user()) :
            $data = rClean($_POST);
            if (!empty($data) && !empty($data["id"])) :
                /**
                 * Get Product 
                 */
                $product = $this->general_model->get("products", "*,price AS newPrice", ["isActive" => 1, "id" => $data["id"], "lang" => $this->viewData->lang]);
                /**
                 * IF NOT EMPTY THE PRODUCT
                 */
                if (!empty($product)) :
                    $wishlists = $this->general_model->get_all("product_wishlists", null, null, ["user_id" => get_active_user()->id]);
                    $contentIds = [];
                    if (!empty($wishlists)) :
                        foreach ($wishlists as $items) :
                            if (!in_array($items->id, $contentIds)) :
                                array_push($contentIds, $items->id);
                            endif;
                        endforeach;
                    endif;
                    if (!in_array($product->id, $contentIds)) :
                        if ($this->general_model->add("product_wishlists", ["user_id" => get_active_user()->id, "product_id" => $data["id"]])) :
                            echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("addedToWishlistSuccessfully")]);
                            die();
                        else :
                            echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("errorOccuredWhileAddingToWishlist")]);
                            die();
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("alreadyAddedToWishlist")]);
                        die();
                    endif;
                endif;
            else :
                die();
            endif;
        else :
            echo json_encode(["success" => false, "title" => lang("error"), "msg" => lang("loginBeforeAddingToWishlist")]);
            die();
        endif;
    }
    /**
     * Remove From Wishlist
     */
    public function removeFromWishlist()
    {
        if (get_active_user()) :
            $data = rClean($_POST);
            if (!empty($data) && !empty($data["id"])) :
                /**
                 * Remove Product 
                 */
                $this->general_model->delete("product_wishlists", ["user_id" => get_active_user()->id, "product_id" => $data["id"]]);
                echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("removedFromWishlistSuccessfully")]);
            else :
                die();
            endif;
        else :
            die();
        endif;
    }
    /**
     * Empty Wishlist
     */
    public function emptyWishlist()
    {
        if (get_active_user()) :
            /**
             * Remove Products 
             */
            $this->general_model->delete("product_wishlists", ["user_id" => get_active_user()->id]);
            echo json_encode(["success" => true, "title" => lang("success"), "msg" => lang("emptyWishlistSuccessfully")]);
        else :
            die();
        endif;
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ============================= Wishlist MODULE ============================== !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    public function installmentTable()
    {
        if (!empty($_POST)) :
            $price = (float)@str_replace(".", ",", clean($_POST["price"]));
            $title = clean($_POST["title"]);
            $quantity = clean($_POST["quantity"]);
            if (!empty($price) && !empty($title) && !empty($quantity)) :
                echo json_encode(["success" => true, "title" => lang("success"), "message" => lang("successWhileInstallmentTable"), "script" => "<script src='https://www.paytr.com/odeme/taksit-tablosu/v2?token=595bf89433f5dba17e7d0a8df2b1e1082f4804ee018be823b65c50b87cf89b34&merchant_id=" . self::merchant_id . "&amount=" . ($price * $quantity) . "&taksit=0&tumu=0'></script>"]);
            else :
                echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("errorWhileInstallmentTable")]);
            endif;
        else :
            echo json_encode(["success" => false, "title" => lang("error"), "message" => lang("errorWhileInstallmentTable")]);
        endif;
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ============================== SITEMAP MODULE ============================== !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate a sitemap index file
     * More information about sitemap indexes: http://www.sitemaps.org/protocol.html#index
     */
    public function sitemapindex()
    {
        $this->load->model("sitemapmodel");
        /**
         * Page URLs
         */
        if (!empty($this->viewData->page_urls)) :
            foreach (array_unique($this->viewData->page_urls) as $key => $value) :
                $this->sitemapmodel->add($value, NULL, 'always', 1);
            endforeach;
        endif;
        /**
         * News Categories
         */
        $news_categories = $this->general_model->get_all("news_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($news_categories)) :
            foreach ($news_categories as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_news") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * News
         */
        $news = $this->general_model->get_all("news", null, "id DESC", ['isActive' => 1, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($news)) :
            foreach ($news as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Product Categories
         */
        $product_categories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($product_categories)) :
            foreach ($product_categories as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_products") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Products
         */
        $products = $this->general_model->get_all("products", null, "id DESC", ['isActive' => 1, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($products)) :
            foreach ($products as $k => $v) :
                if (!empty($v->url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$v->url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Slides
         */
        $slides = $this->general_model->get_all("slides", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($slides)) :
            foreach ($slides as $k => $v) :
                if (!empty($v->button_url)) :
                    $this->sitemapmodel->add($v->button_url, NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Galleries
         */
        $galleries = $this->general_model->get_all("galleries", null, "rank ASC", ["isActive" => 1, "isCover" => 0, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($galleries)) :
            foreach ($galleries as $k => $v) :
                if (!empty($v->url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/{$v->url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        $this->sitemapmodel->output('sitemapindex');
    }
    /**
     * Generate a sitemap url file
     * More information about sitemap example xml: https://www.sitemaps.org/protocol.html#sitemapXmlExample
     */
    public function sitemap()
    {
        $this->load->model("sitemapmodel");
        /**
         * Page URLs
         */
        if (!empty($this->viewData->page_urls)) :
            foreach (array_unique($this->viewData->page_urls) as $key => $value) :
                $this->sitemapmodel->add($value, NULL, 'always', 1);
            endforeach;
        endif;
        /**
         * News Categories
         */
        $news_categories = $this->general_model->get_all("news_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($news_categories)) :
            foreach ($news_categories as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_news") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * News
         */
        $news = $this->general_model->get_all("news", null, "id DESC", ['isActive' => 1, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($news)) :
            foreach ($news as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_news") . "/" . lang("routes_newss") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Product Categories
         */
        $product_categories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($product_categories)) :
            foreach ($product_categories as $k => $v) :
                if (!empty($v->seo_url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_products") . "/{$v->seo_url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Products
         */
        $products = $this->general_model->get_all("products", null, "id DESC", ['isActive' => 1, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($products)) :
            foreach ($products as $k => $v) :
                if (!empty($v->url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$v->url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Slides
         */
        $slides = $this->general_model->get_all("slides", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang]);
        if (!empty($slides)) :
            foreach ($slides as $k => $v) :
                if (!empty($v->button_url)) :
                    $this->sitemapmodel->add($v->button_url, NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        /**
         * Galleries
         */
        $galleries = $this->general_model->get_all("galleries", null, "rank ASC", ["isActive" => 1, "isCover" => 0, "lang" => $this->viewData->lang], [], [], []);
        if (!empty($galleries)) :
            foreach ($galleries as $k => $v) :
                if (!empty($v->url)) :
                    $this->sitemapmodel->add(base_url(lang("routes_galleries") . "/" . lang("routes_gallery") . "/{$v->url}"), NULL, 'always', 1);
                endif;
            endforeach;
        endif;
        $this->sitemapmodel->output();
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ============================== SITEMAP MODULE ============================== !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ========================== FACEBOOK CATALOG MODULE ========================= !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
    function catalog()
    {
        $settings = get_settings();

        $dom = xml_dom();
        $rss = xml_add_child($dom, 'rss');
        xml_add_attribute($rss, 'xmlns:g', 'https://base.google.com/ns/1.0');
        xml_add_attribute($rss, 'version', '2.0');

        $channel = xml_add_child($rss, 'channel');
        xml_add_child($channel, 'title', $settings->company_name);
        xml_add_child($channel, 'link', base_url());
        xml_add_child($channel, 'description', clean($settings->meta_description));

        /**
         * Order
         */
        $order = "p.id DESC";
        /**
         * Likes
         */
        $likes = [];
        $wheres = [];
        /**
         * Wheres
         */
        $wheres["p.isActive"] = 1;
        $wheres["pi.isCover"] = 1;
        $wheres["p.lang"] = $this->viewData->lang;
        $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_categories pc" => ["pwc.category_id = pc.id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];

        $select = "p.content content,GROUP_CONCAT(pc.seo_url) category_seos,GROUP_CONCAT(pc.title) category_titles,GROUP_CONCAT(pc.id) category_ids,p.id,p.title,p.url,pi.url img_url,p.price price,p.discount discount,p.stock stock,p.stockStatus stockStatus,p.isActive,p.isDiscount isDiscount,p.sharedAt,p.price AS newPrice,(CASE when p.isDiscount = 1 then p.price - (p.price * p.discount / 100) else p.price end) AS discountedPrice";
        $distinct = true;
        $groupBy = ["p.id", "pwc.product_id"];
        /** 
         * Get Products
         */
        $products = $this->general_model->get_all("products p", $select, $order, $wheres, $likes, $joins, [], [], $distinct, $groupBy);
        $this->viewData->products = $products;

        //logg($prods);
        if (!empty($products)) :
            foreach ($products as $key => $prod) :
                if (!empty($prod->pvgId)) :
                    $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $prod->pvgId, "lang" => $this->viewData->lang]);
                    $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                    $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                    $product_variations = [];
                    $product_variation_categories = [];
                    if (!empty($product_variation_in_group)) :
                        foreach ($product_variation_in_group as $key => $value) :
                            $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value, "lang" => $this->viewData->lang]);
                            $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key], "lang" => $this->viewData->lang]);
                            array_push($product_variation_categories, $product_variation_group->title);
                            array_push($product_variations, $product_variation->title);
                        endforeach;
                    endif;
                endif;
                $item = xml_add_child($channel, 'item');
                xml_add_child($item, 'id', $prod->id);
                if (!empty($prod->pvgId)) :
                    if (!empty($product_variation_categories) && !empty($product_variations)) :
                        $count = count($product_variation_categories);
                        $i = 1;
                        $prod->title .= ' ( ';
                        foreach ($product_variation_categories as $key => $value) :
                            if ($i < $count) :
                                $prod->title .= $value . ' : ' . $product_variations[$key] . ', ';
                            else :
                                $prod->title .= $value . ' : ' . $product_variations[$key];
                            endif;
                            $i++;
                        endforeach;
                        $prod->title .= ' ) ';
                    endif;
                endif;
                $gtin = rand(100000000000, 999999999999);
                xml_add_child($item, 'title', strto("lower|ucwords", $prod->title));
                xml_add_child($item, 'description', strto("lower|ucwords", (!empty($prod->content) ? clean(@mb_word_wrap($prod->content, 500, "...")) : $prod->title)));
                xml_add_child($item, 'link',  base_url(lang("routes_products") . "/" . lang("routes_product") . "/{$prod->url}"));
                xml_add_child($item, 'image_link', get_picture("products_v", $prod->img_url));
                xml_add_child($item, 'additional_image_link', get_picture("products_v", $prod->img_url));
                xml_add_child($item, 'brand', strto("lower|ucwords", $settings->company_name));
                xml_add_child($item, 'condition', 'new');
                xml_add_child($item, 'availability', ($prod->stock > 0 ? 'in stock' : 'out of stock'));
                xml_add_child($item, 'price', @number_format($prod->newPrice, 2) . ' ' . $this->viewData->currency);
                xml_add_child($item, 'sale_price', @number_format($prod->discountedPrice, 2) . ' ' . $this->viewData->currency);
                xml_add_child($item, 'quantity_to_sell_on_facebook', $prod->stock);
                xml_add_child($item, 'mpn', $gtin);
                xml_add_child($item, 'identifier_exists', "no");
                //https://www.google.com/basepages/producttype/taxonomy-with-ids.en-US.txt
                /**
                 * Get All Categories
                 */
                $categories = $this->general_model->get_all("product_categories", null, "rank ASC", ["isActive" => 1, "lang" => $this->viewData->lang], [], [], []);

                $pselecteds = [];
                $pselectedCategories = $this->general_model->get_all("products_w_categories", null, null, ["product_id" => $prod->id]);
                if (!empty($pselectedCategories)) :
                    foreach ($pselectedCategories as $key => $value) :
                        if (!in_array($value->category_id, $pselecteds)) :
                            array_push($pselecteds, $value->category_id);
                        endif;
                    endforeach;
                endif;
                $category = null;
                $count = count($pselecteds);
                $i = 1;
                foreach ($categories as $k => $v) :
                    if (in_array($v->id, $pselecteds)) :
                        if ($i < $count) :
                            $category .= $v->title . ',';
                        else :
                            $category .= $v->title;
                        endif;
                        $i++;
                    endif;
                endforeach;
                xml_add_child($item, 'google_product_category', 412);
                xml_add_child($item, 'fb_product_category', 28);
            //xml_add_child($item, 'g:custom_label_0', $prod->);
            endforeach;
        endif;
        $this->output->set_content_type('application/xml')->set_output(xml_print($dom, true));
    }
    /**
     * ------------------------------------------------------------------------------------------------
     * ...:::!!! ========================== FACEBOOK CATALOG MODULE ========================= !!!:::...
     * ------------------------------------------------------------------------------------------------
     */
}
