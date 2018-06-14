function loadTabSearch(i) {
    $("ul#homeTabNav li").removeClass('active');
    $("ul#homeTabNav li.tab" + i).addClass('active');
    $("div[id^='tab']").removeClass("in active");
    $("div#tab" + i).addClass("in active");
}

function showAdvanceSearch(t) {
    if ($("#s_subdist_id" + t).is(':hidden')) {
        $("#s_subdist_id" + t).show();
        $("#s_street_id" + t).show();
        $("#s_huongnha_id" + t).show();
        $("#s_duan_id" + t).show();
        $("#timkiem_text" + t).html('Tìm kiếm thu gọn');
    } else {
        $("#s_subdist_id" + t).hide();
        $("#s_street_id" + t).hide();
        $("#s_huongnha_id" + t).hide();
        $("#s_duan_id" + t).hide();
        $("#timkiem_text" + t).html('Tìm kiếm nâng cao');
    }
    return false;
}

function loadJSCSS(filename, filetype) {
    if (filetype == "js") {
        var fileref = document.createElement('script')
        fileref.setAttribute("type", "text/javascript")
        fileref.setAttribute("src", filename)
    } else if (filetype == "css") {
        var fileref = document.createElement("link")
        fileref.setAttribute("rel", "stylesheet")
        fileref.setAttribute("type", "text/css")
        fileref.setAttribute("href", filename)
    }
    if (typeof fileref != "undefined")
        document.getElementsByTagName("head")[0].appendChild(fileref)
}

function gotoUrl(str) {
    window.location.href = str;
    return false;
}

function submitform(formid) {
    document.getElementById(formid).submit();
    return false;
}

function setMenuClicked(i) {
    var options = {
        path: '/',
        expires: 1
    };
    $.cookie('menu_a_clicked', i, options);
}

function getMenuClicked() {
    return $.cookie('menu_a_clicked');
}

function deletetMenuClicked() {
    var options = {
        path: '/',
        expires: 1
    };
    $.cookie('menu_a_clicked', null, options);
}

function setMenuActive() {
    var i;
    i = getMenuClicked();
    if (i == 0 || i == null) i = 1;
    $("#menu_a_" + i).addClass("current");
}

function CheckAll(formid) {
    var fmobj = document.getElementById(formid);
    var rowpp = 20;
    for (var i = 0; i < fmobj.elements.length; i++) {
        var e = fmobj.elements[i];
        if ((e.name != 'allbox') && (e.type == 'checkbox') && (!e.disabled)) {
            e.checked = fmobj.allbox.checked;
        }
    }
    return true;
}

function confirmSubmit(formid, msg) {
    var total = 0;
    var fmobj = document.getElementById(formid);
    for (var i = 0; i < fmobj.elements.length; i++) {
        var e = fmobj.elements[i];
        if ((e.name != 'allbox' && e.name != 'checkList') && (e.type == 'checkbox') && (!e.disabled)) {
            if (e.checked) total++;
        }
    }
    if (total == 0) {
        alert('Bạn phải chọn ít nhất 1 bản ghi');
        return false;
    }
    if (typeof msg != "undefined" && confirm(msg)) {
        document.getElementById(formid).submit();
        return true;
    }
    return true;
}

function confirmDelete(formid) {
    return confirmSubmit(formid, "Bạn có chắc chắn muốn xóa không?");
}

function showDialog(myModal, func, args) {
    $('#' + myModal).modal('show');
    if (typeof func != 'undefined' && func != '') {
        if (typeof args != 'undefined' && args != '') {
            eval(func + '(' + args + ');');
        } else {
            eval(func + '();');
        }
    }
    return false;
}

function closeDialog(myModal) {
    $('#' + myModal).modal('hide');
    return false;
}

function showNoty(text, layout, ntype) {
    var str = '{"text":"' + text + '","layout":"' + layout + '","type":"' + ntype + '"}';
    var options = $.parseJSON(str);
    noty(options);
    return false;
}

