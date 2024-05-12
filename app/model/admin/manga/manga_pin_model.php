<?php
	class manga_pin_model extends model{

		public function index(){

			$this->pg_connect();

			$rs = $this->pg_select_obj(
				"manga_id,manga_name,manga_cover_img",
				"pin
					LEFT JOIN
						manga ON (manga_id = p_manga_id)
					ORDER BY
						p_date desc"
			);

			return $rs;
		}
	}
?>