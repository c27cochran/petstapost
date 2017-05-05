/*!
 * Start Bootstrap - Grayscale Bootstrap Theme (http://startbootstrap.com)
 * Code licensed under the Apache License v2.0.
 * For details, see http://www.apache.org/licenses/LICENSE-2.0.
 */

// jQuery to collapse the navbar on scroll
$(window).scroll(function() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
});

$(function() {

    // jQuery for page scrolling feature - requires jQuery Easing plugin
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });

    $('.simple-ajax-popup-align-top').magnificPopup({
        type: 'ajax',
        alignTop: true,
        overflowY: 'scroll'
    });

    // notifications 
    $.ajax({
        type: "POST",
        url: "../util/get-notifications",
        cache: false,
        success: function(number){
            if (number > 0) {
                $('.notifications').html(number);
                $('.drop-notifications').html(number);
                $('.mobile-notifications').html(number);
            } else {
                $('.notifications').addClass('hidden');
                $('.drop-notifications').addClass('hidden');
                $('.mobile-notifications').addClass('hidden');
            }
        },
        error: function(){
            // do nothing
        }
    });

    $('.show-notifications').magnificPopup({
        type: 'ajax',
        alignTop: true,
        overflowY: 'scroll'
    });

    $("#change-form").on('click', "#submit_change", function(e) {

        var changeFormData = $('#change-form').serialize();
        e.preventDefault();

        $.ajax({
            type: "GET",
            url: "../account/change-password",
            data: changeFormData,
            beforeSend:function(){
                //add a loading gif so the broswer can see that something is happening
                $("#change-response").addClass("loading");
                $(".change-modal-content").addClass("hidden");
                $(".response-error").addClass("hidden");
            },
            success: function(msg){
                setTimeout(function() {
                    $("#change-response").removeClass("loading");
                    $(".response-error").removeClass("hidden");
                    $("#change-response").html(msg);
                    if (msg.indexOf("We've changed your password") > -1) {
                        setTimeout(function() {
                            $("#account-modal").hide();
                        }, 3000);
                    } else {
                        $(".change-modal-content").removeClass("hidden");
                    }
                }, 1000);
            },
            error: function(){
                $("#email-confirm-box").html('<div class="alert-box">' +
                    '<p class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i>&nbsp;There was an issue with our server. Please try again.</p>' +
                  '</div>');
            }
        });
    });  

    // User Search
    // On Search Submit and Get Results
    function search() {
        var query_value = $('#user-search').val();
        $('#search-string').html(query_value);
        if(query_value !== ''){
            $.ajax({
                type: "POST",
                url: "../util/search-users-kibble",
                data: { query: query_value },
                cache: false,
                success: function(html){
                    $("#user-results").html(html);
                }
            });
        }
        return false;    
    }

    $("#user-search").bind("keyup", function(e) {
        // Set Timeout
        clearTimeout($.data(this, 'timer'));

        // Set Search String
        var search_string = $(this).val();

        // Do Search
        if (search_string == '') {
            $("#user-results").fadeOut();
            $('#user-results-text').fadeOut();
        }else{
            $("#user-results").fadeIn();
            $('#user-results-text').fadeIn();
            $(this).data('timer', setTimeout(search, 100));
        };
    });

});

function mention() {

    var comment = $('.comment-box').val();
    var query_value = comment.split("@").pop();

    if ($('#mention-results li').length == 0) {
        $('#mention-results').addClass('hidden');
    } else {
        $('#mention-results').removeClass('hidden');
    }

    if (comment.indexOf("@") >= 0) {
        $.ajax({
            type: "POST",
            url: "../util/mention",
            data: { query: query_value },
            cache: false,
            success: function(html){
                $("#mention-results").html(html);
                $("#mention-results").fadeIn();
            }
        });
    } else {
        $("#mention-results").fadeOut();
    }
    return false;  
}

function addUserHandle(query, handle) { 

    $("#mention-results").fadeOut();

    handle = query.replace(query, handle);
    var comment_val = $('.comment-box').val();
    comment_val = comment_val.replace(query, handle);
    $('.comment-box').val(comment_val);
    $('.comment-box').focus();
}

function mentionCaption() {

    var caption = $('.custom-caption').val();
    var query_value = caption.split("@").pop();

    if ($('#mention-results-caption li').length == 0) {
        $('#mention-results-caption').addClass('hidden');
    } else {
        $('#mention-results-caption').removeClass('hidden');
    }

    if (caption.indexOf("@") >= 0) {
        $.ajax({
            type: "POST",
            url: "../util/mention-caption",
            data: { query: query_value },
            cache: false,
            success: function(html){
                $("#mention-results-caption").html(html);
                $("#mention-results-caption").fadeIn();
            }
        });
    } else {
        $("#mention-results-caption").fadeOut();
    }
    return false;  
}

