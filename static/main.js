"use strict";
window.addEventListener("load", function () {
    $("#changeForm").submit(function(event) {
        event.preventDefault();
        changeHyphenation();
    });
    function changeHyphenation() {
        var word = $('[name="for"]').val();
        var hyphChange = $('[name="new"]').val();
        var data = {
            "newHyphenatedWord": hyphChange
        };
        $.ajax({
            type: 'PUT',
            url: "/api/hyphenation/words/" + word,
            data: JSON.stringify(data),
            success: function(data) {
                alert("Success");
            },
            error: function(xhr) {
                alert(xhr.statusText + xhr.responseText);
            },
            contentType: "application/json",
            dataType: 'json'
        });
    }

    $("#post-form").submit(function(event) {
        event.preventDefault();
        postHyphenation();
    });
    function postHyphenation() {
        var words = $('[name="words"]').val().split(' ');
        var data = {
            "words": words
        };
        $.ajax({
            type: 'POST',
            url: "/api/hyphenation/words/",
            data: JSON.stringify(data),
            success: function(data) {
                alert("Success");
            },
            error: function(xhr) {
                alert(xhr.statusText + xhr.responseText);
            },
            contentType: "application/json",
            dataType: 'json'
        });
    }

    $(".word-delete-button").click(function(event) {
        event.preventDefault();
        deleteWord(event);
    });
    function deleteWord(event) {
        var wordToDelete = event.target.getAttribute('data-word');
        $.ajax({
            type: 'DELETE',
            url: "/api/hyphenation/words/" + wordToDelete,
            success: function(data) {
                $("#"+wordToDelete).remove();
                alert("Success");
            },
            error: function(xhr) {
                alert(xhr.statusText + xhr.responseText);
            },
            contentType: "application/json",
            dataType: 'json'
        });
    }
});