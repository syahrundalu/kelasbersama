<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cbt_tes_soal_jawaban_model extends CI_Model{
	public $table = 'cbt_tes_soal_jawaban';
	
	function __construct(){
        parent::__construct();
    }
	
    function save($data){
        $this->db->insert($this->table, $data);
    }

    function save_batch($data){
        //$this->db->query($sql);
        $this->db->insert_batch($this->table, $data);
    }
    
    function delete($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->delete($this->table);
    }
    
    function update($kolom, $isi, $data){
        $this->db->where($kolom, $isi)
                 ->update($this->table, $data);
    }

    function update_by_tessoal_answer($tessoal_id, $jawaban_id, $data){
        $this->db->where('soaljawaban_tessoal_id="'.$tessoal_id.'" AND soaljawaban_jawaban_id="'.$jawaban_id.'"')
                 ->update($this->table, $data);
    }

    function update_by_tessoal_answer_salah($tessoal_id, $jawaban_id, $data){
        $this->db->where('soaljawaban_tessoal_id="'.$tessoal_id.'" AND soaljawaban_jawaban_id!="'.$jawaban_id.'"')
                 ->update($this->table, $data);
    }
    
	function count_by_kolom($kolom, $isi){
        $this->db->select('COUNT(*) AS hasil')
                 ->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
    
	function get_by_kolom($kolom, $isi){
        $this->db->where($kolom, $isi)
                 ->from($this->table);
        return $this->db->get();
    }
	
	function get_by_kolom_limit($kolom, $isi, $limit){
        $this->db->where($kolom, $isi)
                 ->from($this->table)
				 ->limit($limit);
        return $this->db->get();
    }

    function get_by_tessoal($tessoal_id){
        $this->db->where('soaljawaban_tessoal_id="'.$tessoal_id.'"')
                 ->from($this->table)
                 ->join('cbt_jawaban', 'cbt_tes_soal_jawaban.soaljawaban_jawaban_id = cbt_jawaban.jawaban_id')
                 ->order_by('soaljawaban_order', 'ASC');
        return $this->db->get();
    }

    function get_by_tessoal_answer($tessoal_id, $jawaban_id){
        $this->db->where('cbt_tes_soal_jawaban.soaljawaban_tessoal_id="'.$tessoal_id.'" AND cbt_tes_soal_jawaban.soaljawaban_jawaban_id="'.$jawaban_id.'"')
                 ->from($this->table)
                 ->join('cbt_jawaban', 'cbt_tes_soal_jawaban.soaljawaban_jawaban_id = cbt_jawaban.jawaban_id')
                 ->limit(1);
        return $this->db->get();
    }
	
	function get_datatable($start, $rows, $kolom, $isi){
		$this->db->where('('.$kolom.' LIKE "%'.$isi.'%")')
                 ->from($this->table)
				 ->order_by($kolom, 'ASC')
                 ->limit($rows, $start);
        return $this->db->get();
	}
    
    function get_datatable_count($kolom, $isi){
		$this->db->select('COUNT(*) AS hasil')
                 ->where('('.$kolom.' LIKE "%'.$isi.'%")')
                 ->from($this->table);
        return $this->db->get();
	}

   function get_score($tessoal_id){
    $this->db->select('SUM(cbt_jawaban.poin_jawaban) AS hasil')
             ->where('soaljawaban_tessoal_id="'.$tessoal_id.'" AND soaljawaban_selected=1')
             ->from($this->table)
             ->join('cbt_jawaban', 'cbt_tes_soal_jawaban.soaljawaban_jawaban_id = cbt_jawaban.jawaban_id')
             ->limit(1);
    return $this->db->get();
    }

    function get_nilai($tessoal_id){
        $sql = 'SELECT SUM(cbt_jawaban.poin_jawaban) AS hasil 
        FROM cbt_tes_soal_jawaban 
        JOIN cbt_jawaban ON cbt_tes_soal_jawaban.soaljawaban_jawaban_id = cbt_jawaban.jawaban_id
        JOIN cbt_tes_soal ON cbt_tes_soal_jawaban.soaljawaban_tessoal_id = cbt_tes_soal.tessoal_id   
        WHERE cbt_tes_soal_jawaban.soaljawaban_selected = 1 AND tessoal_tesuser_id="'.$tessoal_id.'"';
        return $this->db->query($sql);
    }


	

}