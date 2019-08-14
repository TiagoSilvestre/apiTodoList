var urlBase = 'http://localhost/apiTodoList/api';
/**todolist.html
 * Redirects Users to login page if is not logged in
 */
function verifyUser() {
    var jwt = getCookie('jwt');
    if (!jwt) {
        alert('Você não tem permissao de acesso');
        window.location.href = 'login.html';
    } else {
        $.post(urlBase+ "/user/validate_token.php", JSON.stringify({jwt: jwt})).done(function (result) {
        }).fail(function (result) {
            alert('Você não tem permissao de acesso');
            window.location.href = 'login.html';
        });
    }
}

function logout() {
    setCookie("jwt", "", 1);
    window.location.href = 'login.html';
}

// get or read cookie
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

// function to set cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// function to make form values to json format
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
