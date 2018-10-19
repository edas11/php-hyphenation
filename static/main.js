"use strict";
window.addEventListener("load", function () {
    function changeHyphenation() {
        var XHR = new XMLHttpRequest();
        var word = document.getElementsByName("for")[0].value;
        var hyphChange = document.getElementsByName("new")[0].value;
        var data = {
            "newHyphenatedWord": hyphChange
        };
        XHR.addEventListener("load", function (event) {
            alert(event.target.responseText);
        });
        XHR.addEventListener("error", function (event) {
            alert('Oops! Something went wrong.');
        });

        XHR.open("PUT", "/api/hyphenation/words/" + word);
        XHR.setRequestHeader( 'Content-Type', 'application/json' );
        XHR.send(JSON.stringify(data));
    }

    function postHyphenation() {
        var XHR = new XMLHttpRequest();
        var words = document.getElementsByName("words")[0].value.split(' ');
        var data = {
            "words": words
        };
        XHR.addEventListener("load", function (event) {
            alert(event.target.responseText);
        });
        XHR.addEventListener("error", function (event) {
            alert('Oops! Something went wrong.');
        });

        XHR.open("POST", "/api/hyphenation/words/");
        XHR.setRequestHeader( 'Content-Type', 'application/json' );
        XHR.send(JSON.stringify(data));
    }

    function deleteWord(event) {
        var XHR = new XMLHttpRequest();
        var wordToDelete = event.target.getAttribute('data-word');

        XHR.addEventListener("load", function (event) {
            var rowToDelete = document.getElementById(wordToDelete);
            rowToDelete.parentNode.removeChild(rowToDelete);
            alert(event.target.responseText);
        });
        XHR.addEventListener("error", function (event) {
            alert('Oops! Something went wrong.');
        });

        XHR.open("DELETE", "/api/hyphenation/words/" + wordToDelete);
        XHR.setRequestHeader( 'Content-Type', 'application/json' );
        XHR.send();
    }

    var changeForm = document.getElementById("changeForm");
    if (changeForm) {
        changeForm.onsubmit = function (event) {
            event.preventDefault();
            changeHyphenation();
        }
    }

    var postForm = document.getElementById("post-form");
    if (postForm) {
        postForm.onsubmit = function (event) {
            event.preventDefault();
            postHyphenation();
        }
    }

    var deleteButtons = document.getElementsByClassName('word-delete-button');
    if (deleteButtons && deleteButtons.length > 0) {
        for (var i = 0; i < deleteButtons.length; i++) {
            deleteButtons[i].onclick = function (event) {
                event.preventDefault();
                deleteWord(event);
            }
        }
    }
});