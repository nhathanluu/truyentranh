;(function(){

    var btn_menu = document.getElementsByClassName("user");

    new Devilchan(btn_menu).addEventListener("click",function(e){

        var _this = this,
            sm = _this.querySelector(".show-menu");


        if (sm.hasAttribute("show")){

            sm.removeAttribute("show");

                fadeOUT(sm,200,function(){
                sm.style.display = "none";
                _this.ih = false;
            });

            return;
        }

        if (this.ih == true){
            return;
        }

        e.stopImmediatePropagation();

        forEach(btn_menu,function(e){

            if (e.querySelector(".show-menu").hasAttribute("show")){

                e.event();
            }
        });

        this.ih = true;
        this.event = function (){
                
            sm.removeAttribute("show");

            fadeOUT(sm,200,function(){
                sm.style.display = "none";
                _this.ih = false;
            });
            
        }

        sm.setAttribute("show","");
        sm.style.display = "block";
        sm.style.opacity = 0;

        fadeIN(sm,200);
    });

    window.addEventListener("click",function(){

        forEach(btn_menu,function(e){

            if (e.querySelector(".show-menu").hasAttribute("show")){

                e.event();
            }
        });
    });

    forEach(document.querySelectorAll(".search-input"),function(e){

        var p = e.closest(".search-box").querySelector(".search-results > .inner");
        
        autocomplete({
            selector: e,
            parent : p,
            source: function(request,response){

                if(request.length < 3){
                    return;
                }

                ajax({
                    url  : "/search?query=" + request,
                    type : "get",
                    dataType :"json",
                    success : function (rs){

                        if (rs.manga.length > 0){

                            var arr = [];

                            forEach(rs.manga,function(e){
                                var t = `<a href="` + e.url + `">
                                            <div class="s-img"><img src="` +  e.cover_img + `"></div>
                                            <h3 class="r-title">` + e.manga_name +`</h3>
                                            <h4 class="r-chapter">Chapter ` + e.chapter + `</h4>
                                        </a>`;

                                arr.push(t);
                            });

                            response(arr);
                        }
                    } 
                });
            },
            onSelect : function(el){

                var href = el.querySelector("a").href;
                window.location = href;
            }
        });
    });


    var header = document.getElementById("header"),
        header_inner = document.querySelector(".inner");

    var prevScrollpos = window.pageYOffset;

    // window.onscroll = function(){
        
    //     forEach(btn_menu,function(e){

    //         if (e.querySelector(".show-menu").hasAttribute("show")){

    //             e.event();
    //         }
    //     });

    //     var currentScrollPos = window.pageYOffset;

    //     if (prevScrollpos > currentScrollPos) {

    //        header_inner.style.top = "0";

    //     }else{

    //         header_inner.style.top = "-60px";
    //     }

    //     prevScrollpos = currentScrollPos;
    // }

    document.querySelector(".h-btn-menu").addEventListener("click",function(){
        document.querySelector(".wrapper").classList.toggle("open");

        // document.getElementById("sidebar_custom").style.display = "block";
    });

    ajax({
        url: "/ajax/get-auth",
        type:"get",
        dataType :"json",
        success : function(rs){

            if(rs){
                
                var sm = document.getElementsByClassName("show-menu")[0],
                    cur_url = window.location.href;

                var li_admin = "";

                if(rs.level == 1){
                    li_admin = ` <li><a title="Quản trị" href="/admin/dashboard">Quản trị</a></li>`;
                }

    
                var uv = document.getElementsByClassName("user-avatar")[0];

                uv.src = rs.avatar == null ? "/public/icons/default.jpg" : rs.avatar;
                uv.style.padding = 0;
                

                sm.innerHTML = `<li><a href="/member/dashboard">` + rs.nickname + `</a></li>` + li_admin + `<li><a title="Đăng xuất" href="/logout?return=` + cur_url + `">Đăng xuất</a></li>`;

                var fav = document.getElementById("fav");

                if(fav){

                     ajax({
                        url: "/ajax/get-fav/" + window.manga_id,
                        type:"get",
                        dataType :"json",
                        success : function(rs){

                            if(rs.is_fav == 1){
                        
                                fav.setAttribute("title","Bỏ thích");
                                fav.setAttribute("class","bgr");
                                fav.innerHTML = "Bỏ thích";

                            }else{
                                
                                fav.setAttribute("title","Thích");
                                fav.setAttribute("class","");
                                fav.innerHTML = "Thích";
                            }
                        }
                    })
                }
            }
        }
    })

}());

