var route = [
    {
        path: "\/admin\/manga\/list",
        module : "_manga_list"
    },
    {
        path: "\/admin\/manga\/[0-9]+",
        module : "_manga_detail"
    },
    {
        path: "\/admin\/manga\/chapters\/[0-9]+",
        module : "_manga_chapters"
    },
    {
        path: "\/admin\/chapter\/detail\/[0-9]+",
        module : "_chapter_detail"
    },
    {
        path: "\/admin\/chapter\/add\/[0-9]+",
        module : "_add_chapter"
    },
    {
        path: "\/admin\/manga\/add",
        module : "_add_manga"
    },
    {
        path: "\/admin\/manga\/pin",
        module : "_pin_manga"
    },
    {
        path: "\/admin\/member\/list",
        module : "_member_list"
    },
    {
        path: "\/admin\/member\/comments\/([0-9]+)",
        module : "_member_comments"
    },
    {
        path: "\/admin\/tag\/list",
        module : "_tag_list"
    },
    {
        path: "\/admin\/tag\/detail/[0-9]+",
        module : "_tag_detail"
    },
    {
        path: "\/admin\/report\/list",
        module : "_report_list"
    },
    {
        path: "\/admin\/crawl",
        module : "_crawl"
    },
    {
        path: "\/admin\/image",
        module : "_image"
    },
    {
        path: "\/admin\/others",
        module : "_others"
    }
];

function cleanupText(text) {
  return text && text
    .trim()
    .replace(/\n{2,}/g, '\n\n')
    .replace(/ +/g, ' ');
}

var Kikyo = new Waifu_Kikyo(route,false);

function _manga_list(){

    var o = {};

    function script(){

        var manga_list = document.querySelector(".manga-list"),
            cache = manga_list.innerHTML;

        var typingTimer,
            doneTypingInterval = 500;

        document.querySelector(".search-box > input").addEventListener("input",function(){

            clearTimeout(typingTimer);

            var _this = this;
            
            if (_this.value.length > 2){

                typingTimer = setTimeout(function(){             

                    ajax({
                        url  : "/search?query=" + _this.value,
                        type : "get",
                        dataType :"json",
                        success : function (rs){

                            if (rs.manga.length > 0){

                                var html = '';

                                forEach(rs.manga,function(e){

                                    var manga_id = e.manga_id; 
                                        href = '/admin/manga/' + manga_id;


                                    html += `<div class="m-item">
                                        <div class="cover-img"><a href="` + href + `" title="` +  e.manga_name + `"><img src="` + e.cover_img + `" alt="` + e.manga_name + `"></a></div>
                                        <div class="m-chaptes clearfix">
                                           <div class="m-list-chapters">
                                                <a href="/admin/manga/chapters/` + e.manga_id + `" title="Chapters">
                                                    <i class="fa fa-list" aria-hidden="true"></i> Chapters
                                                </a>
                                            </div>
                                            <div class="m-manga-link">
                                                <a href="` + e.url + `" title="link to manga" target="_blank">
                                                    <i class="fa fa-external-link" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="manga-name"><a href="` + href + `" title="` + e.manga_name + `">` + e.manga_name + `</a> </div>
                                    </div>`

                                });

                                manga_list.innerHTML = html;
                            }
                        } 
                    });

                }, doneTypingInterval);

            }else{

                manga_list.innerHTML = cache;
            }
            
        });

        new Devilchan(document.querySelectorAll(".select-box select")).addEventListener("change",function(){

            if(this.value == null){
                return;
            }

            window.location.href = this.value;
        })
    }

    o.script = script;

    return o;
}

