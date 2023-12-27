<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Matkul extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data matkul
    function index_get() {
        $Kode_Matkul = $this->get('Kode_Matkul');
        if ($Kode_Matkul  == '') {
            $manajemen_mahasiswa = $this->db->get('mata_kuliah')->result();
        } else {
            $this->db->where('Kode_Matkul',$Kode_Matkul );
            $manajemen_mahasiswa = $this->db->get('mata_kuliah')->result();
        }
        $this->response($manajemen_mahasiswa, 200);
    }

    //Mengirim atau menambah data matkul baru
	function index_post() {
        $data = array(
                'Kode_Matkul' => $this->post('Kode_Matkul'),
                'Nama_Matkul'    => $this->post('Nama_Matkul'),
                'SKS'    => $this->post('SKS'));
            $insert = $this->db->insert('mata_kuliah', $data);
                if ($insert) {
                    $this->response(array('status' => 'success', 'message' => 'Data mata kuliah berhasil ditambahkan','data' => $data), 200);
                } else {
                    $this->response(array('status' => 'fail', 'message' => 'Gagal menambahkan data mata kuliah'), 502);
                }
    }

    //Memperbarui data matkul yang telah ada
    function index_put() {
        $Kode_Matkul = $this->put('Kode_Matkul');
        $data = array(
            'Kode_Matkul'           => $this->put('Kode_Matkul'),
            'Nama_Matkul' => $this->put('Nama_Matkul'),
            'SKS' => $this->put('SKS'));
        $this->db->where('Kode_Matkul', $Kode_Matkul);
        $query = $this->db->get('mata_kuliah');
    
        if ($query->num_rows() > 0) {
            $this->db->where('Kode_Matkul', $Kode_Matkul);
            $update = $this->db->update('mata_kuliah', $data);
            if ($update) {
                $this->response(array('status' => 'success', 'message' => 'Data mata kuliah berhasil diperbarui','data' => $data), 200);
            } else {
                $this->response(array('status' => 'fail', 'message' => 'Gagal memperbarui data mata kuliah'), 502);
            }
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Data mahasiswa tidak ditemukan'), 404);
        }
    }

     //Menghapus salah satu data matkul
     function index_delete() {
        $Kode_Matkul = $this->delete('Kode_Matkul');
        if (empty($Kode_Matkul)) {
            $this->response(array('status' => 'fail', 'message' => 'Kode Matkul tidak diberikan'), 400);
            return;
        }
            $this->db->where('Kode_Matkul', $Kode_Matkul);
                $delete = $this->db->delete('mata_kuliah');
        if ($delete) {
            $this->response(array('status' => 'success', 'message' => 'Data mata kuliah berhasil dihapus'), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal menghapus data mata kuliah'), 502);
        }
    }
    
}
?>