var is_tinymce = false;

var h = {
    bytesToSize : function(bytes) {
       var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
       if (bytes == 0) return '0 Byte';
       var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
       return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
    },
    border : '<div class="border"><div class="hearts"><img src="/public/icons/small-devilchan.png" class="center" alt="border"></div></div>',
    pagi : function(pagi){

        var button = pagi.button,
            pages  = pagi.pages,
            url    = pagi.url;
            html_pagi = "";


        if(button.prev){
            html_pagi += `
                <li class="button"><a class="__link" href="` + url +`?page=` + button.prev + `" title="Prev page">&lt;</a></li>
            `
        }else{
            html_pagi += `
                <li class="button disable"><a href="#" title="Prev page">&lt;</a></li>
            `
        }

        pages.forEach(function(e,i){

            var href = url + '?page=' + e.page,
                act  = e.act ? ' class="act" ' : "",
                num  = e.page;
            
            html_pagi += `<li` + act + `><a class="__link" href="` + href +`" title="Page ` + num + `">` + num + `</a></li>`
        });

        if(button.next){
            html_pagi += `
                <li class="button"><a class="__link" href="` + url +`?page=` + button.next + `" title="Next page">&gt;</a></li>
            `
        }else{
            html_pagi += `
                <li class="button disable"><a href="#" title="Next page">&gt;</a></li>
            `
        }

        return `<div class="pagination-box">
                    <div class="pagination">
                        <ul class="clearfix">
                            ` + html_pagi +`
                        </ul>
                        <div class="jump">
                            <div class="jump-wrapper">
                                <form method="GET" action="?keyword">
                                    <input type="text" name="page" placeholder="Jump" id="jump-input" aria-label="Jump">
                                    <button class="jump-button" title="Jump">
                                        <span class="jump-ok">
                                            <svg enable-background="new 0 0 100 100" id="Layer_1" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="72.2,50.2 48.7,73.7 50.2,75.3 76.4,49.1 50.2,22.9 48.7,24.5 72.2,48 18.5,48 18.5,50.2 "/></svg>
                                        </span>
                                    </button>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>`;
    },
    pagi2 : function(pagi){

        var button = pagi.button,
            pages  = pagi.pages,
            url    = pagi.url;
            html_pagi = "";


        if(button.prev){
            html_pagi += `
                <li class="button"><a class="__link" href="#" data-page-num="` +  button.prev + `" title="Prev page">&lt;</a></li>
            `
        }else{
            html_pagi += `
                <li class="button disable"><a href="#" title="Prev page">&lt;</a></li>
            `
        }

        pages.forEach(function(e,i){

            var act  = e.act ? ' class="act" ' : "",
                num  = e.page;
            
            html_pagi += `<li` + act + `><a class="__link" data-page-num=` + num + ` href="#" title="Page ` + num + `">` + num + `</a></li>`
        });

        if(button.next){
            html_pagi += `
                <li class="button"><a class="__link" data-page-num="` + button.next + `" href="#" title="Next page">&gt;</a></li>
            `
        }else{
            html_pagi += `
                <li class="button disable"><a href="#" title="Next page">&gt;</a></li>
            `
        }

        return `<div class="pagination-box">
                    <div class="pagination">
                        <ul class="clearfix">
                            ` + html_pagi +`
                        </ul>
                        <div class="jump">
                            <div class="jump-wrapper">
                                <form method="GET" action="?keyword">
                                    <input type="text" name="page" placeholder="Jump" id="jump-input" aria-label="Jump">
                                    <button class="jump-button" title="Jump">
                                        <span class="jump-ok">
                                            <svg enable-background="new 0 0 100 100" id="Layer_1" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><polygon points="72.2,50.2 48.7,73.7 50.2,75.3 76.4,49.1 50.2,22.9 48.7,24.5 72.2,48 18.5,48 18.5,50.2 "/></svg>
                                        </span>
                                    </button>
                                </form>    
                            </div>
                        </div>
                    </div>
                </div>`;
    },
    r: function(m){
        var d = dom.create("div");
        d.appendChild(m);

        return d.innerHTML;
    },
    m : function(){
        var l = window.location.pathname,
            s = window.location.search;
            sh = window.location.hash;

        return l + s + sh;
    },
    bc : function(arr){

        function rb(url,title){
            
            return '<li><a class="__link fc1-v2" href="' + url + '" class="fc1-v2">' + title + '</a></li>';
        }

        var li = "",
            len = arr.length,
            last = arr[len-1];

        for (var i = 0; i < (len - 1) ; i++) {
            li += rb(arr[i].url,arr[i].title);
        }

        li += '<li>' + last.title + '</li>';

        return `
            <ol class="breadcrumb">
                <li><a href="/" class="__link fc1-v2">Devilchan</a></li>` + li +
            `</ol>`
    }
}