function addUserHandleCaption(query, handle) { 

    $("#mention-results-caption").fadeOut();

    handle = query.replace(query, handle);
    var comment_val = $('.custom-caption').val();
    comment_val = comment_val.replace(query, handle);
    $('.custom-caption').val(comment_val);
    $('.custom-caption').focus();
}

// Profile links
$('.cover-nav > li.idle').on('click', function(e) {
    
    e.preventDefault();

    $('.cover-nav > li.active').removeClass('active');
    $('.cover-nav > li.active').addClass('idle');
    $(this).addClass('active');
    $(this).removeClass('idle');
});

$('.hide-item-anchor').on('click', function(e) {
    e.preventDefault();
    $(this).parents('.profile-item').addClass('hidden');
});

$(document).on('click', '.favorite', function(e) {
    var item_id = $(this).attr('id').replace('item_', '');
    var user_id = $('#user-id').text();
    var to_user = $('#to-user-'+item_id).text();
    var fav_pop_up = $('#fav-pop-up-'+item_id);
    var fav_icon = $('#fav-icon-'+item_id);
    var fav_count = parseInt($('#fav-count-'+item_id).text());

    fav_icon.addClass('fa-heart');
    
    fav_pop_up.show();

    e.preventDefault();

    $.ajax({
        type: "GET",
        url: "../util/add-favorite",
        data: "item_id="+item_id+"&user_id="+user_id+"&to_user="+to_user,
        success: function(msg){
            if (msg == 'Favorite added!') {
                fav_icon.removeClass('fa-paw');
                fav_icon.addClass('fa-heart');
                if (fav_count) {
                    fav_count = fav_count + 1;
                    $('#fav-count-'+item_id).text(String(fav_count));
                } else {
                    $('#fav-count-'+item_id).text('1');
                }
            }
            if (msg == 'Favorite removed.') {
                fav_icon.removeClass('fa-heart');
                fav_icon.addClass('fa-paw');
                fav_count = fav_count - 1;
                $('#fav-count-'+item_id).text(String(fav_count));
            }
        },
        error: function() {
            fav_pop_up.html('There was an error.');
        }
    });

});

$('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

$("[data-toggle=popover]").popover({
    html: true, 
    content: function() {

        var item_id = $(this).attr('id').replace('comment-link-', '');
        var html = $('.show-comments-'+item_id).html();
        
        return '<div id="popover-content-'+item_id+'">'+
                '<div class="comment-load">'+html+'</div>' +
                '<div class="form-group">'+
                    '<textarea autofocus maxlength="160" class="form-control comment-box comment-text-'+item_id+'" placeholder="Add a comment…" onKeyUp="mention()"></textarea>'+
                '</div><ul class="dropdown-menu" id="mention-results"></ul>'+
                '<button id="comment-btn-'+item_id+'" type="submit" class="btn btn-comment btn-block">Submit</button></div>';
    }
});


$(document).on("click", ".btn-comment", function(e) {
    
    e.preventDefault();
    
    var item_id = $(this).attr('id').replace('comment-btn-', '');
    var user_id = $('#user-id').text();
    var to_user = $('#to-user-'+item_id).text();
    var comment = $('.comment-text-'+item_id).val();
    var comment_hash = comment.replace(/\#/g, '%23');
    var pop_up = $('#popover-content-'+item_id);
    var comment_count = parseInt($('#comment-count-'+item_id).text());

    $.ajax({
        type: "POST",
        url: "../util/add-comment",
        data: "item_id="+item_id+"&user_id="+user_id+"&to_user="+to_user+"&comment="+comment_hash,
        success: function(msg){
            $('.comment-text-'+item_id).val('');
            $('.comment-load').append('<p class="comment-time">Now</p><a class="comment-link" href="javascript:void(0);">Me</a>: '+msg+'<br><br>');
            if (comment_count) {
                comment_count = comment_count + 1;
                $('#comment-count-'+item_id).text(String(comment_count));
            } else {
                $('#comment-count-'+item_id).text('1');
            }
            $.ajax({
                type: "GET",
                url: "../util/show-limited-comments-profile",
                data: "item_id="+item_id,
                success: function(msg){
                    $('.show-comments-'+item_id).html(msg);
                },
                error: function() {
                    $('.show-comments-'+item_id).html('<p>There was an error.</p>');
                }
            });
            setTimeout(function() {
                $("[data-toggle=popover]").popover('hide');
                pop_up.hide();
            }, 2000);
        },
        error: function() {
            $('.comment-load').html('<p>There was an error.</p>');
        }
    });
});

$(document).on("click", ".btn-comment-get-item", function(e) {
    
    e.preventDefault();
    
    var item_id = $(this).attr('id').replace('comment-btn-', '');
    var user_id = $('#user-id').text();
    var to_user = $('#to-user-'+item_id).text();
    var comment = $('.comment-text-'+item_id).val();
    var comment_hash = comment.replace(/\#/g, '%23');
    $('.comment-text-'+item_id).focus();

    $.ajax({
        type: "POST",
        url: "../util/add-comment",
        data: "item_id="+item_id+"&user_id="+user_id+"&to_user="+to_user+"&comment="+comment_hash,
        success: function(msg){
            $('.comment-text-'+item_id).val('');
            $('.comment-data-container').append('<p class="comment-time">Now</p><p class="popup-comment"><a class="comment-link" href="javascript:void(0);">Me</a>: '+msg+'</p><br><br>');
        },
        error: function() {
            $('.comment-data-container').append('<p>There was an error.</p>');
        }
    });
});

$(document).on("click", ".delete-comment-link", function(e) {
    
    e.preventDefault();
    
    var comment_id = $(this).attr('id').replace('delete-comment-', '');
    var item_id = $('.comment-load').parent().attr('id').replace('popover-content-', '');
    var comment_count = parseInt($('#comment-count-'+item_id).text());

    $.ajax({
        type: "GET",
        url: "../util/remove-comment",
        data: "comment_id="+comment_id,
        success: function(msg){
            $('.my-comment-'+comment_id).html(msg);
            if (comment_count) {
                comment_count = comment_count - 1;
                $('#comment-count-'+item_id).text(String(comment_count));
            } else {
                $('#comment-count-'+item_id).text('');
            }
            $.ajax({
                type: "GET",
                url: "../util/show-limited-comments-profile",
                data: "item_id="+item_id,
                success: function(msg){
                    $('.show-comments-'+item_id).html(msg);
                },
                error: function() {
                    $('.show-comments-'+item_id).html('<p>There was an error.</p>');
                }
            });
            setTimeout(function() {
                $('[data-toggle="popover"]').each(function () {
                    if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                        $(this).popover('hide');
                    }
                    $('.my-comment-'+comment_id).hide();
                });
            }, 2000);
        },
        error: function() {
            $('.my-comment').html('<p>There was an error.</p>');
        }
    });
});

$(document).on('click', '.show-others', function(e) {

    e.preventDefault();

    $('.all-favorites').removeClass('hidden');
    $('.two-favorites').addClass('hidden');
});

// $('.navbar-mobile-tog').on('click', function(e) {
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });

