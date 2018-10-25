"use strict";
window.addEventListener("load", function () {
    var urlHost = 'http://172.17.0.1:80';

    // CHANGE HYPHENATION PAGE
    $("#wordToShow").val(getUrlParameter('for'));
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
        doApiRequest(
            'PUT',
            "/api/hyphenation/words/" + word,
            function(data) {
                alert("Success");
            },
            JSON.stringify(data)
        );
    }

    //SHOW PATTERNS PAGE
    $("a.page-link").click(function (event) {
        event.preventDefault();
        var href = event.target.getAttribute("href");
        requestPage(href);
    });
    function requestPage(href) {
        doApiRequest(
            'GET',
            href,
            function(data) {
                preparePatternsPage(data);
            }
        )
    }
    requestPage("/api/hyphenation/patterns?page=1");
    function preparePatternsPage(data) {
        var page = data.page;
        if (page === 0) page = 1;
        var nextPage = page + 1;
        var prvPage = page - 1;

        if (page > 1) {
            $('.page-previous').css('visibility', 'visible');
        } else {
            $('.page-previous').css('visibility', 'hidden');
        }

        var prevPageLinks = $(".page-previous");
        var currentPageLinks = $('.page-current');
        var nextPageLinks = $('.page-next');
        prevPageLinks.attr("href", "/api/hyphenation/patterns?page=" + (prvPage));
        prevPageLinks.eq(1).text(prvPage);
        currentPageLinks.attr("href", "/api/hyphenation/patterns?page=" + page);
        currentPageLinks.text(page);
        nextPageLinks.attr("href", "/api/hyphenation/patterns?page=" + nextPage);
        nextPageLinks.eq(0).text(nextPage);

        var rowsString = "";
        for (var patternNr in data.result) {
            rowsString = rowsString + "<tr><td>" + patternNr + "</td><td>" + data.result[patternNr] + "</td></tr>"
        }
        $("#patterns-table").empty().append(rowsString)
    }

    // POST WORDS PAGE
    $("#post-form").submit(function(event) {
        event.preventDefault();
        postHyphenation();
    });
    function postHyphenation() {
        var words = $('[name="words"]').val().split(' ');
        var data = {
            "words": words
        };
        doApiRequest(
            'POST',
            "/api/hyphenation/words/",
            function(data) {
                displayPostData(data);
                alert("Success");
            },
            JSON.stringify(data)
        );
    }
    function displayPostData(data) {
        $("#post-data").css('display', 'block');

        var skippedWords = data.skippedWords;
        var tableForSkipped = $("#skipped");
        tableForSkipped.empty();
        for (var word in skippedWords) {
            tableForSkipped.append("<tr><td>"+ word +"</td><td>"+ skippedWords[word] +"</td></tr>");
        }

        var hyphenatedWords = data.hyphenatedWords;
        var tableForHyphenated = $("#hyphenated");
        tableForHyphenated.empty();
        for (var word in hyphenatedWords) {
            tableForHyphenated.append("<tr><td>"+ word +"</td><td>"+ hyphenatedWords[word] +"</td></tr>");
        }
    }

    // SHOW WORDS PAGE
    doApiRequest(
        'GET',
        "/api/hyphenation/words",
        function(data) {
            displayHyphenatedWords(data);
            $(".word-delete-button").click(function(event) {
                event.preventDefault();
                deleteWord(event);
            });
        }
    );
    function displayHyphenatedWords(data) {
        var rowsString = "";
        for (var word in data.result) {
            rowsString = rowsString + "<tr id=" + word + ">";
            rowsString = rowsString + "<td>" + word + "</td>";
            rowsString = rowsString + "<td>";
            rowsString = rowsString + data.result[word];
            rowsString = rowsString + '<a class="badge badge-danger word-delete-button" data-word="' + word + '">Delete</a>';
            rowsString = rowsString + '<a class="badge badge-primary" href="/frontend/hyphenation/add-or-change-hyphenation?for=' + word + '">Change</a>';
            rowsString = rowsString + "</td>";
            rowsString = rowsString + "</tr>";
        }
        $("#words-table").empty().append(rowsString);
    }
    function deleteWord(event) {
        var wordToDelete = event.target.getAttribute('data-word');
        doApiRequest(
            'DELETE',
            "/api/hyphenation/words/" + wordToDelete,
            function(data) {
                $("#"+wordToDelete).remove();
                alert("Success");
            }
        );
    }

    function doApiRequest(method, relativeUrl, successFunction, data = null) {
        $.ajax({
            type: method,
            url: urlHost + relativeUrl,
            data: data,
            success: successFunction,
            error: function(xhr) {
                alert(xhr.statusText + xhr.responseText);
            },
            contentType: "application/json",
            dataType: 'json'
        });
    }
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };
});