function load_tinymce(t){

    if (is_tinymce){

        t();

    }else{
        is_tinymce = true;
        var script = document.createElement('script');
        script.src = "/public/js/tinymce/tinymce.js";
        script.onload = function(){
            t();
        }

        document.getElementsByTagName("BODY")[0].appendChild(script);
    }
}

var route = [
    {
        path: "\/",
        module : "_home"
    },
    {
        path: "\/manga\/(.*?)\/chapter-([0-9\.]+)\/*",
        module : "_chapter"
    },
    {
        path : "\/manga\/([a-zA-Z0-9\-]+)-([0-9]+)\/*",
        module : "_manga"
    },
    {
        path : "\/the-loai\/*.*?",
        module :"_genre"
    },
    {
        path : "\/lich-su\/*",
        module :"_history"
    },
    {
        path : "\/(moi-cap-nhat|con-gai|con-trai)\/*",
        module :"_latest"
    }
];


function _latest(){
    var o = {
        script : script
    };

    function script(){

        get_chapters();
    }

    return o;
}

function chapter_history(){

    var obj = {},
        arr = [],
        stor = localStorage.getItem('visit-chapter');

    var a = document.querySelector(".breadcrumb li:nth-child(2) a");

    obj.manga_id = window.manga_id;
    obj.manga_name = a.innerText;
    obj.manga_url = new URL(a.href).pathname;
    obj.manga_cover_img = window.cover_img;
    obj.chapter_number = document.querySelector(".breadcrumb  li:nth-child(3)").innerText;
    obj.chapter_url = window.location.pathname;

    try {

        var json = JSON.parse(stor);

    } catch(e){

        var json = [];
    }



    if(json.length > 0){
            

        for (var i = 0; i < json.length; i++){

            if(json[i].manga_id == window.manga_id){
                
                json.splice(i,1);
            }
        }

        json.unshift(obj);
        localStorage.setItem("visit-chapter",JSON.stringify(json));

    }else{

        arr.push(obj);
        localStorage.setItem("visit-chapter",JSON.stringify(arr));
    }
}

function delete_manga(){
    var stor =  JSON.parse(localStorage.getItem('visit-chapter'));

    new Devilchan(document.querySelectorAll(".delete-manga  a")).addEventListener("click",function(){
        
        var _this = this;

        stor.forEach(function(e,i){

            if(e.manga_id == _this.getAttribute("data-delete-id")){

                stor.splice(i,1);
            }
        });

        localStorage.setItem('visit-chapter',JSON.stringify(stor));

        removeElement(this.closest(".m-item"));
    })
}

