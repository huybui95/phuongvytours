<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        // $this->load->model('supplier_model');
    }

    /**
     * Get contract/s
     * @param  mixed  $id         contract id
     * @param  array   $where      perform where
     * @param  boolean $for_editor if for editor is false will replace the field if not will not replace
     * @return mixed
     */
    public function get($id = '', $where = [], $for_editor = false)
    {
        $this->db->select(' * ');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $supplier = $this->db->get('tblsupplier')->row();
            return $supplier;
        }
    }
    public function get_debts($id = '', $where = [], $for_editor = false)
    {
        $this->db->select(' * ');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $supplier = $this->db->get('tblsupplier_debts')->row();
            return $supplier;
        }
    }
    public function get_invoices_suppliers($id = '', $where = [], $for_editor = false)
    {
        $this->db->select(' * ');
        $this->db->where($where);
        if (is_numeric($id)) {
            $this->db->where('id', $id);
            $supplier = $this->db->get('tblinvoices_supplier')->row();
            return $supplier;
        }
    }

    public function get_invoices_by_supplier($supplier_id)
    {
        $this->db->select('id,prefix');
        $this->db->from('tblinvoices_supplier');
        $this->db->where('supplierid', $supplier_id);
        return $this->db->get()->result_array();
    }
    
    public function get_invoices_by_ids($invoice_ids) {
        $this->db->select('datecreated,duedate,subtotal');
        $this->db->where_in('id', $invoice_ids);
        $query = $this->db->get('tblinvoices_supplier'); // Thay 'invoices' bằng tên bảng thực tế của bạn
        return $query->result_array();
    }

    public function ds_suppliers()
     {
        // $query = $this->db->query('select * from nguoi_dung');
        $query=$this->db->get(db_prefix().'supplier');
        if($query->num_rows()>0)
        {
            return $query->result_array();
            return false;
        }
     }
     public function ds_debts()
     {
        // $query = $this->db->query('select * from nguoi_dung');
        $query=$this->db->get(db_prefix().'supplier_debts');
        if($query->num_rows()>0)
        {
            return $query->result_array();
            return false;
        }
     }
     public function ds_typesuppliers()
     {
        // $query = $this->db->query('select * from nguoi_dung');
        $query=$this->db->get(db_prefix().'typesuppliers');
        if($query->num_rows()>0)
        {
            return $query->result_array();
            return false;
        }
     }
     public function get_typesuppliers($id) {
        $this->db->where('id', $id);
        return $this->db->get('tbltypesuppliers')->row();
    }
    public function add_typesuppliers($data) {
        $this->db->insert(db_prefix() .'typesuppliers', $data);
        return $this->db->insert_id();
    }
    public function update_typesuppliers($data, $id) {
        $this->db->where('id', $id);
        $this->db->update(db_prefix() .'typesuppliers', $data);
        return $this->db->affected_rows() > 0;
    }
    public function delete_typesuppliers($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() .'typesuppliers');
        if ($this->db->affected_rows() > 0) {
            return true;
        }
        return false;
    }
    //    
    public function add_debts($data)
    {
        $this->db->insert(db_prefix() . 'supplier_debts', $data);
        $insert_id = $this->db->insert_id();
        return true;
    }
    public function update_debts($data, $id)
    {
        // $affectedRows = 0;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() .'supplier_debts', $data);
        return true;
    }

    public function add($data)
    {
        $this->db->insert(db_prefix() . 'supplier', $data);
        $insert_id = $this->db->insert_id();
        return true;
    }
    public function update($data, $id)
    {
        // $affectedRows = 0;
        $this->db->where('id', $id);
        $this->db->update(db_prefix() .'supplier', $data);
        return true;
    }

    public function delete($id)
    {
        $this->db->delete(db_prefix() . 'supplier', ['id'=> $id]);
        // $this->db->delete(db_prefix() . 'supplier_members', ['supplier_id'=> $id]);
        return true;
    }
    public function delete_debt($id)
    {
        $this->db->delete(db_prefix() . 'supplier_debts', ['id'=> $id]);
        // $this->db->delete(db_prefix() . 'supplier_members', ['supplier_id'=> $id]);
        return true;
    }
    public function get_typesupplier_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tbltypesuppliers'); // hoặc tên bảng của bạn

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return false;
    }
    public function get_supplier_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('tblsupplier'); // hoặc tên bảng của bạn

        if ($query->num_rows() > 0) {
            return $query->row_array();
        }

        return false;
    }

}
