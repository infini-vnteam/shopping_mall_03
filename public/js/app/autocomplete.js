function autoComplete (){
    var searchs = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        prefetch: '/getDataSearch'
    });
    searchs.clearPrefetchCache();
    searchs.initialize(true);

    $('#search-form #search-box').typeahead({},
    {
        name: 'products',
        display: 'name',
        source: searchs.ttAdapter(),
        templates: {
            empty: [
                '<div class="empty-message">',
                    'there no match result',
                '</div>'
            ].join('\n'),
            suggestion: Handlebars.compile(
                '<div class="tt-suggestion tt-selectable"><a href="/product/view/{{id}}"><img src="{{image}}" class="image-product-icon">{{name}}</a></div>'
            )
        }
    });
}

$(document).ready(autoComplete);
$(document).on('page:load', autoComplete);

$(document).ready(function(){
    function comment()
    {
        var comment = $('#comment').val();
        var productId = $('#product-id').val();
        $.ajax({
            type: 'POST',
            url: '/comment/add',
            data: {
                'comment' : comment,
                'product_id' : productId
            },
            success: function(data) {
                var data = JSON.parse(data);               
                $('#comment-field').append('<div>');
                $('#comment-field').append('<blockquote>');
                $('#comment-field').append('<p>' + data.comment + '</p>');
                $('#comment-field').append('</blockquote>');
                $('#comment-field').append('<h3>&mdash; ' + data.username + '</h3>');
                $('#comment-field').append('</div>');
                $('#comment').val('');
            }
        })
    }


    $(document).on("click", '#submitbutton', function(){
        comment();
    });
});

$(document).ready(function(){
    $('.remove-comment').click(function() {

        var comment_id = $(this).data('id');
        $.ajax({
            type: 'POST',
            url: '/comment/delete',
            data: {
                'comment_id' : comment_id,
            },
            success: function(data) {
                var comment_field = '#comment-' + comment_id;          
                $(comment_field).remove();
            }
        })
    })
});