function get_chapters(){

    var json = localStorage.getItem('visit-chapter');

    try {

        var stor = JSON.parse(json || 1);

    } catch(e){

        var stor = [];
    }

    if(stor.length > 0){

        var html = "";

        for (var i = 0; i < Math.min(3,stor.length); i++) {

            var e = stor[i];

            html += 
            `<li>
                <div class="r-img">
                    <a title="` + e.manga_name + `" href="` + e.manga_url + `">
                        <img src="` +  e.manga_cover_img + `" alt="` + e.manga_name + `"{$r_size}>
                    </a>
                </div>
                <div class="r-title">
                    <a title="`+ e.manga_name +`" href="` + e.manga_url + `">` + e.manga_name + `</a>
                </div>
                <div class="r-chapter">
                    <a title="` + e.chapter_number + `" href="` + e.chapter_url + `">Đọc tiếp ` + e.chapter_number + `</a>
                </div>
            </li>`;
        }

        var box_title = dom.createStr(
        `<div class="box-title h-title clearfix">
            <h2 >Xem gần đây <i class="fa fa-angle-right"></i></h2>
            <a href="/lich-su">Xem tất cả <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></a>
        </div>`)[0];
        var history = dom.createStr(
        `<div class="history">
            <ul class="clearfix">
                ` + html + `
            </ul>
        </div>`)[0];

        var rp_history = document.getElementById("rp-history"),
            rp_box_title = document.getElementById("rp-box-title");

        rp_box_title.parentNode.replaceChild(box_title,rp_box_title);
        rp_history.parentNode.replaceChild(history,rp_history)
    }
}

function _history(){
    var o = {
        script : script
    };

    function script(){
        try{

            var stor = JSON.parse(localStorage.getItem('visit-chapter') || 1);

        }catch(e){

            var stor = [];
        }

        var manga_list = document.querySelector(".manga-list2");

        if(stor.length > 0){

            for (var i = 0; i < stor.length ; i++) {

                var e = stor[i];

                var html = dom.createStr(
                `<div class="m-item">
                    <div class="m-img">
                        <a href="` + e.manga_url + `" title="` + e.manga_name + `">
                            <img src="` + e.manga_cover_img + `">
                        </a>
                    </div>
                    <div class="m-wrap">
                        <div class="m-title"><a href="` + e.manga_url + `" title="` + e.manga_name + `">`+ e.manga_name +`</a></div>
                        <div class="m-chapters">
                            <ul>
                                <li>
                                    <a href="` + e.chapter_url + `" title="Chapter 40">Đọc tiếp ` + e.chapter_number + `</a>
                                </li>
                                <li class="delete-manga">
                                    <a href="javascript:void(0)" data-delete-id="` + e.manga_id +`" ><i class="fa fa-remove"></i> <b>Xóa</b></a>
                                </li>           
                            </ul>
                        </div>
                    </div>
                </div>`)[0];

                manga_list.appendChild(html);
            }

            delete_manga();
        }
    }

    return o;
}

function _home(){
    var o = {
        script : script
    };

    function script(){

        get_chapters();
    }

    return o;
}

function _chapter(){

    var o = {
        script : script
    };

    function script(){

        var lazyLoadInstance = new LazyLoad({
            threshold : 3000,
            callback_loaded : function(el){
                
                el.closest(".chapter-img").classList.remove("shine");
            }
        });
        
        var list_chapter = document.querySelector(".list-chapter"),
            list_chapter_inner = list_chapter.querySelector(".inner"),
            select_tag  = document.querySelector(".select-chapter > select");
        
        select_tag.addEventListener("change",function(){

            var selected = this.options[this.selectedIndex].value;

            window.location.href = selected;
        });

        _comments();

        document.querySelector(".report a").addEventListener("click",function(){

            var overlay = document.querySelector(".overlay");

            if(overlay){
                return;
            }

            overlay = dom.createStr('<div class="overlay"> <div class="modal"> <div class="modal-top"> Báo lỗi<img src="/public/icons/x.svg" alt="close modal"> </div><div class="modal-body"> <select id="modal-select"> <option value="0">Chọn</option> <option value="1">Ảnh trùng</option> <option value="2">Không load được ảnh</option> <option value="3">Sai truyện</option> <option value="4">Khác</option> </select> <textarea id="modal-txt" placeholder="Viết gì đó"></textarea> <div class="modal-send"><a href="javascript:void(0)">Gửi</a></div></div></div></div>')[0]

            new Devilchan([
                overlay,
                overlay.querySelector(".modal-top img")
            ]).addEventListener("click",function(e){

                if (e.target !== this){
                    return;
                }

                e.stopImmediatePropagation();
                
                removeElement(overlay);

            });

            overlay.querySelector(".modal-send a").addEventListener("click",function(){

                var processing = ajax_processing();

                if(!processing){
                    return;
                }

                var report_content = document.getElementById("modal-txt").value,
                    report_type    = document.getElementById("modal-select").value;

                ajax({
                    url : "/ajax/report/" + window.chapter_id,
                    dataType : "json",
                    type:"post",
                    data :{
                        report_content : report_content,
                        report_type : report_type
                    },
                    success : function(rs){

                        removeElement(processing);

                        if(rs.error == 0){

                            removeElement(overlay);
                        }

                        if(rs.message){
                            alert(rs.message);
                        }
                    }
                });
            });

            document.body.appendChild(overlay);
        });


        var stor = window.localStorage;

        if(!localStorage.getItem("visit-chapter")){
            localStorage.setItem('visit-chapter',[]);
        }

        chapter_history();
    }

    return o;
}

