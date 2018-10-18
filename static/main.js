"use strict";
window.addEventListener("load", function () {
    function changeHyphenation() {
        var XHR = new XMLHttpRequest();
        var word = document.getElementsByName("for")[0].value;
        var hyphChange = document.getElementsByName("new")[0].value;
        var data = {
            "newHyphenatedWord": hyphChange
        }
        XHR.addEventListener("load", function (event) {
            alert(event.target.responseText);
        });
        XHR.addEventListener("error", function (event) {
            alert('Oops! Something went wrong.');
        });
        console.log(word);
        XHR.open("PUT", "/api/hyphenation/words/" + word);
        XHR.setRequestHeader( 'Content-Type', 'application/json' );
        console.log(JSON.stringify(data));
        XHR.send(JSON.stringify(data));
    }

    var form = document.getElementById("changeForm");
    form.onsubmit = function (event) {
        event.preventDefault();
        changeHyphenation();
    }
});