function _manga_detail(){

    var o = {};

    function chang_cover_img(){

        document.getElementById("cover-img").addEventListener("change",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var _this = this,
                file = this.files[0];

            ajax({
                "url" : "/ajax/manga/change-cover-img/" + window.manga_id,
                type:"post",
                dataType:"json",
                data:{
                    file : file
                },
                success : function(rs){

                    removeElement(processing);


                    var html_file = dom.createStr('<input type="file" name="file" id="cover-img">')[0];

                    replaceElement(html_file,_this);
                    chang_cover_img();

                    if(rs.cover_img){
                        document.querySelector(".cover-img > img").src = rs.cover_img;
                    }
                    
                    if(rs.message){
                        alert(rs.message);
                    }
                }
            })
        });
    }

    function script(){

        var edit_btn = document.querySelectorAll(".edit-btn");

        new Devilchan(edit_btn).addEventListener("click",function(e){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var manga_name = document.getElementById("manga-name").value,
                manga_description = tinyMCE.get("manga-description").getContent(),
                status = document.getElementById("status").value,
                others_name = document.getElementById("manga-others-name").value;

            ajax({
                "url" : "/ajax/manga/update/" + window.manga_id,
                type:"post",
                dataType :"json",
                data :{
                    manga_name : manga_name,
                    manga_description : manga_description,
                    status : status,
                    others_name : others_name
                },
                success : function(rs){

                    removeElement(processing);
                    alert(rs.message);
                }
            });
        });

        var t1 = new tags_input(document.getElementById("manga_tags"),{

            confirmRemove: function(n){
                return "Xóa thể loại " + n.textContent + " ?";
            },
            onRemove : function(n){
                var tag_name = n.textContent;
                ajax({
                    "url" : "/ajax/manga/remove-tag/" + window.manga_id,
                    type:"post",
                    data:{
                        tag_name : tag_name
                    },
                    success : function(rs){

                    }
                })
            },
            onCreate : function(n,tag){
                var tag_name = n.textContent;

                ajax({
                    "url" : "/ajax/manga/add-tag/" + window.manga_id,
                    type:"post",
                    dataType:"json",
                    data:{
                        tag_name : tag_name
                    },
                    success : function(rs){
                        if (rs.error){

                            removeElement(n);
                            tag.real_tags_input.value = tag.get_tags();
                            alert(rs.message);
                        }
                    }
                })
            }
        });

        autocomplete({
            class : "ac",
            selector: t1.type_input,
            parent :t1.type_input.closest(".manga-tags"),
            source: function(request,response){

                if(request.length < 2){
                    return;
                }

                ajax({
                    url : "/search/tag?query=" + request,
                    type:"get",
                    dataType:"json",
                    success : function(rs){

                        var arr = [];

                        rs.forEach(function(e){
                            arr.push(e.tag_name);
                        });

                        response(arr);
                    }
                })
            },
            event :{
                down: function(e){
                    t1.type_input.value = e.innerText
                },
                up : function(e){
                    t1.type_input.value = e.innerText
                }
            },
            onSelect:function(e,v){
                console.log(e);
            }
        })

        new tags_input(document.getElementById("manga_authors"),{

            confirmRemove: function(n){
                return "Xóa tác giả " + n.textContent + " ?";
            },
            onRemove : function(n){
                var author_name = n.textContent;
                ajax({
                    "url" : "/ajax/manga/remove-author/" + window.manga_id,
                    type:"post",
                    data:{
                        author_name : author_name
                    },
                    success : function(rs){

                    }
                })
            },
            onCreate : function(n,th){
                var author_name = n.textContent;

                ajax({
                    "url" : "/ajax/manga/add-author/" + window.manga_id,
                    type:"post",
                    dataType:"json",
                    data:{
                        author_name : author_name
                    },
                    success : function(rs){

                        removeElement(processing);

                        if (rs.error){

                            th.real_tags_input.value = th.get_tags();

                            alert(rs.message);
                        }
                    }
                })
            }
        });

        var flag = false;

        document.getElementById("drop-manga").addEventListener("click",function(){


            if(flag == true){
                return;
            }

            if(confirm("Xóa truyện này ?")){

                flag = true;
                
                var processing = ajax_processing();

                ajax({
                    "url" : "/ajax/manga/drop-manga/" + window.manga_id,
                    type:"get",
                    dataType :"json",
                    success : function(rs){

                        removeElement(processing);

                        if(rs.error == 0){
                            window.location.href = "/admin/manga/list";
                        }

                        flag = false;
                    }
                });

            }
        })

        chang_cover_img();

        tinymce.init({
            forced_root_block: !1,
            force_br_newlines: !0,
            force_p_newlines: !1,
            entity_encoding: "raw",
            selector: "#manga-description",
            menubar: !1,
            statusbar: !1,
            plugins: ["autoresize", "emotrollface", "paste"],
            paste_as_text: !0,
            toolbar: "emotrollface",
            height: 100,
            autoresize_min_height: 100,
            autoresize_max_height: 200,
            autoresize_bottom_margin: 0,
            content_css : '/public/css/reset.css'  
        });
    }

    o.script = script;

    return o;
}

