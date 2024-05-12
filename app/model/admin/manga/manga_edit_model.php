<?php
	class manga_edit_model extends model{

		public function index($manga_id){


			$manga_description = @$_POST["manga-description"];
			$manga_tags 	   = @$_POST["manga-tags"];
			$manga_name		   = @$_POST["manga-name"];

			$this->tags_handling($manga_tags);
		}

		private function tags_handling($manga_tags){

			$tags = explode(",",$manga_tags);

			foreach ($tags as $tag){
				
				if ($tag != ""){

					$tag = strtolower($tag);

				}
			}
		}
	}
?>