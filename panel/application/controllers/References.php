<?php
defined('BASEPATH') or exit('No direct script access allowed');

class References extends MY_Controller
{
    public $viewFolder = "";
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "references_v";
        $this->load->model("reference_model");
        $this->load->model("reference_category_model");
        $this->load->model("user_model");
        if (!get_active_user()) :
            redirect(base_url("login"));
        endif;
    }
    public function index()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $this->reference_model->get_all(["lang" => $this->session->userdata("activeLang")], "rank ASC");
        $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function datatable()
    {
        $items = $this->reference_model->getRows(["lang" => $this->session->userdata("activeLang")], $_POST);
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
                        <a class="dropdown-item updateReferenceBtn" href="javascript:void(0)" data-url="' . base_url("references/update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        <a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="referencesTable" data-url="' . base_url("references/delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                    </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("references/isActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch' . $i . '"></label></div>';
                $data[] = [$item->rank, '<i class="fa fa-arrows" data-id="' . $item->id . '"></i>', $item->id, $item->title, $item->lang,  $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), turkishDate("d F Y, l H:i:s", $item->sharedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->reference_model->rowCount(["lang" => $this->session->userdata("activeLang")]),
            "recordsFiltered" => $this->reference_model->countFiltered(["lang" => $this->session->userdata("activeLang")], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function new_form()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->categories = $this->reference_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1,"lang" => $this->session->userdata("activeLang")]);
        $viewData->countries = $this->general_model->get_all("countries");
        $viewData->cities = $this->general_model->get_all("cities");
        $viewData->subViewFolder = "add";
        $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function save()
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Kaydı Yapılırken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $getRank = $this->reference_model->rowCount(["lang" => $this->session->userdata("activeLang")]);
            if (!empty($_FILES)) :
                if (!empty($_FILES["img_url"]["name"])) :
                    $image = upload_picture("img_url", "uploads/$this->viewFolder");
                    if ($image["success"]) :
                        $data["img_url"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Kaydı Yapılırken Hata Oluştu. Bayi Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
                if (!empty($_FILES["banner_url"]["name"])) :
                    $image = upload_picture("banner_url", "uploads/$this->viewFolder");
                    if ($image["success"]) :
                        $data["banner_url"] = $image["file_name"];
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Kaydı Yapılırken Hata Oluştu. Bayi Banner Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                        die();
                    endif;
                endif;
            endif;
            $data["seo_url"] = seo($data["title"]);
            $data["content"] = $_POST["content"];
            $data["isActive"] = 1;
            $data["rank"] = $getRank + 1;
            $insert = $this->reference_model->add($data);
            if ($insert) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Bayi Başarıyla Eklendi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Eklenirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function update_form($id)
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->categories = $this->reference_category_model->get_all(["lang" => $this->session->userdata("activeLang")]);
        $viewData->item = $this->reference_model->get(["id" => $id,"lang" => $this->session->userdata("activeLang")]);
        $viewData->countries = $this->general_model->get_all("countries");
        $viewData->cities = $this->general_model->get_all("cities");
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1,"lang" => $this->session->userdata("activeLang")]);
        $this->load->view("{$this->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function update($id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $reference = $this->reference_model->get(["id" => $id,"lang" => $this->session->userdata("activeLang")]);
            $data["img_url"] = $reference->img_url;
            $data["banner_url"] = $reference->banner_url;
            if (!empty($_FILES["img_url"]["name"])) :
                $image = upload_picture("img_url", "uploads/$this->viewFolder");
                if ($image["success"]) :
                    $data["img_url"] = $image["file_name"];
                    if (!empty($reference->img_url)) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}");
                        endif;
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Güncelleştirilirken Hata Oluştu. Bayi Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                    die();
                endif;
            endif;
            if (!empty($_FILES["banner_url"]["name"])) :
                $image = upload_picture("banner_url", "uploads/$this->viewFolder");
                if ($image["success"]) :
                    $data["banner_url"] = $image["file_name"];
                    if (!empty($reference->banner_url)) :
                        if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$reference->banner_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$reference->banner_url}")) :
                            unlink(FCPATH . "uploads/{$this->viewFolder}/{$reference->banner_url}");
                        endif;
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Güncelleştirilirken Hata Oluştu. Bayi Banner Görseli Seçtiğinizden Emin Olup Tekrar Deneyin."]);
                    die();
                endif;
            endif;
            $data["seo_url"] = seo($data["title"]);
            $data["content"] = $_POST["content"];
            $update = $this->reference_model->update(["id" => $id], $data);
            if ($update) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Bayi Başarıyla Güncelleştirildi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function delete($id)
    {
        $reference = $this->reference_model->get(["id" => $id,"lang" => $this->session->userdata("activeLang")]);
        if (!empty($reference)) :
            $delete = $this->reference_model->delete(["id"    => $id]);
            if (!empty($reference->img_url)) :
                if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}")) :
                    unlink(FCPATH . "uploads/{$this->viewFolder}/{$reference->img_url}");
                endif;
            endif;
            if ($delete) :
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Bayi Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Bayi Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function rankSetter()
    {
        $rows = $this->input->post("rows");
        if (!empty($rows)) :
            foreach ($rows as $row) :
                $this->reference_model->update(["id" => $row["id"]], ["rank" => $row["position"]]);
            endforeach;
        endif;
    }
    public function isActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->reference_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı"]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı"]);
            endif;
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
}