function _manga_chapters(){

    var o = {};

    o.script = script;

    function script(){

        new Devilchan(document.getElementsByClassName("delete-chapter")).addEventListener("click",function(){

            var chapter_id = this.getAttribute("data-delete-id");

            var _this = this;

            if(confirm("Xóa " + this.closest(".chapter-item").querySelector(".chapter-title").innerText +  "?")){

                var processing = ajax_processing();

                if(!processing){
                    return;
                }

                ajax({
                    url :"/ajax/chapter/delete/" + chapter_id,
                    type:"get",
                    dataType:"json",
                    success: function(rs){
                        removeElement(processing);

                        if(rs.error == 1){

                            alert(rs.message);

                        }else{

                            removeElement(_this.closest(".chapter-item"));
                        }
                    }
                });
            }
        });

        document.getElementById("update-all-chapters").addEventListener("click",function(){


            if(confirm("Update all chapter?")){

                var chapters = document.getElementById("content-chapters").value;

                ajax({
                    url : "/ajax/manga/update-all-chapters/" + window.manga_id,
                    type :"post",
                    dataType : "json",
                    data:{
                        chapters : chapters
                    },
                    success : function(){
                        
                    }
                })

            }
        });
    }

    return o;
}

function _chapter_detail(){

    var o = {};

    o.script = script;

    function script(){

        document.getElementById("update-chapter").addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var chapter_title = document.getElementById("chapter-title").value,
                chapter_number = document.getElementById("chapter-number").value,
                chapter_content = document.getElementById("chapter-content").value;

            var chapter_id = this.getAttribute("data-chapter-id");

            ajax({
                url : "/ajax/chapter/update/" + chapter_id,
                type :"post",
                dataType : "json",
                data :{
                    chapter_title : chapter_title,
                    chapter_number : chapter_number,
                    chapter_content : chapter_content
                },
                success : function(rs){

                    removeElement(processing);
                    
                    if(rs.message){
                        alert(rs.message)
                    }
                }
            })
        });
    }

    return o;
}

function _add_manga(){

    var o = {};

    o.script = script;

    function script(){

        new tags_input(document.getElementById("manga_tags"));
        new tags_input(document.getElementById("manga_authors"));

        tinymce.init({
            forced_root_block: !1,
            force_br_newlines: !0,
            force_p_newlines: !1,
            entity_encoding: "raw",
            selector: "#manga-description",
            menubar: !1,
            statusbar: !1,
            plugins: ["autoresize", "emotrollface", "paste"],
            paste_as_text: !0,
            toolbar: "emotrollface",
            height: 100,
            autoresize_min_height: 100,
            autoresize_max_height: 200,
            autoresize_bottom_margin: 0,
            content_css : '/public/css/reset.css'
        });

        var edit_btn = document.querySelectorAll(".edit-btn")

        new Devilchan(edit_btn).addEventListener("click",function(e){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var manga_name = document.getElementById("manga-name").value,
                manga_description = tinyMCE.get("manga-description").getContent(),
                status = document.getElementById("status").value,
                cover_img = document.querySelector(".cover-img > img").getAttribute("img-path"),
                manga_tags = document.getElementById("manga_tags").value,
                manga_authors = document.getElementById("manga_authors").value,
                others_name = document.getElementById("manga-others-name").value;

            ajax({
                url : "/ajax/manga/add-manga",
                type:"post",
                dataType :"json",
                data :{
                    manga_name : manga_name,
                    manga_description : manga_description,
                    status : status,
                    cover_img : cover_img,
                    manga_tags : manga_tags,
                    manga_authors : manga_authors,
                    others_name : others_name
                },
                success : function(rs){

                    removeElement(processing);

                    alert(rs.message);

                    if(rs.manga_id){
                        document.location.href = "/admin/manga/" + rs.manga_id;
                    }
                }
            });
        });

        document.getElementById("cover-img").addEventListener("change",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var _this = this,
                file = this.files[0];

            ajax({
                url : "/ajax/manga/upload-img",
                type:"post",
                dataType:"json",
                data:{
                    file : file
                },
                success : function(rs){

                    removeElement(processing);

                    if(rs.error == 0){

                        var dom_cover_img = document.querySelector(".cover-img img");

                        dom_cover_img.src = "/tmp/" + rs.tmp;
                        dom_cover_img.setAttribute("img-path",rs.tmp); 

                    }else{

                        alert(rs.message);
                    }
                }
            })
        });
    }

    return o;
}

