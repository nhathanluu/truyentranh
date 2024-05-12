<?php
	class dashboard_model extends model{

		public function index(){

			$this->pg_connect();

			$manga = $this->pg_select(
				"count(*)",
				"manga"
			)[0][0];

			$chapters = $this->pg_select(
				"count(*)",
				"chapters"
			)[0][0];

			$users = $this->pg_select(
				"count(*)",
				"\"user\""
			)[0][0];

			$reports = $this->pg_select(
				"count(*)",
				"\"report\""
			)[0][0];

			return [
				"manga"    => $manga,
				"chapters" => $chapters,
				"users"    => $users,
				"reports"  => $reports
			];
		}
	}
?>