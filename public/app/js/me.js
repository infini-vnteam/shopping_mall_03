$(document).ready(function(){
    function load_district()
    {
        var province_id = $('#province option:selected').val();
        $.ajax({
            type: 'POST',
            url: '/loaddistrict',
            data: {
                'province_id' : province_id,
            },
            success: function(data) {
                var data = JSON.parse(data);
                $('#district').find('option').remove();
                for (var k in data){
                    $('#district').append('<option value="' + k + '">' + data[k] + '</option>');
                }                               
            }
        })
    }

    $('#province').on("selected", function(){
        load_district();
    });
});
$(document).ready(function(){
    function add_view()
    {
        var product_id = document.location.href;
        product_id = product_id.substring(product_id.lastIndexOf('/') + 1);;
        
        $.ajax({
            type: 'POST',
            url: '/addView',
            data: {
                'product_id' : product_id,
            },
            success: function(data) {
                //onsole.log(data);                    
            }
        })
    }
    $('.product').on("click",add_view());
});
