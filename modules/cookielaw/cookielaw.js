		$(document).ready(function(){
           $("#cookie_close").click(function (event) {
               event.preventDefault();
               $("#cookielaw").fadeOut(400);
               setcook();
           });


       });
		
        function setcook() {
            var name = 'cookielaw_module';
            var value = '1';
            var expire = new Date();
            expire.setMonth(expire.getMonth()+12);
            document.cookie = name + "=" + escape(value) + ((expire==null)?"" : ("; expires=" + expire.toGMTString()))
        }

        
        
        