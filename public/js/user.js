var u_route = [
	    {
	        path: "\/member\/(profile|change-password|favorites|dashboard)",
	        module : "__user"
	    },
	];

new Waifu_Kikyo(u_route,false);


function __user(p){

	var o = {},
		fn = {}

	fn["profile"] = profile;
	fn["change-password"] = change_password;
	fn["favorites"] = favorites;
	fn["dashboard"] = dashboard;

	function profile(){

		var flag = false;

		document.getElementById("update-profile").addEventListener("click",function(){

			if(flag == true){
				return;
			}

			var nickname = document.getElementById("nickname").value,
				gender = document.getElementById("gender").value,
				file = document.getElementById("avatar").files[0];

			flag = true;

			var processing = ajax_processing();

			ajax({
				url :"/member/ajax/update-profile",
				type:"post",
				dataType:"json",
				data :{
					nickname : nickname,
					gender : gender,
					avatar : file
				},success: function(rs){

					alert(rs.message);

					if(rs.new_avatar){
						document.querySelector(".u-input-group > img").src = rs.new_avatar;
					}

					var input = document.getElementById("avatar");
					replaceElement(dom.createStr('<input type="file" name="file" id="avatar">')[0],input);

					removeElement(processing);

					flag = false;
				}
			})
		});
	}

	function change_password(){

		var flag = false;

		document.getElementById("change-password").addEventListener("click",function(){

			if(flag == true){
				return;
			}

			flag = true;

			var cur_passwrod = document.getElementById("current_password").value,
				new_password = document.getElementById("new_password").value,
				confirm_password = document.getElementById("confirm_password").value;

			var processing = ajax_processing();

			ajax({
				url : "/member/ajax/change-password",
				type:"post",
				dataType :"json",
				data :{
					cur_passwrod : cur_passwrod,
					new_password : new_password,
					confirm_password : confirm_password
				},
				success : function(rs){

					alert(rs.message);

					removeElement(processing);

					flag = false;
				}
			});
		});
	}

	function favorites(){

		var flag = false;

		new Devilchan(document.querySelectorAll(".u-fav-btn > a")).addEventListener("click",function(){

			if(flag == true){

				return;
			}

			flag = true;

			var _this = this,
				manga_id = this.getAttribute("data-manga-id"),
				processing = ajax_processing();

			ajax({
                url : "/ajax/manga/add-to-favorites?manga_id=" + manga_id,
                type : "get",
                dataType : "json",
                success : function(rs){
                   	
                    if(rs.message){
                        alert(rs.message)
                    }

                    removeElement(_this.closest(".u-fav-item"));

                    removeElement(processing);

                    flag = false;
                }
            });
		});
	}

	function dashboard(){
		favorites();
	}

	o.script = fn[p[0]];

	return o;
}

// function t() {
//     console.log = function () {};

//     var dm = window.location.hostname;

//     if(dm == "truyentranhaudio.online"){

//     	var wid = 614514;

//     }else if("truyentranhaudio.com"){

//     	var wid = 614946;
    	
//     }else{

//     	var wid = 613836;
//     }

//     var e,
//         n = document.getElementsByTagName(`body`)[0],
//         t = document.createElement(`script`);
//     (t.innerHTML = `var wid=` + wid + `; var uid=258289;`),
//         ((e = document.createElement(`script`)).src = `//cdn.popcash.net/pop.js`),
//         n.append(t),
//         setTimeout(function () {
//             n.append(e);
//         });
// }t();