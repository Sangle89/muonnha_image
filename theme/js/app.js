/*
Author SangIT
Phone 0906 493 124
Email slevan89@gmail.com
*/
if (typeof jQuery === "undefined") {
  throw new Error("RealApp requires jQuery");
}
function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name+'=; Max-Age=-99999999;';  
}
$.RealApp = {};
var listCategory = $('#listCategory');
var listCity = $('#listCity');
var listCity2 = $('#listCity2');
var listCity3 = $('#listCity3');
var listDistrict = $('#listDistrict');
var listDistrict2 = $('#listDistrict2');
var listDistrict3 = $('#listDistrict3');
var listWard = $('#listWard');
var listWard2 = $('#listWard2');
var listWard3 = $('#listWard3');
var listStreet = $('#listStreet');
var listStreet2 = $('#listStreet2');
var listStreet3 = $('#listStreet3');
var listPrice = $('#listPrice');
var listArea = $('#listArea');
var listProject = $('#listProject');
var listProject2 = $('#listProject2');
var listBedroom = $('#listBedroom');
var listDirection = $('#listDirection');
$.RealApp = {
    selectDropdown: function() {
        listCity.selectmenu({
            change: function(event, ui) {
                $.ajax({
                   url: base_url + 'ajax/load_district_by_city',
                   type: 'post',
                   dataType: 'html',
                   data: {city_id: ui.item.value},
                   beforeSend: function() {},
                   success: function(res) {
                        listDistrict.html(res);
                        listDistrict.selectmenu("refresh");
                        $('#CityID').val(ui.item.value);
                   } 
                });
            }
       });
       listDistrict.selectmenu({
            change: function(event, ui) {
                $.ajax({
                   url: base_url + 'ajax/load_ward_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: ui.item.value},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listWard.html(res);
                        listWard.selectmenu("refresh");
                        $('#DistrictID').val(ui.item.value);
                   } 
                });
                
                $.ajax({
                   url: base_url + 'ajax/load_street_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: ui.item.value},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listStreet.html(res);
                        listStreet.selectmenu("refresh");
                        //$('#WardID').val(ui.item.value);
                   } 
                });
                
                $.ajax({
                   url: base_url + 'ajax/load_project_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: ui.item.value},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listProject.html(res);
                        listProject.selectmenu("refresh");
                   } 
                });
            }
       });
       listWard.selectmenu({
            change: function(event, ui) {
                /*$.ajax({
                   url: base_url + 'ajax/load_street_by_ward',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: ui.item.value},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listStreet.html(res);
                        listStreet.selectmenu("refresh");
                        $('#WardID').val(ui.item.value);
                   } 
                });*/
                $('#WardID').val(ui.item.value);
            }
       });
       
       listCategory.selectmenu({
            change: function(event, ui) {
                $('#CatID').val(ui.item.value);
            }
       });
       listStreet.selectmenu({
            change: function(event, ui) {
                $('#StreetID').val(ui.item.value);
            }
       });
       listPrice.selectmenu({
            change: function(event, ui) {
                $('#Price').val(ui.item.value);
            }
       });
       listArea.selectmenu({
            change: function(event, ui) {
                $('#Area').val(ui.item.value);
            }
       });
       listBedroom.selectmenu({
            change: function(event, ui) {
                $('#Bedroom').val(ui.item.value);
            }
       });
       listProject.selectmenu({
            change: function(event, ui) {
                $('#ProjectID').val(ui.item.value);
            }
       });
       listDirection.selectmenu({
            change: function(event, ui) {
                $('#Direction').val(ui.item.value);
            }
       });
    },
    select2Dropdown: function() {
        if($(".select2").length) {
    		$( ".select2" ).select2();
    	};
        listCity2.change(function() {
            $.ajax({
                   url: base_url + 'ajax/load_district_by_city',
                   type: 'post',
                   dataType: 'html',
                   data: {city_id: $(this).find('option:selected').val()},
                   beforeSend: function() {},
                   success: function(res) {
                        listDistrict2.html(res);
                   } 
                });
             
       });
       listDistrict2.change(function(){
                $.ajax({
                   url: base_url + 'ajax/load_ward_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listWard2.html(res);
                       
                   } 
                });
				
				$.ajax({
                   url: base_url + 'ajax/load_street_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listStreet2.html(res);
                   } 
                });
                $.ajax({
                   url: base_url + 'ajax/load_project_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listProject2.html(res);
                        listProject2.selectmenu("refresh");
                   } 
                });
            
       });
       listWard2.change(function(){
            /*
                $.ajax({
                   url: base_url + 'ajax/load_street_by_ward',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listStreet2.html(res);
                        
                   } 
                });*/
            
       });
    },
    select3Dropdown: function() {
        if($(".select2").length) {
    		$( ".select2" ).select2();
    	};
        listCity3.change(function() {
            $.ajax({
                   url: base_url + 'ajax/load_district_by_city',
                   type: 'post',
                   dataType: 'html',
                   data: {city_id: $(this).find('option:selected').val()},
                   beforeSend: function() {},
                   success: function(res) {
                        listDistrict3.html(res);
                   } 
                });
             
       });
       listDistrict3.change(function(){
                $.ajax({
                   url: base_url + 'ajax/load_ward_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {},
                   success: function(res) {
                        listWard3.html(res);
                   } 
                });
				$.ajax({
                   url: base_url + 'ajax/load_street_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {
                    
                   },
                   success: function(res) {
                        listStreet3.html(res);
                   } 
                });
                $.ajax({
                   url: base_url + 'ajax/load_project_by_district',
                   type: 'post',
                   dataType: 'html',
                   data: {district_id: $(this).find('option:selected').val()},
                   beforeSend: function() {},
                   success: function(res) {
                        listProject3.html(res);
                        listProject3.selectmenu("refresh");
                   } 
                });
            
       });
       listWard3.change(function(){});
    },
    filter: function() {},
    viewGoogleMap: function() {
        var map;
        var address = $('#banner-google-map').attr('data-address');
        var zoom_map = $('#banner-google-map').attr('data-zoom');console.log(zoom_map);
        if(address != undefined) {
            geocoder = new google.maps.Geocoder();
            geocoder.geocode({
               'address': address 
            }, function(results, status){
                if(status == google.maps.GeocoderStatus.OK) {
                    var myOptions = {
                        zoom: parseInt(zoom_map),
                        center: results[0].geometry.location,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    }
                    map = new google.maps.Map(document.getElementById('banner-google-map'), myOptions);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location,
                        title: address
                    });
                    var infowindow = new google.maps.InfoWindow({
                        content: address
                      });
                     marker.addListener('click', function() {
                        infowindow.open(map, marker);
                      }); 
                      infowindow.open(map, marker);
                }
				console.log(status);
            });
			
        }
    },
	galleryImage: function() {
		/*
              var sync1 = $(".single-gallery-carousel-content-box");
              var sync2 = $(".single-gallery-carousel-thumbnail-box");
              var slidesPerPage = 4; //globaly define number of elements per page
              var syncedSecondary = true;
              var loop = false;
              if($('.single-gallery-carousel-content-box').find('.item').length > 1) loop=true;
              
              sync1.owlCarousel({
                items : 1,
                slideSpeed : 2000,
                nav: true,
                autoplay: true,
                dots: false,
                loop: loop,
                responsiveRefreshRate : 200,
                navText: ['<svg width="20px" height="30px" viewBox="0 0 11 20"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M9.554,1.001l-8.607,8.607l8.607,8.606"/></svg>','<svg width="20px" height="30px" viewBox="0 0 11 20" version="1.1"><path style="fill:none;stroke-width: 1px;stroke: #000;" d="M1.054,18.214l8.606,-8.606l-8.606,-8.607"/></svg>'],
              }).on('changed.owl.carousel', syncPosition);
            
              sync2
                .on('initialized.owl.carousel', function () {
                  sync2.find(".owl-item").eq(0).addClass("current");
                })
                .owlCarousel({
                items : slidesPerPage,
                dots: false,
                nav: false,
                smartSpeed: 200,
                slideSpeed : 500,
                slideBy: slidesPerPage, //alternatively you can slide by 1, this way the active slide will stick to the first item in the second carousel
                responsiveRefreshRate : 100
              }).on('changed.owl.carousel', syncPosition2);
            
              function syncPosition(el) {
                //if you set loop to false, you have to restore this next line
                //var current = el.item.index;
                
                //if you disable loop you have to comment this block
                var count = el.item.count-1;
                var current = Math.round(el.item.index - (el.item.count/2) - .5);
                
                if(current < 0) {
                  current = count;
                }
                if(current > count) {
                  current = 0;
                }
                
                //end block
            
                sync2
                  .find(".owl-item")
                  .removeClass("current")
                  .eq(current)
                  .addClass("current");
                var onscreen = sync2.find('.owl-item.active').length - 1;
                var start = sync2.find('.owl-item.active').first().index();
                var end = sync2.find('.owl-item.active').last().index();
                
                if (current > end) {
                  sync2.data('owl.carousel').to(current, 100, true);
                }
                if (current < start) {
                  sync2.data('owl.carousel').to(current - onscreen, 100, true);
                }
              }
              
              function syncPosition2(el) {
                if(syncedSecondary) {
                  var number = el.item.index;
                  sync1.data('owl.carousel').to(number, 100, true);
                }
              }
              
              sync2.on("click", ".owl-item", function(e){
                e.preventDefault();
                var number = $(this).index();
                sync1.data('owl.carousel').to(number, 300, true);
              });*/
          
	},
    registerValidate: function() {
        var error = false;
        var inputFullname = $('#formRegister input[name="first_name"]');
        var inputEmail = $('#formRegister input[name="email"]');
        var inputMobiphone = $('#formRegister input[name="mobiphone"]');
        var inputPassword = $('#formRegister input[name="password"]');
        var inputRepassword = $('#formRegister input[name="repassword"]');
        var inputCaptcha = $('#formRegister input[name="captcha"]');
        var inputCity = $('#formRegister select[name="city"]');
        var inputDistrict = $('#formRegister select[name="district"]');
        //Error
        var lblErrorFullname = $('#error_fullname');
        var lblErrorEmail = $('#error_email');
        var lblErrorPassword = $('#error_password');
        var lblErrorRepassword = $('#error_repassword');
        var lblErrorCity = $('#error_city');
        var lblErrorDistrict = $('#error_district');
        var lblErrorMobiphone = $('#error_mobiphone');
        var lblErrorCaptcha = $('#error_captcha');
        $('#formRegister').on('submit', function() {
            if(inputFullname.val() == '') {
                lblErrorFullname.html('Vui lòng nhập họ tên');
                error = true;
                inputFullname.focus();
            } else {
                lblErrorFullname.html('');
                error = false;
            }
            
            if(inputEmail.val() == '') {
                lblErrorEmail.html('Vui lòng nhập Email');
                error = true;
                inputEmail.focus();
            } else {
                lblErrorEmail.html('');
                error = false;
            }
            
            if(inputPassword.val() == '') {
                lblErrorPassword.html('Vui lòng nhập Mật khẩu');
                error = true;
                inputPassword.focus();
            } else {
                lblErrorPassword.html('');
                error = false;
            }
            
            if(inputRepassword.val() == '') {
                lblErrorRepassword.html('Vui lòng xác nhận Mật khẩu');
                error = true;
                inputRepassword.focus();
            } else if(inputRepassword.val() != inputPassword.val()) {
                lblErrorRepassword.html('Vui lòng xác nhận không đúng');
                error = true;
            }else {
                lblErrorRepassword.html('');
                error = false;
            }
            
            if(inputMobiphone.val() == '') {
                lblErrorMobiphone.html('Vui lòng nhập Số điện thoại');
                error = true;
                inputMobiphone.focus();
            } else {
                lblErrorMobiphone.html('');
                error = false;
            }
            
            if(inputCity.find('option:selected').val() == '-1') {
                lblErrorCity.html('Vui lòng chọn Tỉnh/Thành phố');
                error = true;
                inputCity.focus();
            } else {
                lblErrorCity.html('');
                error = false;
            }
            
            if(inputDistrict.find('option:selected').val() == '-1') {
                lblErrorDistrict.html('Vui lòng chọn Quận/Huyện');
                error = true;
                inputDistrict.focus();
            } else {
                lblErrorDistrict.html('');
                error = false;
            }
            
            if(inputCaptcha.val() == '') {
                lblErrorCaptcha.html('Vui lòng nhập mã an toàn');
                error = true;
                inputCaptcha.focus();
            } else {
                lblErrorCaptcha.html('');
                error = false;
            }
            
            if(error == true) {
                $('label.error').css('display','block');
                return false;
            }
                
            else 
                return true;
        });
        
    },
    postValidate: function() {
        
        var map;
        /*    map = new google.maps.Map(document.getElementById('google_map'), {
              center: {lat: -34.397, lng: 150.644},
              zoom: 8
            });*/
		
		var validate = true;
		
		var inputCity = $('#formPost select[name="city_id"]');
        var inputDistrict = $('#formPost select[name="district_id"]');
		var inputWard = $('#formPost select[name="ward_id"]');
		var inputStreet = $('#formPost select[name="street_id"]');
		var inputCategory = $('#formPost select[name="category_id"]');
		var inputTitle = $('#formPost input[name="title"]');
		var inputContent = $('#formPost textarea[name="content"]');
		var inputPriceUnit = $('#formPost input[name="price_unit"]');
		var inputPriceNumber = $('#formPost input[name="price_number"]');
		var inputGuestFullname = $('#formPost input[name="guest_name"]');
        var inputGuestEmail = $('#formPost input[name="guest_email"]');
        var inputGuestMobiphone = $('#formPost input[name="guest_mobiphone"]');
		var inputGuestTelephone = $('#formPost input[name="guest_telephone"]');
		var inputGuestAddress = $('#formPost input[name="guest_address"]');
		var inputFromDate = $('#formPost input[name="from_date"]');
		var inputToDate = $('#formPost input[name="to_date"]');
        var inputCaptcha = $('#formPost input[name="captcha"]');
		
        //Error
        var lblErrorGuestName = $('#errorGuestName');
        var lblErrorGuestEmail = $('#errorGuestEmail');
        var lblErrorGuestMobiphone = $('#errorGuestMobiphone');
        var lblErrorGuestAddress = $('#errorGuestAddress');
		var lblErrorTitle = $('#errorTitle');
		var lblErrorContent = $('#errorContent');
		var lblErrorPriceNumber = $('#errorPriceNumber');
		var lblErrorPriceUnit = $('#errorPriceUnit');
		var lblErrorCategory = $('#errorCategory');
        var lblErrorCity = $('#errorCity');
        var lblErrorDistrict = $('#errorDistrict');
        var lblErrorFromDate = $('#errorFromDate');
        var lblErrorToDate = $('#errorToDate');
        var lblErrorCaptcha = $('#errorCaptcha');
		
        $('#formPost').on('submit', function() {
            if(inputCategory.find('option:selected').val() == '-1') {
                lblErrorCategory.html('Vui lòng chọn nhóm tin');
                $('label.error').css('display','block');
                inputCategory.focus();
                return false;
            } else {
                lblErrorCategory.html('');
            }
            
            
            if(inputDistrict.find('option:selected').val() == '-1') {
                lblErrorDistrict.html('Vui lòng chọn Quận/Huyện');
                $('label.error').css('display','block');
                inputDistrict.focus();
                return false;
            } else {
                lblErrorDistrict.html('');
            }
           
            if(inputTitle.val() == '') {
                lblErrorTitle.html('Vui lòng nhập Tiêu đề');
                $('label.error').css('display','block');
                inputTitle.focus();
                return false;
            } else {
                lblErrorTitle.html('');
            }
            
            if(inputContent.val() == '') {
                lblErrorContent.html('Vui lòng nhập Nội dung');
                $('label.error').css('display','block');
                inputContent.focus();
                return false;
            } else {
                lblErrorContent.html('');
            }
            
            if(inputGuestFullname.val() == '') {
                lblErrorGuestName.html('Vui lòng nhập Họ tên');
                $('label.error').css('display','block');
                inputGuestFullname.focus();
                return false;
            } else {
                lblErrorGuestName.html('');
            }
            
            if(inputGuestMobiphone.val() == '') {
                lblErrorGuestMobiphone.html('Vui lòng nhập Số điện thoại');
                $('label.error').css('display','block');
                inputGuestMobiphone.focus();
                return false;
            } else {
                lblErrorGuestMobiphone.html('');
            }
            
            
            if(inputFromDate.val() == '') {
                lblErrorFromDate.html('Vui lòng chọn ngày đăng');
                $('label.error').css('display','block');
                inputFromDate.focus();
                return false;
            } else {
                lblErrorFromDate.html('');
            }
            
            if(inputToDate.val() == '') {
                lblErrorToDate.html('Vui lòng chọn ngày đăng');
                $('label.error').css('display','block');
                inputToDate.focus();
                return false;
            } else {
                lblErrorToDate.html('');
                
            }
            
            if(inputCaptcha.val() == '') {
                lblErrorCaptcha.html('Vui lòng nhập mã an toàn');
                $('label.error').css('display','block');
                inputCaptcha.focus();
                return false;
            } else {
				$.ajax({
					url: '/ajax/validate_captcha',
					type: 'post',
					data: {captcha: inputCaptcha.val()}	,
					dataType: 'json',
					success: function(res) {
						if(res.success === true){
							lblErrorCaptcha.html('');
							validate = true;
						}else{console.log('false');
							lblErrorCaptcha.html(res.msg).css('display','block');
							validate = false;
						}	
					}
				});
				//return false;
            }
            return validate;
        });
          
    },
	titleLimitCharactor: function(e) {
		var maxChars = $("#formPost input[name='title']");
		var max_length = 99;
        var min_length = 1;
		var var_text = '';
		if (max_length > 0) {
			maxChars.bind('keydown', function(e){
				var_text = maxChars.val(); 
				length = new Number(maxChars.val().length);
				counter = min_length+length;
				if(counter <= 99) 
				{
				    $("#icon-countdown").text(counter + '/' + max_length);
				}
			});
		}
	},
    captchaRefresh: function(target) {
        jQuery.ajax({
            type: "POST",
            url: base_url + "captcha/captcha_refresh",
            success: function(res) {
                if (res)
                {
                    jQuery(target).html(res);
                }
            }
        });
    },
    uploadAvatar: function() {
        $("#fileuploader").uploadFile({
    		url:base_url + "ajax/upload_photo'",
    		fileName:"avatar",
            formData: {filename: 'images'},
            maxFileCount: 15,
            showStatusAfterSuccess: true,
            customProgressBar: function(obj,s)
            {
                
                this.statusbar = $("<div class='ajax-file-upload-statusbar'></div>");
                this.preview = $("<img class='ajax-file-upload-preview' />").width(s.previewWidth).height(s.previewHeight).appendTo(this.statusbar).hide();
                this.filename = $("<div class='ajax-file-upload-filename'></div>").appendTo(this.statusbar).hide();
                this.progressDiv = $("<div class='ajax-file-upload-progress'>").appendTo(this.statusbar).hide();
                this.progressbar = $("<div class='ajax-file-upload-bar'></div>").appendTo(this.progressDiv).hide();
                this.abort = $("<div>" + s.abortStr + "</div>").appendTo(this.statusbar).hide();
                this.cancel = $("<div>" + s.cancelStr + "</div>").appendTo(this.statusbar).hide();
                this.done = $("<div>" + s.doneStr + "</div>").appendTo(this.statusbar).hide();
                this.download = $("<div>" + s.downloadStr + "</div>").appendTo(this.statusbar).hide();
                this.del = $("<div>" + s.deletelStr + "</div>").appendTo(this.statusbar).hide();
                
    
                this.abort.addClass("ajax-file-upload-red");
                this.done.addClass("ajax-file-upload-green");
                this.download.addClass("ajax-file-upload-green");            
                this.cancel.addClass("ajax-file-upload-red");
                this.del.addClass("ajax-file-upload-red");
                
                return this;
            },
            showPreview: true,
            showProgress: false,
            previewWidth: '100px',
            showDelete: true,
            dragDrop: false,
            uploadStr: '<i class="fa fa-upload"></i> Chọn hình ảnh',
            onSuccess: function(files,data,xhr,pd) {
                $('#avatar').val(data);
            },
            onError: function(files,status,errMsg,pd) {
                
            },
            deleteCallback: function(data,pd)
            {
                $.post(base_url + "ajax/delete_avatar",{op:"delete",name:data},
                    function(resp, textStatus, jqXHR)
                    {
                        //Show Message    
                        alert("File Deleted");        
                    });
              
            }
	   });
    },
    uploadPhoto: function() {
        $("#fileuploader").uploadFile({
    		url:base_url + "ajax/upload_photo'",
    		fileName:"myfile",
            formData: {filename: 'images'},
            showStatusAfterSuccess: true,
            customProgressBar: function(obj,s)
            {
                
                this.statusbar = $("<div class='ajax-file-upload-statusbar'></div>");
                this.preview = $("<img class='ajax-file-upload-preview' />").width(s.previewWidth).height(s.previewHeight).appendTo(this.statusbar).hide();
                this.filename = $("<div class='ajax-file-upload-filename'></div>").appendTo(this.statusbar).hide();
                this.progressDiv = $("<div class='ajax-file-upload-progress'>").appendTo(this.statusbar).hide();
                this.progressbar = $("<div class='ajax-file-upload-bar'></div>").appendTo(this.progressDiv).hide();
                this.abort = $("<div>" + s.abortStr + "</div>").appendTo(this.statusbar).hide();
                this.cancel = $("<div>" + s.cancelStr + "</div>").appendTo(this.statusbar).hide();
                this.done = $("<div>" + s.doneStr + "</div>").appendTo(this.statusbar).hide();
                this.download = $("<div>" + s.downloadStr + "</div>").appendTo(this.statusbar).hide();
                this.del = $("<div>" + s.deletelStr + "</div>").appendTo(this.statusbar).hide();
                
    
                this.abort.addClass("ajax-file-upload-red");
                this.done.addClass("ajax-file-upload-green");
                this.download.addClass("ajax-file-upload-green");            
                this.cancel.addClass("ajax-file-upload-red");
                this.del.addClass("ajax-file-upload-red");
                
                return this;
            },
            showPreview: true,
            showProgress: false,
            previewWidth: '100px',
            showDelete: true,
            dragDrop: false,
            uploadStr: '<i class="fa fa-upload"></i> Chọn hình ảnh',
            onSuccess: function(files,data,xhr,pd) {
                 $("<input type='hidden' value='"+data+"' name='images[]'>").appendTo(pd.statusbar);
            },
            onError: function(files,status,errMsg,pd) {
                
            },
            deleteCallback: function(data,pd)
            {
                $.post(base_url + "ajax/delete_avatar",{op:"delete",name:data},
                    function(resp, textStatus, jqXHR)
                    {
                        //Show Message    
                        alert("File Deleted");        
                    });
              
            }
	   });
    },
    search: function() {
        $('#btnSearchAdvance').click(function(){
            $('#searchAdvance').slideToggle();
        });
        
        $('#buttonSearch').click(function(){
           $('#searchForm').submit(); 
        });
    },
    confirmDel: function(url) {
        var conf = confirm('Bạn có chắc chắn muốn xóa ?');
        if(conf == true)
            window.location.href = url;
        else
            return false;
    },
	goToTop: function() {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 50) {
					$('#go-to-top').fadeIn();
                    $('.btnfixedpost').fadeIn();
				} else {
				    $('#go-to-top').fadeOut();
                    $('.btnfixedpost').fadeOut();
				}
			});
            
			// scroll body to 0px on click
			$('#go-to-top').click(function () {
				$('body,html').animate({
					scrollTop: 0
				}, 500);
				return false;
			});	
	},
    searchAuto: function() {
        $( "#search_input" ).autocomplete({
          source: function( request, response ) {
            $.ajax( {
              url: "ajax/searchAuto",
              dataType: "json",
              data: {
                term: request.term
              },
              success: function( data ) {
                response( data );
              }
            } );
          },
          minLength: 2,
          select: function (event, ui) {
            console.log(ui);
            window.location.href = ui.item.data;
          }
        });
    },
    loginPopup: function() {
        $('#btnLoginAjax').on('click', function() {
            var errEmail = true;
            var errPass = true;
            if($('#txtEmail').val().trim().length == 0) {
                $('#errEmptyEmail').show();
                errEmail = true;
            } else {
                $('#errEmptyEmail').hide();
                errEmail = false;
            }
            if($('#txtPassword').val().trim().length == 0) {
                $('#errEmptyPassword').show();
                errPass = true;
            } else {
                errPass = false;
                $('#errEmptyPassword').hide();
            }
            if(errEmail == false && errPass == false) {
				//$('#btnLoginAjax').val('Đang gửi...').attr('disabled', 'disabled');
                $.ajax({
                   url: base_url + 'Ajax/AjaxLogin',
                   type: 'post',
                   dataType: 'json',
                   data: {email: $('#txtEmail').val(), password: $('#txtPassword').val(), remember: $('#rememberMe').val()},
                   success: function(response) {
                    if(response.success == true) {
                        window.location.reload();
                    } else {
                        $('#resMsg').html(response.msg).show();
						$('#btnLoginAjax').val('Đăng nhập').removeAttr('disabled');
                    }
                   },
					onError: function(xhr, ajaxOptions, thrownError){
						$('#resMsg').html(thrownError).show();
						$('#btnLoginAjax').val('Đăng nhập').removeAttr('disabled');
					}
                });
            }
        });
    },
    registerPopup: function() {
        $('#btnRegisterAjax').on('click', function() {
            var errFullname = true;
            var errEmail = true;
            var errPassword = true;
            var errRePassword = true;
            var errMobiPhone = true;
            var errCity = true;
            var errDistrict = true;
            var errCaptcha = true;
            
            if($('#registerForm input[name="first_name"]').val() == '') {
                $('#errFullname').show();
            } else {
                $('#errFullname').hide();
                errFullname = false;
            }
            if($('#registerForm input[name="email"]').val() == '') {
                $('#errRegEmail').show();
            } else {
                $('#errRegEmail').hide();
                errEmail = false;
            }
            if($('#registerForm input[name="mobiphone"]').val() == '') {
                $('#errMobiPhone').show();
            } else {
                $('#errMobiPhone').hide();
                errMobiPhone = false;
            }
            if($('#registerForm input[name="password"]').val() == '') {
                $('#errRegPassword').show();
            } else {
                $('#errRegPassword').hide();
                errPassword = false;
            }
            if($('#registerForm input[name="repassword"]').val() == '') {
                $('#errRegRePassword').show();
            } else {
                $('#errRegRePassword').hide();
                errRePassword = false;
            }
            if($('#registerForm select[name="city"]').val() == '') {
                $('#errCity').show();
            } else {
                $('#errCity').hide();
                errCity = false;
            }
            if($('#registerForm select[name="district"]').val() == '') {
                $('#errDistrict').show();
            } else {
                $('#errDistrict').hide();
                errDistrict = false;
            }
            if($('#registerForm input[name="captcha"]').val() == '') {
                $('#errCaptcha').show();
            } else {
                $('#errCaptcha').hide();
                errCaptcha = false;
            }
            if(errEmail == false && errPassword == false && errFullname == false && errCity == false && errDistrict==false && errCaptcha==false) {
                $('#btnRegisterAjax').val('Đang gửi...').attr('disabled', 'disabled');
                $('#resRegMsg').text('');
                $.ajax({
                   url: base_url + 'Ajax/AjaxRegister',
                   type: 'post',
                   dataType: 'json',
                   data: $('#registerForm').serialize(),
                   success: function(response) {
                    if(response.success == true) {
                        $('#resRegMsg').html('Đăng ký thành công !');
                        setCookie('register_success', 1, 7);
                        setTimeout(function() {
                            $('#registerModal').modal('hide');
                            $('#loginModal').modal('show');
                        },1000);
                    } else {
                        $('#resRegMsg').html(response.msg).show();
                        $.RealApp.captchaRefresh('#regCaptcha');
                        $('#btnRegisterAjax').val('Đăng ký').removeAttr('disabled');
                    }
                   } 
                });
            }
        });
    },
	forgotPopup: function() {
        $('#btnForgotAjax').on('click', function() {
            var errEmail = true;
            var Email = $('#forgotForm input[name="email"]');
            if(Email.val().trim().length == 0) {
                $('#forgotForm .emptyEmail').show();
                errEmail = true;
            } else {
                $('#forgotForm .emptyEmail').hide();
                errEmail = false;
            }
            if(errEmail == false ) {
				$('#btnForgotAjax').val('Đang gửi...').attr('disabled', 'disabled');
                $.ajax({
                   url: base_url + 'Ajax/AjaxForgot',
                   type: 'post',
                   dataType: 'json',
                   data: {email: Email.val()},
                   success: function(response) {
                    if(response.success == true) {
                        $('#forgotMsg').text('Khôi phục mật khẩu thành công ! vui lòng kiểm tra Email và làm theo hướng dẫn.').show();
                        $('#btnForgotAjax').val('Gửi').removeAttr('disabled');
                        //window.location.reload();
                    } else {
                        $('#forgotMsg').html(response.msg).show();
						$('#btnForgotAjax').val('Gửi').removeAttr('disabled');
                    }
                   },
					onError: function(xhr, ajaxOptions, thrownError){
						$('#forgotMsg').html(thrownError).show();
						$('#btnForgotAjax').val('Gửi').removeAttr('disabled');
					}
                });
            }
        });
    }
};
$.RealApp.select3Dropdown();
$.RealApp.select2Dropdown();
$.RealApp.selectDropdown();
$.RealApp.viewGoogleMap();
$.RealApp.galleryImage();
$.RealApp.registerValidate();
$.RealApp.search();
$.RealApp.goToTop();
$.RealApp.searchAuto();
$.RealApp.loginPopup();
$.RealApp.registerPopup();
$.RealApp.forgotPopup();

$(document).ready(function() {
    $('#loginModal').on('shown', function() {
        var cookie_user = getCookie('register_success');
        if(cookie_user != undefined) {
            $('#loginAlertMsg').text('Bạn vui lòng check mail và kích hoạt tài khoản').show();
        }
    });
	$('#registerModal').on('shown', function() {
        $.RealApp.captchaRefresh();
    });
	$('[data-action="user-register"]').on('click', function(){
		$('#registerModal').modal('show');
        $('#loginModal').modal('hide');
        $('#forgotModal').modal('hide');
        $.RealApp.captchaRefresh();
	});
	$('[data-action="user-login"]').on('click', function(){
		$('#registerModal').modal('hide');
        $('#loginModal').modal('show');
        $('#forgotModal').modal('hide');
	});
	$('[data-action="user-forgot"]').on('click', function(){
		$('#registerModal').modal('hide');
		$('#loginModal').modal('hide');
        $('#forgotModal').modal('show');
	});
});