function _manga(){

    var o = {};

    o.script = function(){

        var show_chapters_btn = document.querySelector(".show-all-chapters > a");

        if (show_chapters_btn){

            show_chapters_btn.addEventListener("click",function(e){

                e.preventDefault();

                var tag_list = document.querySelector(".manga-chapters"),
                    hide_tag = tag_list.querySelectorAll(".less");

                forEach(hide_tag,function(e){
                    e.classList.add("active");
                });

                removeElement(show_chapters_btn);
            });
        }

        var ds = document.querySelector(".manga-description > .inner");

        if(ds.offsetHeight >= 200){

            ds.classList.add("shortened");

            var more_btn = dom.createStr(`<a class="read-more" title="Read more" href="javascript:void(0)">+ Read more</a>`)[0];

            more_btn.addEventListener("click",function(){
                ds.classList.toggle("shortened");

                if(ds.classList.contains("shortened")){

                    more_btn.innerHTML = '+ Read more';
                    more_btn.setAttribute("title","Read more");

                }else{

                    more_btn.innerHTML = '- Collapse ';
                    more_btn.setAttribute("title","Collapse");
                }

            });

            document.querySelector(".manga-description").appendChild(more_btn);
        }

        document.getElementById("fav").addEventListener("click",function(el){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var _this = this;

            ajax({
                url : "/ajax/manga/add-to-favorites?manga_id=" + window.manga_id,
                type : "get",
                dataType : "json",
                success : function(rs){
                    
                    removeElement(processing);

                    if(rs.type == "add"){
                        
                        _this.setAttribute("title","Bỏ thích");
                        _this.setAttribute("class","bgr");
                        _this.innerHTML = "Bỏ thích";

                    }else if(rs.type == "remove"){
                        
                        _this.setAttribute("title","Thích");
                        _this.setAttribute("class","");
                        _this.innerHTML = "Thích";
                    }

                    if(rs.message){
                        alert(rs.message)
                    }
                }
            })
        });

        _comments();
    }

    return o;
}

