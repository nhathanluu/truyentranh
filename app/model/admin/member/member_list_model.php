<?php
	class member_list_model extends model{

		public function index(){

			$this->pg_connect();

			$where = "";

			$date = @$_GET["date"];

			if($date == "today"){

				$where = "WHERE create_date BETWEEN NOW() - INTERVAL '24 HOURS' AND NOW()";

			}

			$count = @$this->pg_select(
				"count(*)",
				"\"user\"
					{$where}"
			)[0][0];

			$limit = 20;
			$pagi  = get_pagi($count,$limit,$offset,$page);

			$users = $this->pg_select_obj(
				"user_id,account,nickname,level,avatar",
				"\"user\"
					{$where}
					ORDER BY 
						user_id DESC
					LIMIT 
						{$limit} 
					OFFSET 
						{$offset}"
			);

			return [
				"users" => $users,
				"pagi"  => $pagi,
				"page"  => $page
			];
		}
	}
?>