<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends MY_Controller
{
    public $viewFolder = "";
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "settings_v";
        if (!get_active_user()) :
            redirect(base_url("login"));
        endif;
        $this->load->model("settings_model");
    }
    public function index()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->languages = $this->general_model->get_all("languages");
        $viewData->item = $this->general_model->get("settings");
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function datatable()
    {
        $items = $this->settings_model->getRows([], $_POST);
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
                        <a class="dropdown-item updateSettingsBtn" href="javascript:void(0)" data-url="' . base_url("settings/update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item" href="' . base_url("dashboard/language") . '"><i class="fa fa-language mr-2"></i>Dil Sabitlerini Düzenle</a>
                        <a class="dropdown-item remove-btn d-none" href="javascript:void(0)" data-table="settingsTable" data-url="' . base_url("settings/delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                        </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("settings/isActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch' . $i . '"></label></div>';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $item->company_name, $item->lang, $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->settings_model->rowCount(),
            "recordsFiltered" => $this->settings_model->countFiltered([], (!empty($_POST) ? $_POST : [])),
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
        $viewData->languages = $this->general_model->get_all("languages");
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function save()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"] && (checkEmpty($data)["key"] === "company_name" || checkEmpty($data)["key"] === "email")) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            if (empty($_FILES["logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["mobile_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Mobil Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["favicon"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Favicon Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["contact_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. İletişim Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["blog_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Blog Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["service_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Hizmet Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["reference_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Bayi Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["about_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Kurumsal Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            if (empty($_FILES["gallery_logo"]["name"])) :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Galeri Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                die();
            endif;
            $logo = upload_picture("logo", "uploads/$this->viewFolder", [], "*");
            $mobile_logo = upload_picture("mobile_logo", "uploads/$this->viewFolder", [], "*");
            $favicon = upload_picture("favicon", "uploads/$this->viewFolder", [], "*");
            $contact_logo = upload_picture("contact_logo", "uploads/$this->viewFolder", [], "*");
            $blog_logo = upload_picture("blog_logo", "uploads/$this->viewFolder", [], "*");
            $service_logo = upload_picture("service_logo", "uploads/$this->viewFolder", [], "*");
            $reference_logo = upload_picture("reference_logo", "uploads/$this->viewFolder", [], "*");
            $about_logo = upload_picture("about_logo", "uploads/$this->viewFolder", [], "*");
            $gallery_logo = upload_picture("gallery_logo", "uploads/$this->viewFolder", [], "*");
            $product_logo = upload_picture("product_logo", "uploads/$this->viewFolder", [], "*");
            $product_detail_logo = upload_picture("product_detail_logo", "uploads/$this->viewFolder", [], "*");
            $getRank = $this->settings_model->rowCount();
            if ($logo["success"]) :
                $data["logo"] = $logo["file_name"];
                if ($mobile_logo["success"]) :
                    $data["mobile_logo"] = $mobile_logo["file_name"];
                    if ($favicon["success"]) :
                        $data["favicon"] = $favicon["file_name"];
                        if ($contact_logo["success"]) :
                            $data["contact_logo"] = $contact_logo["file_name"];
                            if ($blog_logo["success"]) :
                                $data["blog_logo"] = $blog_logo["file_name"];
                                if ($service_logo["success"]) :
                                    $data["service_logo"] = $service_logo["file_name"];
                                    if ($reference_logo["success"]) :
                                        $data["reference_logo"] = $reference_logo["file_name"];
                                        if ($about_logo["success"]) :
                                            $data["about_logo"] = $about_logo["file_name"];
                                            if ($gallery_logo["success"]) :
                                                $data["gallery_logo"] = $gallery_logo["file_name"];
                                                if ($product_logo["success"]) :
                                                    $data["product_logo"] = $product_logo["file_name"];
                                                    if ($product_detail_logo["success"]) :
                                                        $data["product_detail_logo"] = $product_detail_logo["file_name"];
                                                        $data["address"] = $this->input->post("address");
                                                        $data["address_2"] = $this->input->post("address_2");
                                                        $data["favicon"] = $favicon["file_name"];
                                                        $data["isActive"] = 1;
                                                        $data["rank"] = $getRank + 1;
                                                        $data["map"] = htmlspecialchars(html_entity_decode($this->input->post("map")));
                                                        $data["map_2"] = htmlspecialchars(html_entity_decode($this->input->post("map_2")));
                                                        $data["about_us"] = $this->input->post("about_us");
                                                        $data["metrica"] = $this->input->post("metrica");
                                                        $data["analytics"] = $this->input->post("analytics");
                                                        $data["live_support"] = $this->input->post("live_support");
                                                        $insert = $this->settings_model->add($data);
                                                        if ($insert) :
                                                            $content = file_get_contents(FCPATH . "assets/language/language.json", "r");
                                                            $create = fopen(FCPATH . "assets/language/" . $data["lang"] . ".json", "w");
                                                            $create = fwrite($create, $content);
                                                            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ayar Başarıyla Eklendi."]);
                                                        else :
                                                            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                                                        endif;
                                                    else :
                                                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Ürün Detay Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                                    endif;
                                                else :
                                                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Ürünler Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                                endif;
                                            else :
                                                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Galeri Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                            endif;
                                        else :
                                            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Kurumsal Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                        endif;
                                    else :
                                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Bayi Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                    endif;
                                else :
                                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Hizmet Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                                endif;
                            else :
                                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Blog Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                            endif;
                        else :
                            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. İletişim Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Favicon Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Mobil Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                endif;
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Eklenirken Hata Oluştu. Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function update_form($id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->languages = $this->general_model->get_all("languages");
        $viewData->item = $this->general_model->get("settings", null, ["id" => $id]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function update($id)
    {
        $settings = $this->settings_model->get(["id" => $id]);
        if (!empty($settings)) :
            $data = rClean($this->input->post());
            if (checkEmpty($data)["error"] && (checkEmpty($data)["key"] === "company_name" || checkEmpty($data)["key"] === "email")) :
                $key = checkEmpty($data)["key"];
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
            else :
                if (!empty($_FILES["logo"]["name"])) :
                    $image = upload_picture("logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["logo"] = $image["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}");
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["mobile_logo"]["name"])) :
                    $image = upload_picture("mobile_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["mobile_logo"] = $image["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}");
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Mobil Logo Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["favicon"]["name"])) :
                    $image = upload_picture("favicon", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["favicon"] = $image["file_name"];
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}");
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Favicon Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["contact_logo"]["name"])) :
                    $image = upload_picture("contact_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["contact_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. İletişim Logosunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["blog_logo"]["name"])) :
                    $image = upload_picture("blog_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["blog_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Blog Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["service_logo"]["name"])) :
                    $image = upload_picture("service_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["service_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Hizmet Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["reference_logo"]["name"])) :
                    $image = upload_picture("reference_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["reference_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Bayi Logosusunu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["about_logo"]["name"])) :
                    $image = upload_picture("about_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["about_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Kurumsal Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["gallery_logo"]["name"])) :
                    $image = upload_picture("gallery_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["gallery_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Galeri Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["product_logo"]["name"])) :
                    $image = upload_picture("product_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["product_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Ürünler Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["product_detail_logo"]["name"])) :
                    $image = upload_picture("product_detail_logo", "uploads/$this->viewFolder", [], "*");
                    if ($image["success"]) :
                        $data["product_detail_logo"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu. Ürün Detay Logosu Seçtiğinizden Emin Olup, Lütfen Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                $data["company_name"] = strip_slashes($data["company_name"]);
                $data["slogan"] = strip_slashes($data["slogan"]);
                $data["address"] = $this->input->post("address");
                $data["map"] = $this->input->post("map");
                $data["address_2"] = $this->input->post("address_2");
                $data["map_2"] = $this->input->post("map_2");
                $data["about_us"] = $this->input->post("about_us");
                $data["live_support"] = $this->input->post("live_support");
                $data["metrica"] = $this->input->post("metrica");
                $data["analytics"] = $this->input->post("analytics");
                $update = $this->general_model->update("settings", ["id" => $id], $data);
                if ($update) :
                    echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ayar Başarıyla Güncelleştirildi."]);
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                endif;
            endif;
        endif;
    }
    public function json_form($id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "json";
        $viewData->item = $this->general_model->get("settings", null, ["id" => $id]);
        if (!file_exists(FCPATH . "assets/language/" . $viewData->item->lang . ".json")) :
            $content = file_get_contents(FCPATH . "assets/language/language.json", "r");
            $create = fopen(FCPATH . "assets/language/" . $viewData->item->lang . ".json", "w");
            $create = fwrite($create, $content);
        endif;
        $viewData->content = json_decode(file_get_contents(FCPATH . "assets/language/" . $viewData->item->lang . ".json", "r"));
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function update_json($id)
    {
        $item = $this->general_model->get("settings", null, ["id" => $id]);
        $data = $this->input->post();
        unset($data["routes"]["0"]);
        $data["routes"]["sitemap.xml"] = "sitemap.xml";
        $data["routes"]["sitemapindex.xml"] = "sitemapindex.xml";
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        $create = fopen(FCPATH . "assets/language/" . $item->lang . ".json", "w");
        $create = fwrite($create, $data);
        if ($create) :
            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Dil Sabitleri Başarıyla Güncelleştirildi."]);
        else :
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Dil Sabitleri Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
        endif;
    }
    public function uploadImage()
    {
        $image = upload_picture("file", "uploads/tinymce");
        if ($image["success"]) :
            $filename = $image["file_name"];
            echo json_encode(['location' => base_url("uploads/tinymce/{$filename}")]);
        else :
            // Notify editor that the upload failed
            header("HTTP/1.1 500 Server Error");
        endif;
    }
    public function delete($id)
    {
        $settings = $this->settings_model->get(["id" => $id]);
        if (!empty($settings)) :
            $delete = $this->settings_model->delete(["id" => $id]);
            if ($delete) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->logo}");
                endif;
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->mobile_logo}");
                endif;
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$settings->favicon}");
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Ayar Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Ayar Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function rankSetter()
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->settings_model->update(
                    [
                        "id" => $row["id"]
                    ],
                    ["rank" => $row["position"] + 1]
                );
            endforeach;
        endif;
    }
}