function setPreviewAvatar(input, imgobj) {
    if (!/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(input.files[0].name)) {
        alert('Chỉ chấp nhận các tệp đuôi JPG, JPEG, GIF, PNG');
        input.value = "";
        return false;
    }
    if (input.files[0].size > 1 * 1024 * 1024) {
        alert("Tệp ảnh phải có dung lượng nhỏ hơn 1 MB");
        input.value = "";
        return false;
    }
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#' + imgobj).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
    return false;
}

function setThumbImage(obj) {
    $("#previews span").removeClass();
    $("#previews span img").attr('title', 'Đặt làm ảnh đại diện');
    $(obj).parent().addClass('selected');
    $(obj).parent().children("img").attr('title', 'Ảnh đại diện');
    $("#mainpicid").val($(obj).attr('alt'));
}

function deleteThumbImage(obj, i) {
    var ok = 0;
    if ($(obj).parent().attr('class') == 'selected') {
        $("#mainpicid").val("");
        ok = 1;
    }
    $(obj).parent().remove();
    $("#hinhanh" + i).val("");
    if (ok == 1) {
        $("#previews span:first").addClass('selected');
        $("#previews span:first img").attr('title', 'Ảnh đại diện');
        $("#mainpicid").val($("#previews span:first img").attr('alt'));
    }
}

function previewPhotos(input, i) {
    if ($('#previews span').length >= 10) {
        return 0;
    }
    var l = input.files.length;
    if (i == l) return 0;
    if (input.files && input.files[i]) {
        if (!input.files[i].type.match('image.*')) {
            return 0;
        }
        if (input.files[i].size > 2 * 1024 * 1024) {
            return 0;
        }
        var selected = '',
            title = 'Đặt làm ảnh đại diện';
        if (i == 0 && $("#mainpicid").val() == "") {
            selected = 'selected';
            title = 'Ảnh đại diện';
        }
        var ename = input.files[i].name;
        var reader = new FileReader();
        reader.onload = function(e) {
            var imghtml = "<span class='" + selected + "'><img onclick='setThumbImage(this);' src='" + e.target.result + "' alt='" + ename + "' class='img-polaroid' style='max-width:120px; height:120px; cursor:;' title='" + title + "'><i class='corner'></i></span>";
            $('#previews .newlist').append(imghtml);
            previewPhotos(input, i + 1);
        }
        reader.readAsDataURL(input.files[i]);
    }
}

function setPreviewPhotos(input) {
    var l = input.files.length;
    if (l > 10) {
        alert('Bạn chỉ được upload tối đa 10 ảnh');
        input.value = "";
        return false;
    }
    for (var i = 0; i < l; i++) {
        if (!/(\.png|\.gif|\.jpg|\.jpeg)$/i.test(input.files[i].name)) {
            alert('Chỉ chấp nhận các tệp đuôi JPG, JPEG, GIF, PNG');
            input.value = "";
            return false;
        }
    }
    for (var i = 0; i < l; i++) {
        if (input.files[i].size > 2 * 1024 * 1024) {
            alert("Các tệp ảnh phải có dung lượng nhỏ hơn 2 MB");
            input.value = "";
            return false;
        }
    }
    $('#previews .newlist').empty();
    previewPhotos(input, 0);
    return false;
}

function deletePreview(obj) {
    if (confirm('Bạn có muốn xóa ảnh này không?')) {
        $(obj).remove();
    }
}

function getDistrict(obj, to_id, t) {
    if (typeof t == 'undefined') {
        var t = 0;
    }
    var province_id, req1, req2;
    province_id = $(obj).val();
    req1 = vncms_url + "/?sub=ajax&act=get_district&type=" + t + "&province_id=" + province_id;
    $("#" + to_id).load(req1);
    $("#" + to_id).val(0);
    $("#" + to_id).change();
    return false;
}

function getDuan(obj, province_id, to_id, t) {
    if (typeof t == 'undefined') {
        var t = 0;
    }
    var district_id, req1, req2;
    district_id = $(obj).val();
    province_id = $('#' + province_id).val();
    req1 = vncms_url + "/?sub=ajax&act=get_duan&type=" + t + "&district_id=" + district_id + "&province_id=" + province_id;
    $("#" + to_id).load(req1);
    $("#" + to_id).val(0);
    $("#" + to_id).change();
    return false;
}