function _add_chapter(){
    var o = {};

    o.script = script;

    function script(){

        document.getElementById("add-chapter").addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var manga_id = this.getAttribute("data-manga-id");

            var chapter_title = document.getElementById("chapter-title").value,
                chapter_number = document.getElementById("chapter-number").value,
                chapter_content = document.getElementById("chapter-content").value;

            ajax({
                url : "/ajax/chapter/add/" + manga_id,
                type:"post",
                dataType :"json",
                data :{
                    chapter_title : chapter_title,
                    chapter_number : chapter_number,
                    chapter_content : chapter_content
                },
                success : function(rs){

                    removeElement(processing);

                    alert(rs.message);

                    if(rs.chapter_id){
                        window.location.href = "/admin/chapter/detail/" + rs.chapter_id
                    }
                }
            });
        });
    }

    return o;
}

function _pin_manga(){
    var o = {};

    o.script = script;

    function script(){

        document.getElementById("pin-manga").addEventListener("click",function(){

            var processing =  ajax_processing();

            if(!processing){
                return;
            }

            var manga_id = document.getElementById("manga-id").value;

            ajax({
                url:"/ajax/manga/pin/" + manga_id,
                type:"get",
                dataType:"json",
                success: function(rs){
                    
                    removeElement(processing);

                    if(rs.message){
                        alert(rs.message);
                    }

                    document.getElementById("manga-id").value = "";

                    if(rs.error == 0){

                        var e = rs.manga,
                            manga_id = e.manga_id; 
                            href = '/admin/manga/' + manga_id;

                        var html = dom.createStr( 
                            `<div class="m-item">
                                <div class="unpin"><a href="javascript:void(0)" data-manga-id="` + e.manga_id + `"><img alt="Unpin" src="/public/icons/x.svg"></a></div>
                                <div class="cover-img"><a href="` + href + `" title="` +  e.manga_name + `"><img src="` + e.cover_img + `" alt="` + e.manga_name + `"></a></div>
                                <div class="m-chaptes clearfix">
                                   <div class="m-list-chapters">
                                        <a href="/admin/manga/chapters/` + e.manga_id + `" title="Chapters">
                                            <img src="/public/icons/list.svg" alt="chapters">Chapters
                                        </a>
                                    </div>
                                    <div class="m-manga-link">
                                        <a href="` + e.url + `" title="link to manga" target="_blank">
                                            <i class="fa fa-external-link" aria-hidden="true"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="manga-name"><a href="` + href + `" title="` + e.manga_name + `">` + e.manga_name + `</a> </div>
                            </div>`)[0];

                        var manga_list = document.getElementsByClassName("manga-list")[0];

                        manga_list.insertBefore(html, manga_list.firstChild);
                    }
                }
            })
        });

        on(".manga-list","click",".unpin a",function(){

            var processing =  ajax_processing();

            if(!processing){
                return;
            }

            var _this = this,
                manga_id = this.getAttribute("data-manga-id");

             ajax({
                url:"/ajax/manga/unpin/" + manga_id,
                type:"get",
                dataType:"json",
                success: function(rs){
                    
                    removeElement(processing);

                    if(rs.error == 0){
                        removeElement(_this.closest(".m-item"));
                    }
                }
            })
        });
    }

    return o;
}

