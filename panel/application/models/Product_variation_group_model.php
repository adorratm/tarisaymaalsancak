<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_variation_group_model extends CI_Model
{
    public $tableName = "product_variation_groups";
    public function __construct()
    {
        parent::__construct();
        $this->column_order = ['product_variation_groups.rank', 'product_variation_groups.id', 'product_variation_groups.id', 'product_variation_groups.title', 'product_variation_groups.variation_categories', 'product_variation_groups.variations', 'product_variation_groups.price', 'product_variation_groups.discount', 'product_variation_groups.stock','product_variation_groups.lang', 'product_variation_groups.isActive', 'product_variation_groups.createdAt', 'product_variation_groups.updatedAt'];
        // Set searchable column fields
        $this->column_search = ['product_variation_groups.rank', 'product_variation_groups.id', 'product_variation_groups.id', 'product_variation_groups.title', 'product_variation_groups.variation_categories', 'product_variation_groups.variations', 'product_variation_groups.price', 'product_variation_groups.discount', 'product_variation_groups.stock','product_variation_groups.lang', 'product_variation_groups.isActive', 'product_variation_groups.createdAt', 'product_variation_groups.updatedAt'];
        // Set default order
        $this->order = ['product_variation_groups.rank' => 'ASC'];
    }
    public function get_all($where = [], $order = "product_variation_groups.id ASC")
    {
        return $this->db->where($where)->order_by($order)->get($this->tableName)->result();;
    }
    public function add($data = [])
    {
        return $this->db->insert($this->tableName, $data);
    }
    public function get($where = [])
    {
        return $this->db->where($where)->get($this->tableName)->row();
    }
    public function update($where = [], $data = [])
    {
        return $this->db->where($where)->update($this->tableName, $data);
    }
    public function delete($where = [])
    {
        return $this->db->where($where)->delete($this->tableName);
    }
    public function getRows($where = [], $postData = [])
    {
        if (!empty($where)) :
            $this->db->where($where);
        endif;
        $this->_get_datatables_query($postData);
        if ($postData['length'] != -1) :
            $this->db->limit($postData['length'], $postData['start']);
        endif;
        return $this->db->get()->result();
    }
    private function _get_datatables_query($postData = [])
    {
        $this->db->where(["product_variation_groups.id!=" => null]);
        $this->db->select('
        product_variation_groups.id id,
        product_variation_groups.price price,
        product_variation_groups.discount discount,
        product_variation_groups.stock stock,
		product_variation_groups.rank rank,
		product_variation_groups.id variation_group_id,
        GROUP_CONCAT(DISTINCT pvc.title,\'\') AS variation_categories,
        GROUP_CONCAT(DISTINCT pv.title,\'\') AS variations,
        product_variation_groups.lang lang,
		product_variation_groups.isActive isActive,
		product_variation_groups.createdAt createdAt,
        product_variation_groups.updatedAt updatedAt',    false);
        $this->db->join("product_variation_categories as pvc", "find_in_set(pvc.id,json_unquote(product_variation_groups.category_id)) <>0", "left", false);
        $this->db->join("product_variations as pv", "find_in_set(pv.id,json_unquote(product_variation_groups.variation_id)) <>0", "left", false);
        $this->db->from($this->tableName);
        $this->db->group_by("product_variation_groups.id");
        $i = 0;
        // loop searchable columns
        if (!empty($this->column_search)) :
            foreach ($this->column_search as $item) :
                // if datatable send POST for search
                if (!empty($postData['search'])) :
                    // first loop
                    if ($i === 0) :
                        // open bracket
                        $this->db->group_start();
                        $this->db->like($item, $postData['search'], 'both');
                        $this->db->or_like($item, strto("lower", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|upper", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|ucwords", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|capitalizefirst", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|ucfirst", $postData['search']), 'both');
                    else :
                        $this->db->or_like($item, $postData['search'], 'both');
                        $this->db->or_like($item, strto("lower", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|upper", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|ucwords", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|capitalizefirst", $postData['search']), 'both');
                        $this->db->or_like($item, strto("lower|ucfirst", $postData['search']), 'both');
                    endif;
                    // last loop
                    if (count($this->column_search) - 1 == $i) :
                        // close bracket
                        $this->db->group_end();
                    endif;
                endif;
                $i++;
            endforeach;
        endif;
        if (isset($postData['order'])) :
            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        elseif (isset($this->order)) :
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        endif;
    }
    public function rowCount($where = [])
    {
        return $this->db->where($where)->count_all_results($this->tableName);
    }
    public function countFiltered($where = [], $postData = null)
    {
        $this->_get_datatables_query($postData);
        $query = $this->db->where($where)->get();
        return $query->num_rows();
    }
}