function getSubdist(obj, to_id, t) {
    if (typeof t == 'undefined') {
        var t = 0;
    }
    var district_id, req1, req2;
    district_id = $(obj).val();
    req1 = vncms_url + "/?sub=ajax&act=get_subdist&type=" + t + "&district_id=" + district_id;
    $("#" + to_id).load(req1);
    $("#" + to_id).val(0);
    $("#" + to_id).change();
    return false;
}

function getStreet(obj, to_id, t) {
    if (typeof t == 'undefined') {
        var t = 0;
    }
    var district_id, req1, req2;
    district_id = $(obj).val();
    req1 = vncms_url + "/?sub=ajax&act=get_street&type=" + t + "&district_id=" + district_id;
    $("#" + to_id).load(req1);
    $("#" + to_id).val(0);
    $("#" + to_id).change();
    return false;
}

function getSubCat(obj, to_id) {
    var cat_id, req1, req2;
    cat_id = $(obj).val();
    req1 = vncms_url + "/?sub=ajax&act=get_subcat&type=0&cat_id=" + cat_id;
    $("#" + to_id).load(req1);
    $("#" + to_id).val(0);
    $("#" + to_id).change();
    return false;
}

function getTongTienBDS(to_id, giaban, donvigia_id, dientich) {
    var req1 = vncms_url + "/?sub=ajax&act=getTongTienBDS&giaban=" + giaban + "&donvigia_id=" + donvigia_id + "&dientich=" + dientich;
    $("#" + to_id).load(req1);
    return false;
}

function executeComma1(temp) {
    if (temp == 0) return 0;
    temp = temp.toString();
    for (i = 0; i < temp.length; i++) {
        for (k = i; k < temp.length; k++) {
            if (temp.charAt(k) == '.') {
                temp = temp.replace('.', '');
            }
        }
    }
    var j = 0;
    var s = "";
    var s1 = "";
    var s2 = "";
    for (i = temp.length - 1; i >= 0; i--) {
        j = j + 1;
        if (j == 3) {
            j = 0;
            s1 = temp.substring(0, i);
            s2 = temp.substring(i, i + 3);
            s = "." + s2 + s;
        }
    }
    if (s1.length > 0) {
        s = s1 + s;
        return s;
    } else if (s.length > 0 && s2.length > 0) {
        return s.substring(1, s.length);
    }
}

function executeComma2(fr) {
    fr.value = executeComma1(fr.value);
}

function executeComma(event, fr) {
    temp = fr.value;
    fr.value = temp.replace(/[^0-9]/g, '');
    if ((event.keyCode >= 96 && event.keyCode <= 105)) {
        executeComma2(fr);
    } else if (event.keyCode >= 48 && event.keyCode <= 57) {
        executeComma2(fr);
    } else if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9) {
        executeComma2(fr);
    } else {
        executeComma2(fr);
    }
}

function onlyAcceptFloat(event, fr) {
    var temp = fr.value;
    fr.value = temp.replace(/[^0-9.]/g, '');
}

function onlyAcceptFloat2(event, fr) {
    var temp = fr.value;
    fr.value = temp.replace(/[\s]/, '').replace(/[\,]/, '.');
}

function onlyAcceptInt(event, fr) {
    var temp = fr.value;
    fr.value = temp.replace(/[^0-9]/g, '');
}

function GetMoneyText(money) {
    if (money == 0) return 0;
    money = Math.round(money * 10) / 10;
    var retval = '';
    var sodu = 0;
    if (money >= 1000000000) {
        sodu = Math.floor(money / 1000000000);
        retval += sodu + ' tỷ ';
        money = money - (sodu * 1000000000);
    }
    if (money >= 1000000) {
        sodu = Math.floor(money / 1000000);
        retval += sodu + ' triệu ';
        money = money - (sodu * 1000000);
    }
    if (money >= 1000) {
        sodu = Math.floor(money / 1000);
        retval += sodu + ' nghìn ';
        money = money - (sodu * 1000);
    }
    if (money > 0) {
        retval += money + ' đồng';
    }
    return retval;
}

