<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Mahasiswa extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //Menampilkan data mahasiswa
    function index_get() {
        $NPM= $this->get('NPM');
        if ($NPM == '') {
            $manajemen_mahasiswa = $this->db->get('mahasiswa')->result();
        } else {
            $this->db->where('NPM',$NPM);
            $manajemen_mahasiswa = $this->db->get('mahasiswa')->result();
        }
        $this->response($manajemen_mahasiswa, 200);
    }

    //Mengirim atau menambah data mahasiswa baru
    function index_post() {
        $data = array(
            'NPM'           => $this->post('NPM'),
            'Nama'          => $this->post('Nama'),
            'Alamat'        => $this->post('Alamat'),
            'Email'         => $this->post('Email'),
            'Foto'          => $this->post('Foto'),
            'Fakultas'      => $this->post('Fakultas'),
            'Jurusan'      => $this->post('Jurusan'),
            'Tanggal_Lahir' => $this->post('Tanggal_Lahir'),
            'Jenis_Kelamin' => $this->post('Jenis_Kelamin'));
        $insert = $this->db->insert('mahasiswa', $data);
        if ($insert) {
            $this->response(array('status' => 'success', 'message' => 'Data mahasiswa berhasil ditambahkan','data' => $data), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal menambahkan data mahasiswa'), 502);
        }
    }

    //Memperbarui data mahasiswa yang telah ada
    function index_put() {
        $NPM = $this->put('NPM');
        $data = array(
            'NPM'           => $this->put('NPM'),
            'Nama'          => $this->put('Nama'),
            'Alamat'        => $this->put('Alamat'),
            'Email'         => $this->put('Email'),
            'Foto'          => $this->put('Foto'),
            'Fakultas'      => $this->put('Fakultas'),
            'Jurusan'      => $this->put('Jurusan'),
            'Tanggal_Lahir' => $this->put('Tanggal_Lahir'),
            'Jenis_Kelamin' => $this->put('Jenis_Kelamin'));
        $this->db->where('NPM', $NPM);
        $query = $this->db->get('mahasiswa');
    
        if ($query->num_rows() > 0) {
            $this->db->where('NPM', $NPM);
            $update = $this->db->update('mahasiswa', $data);
            if ($update) {
                $this->response(array('status' => 'success', 'message' => 'Data mahasiswa berhasil diperbarui','data' => $data), 200);
            } else {
                $this->response(array('status' => 'fail', 'message' => 'Gagal memperbarui data mahasiswa'), 502);
            }
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Data mahasiswa tidak ditemukan'), 404);
        }
    }
    
    //Menghapus salah satu data mahasiswa
    function index_delete() {
        $NPM = $this->delete('NPM');
        if (empty($NPM)) {
            $this->response(array('status' => 'fail', 'message' => 'NPM tidak diberikan'), 400);
            return;
        }
            $this->db->where('NPM ', $NPM );
                $delete = $this->db->delete('mahasiswa');
        if ($delete) {
            $this->response(array('status' => 'success', 'message' => 'Data mata kuliah berhasil dihapus'), 200);
        } else {
            $this->response(array('status' => 'fail', 'message' => 'Gagal menghapus data mata kuliah'), 502);
        }
    }
}
?>
