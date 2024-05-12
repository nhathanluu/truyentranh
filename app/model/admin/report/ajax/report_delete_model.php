<?php
	class report_delete_model extends model{
		public function index($rp_id){
			
			$rs = $this->pg_query("DELETE FROM report WHERE r_id = {$rp_id}");

			if($rs){
				success();
			}
		}
	}
?>