function GetMoneyText2(money) {
    if (money == 0) return 0;
    money = Math.round(money * 10) / 10
    var retval = '';
    var sodu = 0;
    if (money >= 1000000000) {
        sodu = money / 1000000000;
        return sodu + ' tỷ ';
    }
    if (money >= 1000000) {
        sodu = money / 1000000;
        return sodu + ' triệu ';
    }
    if (money >= 1000) {
        sodu = money / 1000;
        return sodu + ' nghìn ';
    }
    return GetMoneyText(money);
}

function GetTotalDay(day1, day2) {
    var sec = day2 - day1;
    sec = sec / 24;
    sec = sec / 60;
    sec = sec / 60;
    sec = sec / 1000;
    return Math.round(sec);
}

function UpdateQueryString(key, value, url) {
    if (!url) url = window.location.href;
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)(.*)", "gi");
    if (re.test(url)) {
        if (typeof value !== 'undefined' && value !== null)
            return url.replace(re, '$1' + key + "=" + value + '$2$3');
        else {
            return url.replace(re, '$1$3').replace(/(&|\?)$/, '');
        }
    } else {
        if (typeof value !== 'undefined' && value !== null) {
            var separator = url.indexOf('?') !== -1 ? '&' : '?',
                hash = url.split('#');
            url = hash[0] + separator + key + '=' + value;
            if (hash[1]) url += '#' + hash[1];
            return url;
        } else
            return url;
    }
}

function refresh_captcha(imgid) {
    var oldsrc = $('#' + imgid).attr('src');
    var newsrc, newt;
    newt = new Date().getMilliseconds();
    newsrc = UpdateQueryString('tim', newt, oldsrc);
    $('#' + imgid).attr('src', newsrc);
    return false;
}

function clearInput(obj) {
    $(obj).val('');
}
var uniChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTVWXYZàáảãạâầấẩẫậăằắẳẵặèéẻẽẹêềếểễệđìíỉĩịòóỏõọôồốổỗộơờớởỡợùúủũụưừứửữựỳýỷỹỵÀÁẢÃẠÂẦẤẨẪẬĂẰẮẲẴẶÈÉẺẼẸÊỀẾỂỄỆĐÌÍỈĨỊÒÓỎÕỌÔỒỐỔỖỘƠỜỚỞỠỢÙÚỦŨỤƯỪỨỬỮỰỲÝỶỸỴÂĂĐÔƠƯ1234567890~!@#$%^&*()_+=-{}][|\":;'\\/.,<>? \n\r\t";
var KoDauChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTVWXYZaaaaaaaaaaaaaaaaaeeeeeeeeeeediiiiiooooooooooooooooouuuuuuuuuuuyyyyyAAAAAAAAAAAAAAAAAEEEEEEEEEEEDIIIOOOOOOOOOOOOOOOOOOOUUUUUUUUUUUYYYYYAADOOU1234567890~!@#$%^&*()_+=-{}][|\":;'\\/.,<>? \n\r\t";
var Alphabe = "qwertyuioplkjhgfdsazxcvbnm0123456789QWERTYUIOPASDFGHJKLZXCVBNM";

function UnicodeToKoDau(s) {
    var retVal = '';
    if (s == null)
        return retVal;
    var pos;
    var c = 'a';
    for (var i = 0; i < s.length; i++) {
        if (c == ' ' && s[i] == ' ')
            continue;
        c = s[i];
        pos = uniChars.indexOf(c);
        if (pos >= 0)
            retVal += KoDauChars[pos];
    }
    return retVal;
}

function UnicodeToKoDauUrl(s) {
    var retval = '';
    if (s != null && s != '') {
        var reg_replace_white_space = new RegExp('( )+', "g");
        s = s.replace(reg_replace_white_space, '-');
        if (s.length > 100)
            s = s.substring(0, 100);
        s = UnicodeToKoDau(s);
        var reg_replace_html_tag = new RegExp('<[^>]*>');
        s = s.replace(reg_replace_html_tag, '');
        var ss = '';
        for (var i = 0; i < s.length; i++) {
            if (Alphabe.indexOf(s[i]) >= 0)
                ss += s[i];
            else
                ss += '-';
        }
        ss = ss.replace(reg_replace_white_space, '-');
        retval = ss;
        var reg_replace_urlchar = new RegExp('-+');
        retval = retval.replace(reg_replace_urlchar, '-');
        return retval.length > 100 ? retval.substring(0, 100) : retval;
    }
    return retval;
}

