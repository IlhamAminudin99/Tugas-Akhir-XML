<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Nilai extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data nilai
    function index_get() {
        $ID_Nilai = $this->get('ID_Nilai');
        if ($ID_Nilai  == '') {
            $manajemen_mahasiswa = $this->db->get('nilai')->result();
        } else {
            $this->db->where('ID_Nilai',$ID_Nilai );
            $manajemen_mahasiswa = $this->db->get('nilai')->result();
        }
        $this->response($manajemen_mahasiswa, 200);
    }

    //Mengirim atau menambah data nilai
	function index_post() {
        $data = array(
            'ID' => $this->post('ID'),
            'ID_Matkul' => $this->post('ID_Matkul'),
            'Nilai' => $this->post('Nilai')
        );
    
        // Memasukkan data ke dalam tabel nilai
        $insert = $this->db->insert('nilai', $data);
    
        if ($insert) {
            // Lakukan JOIN antara tabel nilai, mahasiswa, dan mata_kuliah
            $this->db->select('mahasiswa.Nama AS Nama_Mahasiswa, mata_kuliah.Nama_Matkul AS Nama_Matkul, nilai.nilai AS Nilai');
            $this->db->from('nilai');
            $this->db->join('mahasiswa', 'mahasiswa.ID = nilai.ID');
            $this->db->join('mata_kuliah', 'mata_kuliah.ID_Matkul = nilai.ID_Matkul');
    
            // Menjalankan query JOIN
            $query = $this->db->get();
            $result = $query->result_array();
    
            $this->response(array('status' => 'success', 'message' => 'Data nilai berhasil ditambahkan', 'related_data' => $result), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal menambahkan data nilai'), 502);
        }
    }
    

    //Memperbarui data nilai
	function index_put() {
        $ID_Nilai = $this->put('ID_Nilai');
        $data = array(
            'ID' => $this->put('ID'),
            'ID_Matkul' => $this->put('ID_Matkul'),
            'Nilai' => $this->put('Nilai')
        );
    
        // Memperbarui data di tabel nilai berdasarkan ID_Nilai
        $this->db->where('ID_Nilai', $ID_Nilai);
        $update = $this->db->update('nilai', $data);
    
        if ($update) {
            // Jika berhasil memperbarui, ambil data terkait melalui JOIN
            $this->db->select('mahasiswa.Nama AS Nama_Mahasiswa, mata_kuliah.Nama_Matkul AS Nama_Matkul, nilai.Nilai AS Nilai');
            $this->db->from('nilai');
            $this->db->join('mahasiswa', 'mahasiswa.ID = nilai.ID');
            $this->db->join('mata_kuliah', 'mata_kuliah.ID_Matkul = nilai.ID_Matkul');
            $this->db->where('ID_Nilai', $ID_Nilai);
            $query = $this->db->get();
            $result = $query->result_array();
    
            $this->response(array('status' => 'success', 'message' => 'Data nilai berhasil diupdate', 'related_data' => $result), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal mengupdate data nilai'), 502);
        }
    }
    
    //Memperbarui data matkul yang telah ada
     function index_delete() {
        $ID_Nilai = $this->delete('ID_Nilai');
        if (empty($ID_Nilai)) {
            $this->response(array('status' => 'fail', 'message' => 'Kode Matkul tidak diberikan'), 400);
            return;
        }
            $this->db->where('ID_Nilai', $ID_Nilai);
                $delete = $this->db->delete('nilai');
        if ($delete) {
            $this->response(array('status' => 'success', 'message' => 'Data nilai berhasil dihapus'), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal menghapus data nilai'), 502);
        }
    }
}
?>