function _member_list(){

    var o = {};

    o.script = script;

    var fn = {};

    fn["ban-account"]   = ban_account;
    fn["unban-account"] = unban_account;

    function script(){

        on(".member-list","click",".a-u-btn",function(){

            var fn_name = this.id,
                user_id = this.closest(".a-member-item").getAttribute("data-user-id");

            if(fn_name == "ban-account" || fn_name == "unban-account"){

                this.processing = ajax_processing();

                if(!this.processing){
                    return;
                }
            }

            fn[fn_name].call(this,user_id);

        });

        var typingTimer,
            doneTypingInterval = 700;

        var save = document.querySelector(".member-list").innerHTML;

        document.querySelector(".search-box > input").addEventListener("input",function(){

            clearTimeout(typingTimer);

            var query = cleanupText(this.value);

            if(query.length > 2){

                typingTimer = setTimeout(function(){

                    ajax({
                        url : "/ajax/member/search?query=" + query,
                        type:"get",
                        dataType :"json",
                        success : function(rs){

                            var html = "";

                            forEach(rs,function(u){

                                var avatar = u.avatar != null ?  u.avatar : "/public/icons/default.jpg",
                                    html_ban = "";
                                    
                                if(u.level == 3){

                                    html_ban = 
                                        `<a href="javascript:void(0)" id="unban-account" class="a-u-btn bgc3-v3" title="Mở khóa tài khoản">
                                            <img src="/public/icons/open_lock.svg" alt="Ban account">Mở khóa tài khoản
                                        </a>`;

                                }else{

                                    html_ban = 
                                    `<a href="javascript:void(0)" id="ban-account" class="a-u-btn bgr" title="Khóa tài khoản">
                                            <img src="/public/icons/ban.svg" alt="Khóa tài khoản">Khóa tài khoản
                                        </a>`;
                                }



                                html += 
                                `<div class="a-member-item clearfix" data-user-id="` + u.user_id + `">
                                    <div class="img-holder"><img src="` + avatar + `"></div>
                                    <div class="a-user">
                                        <div class="a-user-name"><a href="#"><span class="a-u-n">` + u.account +`</span> - <span class="nickname">( ` + u.nickname + ` )</span> - USER ID : <span class="user-id">`+ u.user_id +`</span></a></div>
                                        <div class="a-user-btn">
                                            <a href="/admin/member/comments/` + u.user_id + `" title="Bình luận">
                                                  <img src="/public/icons/cmt.svg" alt="Bình luận">Bình luận
                                            </a>
                                            ` + html_ban + `
                                        </div>
                                    </div>
                                </div>`;
                            });

                            document.querySelector(".member-list").innerHTML = html;
                        }
                    });

                }, doneTypingInterval);
            }else{

                document.querySelector(".member-list").innerHTML = save;
            }
        });

        new Devilchan(document.querySelectorAll(".select-box select")).addEventListener("change",function(){

            if(this.value == null){
                return;
            }

            window.location.href = this.value;
        })
    }

    function ban_account(user_id){

        var _this = this;

        ajax({
            url : "/ajax/member/ban/" + user_id,
            type:"get",
            dataType :"json",
            success : function(rs){

                removeElement(_this.processing);

                if(rs.error){
                    alert(rs.message);
                    return;
                }

                var dom = window.dom.createStr(`<a href="javascript:void(0)" id="unban-account" class="a-u-btn bgc3-v3" title="Mở khóa tài khoản ">
                        <img src="/public/icons/open_lock.svg" alt="Mở khóa tài khoản ">Mở khóa tài khoản 
                    </a>`)[0];

                replaceElement(dom,_this);
            }
        });
    }

    function unban_account(user_id){

        var _this = this;
        
        ajax({
            url : "/ajax/member/unban/" + user_id,
            type:"get",
            dataType :"json",
            success : function(rs){

                removeElement(_this.processing);

                if(rs.error){
                    alert(rs.message);
                    return;
                }

                var dom = window.dom.createStr(`<a href="javascript:void(0)" id="ban-account" class="a-u-btn bgr" title="Khóa tài khoản">
                    <img src="/public/icons/ban.svg" alt="Khóa tài khoản">Khóa tài khoản 
                </a>`)[0];

                replaceElement(dom,_this);
            }
        });
    }

    return o;
}