function add_bookmark(title, url) {
    if (typeof title == 'undefined') title = window.document.title;
    if (typeof url == 'undefined') url = '';
    if (document.all) {
        window.external.AddFavorite(url, title);
    } else if (window.opera && window.print) {
        var elem = document.createElement('a');
        elem.setAttribute('href', url);
        elem.setAttribute('title', title);
        elem.setAttribute('rel', 'sidebar');
        elem.click();
    } else if (window.sidebar) {
        alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');
    } else {
        alert('Press ' + (navigator.userAgent.toLowerCase().indexOf('mac') != -1 ? 'Command/Cmd' : 'CTRL') + ' + D to bookmark this page.');
    }
}

function send_friend() {
    addthis_sendto("email");
    return false;
}

function undercontruct() {
    alert('THÔNG BÁO:\nTính năng này đang được chúng tôi nâng cấp và sẽ được kích hoạt trong thời gian tới!');
    return false;
}

function getYoutubeId(url) {
    if (url === null) {
        return "";
    }
    var vid;
    var results;
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var results = url.match(regExp);
    if (results && results[7].length == 11) {
        vid = results[7];
    }
    return vid;
}

function getYoutubeThumbs(vid, size) {
    size = (size === null) ? "big" : size;
    if (size == "default") {
        return "http://img.youtube.com/vi/" + vid + "/default.jpg";
    } else
    if (size == "small") {
        return "http://img.youtube.com/vi/" + vid + "/2.jpg";
    } else {
        return "http://img.youtube.com/vi/" + vid + "/0.jpg";
    }
}

function isFullname(str) {
    if (str.length < 3 || str.length > 30) return false;
    if (str.length > 0) {
        if (str[0] > '0' && str[0] < '9') {
            return false;
        }
        return true;
    }
    return false;
}

function isEmail(email) {
    var pattern = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    return pattern.test(email);
}

function isUserName(str) {
    var pattern = /^[a-zA-Z0-9_]{6,32}$/;
    if (pattern.test(str)) {
        if (str[0] > '0' && str[0] < '9') {
            return false;
        }
        return true;
    }
    return false;
}

function isNumber(value) {
    var pattern = /^\d+$/;
    return pattern.test(value);
}

function isMobileNumber(str) {
    if (!isNumber(str)) return false;
    if (str[0] != 0) return false;
    if (str[1] == 1 && str.length != 11) return false;
    if (str[1] == 9 && str.length != 10) return false;
    return true;
}

function validateTitle(obj, name, lmin, lmax) {
    var tobj = $(obj).parent().parent();
    var v = $(obj).val();
    if (v == "" || v.length < lmin || v.length > lmax) {
        showErrorObj(tobj, name + " phải tối thiểu " + lmin + " ký tự, tối đa " + lmax);
        return false;
    } else {
        hideErrorObj(tobj);
        return true;
    }
}

function validateFullname(obj) {
    var temp = $(obj).val();
    return validateObj(obj, 'isFullname', "Họ tên không hợp lệ (tối thiểu 3 ký tự, tối đa 30)");
}

function validateUsername(obj) {
    return validateObj(obj, 'isUserName', "Tên tài khoản không hợp lệ (tối thiểu 6 ký tự, tối đa 32)");
}

function validateMobile(obj) {
    return validateObj(obj, 'isMobileNumber', "Số di động không đúng định dạng");
}

function validateEmail(obj) {
    return validateObj(obj, 'isEmail', "Email không đúng định dạng");
}

