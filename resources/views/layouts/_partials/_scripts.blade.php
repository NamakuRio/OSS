<!-- General JS Scripts -->
<script src="@asset('assets/modules/jquery.min.js')"></script>

<script src="@asset('assets/modules/popper.js')"></script>
<script src="@asset('assets/modules/tooltip.js')"></script>
<script src="@asset('assets/modules/bootstrap/js/bootstrap.min.js')"></script>
<script src="@asset('assets/modules/nicescroll/jquery.nicescroll.min.js')"></script>
<script src="@asset('assets/modules/moment.min.js')"></script>
<script src="@asset('assets/modules/sweetalert/sweetalert.min.js')"></script>
<script src="@asset('assets/js/stisla.js')"></script>

<!-- JS Libraies -->
@yield('js-library')

<!-- Page Specific JS File -->
@yield('js-page')

<!-- Template JS File -->
<script src="@asset('assets/js/scripts.js')"></script>
<script src="@asset('assets/js/custom.js')"></script>

<script>
    var url = "@url('/')";
    var timeOutAlert = null;
    var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9+/=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/rn/g,"n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

    $(document).on("DOMContentLoaded", function () {
        lazyLoad();
    });

    $(document).on("DOMNodeInserted", function () {
        lazyLoad();
    });

    function notification(icon, title, position = "top-end", showConfirmButton = false, timer = 3000, timerProgressBar = true)
    {
        const Toast = Swal.mixin({
            toast: true,
            position: position,
            showConfirmButton: showConfirmButton,
            timer: timer,
            timerProgressBar: timerProgressBar,
            onOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: icon,
            title: title
        });
    }

    function swalNotification(status, message)
    {
        Swal.fire({
            icon: status,
            title: message
        });
    }

    function alert(location, status, message, time = 0)
    {
        var alert = $(location).html(`<div class="alert alert-`+status+`">`+message+`</div>`);

        if(time != 0){
            clearTimeout(timeOutAlert);
            timeOutAlert = setTimeout(() => {
                alert.empty();
            }, time);

        }
    }

    function focusable(target, time = 1)
    {
        setTimeout(() => {
            $(target).focus();
        }, time);
    }

    function lazyLoad()
    {
        var lazyImages = [].slice.call(document.querySelectorAll("img.lazy"));

        if("IntersectionObserver" in window) {
            let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if(entry.isIntersecting){
                        let lazyImage = entry.target;
                        lazyImage.src = lazyImage.dataset.original;
                        lazyImage.classList.remove("lazy");
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            lazyImages.forEach(function(lazyImage) {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            notification('error', 'Gagal memuat gambar');
        }
    }

    function checkCSRFToken(errorMessage = "")
    {
        if(errorMessage == 'CSRF token mismatch.') {
            location.reload();
        }
    }
</script>

@yield('js-script')