function _member_comments(){

    var o = {};

    o.script = script;

    function script(){

        var dom_cmts = document.querySelectorAll(".delete-cmt"); 

        new Devilchan(dom_cmts).addEventListener("click",function(){

            var cmt_id = this.getAttribute("data-cmt-id");

            if(confirm("Xóa bình luận này")){

                var processing =  ajax_processing();

                if(!processing){
                    return;
                }

                var _this = this;

                ajax({
                    url : "/ajax/member/delete-cmt/" + cmt_id,
                    type:"get",
                    dataType :"json",
                    success : function(rs){

                        removeElement(processing);

                        removeElement(_this.closest(".u-cmt-item"));
                    }
                });
            }
        });
    }

    return o;
}

function _tag_list(){

    var o = {};

    o.script = script;

    function script(){

        var dom_cmts = document.querySelectorAll(".delete-tag"); 

        new Devilchan(dom_cmts).addEventListener("click",function(){

            var cmt_id = this.getAttribute("data-tag-id"),
                tag_name = this.getAttribute("data-tag-name");

            if(confirm("Xóa thể loại " + tag_name + " ?")){

                var processing =  ajax_processing();

                if(!processing){
                    return;
                }

                var _this = this;

                ajax({
                    url : "/ajax/tag/delete-tag/" + cmt_id,
                    type:"get",
                    dataType :"json",
                    success : function(rs){

                        removeElement(processing);

                        removeElement(_this.closest("li"));
                    }
                });
            }
        });

        document.querySelector(".select-box select").addEventListener("change",function(){

            var value = this.value,
                url = window.location.href.split(/[?#]/)[0];

            window.location.href = url + "?sort=" + value;
        })
    }

    return o;
}

function _tag_detail(){

    var o = {};

    o.script = script;

    function script(){

        document.getElementById("update-description").addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var tag_id = this.getAttribute("data-tag-id"),
                description = document.getElementById("tag-description").value;

            ajax({
                url : "/ajax/tag/update-tag/" + tag_id,
                type:"post",
                dataType :"json",
                data :{
                    description : description
                },
                success : function(rs){

                    removeElement(processing);

                    if(rs.message){
                        alert(rs.message);
                    }
                }
            });
        });
    }

    return o;
}

function _report_list(){

    var o = {};

    o.script = script;

    function script(){

        new Devilchan(document.getElementsByClassName("del-rp")).addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var r_id = this.getAttribute("data-r-id"),
                _this = this;

            ajax({
                url :"/ajax/report/delete/" + r_id,
                type :"post",
                dataType : "json",
                success : function(rs){

                    removeElement(processing);

                    if(rs.error == 0){

                        removeElement(_this.closest(".rp-item"));

                    }else{

                        alert("Error!");
                    }
                }
            })
        });
    }

    return o;
}

function _crawl(){

    var o = {};

    o.script = script;

    function script(){

        var fn = {};

        fn["send"] = function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var url = document.getElementById("url").value;

            ajax({
                url:"/ajax/crawl/add-manga",
                type :"post",
                dataType :"json",
                data : {
                    url : url
                },
                success : function(rs){
                    removeElement(processing);

                    if(rs.message){
                        alert(rs.message)
                    }
                }
            });
        }

        var flag = false;

        fn["stop"] = function(){

            if(flag == true){
                return;
            }

            if (!confirm("Stop crawling?")){
                return;
            }

            var processing = ajax_processing();

            flag = true;

            ajax({
                url:"/ajax/crawl/stop",
                type :"get",
                dataType :"json",
                success : function(rs){

                    removeElement(processing);

                    if(rs.message){
                        alert(rs.message);
                    }

                    flag = false;
                }
            });
        }

        new Devilchan(document.getElementsByClassName("edit-btn")).addEventListener("click",function(){

            fn[this.id].call(this);
        });
    }

    return o;
}

