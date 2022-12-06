<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders extends MY_Controller
{
    public $viewFolder = "";
    public function __construct()
    {
        parent::__construct();
        $this->viewFolder = "orders_v";
        $this->load->model("order_model");
        $this->load->helper("text");
        if (!get_active_user()) :
            redirect(base_url("login"));
        endif;
    }
    public function index()
    {
        $viewData = new stdClass();
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "list";
        $viewData->items = $this->order_model->get_all([], "id DESC");
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/index", $viewData);
    }
    public function datatable()
    {
        $items = $this->order_model->getRows([], $_POST);
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
                        <a class="dropdown-item updateOrderBtn" href="javascript:void(0)" data-url="' . base_url("orders/update_form/$item->id") . '"><i class="fa fa-pen mr-2"></i>Kaydı Düzenle</a>
                        ';
                if (!empty($item->paymentType) && $item->status != "Ödeme Bekleniyor.") :
                    $proccessing .= '<a class="dropdown-item updateOrderCargoBtn" href="javascript:void(0)" data-url="' . base_url("orders/update_cargo_form/$item->id") . '"><i class="fa fa-truck-loading mr-2"></i>Kargo Bilgisi Oluştur</a>';
                endif;
                $proccessing .= '<a class="dropdown-item remove-btn" href="javascript:void(0)" data-table="ordersTable" data-url="' . base_url("orders/delete/$item->id") . '"><i class="fa fa-trash mr-2"></i>Kaydı Sil</a>
                    </div>
                </div>';
                $checkbox = '<div class="custom-control custom-switch"><input data-id="' . $item->id . '" data-url="' . base_url("orders/isActiveSetter/{$item->id}") . '" data-status="' . ($item->isActive == 1 ? "checked" : null) . '" id="customSwitch' . $i . '" type="checkbox" ' . ($item->isActive == 1 ? "checked" : null) . ' class="my-check custom-control-input" >  <label class="custom-control-label" for="customSwitch' . $i . '"></label></div>';
                $data[] = [$item->id, $item->full_name, $item->email, $item->phone, number_format($item->total, 2), ($item->status == "Ödeme Bekleniyor." ? "<span class='text-danger font-weight-bold'>" . $item->status . "</span>" : "<span class='text-success font-weight-bold'>" . $item->status . "</span>"), (empty($item->paymentType) ? "<span class='text-danger font-weight-bold'>Ödeme Bekleniyor.</span>" : "<span class='text-success font-weight-bold'>" . $item->paymentType . "</span>"), (empty($item->shippingStatus) ? "<span class='text-danger font-weight-bold'>Kargo Bilgileri Bekleniyor.</span>" : "<span class='text-success font-weight-bold'>" . $item->shippingStatus . "</span>"), $checkbox, turkishDate("d F Y, l H:i:s", $item->createdAt), turkishDate("d F Y, l H:i:s", $item->updatedAt), $proccessing];
            endforeach;
        endif;
        $output = [
            "draw" => (!empty($_POST['draw']) ? $_POST['draw'] : 0),
            "recordsTotal" => $this->order_model->rowCount(),
            "recordsFiltered" => $this->order_model->countFiltered([], (!empty($_POST) ? $_POST : [])),
            "data" => $data,
        ];
        // Output to JSON format
        echo json_encode($output);
    }
    public function update_form($id)
    {
        $viewData = new stdClass();
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
            ["orders.id" => $id],
            ["users" => ["users.id = orders.user_id", "left"]]
        );
        $viewData->order_data = json_decode(base64_decode($item->order_data), true);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "update";
        $viewData->item = $item;
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function update($id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $order = $this->general_model->get("orders", null, ["id" => $id]);
            if ($data["status"] == "İptal Edildi." && $order->paymentType == "Kredi Kartı") :
                ####################### DÜZENLEMESİ ZORUNLU ALANLAR #######################
                #
                ## API Entegrasyon Bilgileri - Mağaza paneline giriş yaparak BİLGİ sayfasından alabilirsiniz.
                $merchant_id     = "241819";
                $merchant_key     = "Aey1bpuKFbjfXozS";
                $merchant_salt    = "MgYngW25Xffy8Q4s";
                #
                # Sipariş No: İade etmek istediğiniz siparişin numarası.
                $merchant_oid   = $order->order_code;
                #
                # İade Tutarı: Örneğin işlem 11.97 TL veya 11.97 USD ise.
                $return_amount  = $order->total;
                #
                # Referans Numarası: En fazla 64 karakter, alfa numerik. Zorunlu değil.
                $reference_no  = $order->order_code;
                #
                ####### Bu kısımda herhangi bir değişiklik yapmanıza gerek yoktur. #######
                $paytr_token = base64_encode(hash_hmac('sha256', $merchant_id . $merchant_oid . $return_amount . $merchant_salt, $merchant_key, true));
                $post_vals = [
                    'merchant_id' => $merchant_id,
                    'merchant_oid' => $merchant_oid,
                    'return_amount' => $return_amount,
                    //'reference_no' => $reference_no,
                    'paytr_token' => $paytr_token
                ];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/iade");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 90);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 90);
                //XXX: DİKKAT: lokal makinanızda "SSL certificate problem: unable to get local issuer certificate" uyarısı alırsanız eğer
                //aşağıdaki kodu açıp deneyebilirsiniz. ANCAK, güvenlik nedeniyle sunucunuzda (gerçek ortamınızda) bu kodun kapalı kalması çok önemlidir!
                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                $result = @curl_exec($ch);
                if (curl_errno($ch)) :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => curl_error($ch)]);
                    curl_close($ch);
                    die();
                endif;
                curl_close($ch);
                $result = json_decode($result, 1);
                /*
                    $result değeri içerisinde;
                    [status]        - İade talebi başarılı ise success döner.
                    [is_test]       - İade talebi test işlem içinse 1 döner.
                    [merchant_oid]  - İade talebi yapılan sipariş numarası.
                    [return_amount] - İade talebi yapılan tutar.
                    bilgileri dönmektedir.
                */
                if ($result["status"] == 'success') :
                    $update = $this->order_model->update(["id" => $id], $data);
                    if ($update) :
                        $user = $this->general_model->get("users", null, ["id" => $order->user_id]);
                        $this->viewData = new stdClass();
                        $this->viewData->lang = $user->lang;
                        $this->viewData->settings = get_settings();
                        $this->lang->load($this->viewData->lang, $this->viewData->lang);
                        /**
                         * SET EMAIL DATA
                         */
                        $this->viewData->user = $user;
                        $this->viewData->subject = lang("orderInformations");
                        $this->viewData->address_title = $order->address_title;
                        $this->viewData->order = $order;
                        $this->viewData->order_data = json_decode(base64_decode($order->order_data), true);
                        if ($data["status"] == "İptal Edildi." && $order->status !== "İptal Edildi.") :
                            /**
                             * Stock Increase
                             */
                            if (!empty($this->viewData->order_data["cart"])) :
                                foreach ($this->viewData->order_data["cart"] as $cart_key => $cart_value) :
                                    /**
                                     * Cart & Wishlist Products
                                     */
                                    $wheres["p.isActive"] = 1;
                                    $wheres["pi.isCover"] = 1;
                                    $wheres["p.id"] = $cart_value["id"];
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
                                        $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"]]);
                                        $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                        $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                        $product_variations = [];
                                        $product_variation_categories = [];
                                        if (!empty($product_variation_in_group)) :
                                            foreach ($product_variation_in_group as $key => $value) :
                                                $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value]);
                                                $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key]]);
                                                array_push($product_variation_categories, $product_variation_group->title);
                                                array_push($product_variations, $product_variation->title);
                                            endforeach;
                                        endif;
                                    endif;
                                    if (!empty($product)) :
                                        $product->stock = (float)$product->stock + (float)$cart_value["qty"];
                                        if ($product->stockStatus) :
                                            if (!empty($cart_value["options"]["pvgId"])) :
                                                $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"]], ["stock" => $product->stock]);
                                            else :
                                                $this->general_model->update("products", ["id" => $cart_value["id"]], ["stock" => $product->stock]);
                                            endif;
                                        endif;
                                    endif;
                                endforeach;
                            endif;
                            /**
                             * Stock Increase
                             */
                        elseif ($order->status == "İptal Edildi." && $data["status"] !== "İptal Edildi.") :
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
                                        $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"]]);
                                        $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                        $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                        $product_variations = [];
                                        $product_variation_categories = [];
                                        if (!empty($product_variation_in_group)) :
                                            foreach ($product_variation_in_group as $key => $value) :
                                                $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value]);
                                                $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key]]);
                                                array_push($product_variation_categories, $product_variation_group->title);
                                                array_push($product_variations, $product_variation->title);
                                            endforeach;
                                        endif;
                                    endif;
                                    if (!empty($product)) :
                                        $product->stock = (float)$product->stock - (float)$cart_value["qty"];
                                        if ($product->stockStatus) :
                                            if (!empty($cart_value["options"]["pvgId"])) :
                                                $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"]], ["stock" => $product->stock]);
                                            else :
                                                $this->general_model->update("products", ["id" => $cart_value["id"]], ["stock" => $product->stock]);
                                            endif;
                                        endif;
                                    endif;
                                endforeach;
                            endif;
                            /**
                             * Stock Decrease
                             */
                        endif;
                        $this->viewData->address = $order->address . " " . $order->quarter . " " . $order->neighborhood . " " . $order->district . " " . $order->city . " / " . $order->country;
                        $this->viewData->message = $this->viewData->settings->company_name . ' ' . lang("orderInformationMessage") . " : <b>" . lang($data["status"]) . "</b> " . lang("orderInformationMessage3");
                        $this->viewData->symbol = $order->symbol;
                        /**
                         * Send To User
                         */
                        $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                        if (send_emailv2([$this->viewData->user->email], $this->viewData->subject, $mailViewData)) :
                            echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Sipariş Başarıyla Güncelleştirildi."]);
                        else :
                            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Durumu Maili Gönderilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                        endif;
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => $result["err_msg"]]);
                    die();
                endif;
            else :
                $update = $this->order_model->update(["id" => $id], $data);
                if ($update) :
                    $user = $this->general_model->get("users", null, ["id" => $order->user_id]);
                    $this->viewData = new stdClass();
                    $this->viewData->lang = $user->lang;
                    $this->viewData->settings = get_settings();
                    $this->lang->load($this->viewData->lang, $this->viewData->lang);
                    /**
                     * SET EMAIL DATA
                     */
                    $this->viewData->user = $user;
                    $this->viewData->subject = lang("orderInformations");
                    $this->viewData->address_title = $order->address_title;
                    $this->viewData->order = $order;
                    $this->viewData->order_data = json_decode(base64_decode($order->order_data), true);
                    if ($data["status"] == "İptal Edildi." && $order->status !== "İptal Edildi.") :
                        /**
                         * Stock Increase
                         */
                        if (!empty($this->viewData->order_data["cart"])) :
                            foreach ($this->viewData->order_data["cart"] as $cart_key => $cart_value) :
                                /**
                                 * Cart & Wishlist Products
                                 */
                                $wheres["p.isActive"] = 1;
                                $wheres["pi.isCover"] = 1;
                                $wheres["p.id"] = $cart_value["id"];
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
                                    $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"]]);
                                    $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                    $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                    $product_variations = [];
                                    $product_variation_categories = [];
                                    if (!empty($product_variation_in_group)) :
                                        foreach ($product_variation_in_group as $key => $value) :
                                            $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value]);
                                            $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key]]);
                                            array_push($product_variation_categories, $product_variation_group->title);
                                            array_push($product_variations, $product_variation->title);
                                        endforeach;
                                    endif;
                                endif;
                                if (!empty($product)) :
                                    $product->stock = (float)$product->stock + (float)$cart_value["qty"];
                                    if ($product->stockStatus) :
                                        if (!empty($cart_value["options"]["pvgId"])) :
                                            $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"]], ["stock" => $product->stock]);
                                        else :
                                            $this->general_model->update("products", ["id" => $cart_value["id"]], ["stock" => $product->stock]);
                                        endif;
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        /**
                         * Stock Increase
                         */
                    elseif ($order->status == "İptal Edildi." && $data["status"] !== "İptal Edildi.") :
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
                                    $product_variation_group = $this->general_model->get("product_variation_groups", null, ["isActive" => 1, "id" => $cart_value["options"]["pvgId"]]);
                                    $product_variation_group_in_group = explode(",", $product_variation_group->category_id);
                                    $product_variation_in_group = explode(",", $product_variation_group->variation_id);
                                    $product_variations = [];
                                    $product_variation_categories = [];
                                    if (!empty($product_variation_in_group)) :
                                        foreach ($product_variation_in_group as $key => $value) :
                                            $product_variation = $this->general_model->get("product_variations", null, ["isActive" => 1, "id" => $value]);
                                            $product_variation_group = $this->general_model->get("product_variation_categories", null, ["isActive" => 1, "id" => $product_variation_group_in_group[$key]]);
                                            array_push($product_variation_categories, $product_variation_group->title);
                                            array_push($product_variations, $product_variation->title);
                                        endforeach;
                                    endif;
                                endif;
                                if (!empty($product)) :
                                    $product->stock = (float)$product->stock - (float)$cart_value["qty"];
                                    if ($product->stockStatus) :
                                        if (!empty($cart_value["options"]["pvgId"])) :
                                            $this->general_model->update("product_variation_groups", ["id" => $cart_value["options"]["pvgId"], "product_id" => $cart_value["id"]], ["stock" => $product->stock]);
                                        else :
                                            $this->general_model->update("products", ["id" => $cart_value["id"]], ["stock" => $product->stock]);
                                        endif;
                                    endif;
                                endif;
                            endforeach;
                        endif;
                        /**
                         * Stock Decrease
                         */
                    endif;
                    $this->viewData->address = $order->address . " " . $order->quarter . " " . $order->neighborhood . " " . $order->district . " " . $order->city . " / " . $order->country;
                    $this->viewData->message = $this->viewData->settings->company_name . ' ' . lang("orderInformationMessage") . " : <b>" . lang($data["status"]) . "</b> " . lang("orderInformationMessage2");
                    $this->viewData->symbol = $order->symbol;
                    /**
                     * Send To User
                     */
                    $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                    if (send_emailv2([$this->viewData->user->email], $this->viewData->subject, $mailViewData)) :
                        echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Sipariş Başarıyla Güncelleştirildi."]);
                    else :
                        echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Durumu Maili Gönderilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                    endif;
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                endif;
            endif;
        endif;
    }
    public function update_cargo_form($id)
    {
        $viewData = new stdClass();
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
            ["orders.id" => $id,],
            ["users" => ["users.id = orders.user_id", "left"]]
        );
        $viewData->order_data = json_decode(base64_decode($item->order_data), true);
        $viewData->viewFolder = $this->viewFolder;
        $viewData->subViewFolder = "cargo";
        $viewData->item = $item;
        $viewData->settings = $this->general_model->get_all("settings", null, null, ["isActive" => 1]);
        $this->load->view("{$viewData->viewFolder}/{$viewData->subViewFolder}/content", $viewData);
    }
    public function update_cargo($id)
    {
        $data = rClean($this->input->post());
        if (checkEmpty($data)["error"]) :
            $key = checkEmpty($data)["key"];
            echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Kargo Bilgisi Güncelleştirilirken Hata Oluştu. \"{$key}\" Bilgisini Doldurduğunuzdan Emin Olup Tekrar Deneyin."]);
        else :
            $order = $this->general_model->get("orders", null, ["id" => $id]);
            $update = $this->order_model->update(["id" => $id], $data);
            if ($update) :
                $user = $this->general_model->get("users", null, ["id" => $order->user_id]);
                $this->viewData = new stdClass();
                $this->viewData->lang = $user->lang;
                $this->viewData->settings = get_settings();
                $this->lang->load($this->viewData->lang, $this->viewData->lang);
                /**
                 * SET EMAIL DATA
                 */
                $this->viewData->user = $user;
                $this->viewData->subject = lang("orderShippingInformations");
                $this->viewData->address_title = $order->address_title;
                $this->viewData->order = $order;
                $this->viewData->order_data = json_decode(base64_decode($order->order_data), true);
                $this->viewData->address = $order->address . " " . $order->quarter . " " . $order->neighborhood . " " . $order->district . " " . $order->city . " / " . $order->country;
                $this->viewData->message = $this->viewData->settings->company_name . ' ' . lang("orderShippingConfirmMessage");
                $this->viewData->symbol = $order->symbol;
                $cargo_company = $data["shippingCompany"];
                $cargo_code = $data["shippingCode"];
                $this->viewData->message .= "<p><b>{$cargo_company} " . lang("orderTrackingText") . " :</b> {$cargo_code}</p>";
                switch ($data["shippingCompany"]):
                    case "ARAS":
                        $this->viewData->message .= "<a href='https://kargotakip.araskargo.com.tr/mainpage.aspx?code=" . (!empty($cargo_code) ? $cargo_code : null) . "' class='d-flex justify-content-center align-middle align-items-center mx-auto text-center px-auto btn btn-danger' target='_blank'>{$cargo_company} " . lang("clickToTracking") . "<a>";
                        break;
                    case "MNG":
                        $this->viewData->message .= "<a href='https://service.mngkargo.com.tr/iactive/popup/KargoTakip/link1.asp?k=" . (!empty($cargo_code) ? $cargo_code : null) . "' class='d-flex justify-content-center align-middle align-items-center mx-auto text-center px-auto btn btn-danger' target='_blank'>{$cargo_company} " . lang("clickToTracking") . "<a>";
                        break;
                    case "SÜRAT":
                        $this->viewData->message .= "<a href='https://suratkargo.com.tr/KargoTakip/?kargotakipno=" . (!empty($cargo_code) ? $cargo_code : null) . "' class='d-flex justify-content-center align-middle align-items-center mx-auto text-center px-auto btn btn-danger' target='_blank'>{$cargo_company} " . lang("clickToTracking") . "<a>";
                        break;
                    case "YURTİÇİ":
                        $this->viewData->message .= "<a href='https://selfservis.yurticikargo.com/reports/SSWDocumentDetail.aspx?DocId=" . (!empty($cargo_code) ? $cargo_code : null) . "' class='d-flex justify-content-center align-middle align-items-center mx-auto text-center px-auto btn btn-danger' target='_blank'>{$cargo_company} " . lang("clickToTracking") . "<a>";
                        break;
                    default:
                    case "MNG":
                        $this->viewData->message .= "<a href='https://service.mngkargo.com.tr/iactive/popup/KargoTakip/link1.asp?k=" . (!empty($cargo_code) ? $cargo_code : null) . "' class='d-flex justify-content-center align-middle align-items-center mx-auto text-center px-auto btn btn-danger' target='_blank'>{$cargo_company} " . lang("clickToTracking") . "<a>";
                        break;
                        break;
                endswitch;
                /**
                 * Send To User
                 */
                $mailViewData = $this->load->view("includes/mail_template", (array)$this->viewData, true);
                if (send_emailv2([$this->viewData->user->email], $this->viewData->subject, $mailViewData)) :
                    $this->general_model->update("orders", ["id" => $order->id], ["shippingStatus" => 'Kargoya Verildi.', "status" => 'Kargoya Verildi.']);
                    echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Sipariş Kargo Bilgisi Başarıyla Güncelleştirildi."]);
                else :
                    echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Kargo Maili Gönderilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
                endif;
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Kargo Bilgisi Güncelleştirilirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;

        endif;
    }
    public function delete($id)
    {
        $order = $this->order_model->get(["id" => $id]);
        if (!empty($order)) :
            $order_model = $this->order_model->delete(["id"    => $id]);
            if ($order_model) :
                if (!empty($order->img_url)) :
                    if (!is_dir(FCPATH . "uploads/{$this->viewFolder}/{$order->img_url}") && file_exists(FCPATH . "uploads/{$this->viewFolder}/{$order->img_url}")) :
                        unlink(FCPATH . "uploads/{$this->viewFolder}/{$order->img_url}");
                    endif;
                endif;
                echo json_encode(["success" => true, "title" => "Başarılı!", "message" => "Sipariş Başarıyla Silindi."]);
            else :
                echo json_encode(["success" => false, "title" => "Başarısız!", "message" => "Sipariş Silinirken Hata Oluştu, Lütfen Tekrar Deneyin."]);
            endif;
        endif;
    }
    public function isActiveSetter($id)
    {
        if (!empty($id)) :
            $isActive = (intval($this->input->post("data")) === 1) ? 1 : 0;
            if ($this->order_model->update(["id" => $id], ["isActive" => $isActive])) :
                echo json_encode(["success" => True, "title" => "İşlem Başarıyla Gerçekleşti", "msg" => "Güncelleme İşlemi Yapıldı"]);
            else :
                echo json_encode(["success" => False, "title" => "İşlem Başarısız Oldu", "msg" => "Güncelleme İşlemi Yapılamadı"]);
            endif;
        endif;
    }
}
