<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends MY_Controller
{
    public $viewFolder = "";
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "products_v";
        $this->load->model("product_model");
        $this->load->model("product_category_model");
        $this->load->model("products_w_categories_model");
        $this->load->model("product_image_model");
        $this->load->model("product_variation_category_model");
        $this->load->model("product_variation_model");
        $this->load->model("product_variation_group_model");
        $this->load->model("product_part_model");
        $this->load->model("product_dimension_model");
        if (!get_active_user()) :
            redirect(base_url("login"));
        endif;
    }
    public function index()
    {
        $viewData = new stdClass();
        $items = $this->product_model->get_all(["lang" => $this->session->userdata("activeLang")], "rank ASC");
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $items;
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function datatable()
    {
        $items = $this->product_model->getRows(["lang" => $this->session->userdata("activeLang")], $_POST);
        $data = [];
        $i = (!empty($_POST['start']) ? $_POST['start'] : 0);
        if (!empty($items)) :
            foreach ($items as $item) :
                $i++;
                $proccessing = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        İşlemler
                    </button>
                    <div class="dropdown-menu rounded-0 dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item updateProductBtn" href="javascript:void(0)" data-url="' . base_url("products/update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="productTable" data-url="' . base_url("products/delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                        <a class="dropdown-item" href="' . base_url("products/upload_form/$item->id") . '"><i class="fa fa-image mr-2"></i>Resimler</a>
                        <a class="dropdown-item" href="' . base_url("products/variation/$item->id") . '"><i class="fa fa-share-alt mr-2"></i>Varyasyon Grupları</a>
                        <a class="dropdown-item" href="' . base_url("products/parts/$item->id") . '"><i class="fa fa-dropbox mr-2"></i>Parça İşlemleri</a>
                        <a class="dropdown-item" href="' . base_url("products/dimensions/$item->id") . '"><i class="fa fa-ruler mr-2"></i>Ölçü İşlemleri</a>
                    </div>
                </div>';
                $checkbox1 = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/isNewSetter/{$item->id}") . '" data-status="' . ($item->isNew == 1 ? "checked" : null) . '" id="customSwitch1' . $i . '" type="checkbox" ' . ($item->isNew == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch1' . $i . '"></label></div>';
                $checkbox2 = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/isSuggestedSetter/{$item->id}") . '" data-status="' . ($item->isSuggested == 1 ? "checked" : null) . '" id="customSwitch2' . $i . '" type="checkbox" ' . ($item->isSuggested == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch2' . $i . '"></label></div>';
                $checkbox3 = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/isDiscountSetter/{$item->id}") . '" data-status="' . ($item->isDiscount == 1 ? "checked" : null) . '" id="customSwitch3' . $i . '" type="checkbox" ' . ($item->isDiscount == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch3' . $i . '"></label></div>';
                $checkbox4 = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/isActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch4' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch4' . $i . '"></label></div>';
                $deleteCheckbox = '<input type="checkbox" name="selectedProduct[]" class="editor-active" value="' . $item->id . '">';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $deleteCheckbox, $item->title, number_format($item->price, 2), $checkbox1, $checkbox2, $checkbox3, $checkbox4, turkishDate("d F Y, l H:i:s", $item->updatedAt), turkishDate("d F Y, l H:i:s", $item->sharedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->product_model->rowCount(["lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->product_model->countFiltered(["lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function new_form()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "add";
        $viewData->categories = $this->product_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function save()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] !== "discount" && checkEmpty($data)["key"] !== "price" && checkEmpty($data)["key"] !== "external_url" && checkEmpty($data)["key"] !== "content" && checkEmpty($data)["key"] !== "features" && checkEmpty($data)["key"] !== "description" && checkEmpty($data)["key"] !== "company_code" && checkEmpty($data)["key"] !== "stock" && checkEmpty($data)["key"] !== "vat") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $getRank = $this->product_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
            unset($data["category_id"]);
            if (!empty($_FILES)) :
                if (!empty($_FILES["banner_url"]["name"])) :
                    $image = upload_picture("banner_url", "uploads/$this->viewFolder");
                    if ($image["success"]) :
                        $data["banner_url"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Kaydı Yapılırken Hata Oluştu. Ürün Banner Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
            endif;
            $data["title"] = stripslashes($data["title"]);
            $data["url"] = seo($data["title"]);
            $data["content"] = $_POST["content"];
            $data["description"] = $_POST["description"];
            $data["features"] = $_POST["features"];
            $data["isActive"] = 1;
            $data["rank"] = $getRank + 1;
            $insert = $this->product_model->add($data);
            if ($insert) :
                if (!empty($_POST["category_id"])) :
                    $this->products_w_categories_model->delete(["product_id" => $insert]);
                    $arrayOfCategoryIds = [];
                    foreach (array_unique($_POST["category_id"]) as $key => $value) :
                        $this->products_w_categories_model->add(["product_id" => $insert, "category_id" => $value]);
                        array_push($arrayOfCategoryIds, $value);
                    endforeach;
                    if (!empty($arrayOfCategoryIds)) :
                        if (in_array(9, $arrayOfCategoryIds)) :
                            $this->syncVariations($insert, 1);
                        endif;
                        $this->syncDimensions($insert, $arrayOfCategoryIds);
                        $this->syncParts($insert, $arrayOfCategoryIds);
                    endif;
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Başarıyla Eklendi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function update_form($id)
    {
        $viewData = new stdClass();
        $viewData->item = $this->product_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->selectedCategories = $this->products_w_categories_model->get_all(["product_id" => $id]);
        $viewData->categories = $this->product_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function update($id)
    {
        $data = $this->input->post();
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] !== "discount" && checkEmpty($data)["key"] !== "price" && checkEmpty($data)["key"] !== "external_url" && checkEmpty($data)["key"] !== "content" && checkEmpty($data)["key"] !== "features" && checkEmpty($data)["key"] !== "description" && checkEmpty($data)["key"] !== "company_code" && checkEmpty($data)["key"] !== "stock" && checkEmpty($data)["key"] !== "vat") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $product = $this->product_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
            $data["title"] = stripslashes($data["title"]);
            $data["url"] = seo($data["title"]);
            $data["content"] = $_POST["content"];
            $data["features"] = $_POST["features"];
            $data["description"] = $_POST["description"];
            $data["banner_url"] = $product->banner_url;
            if (!empty($_FILES["banner_url"]["name"])) :
                $image = upload_picture("banner_url", "uploads/$this->viewFolder");
                if ($image["success"]) :
                    $data["banner_url"] = $image["file_name"];
                    if (!empty($product->banner_url)) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}");
                        endif;
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Güncelleştirilirken Hata Oluştu. Ürün Banner Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                    die();
                endif;
            endif;
            //$data["features"] = $_POST["features"];
            unset($data["category_id"]);
            $update = $this->product_model->update(["id" => $id], $data);
            if ($update) :
                if (!empty($_POST["category_id"])) :
                    $this->products_w_categories_model->delete(["product_id" => $id]);
                    foreach (array_unique($_POST["category_id"]) as $key => $value) :
                        $this->products_w_categories_model->add(["product_id" => $id, "category_id" => $value]);
                    endforeach;
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Başarıyla Güncelleştirildi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function priceForm()
    {
        if ($this->input->method() == "post") :
            if (!empty($_POST["product_ids"])) :
                $viewData = new stdClass();
                $viewData->viewFolder = $this->viewFolder;
                $viewData->subViewFolder = "price";
                $wheres["p.isActive"] = 1;
                $wheres["p.lang"] = $this->session->userdata("activeLang");
                $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
                $select = "p.id,p.title,p.url,pi.url img_url,p.discount discount,p.stock stock,p.price,p.lang";
                $distinct = TRUE;
                $groupBy = ["p.id", "pwc.product_id"];
                $viewData->products = $this->general_model->get_all("products p", $select, "p.id ASC", $wheres, [], $joins, [], ["p.id" => $_POST["product_ids"]], $distinct, $groupBy);
                $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
                $viewData->links = null;
                $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
            else :
                die('<div class="alert alert-danger" role="alert">En az 1 ürünü işaretlemiş olmalısınız!</div>');
            endif;
        else :
            $viewData = new stdClass();
            $viewData->viewFolder = $this->viewFolder;
            $viewData->subViewFolder = "price";
            $wheres["p.isActive"] = 1;
            $wheres["pi.isCover"] = 1;
            $wheres["p.lang"] = $this->session->userdata("activeLang");
            $joins = ["products_w_categories pwc" => ["p.id = pwc.product_id", "left"], "product_variation_groups pvg" => ["p.id = pvg.product_id", "left"], "product_images pi" => ["pi.product_id = p.id", "left"]];
            $select = "p.id,p.title,p.url,pi.url img_url,p.discount discount,p.stock stock,p.price,p.lang";
            $distinct = TRUE;
            $groupBy = ["p.id", "pwc.product_id"];
            /**
             * Pagination
             */
            $config = [];
            $config['base_url'] = base_url("products/priceform");
            $config['uri_segment'] = 3;
            $config['use_page_numbers'] = TRUE;
            $config["full_tag_open"] = "<ul class='pagination flex-wrap justify-content-center'>";
            $config["first_link"] = "<i class='fa fa-angle-double-left fa-1x'></i>";
            $config["first_tag_open"] = "<li class='page-item'>";
            $config["first_tag_close"] = "</li>";
            $config["prev_link"] = "<i class='fa fa-angle-left fa-1x'></i>";
            $config["prev_tag_open"] = "<li class='page-item'>";
            $config["prev_tag_close"] = "</li>";
            $config["cur_tag_open"] = "<li class='page-item active'><a class='page-link active' title='" . get_settings()->company_name . "' rel='dofollow' href='" . str_replace("index.php/", "", current_url()) . "'>";
            $config["cur_tag_close"] = "</a></li>";
            $config["num_tag_open"] = "<li class='page-item'>";
            $config["num_tag_close"] = "</li>";
            $config["next_link"] = "<i class='fa fa-angle-right fa-1x'></i>";
            $config["next_tag_open"] = "<li class='page-item'>";
            $config["next_tag_close"] = "</li>";
            $config["last_link"] = "<i class='fa fa-angle-double-right fa-1x'></i>";
            $config["last_tag_open"] = "<li class='page-item'>";
            $config["last_tag_close"] = "</li>";
            $config["full_tag_close"] = "</ul>";
            $config['attributes'] = array('class' => 'page-link');
            $config['total_rows'] = $this->general_model->rowCount("products p", $wheres, [], $joins, [], $distinct, $groupBy, "p.id");
            $config['per_page'] = 25;
            $config["num_links"] = 5;
            $config['reuse_query_string'] = true;
            $this->pagination->initialize($config);
            $uri_segment = 3;
            $offset = (!empty($uri_segment) ? $uri_segment - 1 : 0) * $config['per_page'];
            $viewData->products = $this->general_model->get_all("products p", $select, "p.title ASC", $wheres, [], $joins, [$config["per_page"], $offset], [], $distinct, $groupBy);
            $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
            $viewData->links = $this->pagination->create_links();
            $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
        endif;
    }
    public function priceUpdateByRate()
    {
        $data = rClean($_POST);
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Seçili Ürün Fiyatları Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            if ($data["rate"] > 0) :
                $productIds = explode(",", $data["product_ids"]);
                if ($data["ascending"] == "false") :
                    $this->db->set("price", "price-((price*" . $data["rate"] . ")/100)", FALSE);
                else :
                    $this->db->set("price", "price+((price*" . $data["rate"] . ")/100)", FALSE);
                endif;
                $this->db->where_in("id", $productIds);
                $this->db->update("products");
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Seçmiş Olduğunuz Ürünlerin Fiyatları Başarıyla Güncellendi."]);
            endif;
        endif;
    }
    public function bulkupdate()
    {
        if (!empty($_POST)) :
            foreach ($_POST as $pkey => $pvalue) :
                foreach ($pvalue as $pkey2 => $pvalue2) :
                    if (!is_array($pvalue2)) :
                        if ($pkey == "pricemain") :
                            $this->general_model->update("products", ["id" => $pkey2], ["price" => $pvalue2]);
                        endif;
                        if ($pkey == "stockmain") :
                            $this->general_model->update("products", ["id" => $pkey2], ["stock" => $pvalue2]);
                        endif;
                        if ($pkey == "discountmain") :
                            $this->general_model->update("products", ["id" => $pkey2], ["discount" => $pvalue2]);
                        endif;
                    endif;
                    if (is_array($pvalue2)) :
                        foreach ($pvalue2 as $key => $value) :
                            if ($pkey == "price") :
                                $this->general_model->update("product_variation_groups", ["id" => $key], ["price" => $value]);
                            endif;
                            if ($pkey == "stock") :
                                $this->general_model->update("product_variation_groups", ["id" => $key], ["stock" => $value]);
                            endif;
                            if ($pkey == "discount") :
                                $this->general_model->update("product_variation_groups", ["id" => $key], ["discount" => $value]);
                            endif;
                        endforeach;
                    endif;
                endforeach;
            endforeach;
            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Tüm Ürün Fiyat, Stok ve İndirim Oranları Başarıyla Güncellendi."]);
        endif;
    }
    public function delete($id)
    {
        $product = $this->product_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        if (!empty($product)) :
            $product_images = $this->product_image_model->get_all(["product_id" => $id, "lang" => $this->session->userdata("activeLang")]);
            $delete = $this->product_model->delete(["id"    => $id]);
            if ($delete) :
                $this->general_model->delete("product_dimensions", ["product_id" => $id]);
                $this->general_model->delete("product_parts", ["product_id" => $id]);
                if (!empty($product->banner_url)) :
                    if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}")) :
                        unlink(FCPATH . "uploads/{$this->viewFolder}/{$product->banner_url}");
                    endif;
                endif;
                if (!empty($product_images)) :
                    foreach ($product_images as $key => $value) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->url}");
                        endif;
                    endforeach;
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function deleteBulk()
    {
        $productIds = explode(",", $_POST["product_ids"]);
        $products = $this->general_model->get_all("products", null, null, ["lang" => $this->session->userdata("activeLang")], [], [], [], ["id" => $productIds]);
        if (!empty($products)) :
            $delete = $this->db->where_in("id", $productIds)->delete("products");
            if ($delete) :
                $this->db->where_in("product_id", $productIds)->delete("product_dimensions");
                $this->db->where_in("product_id", $productIds)->delete("product_parts");
                $this->db->where_in("product_id", $productIds)->delete("product_variation_groups");
                $product_images = $this->general_model->get_all("product_images", null, null, ["lang" => $this->session->userdata("activeLang")], [], [], [], ["product_id" => $productIds]);
                foreach ($products as $key => $value) :
                    if (!empty($value->banner_url)) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->banner_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->banner_url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->banner_url}");
                        endif;
                    endif;
                endforeach;
                if (!empty($product_images)) :
                    foreach ($product_images as $key => $value) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$value->url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$value->url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$value->url}");
                        endif;
                    endforeach;
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Seçili Ürünler Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Seçili Ürünler Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function rankSetter()
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->product_model->update(
                    [
                        "id" => $row["id"]
                    ],
                    ["rank" => $row["position"]]
                );
            endforeach;
        endif;
    }
    public function isActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function isNewSetter($id)
    {
        if (!empty($id)) :
            $isNew = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_model->update(["id" => $id], ["isNew" => $isNew])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function isSuggestedSetter($id)
    {
        if (!empty($id)) :
            $isSuggested = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_model->update(["id" => $id], ["isSuggested" => $isSuggested])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function isDiscountSetter($id)
    {
        if (!empty($id)) :
            $isDiscount = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_model->update(["id" => $id], ["isDiscount" => $isDiscount])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function variation($id)
    {
        $viewData = new stdClass();
        $viewData->variation_categories = $this->product_variation_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->variations = $this->product_variation_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "variation/list";
        $viewData->item = $this->product_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function variationGroupDatatable($product_id)
    {
        $items = $this->product_variation_group_model->getRows(["product_id" => $product_id, "product_variation_groups.lang" => $this->session->userdata("activeLang")], $_POST);
        $data = [];
        $i = (!empty($_POST['start']) ? $_POST['start'] : 0);
        if (!empty($items)) :
            foreach ($items as $item) :
                $i++;
                $proccessing = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        İşlemler
                    </button>
                    <div class="dropdown-menu rounded-0 dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item updateProductVariationGroupBtn" href="javascript:void(0)" data-url="' . base_url("products/variation_group_update_form/$item->variation_group_id/$product_id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="productVariationGroupTable" data-url="' . base_url("products/variation_group_delete/$item->variation_group_id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                        </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->variation_group_id . '" data-url="' . base_url("products/variationGroupisActiveSetter/{$item->variation_group_id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch' . $i . '"></label></div>';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->variation_group_id . '"></i>', $item->variation_group_id, $item->variation_categories, $item->variations, $item->price, $item->discount, $item->stock, $item->lang, $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->product_variation_group_model->rowCount(["product_id" => $product_id, "product_variation_groups.lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->product_variation_group_model->countFiltered(["product_id" => $product_id, "product_variation_groups.lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function variation_group_new_form($product_id)
    {
        $viewData = new stdClass();
        $viewData->categories = $this->product_variation_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->variations = $this->product_variation_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->product_id = $product_id;
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "variation/add";
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function variation_group_save($product_id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] != "discount" && checkEmpty($data)["key"] != "vat" && checkEmpty($data)["key"] != "price" && checkEmpty($data)["key"] != "stockStatus" && checkEmpty($data)["key"] != "stock") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            if (!array_key_exists("category_id", $data) || empty($data["category_id"] || !array_key_exists("variation_id", $data) || empty($data["variation_id"]))) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            $product = $this->product_model->get(["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
            if ($product) :
                $getRank = $this->product_variation_group_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
                $data["title"] = stripslashes($data["title"]);
                $data["lang"] = $product->lang;
                $data["isActive"] = 1;
                $data["product_id"] = $product_id;
                $data["rank"] = $getRank + 1;
                $array_filtered_category_id = implode(",", array_filter($data["category_id"]));
                $array_filtered_variation_id = implode(",", array_filter($data["variation_id"]));
                $data["category_id"] = $array_filtered_category_id;
                $data["variation_id"] =  $array_filtered_variation_id;
                $insert = $this->product_variation_group_model->add($data);
                if ($insert) :
                    echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Varyasyon Grubu Başarıyla Eklendi."]);
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                endif;
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function variation_group_update_form($id, $product_id)
    {
        $viewData = new stdClass();
        $viewData->categories = $this->product_variation_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->variations = $this->product_variation_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->product_id = $product_id;
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "variation/update";
        $viewData->item = $this->product_variation_group_model->get(["id"    => $id, "product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function variation_group_update($id, $product_id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] != "discount" && checkEmpty($data)["key"] != "vat" && checkEmpty($data)["key"] != "price" && checkEmpty($data)["key"] != "stockStatus" && checkEmpty($data)["key"] != "stock") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            if (!array_key_exists("category_id", $data) || empty($data["category_id"] || !array_key_exists("variation_id", $data) || empty($data["variation_id"]))) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            $data["title"] = stripslashes($data["title"]);
            $data["product_id"] = $product_id;
            $array_filtered_category_id = implode(",", array_filter($data["category_id"]));
            $array_filtered_variation_id = implode(",", array_filter($data["variation_id"]));
            $data["category_id"] = $array_filtered_category_id;
            $data["variation_id"] =  $array_filtered_variation_id;
            $update = $this->product_variation_group_model->update(["id" => $id, "product_id" => $product_id], $data);
            if ($update) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Varyasyon Grubu Başarıyla Güncelleştirildi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function variation_group_delete($id)
    {
        $product_variation_category = $this->product_variation_group_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        if (!empty($product_variation_category)) :
            $delete = $this->product_variation_group_model->delete(["id" => $id]);
            if ($delete) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Varyasyon Grubu Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Varyasyon Grubu Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function variationGroupRankSetter($product_id)
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->product_variation_group_model->update(
                    [
                        "id" => $row["id"],
                        "product_id" => $product_id
                    ],
                    ["rank" => $row["position"]]
                );
            endforeach;
        endif;
    }
    public function variationGroupisActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_variation_group_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "Başarılı!", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "Başarısız!", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function detailDatatable($product_id)
    {
        $items = $this->product_image_model->getRows(
            ["product_id" => $product_id],
            $_POST
        );
        $data = [];
        $i = (!empty($_POST['start']) ? $_POST['start'] : 0);
        if (!empty($items)) :
            foreach ($items as $item) :
                $i++;
                $proccessing = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        İşlemler
                    </button>
                    <div class="dropdown-menu rounded-0 dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item updateSkuBtn" href="javascript:void(0)" data-table="detailTable" data-url="' . base_url("products/fileUpdate/{$item->id}/{$product_id}") . '"><i class="fa fa-barcode mr-2"></i>SKU Kodu Ekle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="detailTable" data-url="' . base_url("products/fileDelete/{$item->id}") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                        </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/fileIsActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch' . $i . '"></label></div>';
                $checkbox2 = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-table="detailTable" data-url="' . base_url("products/fileIsCoverSetter/{$item->id}/$item->product_id/$item->lang") . '" data-status="' . ($item->isCover == 1 ? "checked" : null) . '" id="customSwitch2' . $i . '" type="checkbox" ' . ($item->isCover == 1 ? "checked" : null) . ' class="isCover custom-control-input" >  <label class="custom-control-label" for="customSwitch2' . $i . '"></label></div>';
                $image = '<img src="' . base_url("uploads/{$this->viewFolder}/{$item->url}") . '" width="75">';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $image, $item->url, $item->lang, $checkbox2, $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->product_image_model->rowCount(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->product_image_model->countFiltered(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function upload_form($id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "image";
        $viewData->item = $this->product_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $viewData->items = $this->product_image_model->get_all(["product_id" => $id, "lang" => $this->session->userdata("activeLang")], "rank ASC");
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function fileUpdate($id, $product_id)
    {
        $viewData = new stdClass();
        $viewData->product_id =  $product_id;
        $viewData->categories = $this->product_variation_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->variations = $this->product_variation_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->groups = $this->product_variation_group_model->get_all(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->item = $this->product_image_model->get(["id" => $id, "product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "file_update";
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function file_update($id, $product_id)
    {
        $data = rClean($this->input->post());
        if (!empty($data["variation_group_id"])) :
            $data["variation_group_id"] = implode(",", $data["variation_group_id"]);
        else :
            $data["variation_group_id"] = null;
        endif;
        $update = $this->product_image_model->update(["id" => $id, "product_id" => $product_id], $data);
        if ($update) :
            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Görseli Varyasyon Grupları Başarıyla Güncelleştirildi."]);
        else :
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Görseli Varyasyon Grupları Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
        endif;
    }
    public function file_upload($product_id, $lang)
    {
        $resize = ['height' => 1000, 'width' => 1000, 'maintain_ratio' => FALSE, 'master_dim' => 'height'];
        $image = upload_picture("file", "uploads/$this->viewFolder/", $resize);
        if ($image["success"]) :
            $getRank = $this->product_image_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
            $this->product_image_model->add(
                [
                    "url"           => $image["file_name"],
                    "rank"          => $getRank + 1,
                    "product_id"    => $product_id,
                    "isActive"      => 1,
                    "lang"          => $lang
                ]
            );
        else :
            echo $image["error"];
        endif;
    }
    public function fileDelete($id)
    {
        $fileName = $this->product_image_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $delete = $this->product_image_model->delete(["id" => $id]);
        if ($delete) :
            $url = FCPATH . "uploads/{$this->viewFolder}/{$fileName->url}";
            if (!is_dir($url) && file_exists($url)) :
                unlink($url);
            endif;
            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Görseli Başarıyla Silindi."]);
        else :
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Görseli Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
        endif;
    }
    public function fileIsActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_image_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı"]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı"]);
            endif;
        endif;
    }
    public function fileRankSetter($product_id)
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->product_image_model->update(
                    [
                        "id" => $row["id"],
                        "product_id" => $product_id
                    ],
                    ["rank" => $row["position"]]
                );
            endforeach;
        endif;
    }
    public function fileIsCoverSetter($id, $product_id, $lang)
    {
        if (!empty($id) && !empty($lang)) :
            $isCover = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_image_model->update(["id" => $id, "product_id" => $product_id], ["isCover" => $isCover, "lang" => $lang])) :
                $this->product_image_model->update(["id!=" => $id, "product_id" => $product_id], ["isCover" => 0, "lang" => $lang]);
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı"]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı"]);
            endif;
        endif;
    }
    public function parts($product_id)
    {
        $viewData = new stdClass();
        $viewData->product_id =  $product_id;
        $viewData->item = $this->product_model->get(["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "parts/list";
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function partDatatable($product_id)
    {
        $items = $this->product_part_model->getRows(["product_id" => $product_id], $_POST);
        $data = [];
        $i = (!empty($_POST['start']) ? $_POST['start'] : 0);
        if (!empty($items)) :
            foreach ($items as $item) :
                $i++;
                $proccessing = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        İşlemler
                    </button>
                    <div class="dropdown-menu rounded-0 dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item updateProductPartBtn" href="javascript:void(0)" data-url="' . base_url("products/part_update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="productPartsTable" data-url="' . base_url("products/part_delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                    </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/partIsActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch4' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch4' . $i . '"></label></div>';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $item->title, number_format($item->price, 2), $item->quantity, $item->lang, $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->product_part_model->rowCount(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->product_part_model->countFiltered(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function part_new_form($product_id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "parts/add";
        $viewData->product_id = $product_id;
        $viewData->product_variation_groups = $this->general_model->get_all("product_variation_groups", null, "rank ASC", ["isActive" => 1, "product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function partSave()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Parçası Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $getRank = $this->product_part_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
            $data["title"] = stripslashes($data["title"]);
            $data["isActive"] = 1;
            $data["rank"] = $getRank + 1;
            $insert = $this->product_part_model->add($data);
            if ($insert) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Parçası Başarıyla Eklendi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Parçası Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function part_update_form($id)
    {
        $viewData = new stdClass();
        $viewData->item = $this->product_part_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->product_variation_groups = $this->general_model->get_all("product_variation_groups", null, "rank ASC", ["isActive" => 1, "product_id" => $viewData->item->product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "parts/update";
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function partUpdate($id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Parçası Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            if (empty($data["pvgId"])) :
                $data["pvgId"] = null;
            endif;
            $data["title"] = stripslashes($data["title"]);
            $update = $this->product_part_model->update(["id" => $id], $data);
            if ($update) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Parçası Başarıyla Güncelleştirildi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Parçası Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function partDelete($id)
    {
        $productPart = $this->product_part_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        if (!empty($productPart)) :
            $delete = $this->product_part_model->delete(["id"    => $id]);
            if ($delete) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Parçası Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Parçası Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function partRankSetter()
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->product_part_model->update(
                    [
                        "id" => $row["id"]
                    ],
                    ["rank" => $row["position"]]
                );
            endforeach;
        endif;
    }
    public function partIsActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_part_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function dimensions($product_id)
    {
        $viewData = new stdClass();
        $viewData->product_id =  $product_id;
        $viewData->item = $this->product_model->get(["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "dimensions/list";
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function dimensionsDatatable($product_id)
    {
        $items = $this->product_dimension_model->getRows(["product_id" => $product_id], $_POST);
        $data = [];
        $i = (!empty($_POST['start']) ? $_POST['start'] : 0);
        if (!empty($items)) :
            foreach ($items as $item) :
                $i++;
                $proccessing = '
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-primary rounded-0 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        İşlemler
                    </button>
                    <div class="dropdown-menu rounded-0 dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item updateProductDimensionsBtn" href="javascript:void(0)" data-url="' . base_url("products/dimensions_update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="productDimensionsTable" data-url="' . base_url("products/dimensions_delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                    </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("products/dimensionsIsActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch4' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch4' . $i . '"></label></div>';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $item->title, $item->width, $item->height, $item->depth, $item->lang, $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->product_dimension_model->rowCount(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->product_dimension_model->countFiltered(["product_id" => $product_id, "lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function dimensions_new_form($product_id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "dimensions/add";
        $viewData->product_id = $product_id;
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function dimensionsSave()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] !== "width" && checkEmpty($data)["key"] !== "height" && checkEmpty($data)["key"] !== "depth") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Ölçüsü Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $getRank = $this->product_dimension_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
            $data["title"] = stripslashes($data["title"]);
            $data["isActive"] = 1;
            $data["rank"] = $getRank + 1;
            $insert = $this->product_dimension_model->add($data);
            if ($insert) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Ölçüsü Başarıyla Eklendi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Ölçüsü Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function dimensions_update_form($id)
    {
        $viewData = new stdClass();
        $viewData->item = $this->product_dimension_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "dimensions/update";
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1, "lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function dimensionsUpdate($id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && checkEmpty($data)["key"] !== "width" && checkEmpty($data)["key"] !== "height" && checkEmpty($data)["key"] !== "depth") :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Ölçüsü Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $data["title"] = stripslashes($data["title"]);
            $update = $this->product_dimension_model->update(["id" => $id], $data);
            if ($update) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Ölçüsü Başarıyla Güncelleştirildi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Ölçüsü Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function dimensionsDelete($id)
    {
        $productDimension = $this->product_dimension_model->get(["id" => $id, "lang" => $this->session->userdata("activeLang")]);
        if (!empty($productDimension)) :
            $delete = $this->product_dimension_model->delete(["id"    => $id]);
            if ($delete) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ürün Ölçüsü Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ürün Ölçüsü Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function dimensionsRankSetter()
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->product_dimension_model->update(
                    [
                        "id" => $row["id"]
                    ],
                    ["rank" => $row["position"]]
                );
            endforeach;
        endif;
    }
    public function dimensionsIsActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->product_dimension_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı."]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı."]);
            endif;
        endif;
    }
    public function syncParts($product_id = null, $arrayOfCategoryIds = [])
    {
        $categories = $this->general_model->get_all("product_categories", "id,title", "rank ASC", ["lang" => $this->session->userdata("activeLang")], [], [], [], ["id" => $arrayOfCategoryIds]);
        if (!empty($categories)) :
            foreach ($categories as $categoryKey => $categoryValue) :
                $categoryParts = $this->general_model->get_all("product_category_parts", null, "rank ASC", ["category_id" => $categoryValue->id, "lang" => $this->session->userdata("activeLang")]);
                if (!empty($categoryParts)) :
                    $product = $this->general_model->get("products", "id", ["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
                    if (!empty($products)) :
                        $productParts = $this->general_model->get_all("product_parts", null, "rank ASC", ["product_id" => $product->id, "lang" => $this->session->userdata("activeLang")]);
                        $getRank = $this->general_model->rowCount("product_parts", ["lang" => $this->session->userdata("activeLang")]);
                        if (empty($productParts)) :
                            $partArray = [];
                            foreach ($categoryParts as $categoryPartKey => $categoryPartValue) :
                                $getRank += 1;
                                array_push($partArray, ["product_id" => $product->id, "lang" => $categoryPartValue->lang, "title" => $categoryPartValue->title, "rank" => $getRank, "isActive" => 1, "quantity" => 1, "price" => 0]);
                            endforeach;
                            if (!empty($partArray)) :
                                $this->db->insert_batch("product_parts", $partArray);
                            endif;
                        else :
                            $partArray = [];
                            foreach ($categoryParts as $categoryPartKey => $categoryPartValue) :
                                if (!$this->general_model->rowCount("product_parts", ["product_id" => $product->id, "lang" => $categoryPartValue->lang, "title" => $categoryPartValue->title])) :
                                    $getRank += 1;
                                    array_push($partArray, ["product_id" => $product->id, "lang" => $categoryPartValue->lang, "title" => $categoryPartValue->title, "rank" => $getRank, "isActive" => 1, "quantity" => 1, "price" => 0]);
                                endif;
                            endforeach;
                            if (!empty($partArray)) :
                                $this->db->insert_batch("product_parts", $partArray);
                            endif;
                        endif;
                    endif;
                endif;
            endforeach;
        endif;
    }
    public function syncDimensions($product_id = null, $arrayOfCategoryIds = [])
    {
        if (!empty($product_id) && !empty($arrayOfCategoryIds)) :
            $categories = $this->general_model->get_all("product_categories", "id,title", "rank ASC", ["lang" => $this->session->userdata("activeLang")], [], [], [], ["id" => $arrayOfCategoryIds]);
            if (!empty($categories)) :
                foreach ($categories as $categoryKey => $categoryValue) :
                    $categoryDimensions = $this->general_model->get_all("product_category_dimensions", null, "rank ASC", ["category_id" => $categoryValue->id, "lang" => $this->session->userdata("activeLang")]);
                    if (!empty($categoryDimensions)) :
                        $product = $this->general_model->get("products", "id", ["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
                        if (!empty($product)) :
                            $productDimensions = $this->general_model->get_all("product_dimensions", null, "rank ASC", ["product_id" => $product->id, "lang" => $this->session->userdata("activeLang")]);
                            $getRank = $this->general_model->rowCount("product_dimensions", ["lang" => $this->session->userdata("activeLang")]);
                            if (empty($productDimensions)) :
                                $dimensionArray = [];
                                foreach ($categoryDimensions as $categoryDimensionKey => $categoryDimensionValue) :
                                    $getRank += 1;
                                    array_push($dimensionArray, ["product_id" => $product->id, "lang" => $categoryDimensionValue->lang, "title" => $categoryDimensionValue->title, "rank" => $getRank, "isActive" => 1, "width" => 0, "height" => 0, "depth" => 0]);
                                endforeach;
                                if (!empty($dimensionArray)) :
                                    $this->db->insert_batch("product_dimensions", $dimensionArray);;
                                endif;
                            else :
                                $dimensionArray = [];
                                foreach ($categoryDimensions as $categoryDimensionKey => $categoryDimensionValue) :
                                    if (!$this->general_model->rowCount("product_dimensions", ["product_id" => $product->id, "lang" => $categoryDimensionValue->lang, "title" => $categoryDimensionValue->title])) :
                                        $getRank += 1;
                                        array_push($dimensionArray, ["product_id" => $product->id, "lang" => $categoryDimensionValue->lang, "title" => $categoryDimensionValue->title, "rank" => $getRank, "isActive" => 1, "width" => 0, "height" => 0, "depth" => 0]);
                                    endif;
                                endforeach;
                                if (!empty($dimensionArray)) :
                                    $this->db->insert_batch("product_dimensions", $dimensionArray);
                                endif;
                            endif;
                        endif;
                    endif;
                endforeach;
            endif;
        endif;
    }
    public function syncVariations($product_id = null, $categoryId = null)
    {
        if (!empty($product_id) && !empty($categoryId)) :
            $variations = $this->general_model->get_all("product_variations", null, "rank ASC", ["category_id" => $categoryId, "lang" => $this->session->userdata("activeLang")]);
            if (!empty($variations)) :
                $product = $this->product_model->get(["id" => $product_id, "lang" => $this->session->userdata("activeLang")]);
                if (!empty($product)) :
                    $getRank = $this->product_variation_group_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
                    $data = [];
                    foreach ($variations as $vKey => $vValue) :
                        $getRank++;
                        array_push($data, [
                            "title" => $product->title . $getRank,
                            "lang" => $product->lang,
                            "price" => $product->price,
                            "discount" => $product->discount,
                            "stock" => $product->stock,
                            "stockStatus" => $product->stockStatus,
                            "isActive" => 1,
                            "product_id" => $product_id,
                            "rank" => $getRank,
                            "category_id" => $categoryId,
                            "variation_id" => $vValue->id
                        ]);
                    endforeach;
                    if (!empty($data)) :
                        $this->db->insert_batch("product_variation_groups", $data);
                    endif;
                endif;
            endif;
        endif;
    }
}
