$(function () {
    $('.navbar-toggle').click(function () {
        $('.navbar-nav').toggleClass('slide-in');
        $('.side-body').toggleClass('body-slide-in');
        $('#search').removeClass('in').addClass('collapse').slideUp(200);

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').toggleClass('slide-in');
        
    });
   
   // Remove menu for searching
   $('#search-trigger').click(function () {
        $('.navbar-nav').removeClass('slide-in');
        $('.side-body').removeClass('body-slide-in');

        /// uncomment code for absolute positioning tweek see top comment in css
        //$('.absolute-wrapper').removeClass('slide-in');

    });
});

// show image primary
function image_preview() {
  var preview = $(".upload-image img");

  $("#image").change(function(event){
     var input = $(event.currentTarget);
     var file = input[0].files[0];
     var reader = new FileReader();
     reader.onload = function(e){
         image_base64 = e.target.result;
         preview.attr("src", image_base64);
     };
     reader.readAsDataURL(file);
  });
}

$(document).ready(image_preview);
$(document).on('page:load', image_preview);

$(function(){

    $('#example').DataTable( {
        "lengthMenu": [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]]
    } );
});

$(function(){
    $('#example-1').DataTable( {
      "lengthMenu": [[3, 5, 10, 25, -1], [3, 5, 10, 25, "All"]]
    } );
});

$(function(){
    $('#example-2').DataTable( {
      "lengthMenu": [[3, 5, 10, 25, -1], [3, 5, 10, 25, "All"]]
    } );
});
var i = 0;
function add_new_field() {
    var html = '<br><hr /><div class="row"><div class="col-md-6"><div class="form-group"><label for="image[]">Image</label><input type="file" name="image[]" class="file form-control width-custom" id="image[]"></div><div class="form-group"><label for="color[]">Color :</label>                            <select name="color[]" class="form-control width-custom" id="color"><option value="1">White</option><option value="2">Black</option><option value="3">Blue</option><option value="4">Yellow</option><option value="5">Red</option><option value="6">Green</option><option value="7">Purple</option><option value="8">Orange</option><option value="9">Light blue</option><option value="10">Sky blue</option><option value="11">Grey</option></select>'
    //var size = '<div class="form-group"> <label for="size[]">Size : </label> <label><input type="checkbox" name="size[][]" value="1">21</label><label><input type="checkbox" name="size[][]" value="2">22</label><label><input type="checkbox" name="size[][]" value="3">23</label><label><input type="checkbox" name="size[][]" value="4">24</label><label><input type="checkbox" name="size[][]" value="5">25</label><label><input type="checkbox" name="size[][]" value="6">26</label><label><input type="checkbox" name="size[][]" value="7">27</label><label><input type="checkbox" name="size[][]" value="8">28</label><label><input type="checkbox" name="size[][]" value="9">29</label><label><input type="checkbox" name="size[][]" value="10">30</label><label><input type="checkbox" name="size[][]" value="11">31</label></div>' ;
    var details = '</div></div><div class="col-md-1"></div><div class="col-md-5"><div class="form-group "><label for="imageDetail1[]">Image details</label>                                    <input type="file" name="imageDetail1[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail2[]">Image detail2</label><input type="file" name="imageDetail2[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail3[]">Image detail3</label><input type="file" name="imageDetail3[]" class="file form-control width-150"></div><div class="form-group "><label for="imageDetail4[]">Image detail4</label><input type="file" name="imageDetail4[]" class="file form-control width-150"></div></div>';  
    html = html  + details;
    $("#add-new-field").click(function(){
        
    $("#field-color").append(html);
    });
}
$(document).ready(add_new_field);

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


    $('#province').on("change", function(){
        load_district();
    });
});

    
$(document).ready(function(){
    $('#date-start').datetimepicker({
        format: 'DD/MM/YYYY'
    });
    $('#date-end').datetimepicker({
        format: 'DD/MM/YYYY'
    });

    $('#date-start').on("dp.change", function(){
      $('#date-end').removeAttr("disabled");
    })
    $('#date-end').on("dp.change", function(){
      var date_start = $('#date-start').val();
      var date_end = $('#date-end').val();

      var parts = date_start.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]);

      var parts = date_end.split('/');
      var date_end = new Date(parts[2], parts[1]-1, parts[0]);
      if (date_end < date_start){
        alert('Date end must be after date start');
        $('#date-end').val(null);
      }
    })

    $('#1-day').on("click", function(){
      var date = $('#date-start').val();

      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]);

      var day = 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + day);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
    $('#1-week').on("click", function(){
      var date = $('#date-start').val();
      
      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]);

      var days = 7 * 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + days);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
    $('#1-month').on("click", function(){
      var date = $('#date-start').val();

      //change date format to YYYYMMDD
      var parts = date.split('/');
      var date_start = new Date(parts[2], parts[1]-1, parts[0]); 

      var days = 30 * 60 * 60 * 24 * 1000; 
      var date_end = new Date(date_start.getTime() + days);
      $('#date-end').data("DateTimePicker").date(date_end);
    })
})