function validatePassword(passid1, passid2) {
    var v1 = $('#' + passid1).val();
    var v2 = $('#' + passid2).val();
    var tobj1 = $('#' + passid1).parent().parent();
    var tobj2 = $('#' + passid2).parent().parent();
    var ok1 = 1,
        ok2 = 1;
    if (v1.length < 6 || v1.length > 32) {
        showErrorObj(tobj1, "Mật khẩu phải tối thiểu 6 ký tự, tối đa 32");
        ok1 = 0;
    }
    if (v2 == '' || v1 != v2) {
        showErrorObj(tobj2, "Mật khẩu nhắc lại phải giống mật khẩu");
        ok2 = 0;
    }
    if (ok1) {
        hideErrorObj(tobj1);
    }
    if (ok2) {
        hideErrorObj(tobj2);
    }
    return (ok1 * ok2 == 1) ? true : false;
}

function showErrorObj(tobj, msg) {
    if (tobj.find('.show_error').length == 0) {
        tobj.append("<div class='show_error'>" + msg + "</div>");
    } else {
        tobj.find('.show_error').html(msg);
        tobj.find('.show_error').show();
    }
    tobj.addClass('error');
    if (typeof(errorpaddingleft) !== 'undefined') {
        tobj.find('.show_error').css('padding-left', errorpaddingleft);
    } else {
        tobj.find('.show_error').css('padding-left', '100px');
    }
}

function showErrorObj2(tobj, msg) {
    showErrorObj(tobj, msg);
    tobj.find('.show_error').css('padding-left', '71%');
}

function hideErrorObj(tobj) {
    if (tobj.find('.show_error').length > 0) {
        tobj.find('.show_error').hide();
        tobj.removeClass('error');
    }
}

function validateTitle3(obj, name, lmin, lmax) {
    var tobj = $(obj).parent().parent();
    var v = $(obj).val();
    if (v == "" || v.length < lmin || v.length > lmax) {
        showErrorObj3(tobj, name + " phải tối thiểu " + lmin + " ký tự, tối đa " + lmax);
        return false;
    } else {
        hideErrorObj(tobj);
        return true;
    }
}

function showErrorObj3(tobj, msg) {
    showErrorObj(tobj, msg);
    tobj.find('.show_error').css('padding-left', '0px');
}

function validateObj(obj, func, msg) {
    var str = $(obj).val();
    var tobj = $(obj).parent().parent();
    if (window[func](str) == false) {
        showErrorObj(tobj, msg);
        return false;
    } else {
        hideErrorObj(tobj);
        return true;
    }
}

function getTypeBds(i) {}

function OnloadExecute() {
    if (!window.onload_queue || window.onload_queue.length == 0) {
        return
    }
    for (var a = 0; a < window.onload_queue.length; a++) {
        window.onload_queue[a]()
    }
}

function Onload(a, c) {
    if (typeof(a) != "function") {
        return
    }
    if (!window.onload_queue_set) {
        window.onload_queue_set = true;
        var b = window.onload;
        window.onload = function() {
            if (typeof b != "undefined" && b != null) {
                b()
            }
            setTimeout(OnloadExecute, 10)
        }
    }
    if (!window.onload_queue) {
        window.onload_queue = []
    }
    if (c) {
        window.onload_queue.unshift(a)
    } else {
        window.onload_queue[window.onload_queue.length] = a
    }
}

function area_highlight() {
    var a = document.getElementsByTagName("area");
    var b = document.getElementById("area_highlight");
    for (i = 0; i < a.length; i++) {
        a[i].onmouseover = function() {
            var d = this.id.substring(this.id.indexOf("_") + 1);
            var e = document.getElementById("region_" + d);
            b.className = "sprite_index_vn_hover_hover_region_" + d;
        };
        a[i].onmouseout = function() {
            var d = this.id.substring(this.id.indexOf("_") + 1);
            var e = document.getElementById("region_" + d);
            b.className = "";
        }
    }
}
Onload(area_highlight);

function displayOtherProvince(obj) {
    if ($("#otherProvinces").css('display') == 'none') {
        $("#otherProvinces").show();
        $(obj).css('font-weight', 'bold');
        $("#otherProvinces").mouseleave(function() {
            $("#otherProvinces").hide();
            $(obj).css('font-weight', 'normal');
        });
    } else {
        $("#otherProvinces").hide();
        $(obj).css('font-weight', 'normal');
    }
    return false;
}