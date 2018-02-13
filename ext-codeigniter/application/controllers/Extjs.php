<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extjs extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $set = $this->db->limit($this->input->get("limit"), $this->input->get("start"))->get('log');
        
        $count = $this->db->get('log');
        $arr_value = array(
            'response' => $set->result(),
            'jumlah' => $count->num_rows()
        );
        
        echo json_encode($arr_value);
        exit;
    }

    function save() {
        $this->db->select("*");
        $this->db->from("log");
        $this->db->where("log_id", $this->input->post('log_id', TRUE));
        $query = $this->db->get();
        $hasil = array(
            "responseText" => "Gagal",
            "success" => false
        );
        if ($query->num_rows() >= 1) {
            $data = array(
                'log_ip' => $this->input->post("log_ip"),
                'log_os' => $this->input->post("log_os"),
                'log_browser' => $this->input->post("log_browser"),
                'log_type' => $this->input->post("log_type"),
                'log_status' => $this->input->post("log_status"),
                'log_time' => $this->input->post("log_time"),
                'log_email' => $this->input->post("log_email"),
                'log_password' => $this->input->post("log_password"),
                'log_url' => $this->input->post("log_url")
            );
            $sukses = $this->db->where("log_id", $this->input->post("log_id"))->update("log", $data);
        } else {
            $data = array(
                'log_ip' => $this->input->post("log_ip"),
                'log_os' => $this->input->post("log_os"),
                'log_browser' => $this->input->post("log_browser"),
                'log_type' => $this->input->post("log_type"),
                'log_status' => $this->input->post("log_status"),
                'log_time' => $this->input->post("log_time"),
                'log_email' => $this->input->post("log_email"),
                'log_password' => $this->input->post("log_password"),
                'log_url' => $this->input->post("log_url")
            );
            $sukses = $this->db->insert("log", $data);
        }
        if ($sukses) {
            $pesan = "Berhasil menyimpan data";
        } else {
            $pesan = "Gagal menyimpan data";
        }
        $hasil = array(
            "responseText" => $pesan,
            "success" => $sukses
        );
        echo json_encode($hasil);
        exit;
    }

    function delete() {
        $id = explode(";", $this->input->post('id'));
        $sukses = false;
        foreach ($id as $val) {
            if (empty($val))
                continue;
            $sukses = $this->db->where("log_id", $val)->delete("log");
        }

        if ($sukses) {
            $pesan = "Berhasil menghapus data";
        } else {
            $pesan = "Gagal menghapus data";
        }
        $hasil = array(
            "responseText" => $pesan,
            "success" => $sukses
        );
        echo json_encode($hasil);
        exit;
    }

}