function _image(){

    var o = {};

    o.script = script;

    function script(){

        document.getElementById("send").addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var url = document.getElementById("url").value;

            ajax({
                url:"/ajax/image/upload",
                type :"post",
                dataType :"json",
                data:{
                    url : url
                },
                success : function(rs){

                    removeElement(processing);

                    if(rs.message){
                        alert(rs.message);
                    }
                }
            });
        });
        
        new Devilchan(document.getElementsByClassName("delete")).addEventListener("click",function(){
            
            var id = this.getAttribute("data-id");

            ajax({
                url:"/ajax/image/delete",
                type :"post",
                dataType :"json",
                data:{
                    id : id
                },
                success : function(rs){

                    
                }
            });

            removeElement(this.closest(".d-img-holder"));
        });
    }

    return o;
}

function _others(){

    var o = {};

    o.script = script;

    function _create(n,tag){

        var type = tag.real_tags_input.getAttribute("data-cate");

        ajax({
            url : "/ajax/set-tag",
            type :"post",
            data :{
                type : type,
                tag : n.textContent
            },
            dataType :"json",
            success : function(rs){

                if(rs.error){
                    removeElement(n);
                }

                if(rs.message){
                    alert(rs.message);
                }
            }
        })
    }

    function _remove(n,tag){

        var type = tag.real_tags_input.getAttribute("data-cate");

        ajax({
            url : "/ajax/del-tag",
            type :"post",
            data :{
                type : type,
                tag : n.textContent
            },
            dataType :"json",
            success : function(rs){

                if(rs.error){
                    removeElement(n);
                }

                if(rs.message){
                    alert(rs.message);
                }
            }
        })
    }

    function script(){

        var t1 = new tags_input(document.getElementById("con-trai"),{

            confirmRemove: function(n){
                return "Xóa thể loại " + n.textContent + " ?";
            },
            onRemove : _remove,
            onCreate : _create
        });

        autocomplete({
            class : "ac",
            selector: t1.type_input,
            parent :t1.type_input.closest(".manga-tags"),
            source: function(request,response){

                if(request.length < 2){
                    return;
                }

                ajax({
                    url : "/search/tag?query=" + request,
                    type:"get",
                    dataType:"json",
                    success : function(rs){

                        var arr = [];

                        rs.forEach(function(e){
                            arr.push(e.tag_name);
                        });

                        response(arr);
                    }
                })
            },
            event :{
                down: function(e){
                    t1.type_input.value = e.innerText
                },
                up : function(e){
                    t1.type_input.value = e.innerText
                }
            },
            onSelect:function(e,v){
                
            }
        })

        var t2 = new tags_input(document.getElementById("con-gai"),{

            confirmRemove: function(n){
                return "Xóa thể loại " + n.textContent + " ?";
            },
            onRemove : function(n){
                
            },
            onRemove : _remove,
            onCreate : _create
        });

        autocomplete({
            class : "ac",
            selector: t2.type_input,
            parent :t2.type_input.closest(".manga-tags"),
            source: function(request,response){

                if(request.length < 2){
                    return;
                }

                ajax({
                    url : "/search/tag?query=" + request,
                    type:"get",
                    dataType:"json",
                    success : function(rs){

                        var arr = [];

                        rs.forEach(function(e){
                            arr.push(e.tag_name);
                        });

                        response(arr);
                    }
                })
            },
            event :{
                down: function(e){
                    t2.type_input.value = e.innerText
                },
                up : function(e){
                    t2.type_input.value = e.innerText
                }
            },
            onSelect:function(e,v){
               
            }
        });

        document.getElementById("clean-cache").addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            ajax({
                url:"/ajax/clean-cache",
                type :"get",
                dataType :"json",
                success : function(rs){
                    
                    removeElement(processing);
                }
            });
        });
    }

    return o;
}