function _comments(){

    new Devilchan([document.getElementById("comment-content")]).addEventListener("click",function(){

        var cmt_btn = dom.createStr(
            `<div class="cmt-area-button">
                <button class="cmt-btn">Gửi</button>
            </div>`
        )[0];

        cmt_btn.addEventListener("click",function(){

            var processing = ajax_processing();

            if(!processing){
                return;
            }

            var comment_content =  tinyMCE.get("comment-content").getContent();

            ajax({
                url : "/ajax/comment/add",
                type:"post",
                dataType:"json",
                data :{
                    manga_id : window.manga_id,
                    chapter_id : window.chapter_id || 0,
                    comment_content  : comment_content
                },
                success:function(rs){

                    removeElement(processing);

                    if (rs.error == 1){

                        alert(rs.message);
                        return;
                    }

                    tinymce.get("comment-content").setContent('');

                    var dom_cmt_item = dom.createStr('<div data-cmt-id="' + rs.cmt.cmt_id + '" class="cmt-item">' + cmt_item(rs.cmt,true) + '</div>')[0];
                    var dom_list_cmt = document.getElementsByClassName("list-cmt")[0];

                    if (dom_list_cmt.querySelector(".cmt-item")){

                        dom_list_cmt.insertBefore(dom_cmt_item, dom_list_cmt.firstChild);

                    }else{

                        dom_list_cmt.appendChild(dom_cmt_item);
                    }

                    reply_event(dom_cmt_item.getElementsByClassName("reply-cmt"));
                }
            })
        });

        this.parentNode.appendChild(cmt_btn);

        new Devilchan([this]).removeEventListener("click");

        load_tinymce(function(){
            tinymce.init({
                forced_root_block: !1,
                force_br_newlines: !0,
                force_p_newlines: !1,
                entity_encoding: "raw",
                selector: "#comment-content",
                menubar: !1,
                statusbar: !1,
                plugins: ["autoresize", "emotrollface", "paste"],
                paste_as_text: !0,
                toolbar: "emotrollface",
                height: 100,
                autoresize_min_height: 100,
                autoresize_max_height: 100,
                autoresize_bottom_margin: 0,
                content_css : '/public/css/reset.css'
            });
        });
    });

    function _pagi(){

        new Devilchan(document.querySelectorAll(".pagination > ul > li > a")).addEventListener("click",function(e){

            e.preventDefault();

            var num = this.getAttribute("data-page-num");
            get_cmts(num);
            
        });
    }

    function _jump_btn(){

        var jbtn = document.getElementsByClassName("jump-button")[0];

        if(jbtn != null){

            jbtn.addEventListener("click",function(e){

                e.preventDefault();

                var num = document.getElementById("jump-input").value;

                get_cmts(num);
            });
        }
    }

    function _show_all_cmts(){

        new Devilchan(document.querySelectorAll(".show-all-cmts > a")).addEventListener("click",function(e){

            var cmt_id = this.closest(".cmt-item").getAttribute("data-cmt-id"),
                h_cmt_id = this.getAttribute("h-cmt-id");

            removeElement(this.closest(".show-all-cmts"));

            ajax({
                url : "/ajax/comment/show/" + cmt_id,
                type : "post",
                dataType :"json",
                data:{
                    h_cmt_id : h_cmt_id
                },
                success : function(rs){

                    forEach(rs.cmts,function(cmt,i){

                        var dom_cmt_item = dom.createStr('<div class="cmt-item">' + cmt_item(cmt) + '</div>')[0],
                            firs_node = document.querySelector(".child-box").firstChild;

                        document.querySelector(".child-box").insertBefore(dom_cmt_item,firs_node);
                    });
                }
            })
        });
    }

    _pagi();
    _jump_btn();
    _show_all_cmts();

    reply_event(document.getElementsByClassName("reply-cmt"));

    function render_cmt(e){

        var dom_cmt_item = dom.createStr('<div data-cmt-id="' + e.cmt_id + '" class="cmt-item">' + cmt_item(e,true) + '</div>')[0];

        reply_event(dom_cmt_item.getElementsByClassName("reply-cmt"));

        return dom_cmt_item;
    }

    function reply_event(elements){

        forEach(elements,function(el){

            el.addEventListener("click",function(){

                var checker = this.closest(".cmt-item").querySelector(".comment-area");

                if(checker != null){
                    checker.style.display = "block";
                    return;
                }

                var cmt_id = this.closest(".cmt-item").getAttribute("data-cmt-id");

                load_tinymce(function(){

                    var cmt_area = dom.createStr(
                        `<div class="comment-area">
                            <textarea id="comment-content-` + cmt_id + `"></textarea>
                            <div class="cmt-area-button">
                                <button class="cmt-btn">Gửi</button>
                            </div>
                        </div>`
                    )[0];

                    var cmt_btn = cmt_area.querySelector(".cmt-area-button");

                    cmt_btn.addEventListener("click",function(){

                        var processing = ajax_processing();

                        if(!processing){
                            return;
                        }

                        var _this = this,
                            comment_content =  tinyMCE.get("comment-content-" + cmt_id).getContent();

                        ajax({
                            url : "/ajax/comment/reply",
                            type:"post",
                            dataType:"json",
                            data :{
                                reply_id : cmt_id,
                                manga_id : window.manga_id,
                                chapter_id : window.chapter_id,
                                comment_content  : comment_content
                            },
                            success:function(rs){

                                removeElement(processing);

                                if (rs.error == 1){
                                    alert(rs.message);
                                    return;
                                }

                                var p_cmt_item = _this.closest(".cmt-item");
                                var dom_cmt_item = dom.createStr('<div data-cmt-id="' + rs.cmt.cmt_id + '" class="cmt-item">' + cmt_item(rs.cmt) + '</div>')[0];
                                reply_event(dom_cmt_item.getElementsByClassName("reply-cmt"));

                                var dom_child_box = p_cmt_item.querySelector(".child-box");

                                if (dom_child_box){

                                    dom_child_box.appendChild(dom_cmt_item);

                                }else{

                                    dom_child_box = dom.createStr('<div class="child-box"></div>')[0];
                                    dom_child_box.appendChild(dom_cmt_item);
                                    p_cmt_item.appendChild(dom_child_box);
                                }

                                tinymce.get("comment-content-" + cmt_id).setContent('');

                                cmt_area.style.display = "none";
                            }
                        })
                    });

                    insertAfter(cmt_area,el.closest(".cmt-item").querySelector(".cmt-time"))

                    tinymce.init({
                        forced_root_block: !1,
                        force_br_newlines: !0,
                        force_p_newlines: !1,
                        entity_encoding: "raw",
                        selector: "#comment-content-" + cmt_id,
                        menubar: !1,
                        statusbar: !1,
                        plugins: ["autoresize", "emotrollface", "paste"],
                        paste_as_text: !0,
                        toolbar: "emotrollface",
                        height: 100,
                        autoresize_min_height: 100,
                        autoresize_max_height: 100,
                        autoresize_bottom_margin: 0,
                        content_css : '/public/css/reset.css'
                    });
                })
            });
        });
    }

    function cmt_item(e,rep = false){
        console.log('e', e);

        var cmt_dom = '';

        if(rep == true){

            cmt_dom = '<div class="reply-cmt">Reply</div>';
        }

        var dom_chapter_number = '';

        if(e.chapter_number){
           dom_chapter_number = '<span class="cmt-chapter">Chapter ' + e.chapter_number + '</span>';
        }

        return `<div class="avatar">
                    <img src="` + ((e.avatar === "" || e.avatar == null) ?  "/public/icons/default.jpg" : e.avatar) + `">
                    ` + cmt_dom + `
                </div>
                <p class="cmt-user-name">
                    <span class="nickname">` + e.nickname + `</span>
                    ` + dom_chapter_number + `
                </p>
                <p class="cmt-content">` +  e.cmt_content + `</p>
                <p class="cmt-time"><i>` + e.cmt_date_published + `</i></p>`;
    }

    function get_cmts(num){

        ajax({
            url:"/ajax/comment/get/" + window.manga_id + "?page=" + num,
            type : "get",
            dataType:"json",
            success : function(rs){

                var dom_list_cmt = document.getElementsByClassName("list-cmt")[0];

                dom_list_cmt.innerHTML = "";

                forEach(rs.comments,function(e,i){

                    var dom_cmt_item = render_cmt(e);

                    dom_list_cmt.appendChild(dom_cmt_item);

                    if(e.child_cmts != null){

                        var child_cmts = JSON.parse(e.child_cmts).reverse(),
                            show_all_btn = "";

                        var dom_child_box = dom.createStr('<div class="child-box"></div>')[0];


                        forEach(child_cmts,function(ce){
                            
                            dom_child_box.appendChild(dom.createStr('<div class="cmt-item">' + cmt_item(ce) + '</div>')[0]);
                        });

                        insertAfter(dom_child_box,dom_cmt_item.querySelector(".cmt-time"));

                         if(e.count > 3){
                        
                            show_all_btn = dom.createStr(`<div class="show-all-cmts">
                                                <a h-cmt-id="` + child_cmts[0].cmt_id + `" href="javascript:void(0)" title="Show all comments">Show all comments + </a>
                                            </div>`)[0];

                            dom_cmt_item.insertBefore(show_all_btn,dom_child_box)
                        }
                    }
                });

                var pagi = dom.createStr(window.h.pagi2(rs.pagi))[0];

                var cur_pagi = document.querySelector(".pagination-box");

                cur_pagi.parentNode.replaceChild(pagi,cur_pagi);

                _pagi();
                _jump_btn();
                _show_all_cmts();
                
                location.href = "#comment-box";
            }
        });
    }
}

function _genre(){

    var o = {};

    o.script = function(){
        
        document.querySelector(".manga-actions > select").addEventListener("change",function(){

            var selected = this.options[this.selectedIndex].value;

            window.location.href = selected;
        });
    }

    return o;
}

var Kikyo = new Waifu_Kikyo(route,false);