// $('.navbar-web-tog').on('click', function(e) {
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });

// $('.show-notifications').on('click', function(e) {
//     $('.drop-notifications').toggleClass('hidden');
//     $('.notifications').addClass('hidden');
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });

// Item cropping
$("#item-input").change(function (e) {
    e.preventDefault();
    $(".crop-preview-item").removeClass("hidden");
    $(".item-upload").addClass("hidden");
    $(".item-save").removeClass("hidden");
 });

$("#original-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("original-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#bw-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").addClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("bw-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#chrome-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").addClass("chrome-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("chrome-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#bold-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").addClass("bold-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("bold-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#fade-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").addClass("fade-filter");
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("fade-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#color-blast-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").addClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("color-blast-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#antique-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").addClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("antique-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#brighten-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").addClass("brighten-filter");
    $(".item-view img").removeClass("enhance-filter");
    $("#item-filter").html("brighten-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$("#enhance-item").on('click', function(e) {
    e.preventDefault();
    $(".item-view img").removeClass("bw-filter");
    $(".item-view img").removeClass("fade-filter");
    $(".item-view img").removeClass("chrome-filter");
    $(".item-view img").removeClass("bold-filter");
    $(".item-view img").removeClass("color-blast-filter");
    $(".item-view img").removeClass("antique-filter");
    $(".item-view img").removeClass("brighten-filter");
    $(".item-view img").addClass("enhance-filter");
    $("#item-filter").html("enhance-filter");

    var img_url = $('.item-view img').attr('src');
    var item_filter = $("#item-filter").text();
    $('#item-filter-input').val(item_filter);
    var username = $('#item-username').text();
    var filename = $('#item-filename').text();
});

$('#close-modal-item').on('click', function(e) {
    e.preventDefault();
    $('#item-modal').modal('hide');
    $('.item-form').removeClass('hidden');
    $(".item-upload").removeClass("hidden");
    $(".item-body").removeClass("loading_circle");
    $('.item-pic-upload').addClass('hidden');
    $(".crop-preview-item").addClass("hidden");
});

// $('.navbar-mobile-tog').on('click', function(e) {
//     $('.mobile-notifications').addClass('hidden');
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });

// $('.navbar-web-tog').on('click', function(e) {
//     e.preventDefault();
//     $('.drop-notifications').addClass('hidden');
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });

// $('.show-notifications').on('click', function(e) {
//     e.preventDefault();
//     $('.drop-notifications').addClass('hidden');
//     $.ajax({
//         type: "POST",
//         url: "../util/remove-notifications",
//         success: function(msg){
//             // Do nothing
//         },
//         error: function() {
//             // Do nothing
//         }
//     });
// });