$(document).ready(function(){
  var product_id;
  var product_name;
  
  $(document).on('click', '.btn-add', function() {
    product_id = $(this).attr('id');
    product_name = $('#name-'+ product_id).text();
  })

  $(document).on('click', '.btn-ok', function() {
    var sale = $('#sale').val();
    
    if(sale != ""){
      if ($("#btn-remove-" + product_id).length){
        $("#btn-remove-" + product_id).remove();
      }

      $('#sale-product-box').append('<span style="margin: 3px 3px" class="btn btn-remove bg-green" id="btn-remove-'
        + product_id + '"><i class="fa fa-close"></i>'
        + product_name + '(' + sale + '%)' +
        '<input type="hidden" name="products[id][]" value="'+ product_id +
        '"><input type="hidden" name="products[sale][]" value="'+ sale +
        '"></span>');
      
      $('#sale').val(null);
    }
  })

  $(document).on('click', '.btn-remove', function() {
    $(this).remove();
  })

})

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

$(document).ready(function(){

  $('#sale').on("change", function(){
    var value = $('#sale').val();
    if(!isNumber(value)){
      $('.modal-body').append('<p>Value must be number</p>');
      $('#sale').val(null);
      return;
    }
    if (value < 1 || value > 100){
      $('.modal-body').append('<p>Value must be from 1 to 100</p>');
      $('#sale').val(null);
      return;
    }
  })

})

$(document).ready(function(){
    $(".remove-form").submit(function(e) {
        var form_id = $(this).attr('id');
        var product_id = $('#' + form_id + ' input[name=product_id]').val();
        var sale_program_id = $('#' + form_id + ' input[name=sale_program_id]').val();

        $.ajax({
            type: 'POST',
            url: '/admin/sale_programs/removeproduct',
            data: {
                'product_id' : product_id,
                'sale_program_id' : sale_program_id
            },
            success: function(data) {
                parts = form_id.split('-');
                $('#row-' + parts[1]).remove();                              
            }
        })
        e.preventDefault();

    })
});

$(document).ready(function(){
    $(".btn-load-more").click(function() {
        var index = $(this).attr('id');
        var user_id = $("#user_id").val();
        var new_index = parseInt(index) + 2;
        $.ajax({
            type: 'POST',
            url: '/admin/users/loadmoreaction',
            data: {
                'index' : index,
                'user_id' : user_id
            },
            success: function(data) {
                var data = JSON.parse(data);
                for (var k in data){
                  $('.timeline').append('<li class="time-label">'
                    + '<span class="bg-red">'
                    + k
                    + '</span>'
                    + '</li>'
                    );
                  for (var i in data[k]){
                    $('.timeline').append('<li>'
                      + '<i class="'
                      + data[k][i]['icon-class']
                      + '"></i>'
                      + '<div class="timeline-item">'
                      + '<span class="time">'
                      + '<i class="fa fa-clock-o"></i> '
                      + data[k][i]['time']
                      + '</span>'
                      + '<div class="timeline-body">'
                      + data[k][i]['content']
                      + '</div>'
                      + '</div>'
                      + '</li>'
                      );
                  }

                  $('.btn-load-more').attr('id', new_index);  
                       
                }                           
            }
        })
    })
});
