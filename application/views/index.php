<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<div class="container">
  <div class="row">
    <p>&nbsp;</p>
    <h3 class="text-center">Assignment</h3>
    <button type="button" class="btn btn-primary" onclick="hide_show_section('add-new-product-form')">Add new product</button>
    <div class="row add-new-product-form" style="display: none;">
      <form class="form-inline" action="" id="add_new_product_form_data" method="post">
        <input type="text" class="input-medium span2" id="name" name="name" placeholder="name">
        <input type="text" class="input-medium span2" id="brand_name" name="brand_name" placeholder="brand_name">
        <input type="number" class="input-medium span2" id="price" name="price" placeholder="price">
        <input type="number" class="input-medium span2" id="qnt" name="qnt" placeholder="qnt">
        <input type="submit" class="input-medium span2" value="Add">
      </form>
    </div>
    <hr />
    <div class="row show-message"></div>
    <div class="col-sm-12 col-md-10 col-md-offset-1">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th class="text-center">Price</th>
            <th class="text-center">Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody class="table-form-data">
          <?php
            if ($result) {
              foreach ($result as $key => $value) {
          ?>
              <tr>
                <td>
                  <div class="media">
                    <div class="media-body">
                      <h4 class="media-heading"><a href="#" id="name"><?= $value['name'] ?></a></h4>
                      <h5 class="media-heading"> by <a href="#" id="brand_name"><?= $value['brand_name'] ?></a></h5>
                    </div>
                  </div>
                </td>
                <td>
                  <input type="text" class="form-control" id="qnt_value" onkeyup="getQntValue(<?=$value['id'] ?>,<?=$value['qnt'] ?>)" value="<?= $value['qnt'] ?>">
                </td>
                <td class="text-center" id="price"><strong><?= $value['price'] ?></strong></td>
                <td class="text-center" id="total_price"><strong>Rs. <?= $value['total_product_qnt_price']; ?></strong></td>
                <td><button type="button" class="btn btn-danger" onclick="deleteProduct(<?=$value['id']?>)"> <span class="glyphicon glyphicon-remove"></span> Remove </button></td>
              </tr>             

          <?php
            } //end foreach
          } //end if
          ?>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <h5>Subtotal</h5>
            </td>
            <td class="text-right">
              <h5 id="sub_total"><strong>Rs. <?= $result[0]['total_amount'] ?></strong></h5>
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <h5>Fixed shipping</h5>
            </td>
            <td class="text-right">
              <h5 id="fix_shipping"><strong>Rs. 50</strong></h5>
            </td>
          </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td>
              <h3>Total</h3>
            </td>
            <td class="text-right">
              <h3 id="grand_total"><strong>Rs. <?= $result[0]['total_amount'] + 50; ?></strong></h3>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>


<script>
  var base_url = '<?=base_url()?>';
  
  // hide or show the particualer section using this method
  function hide_show_section(className){
    var visibilityBoolean = $('.'+className+':visible').length;
    // console.log(visibilityBoolean);
    if(visibilityBoolean == 0){
      $('.'+className).css('display','block');
    }else{
      $('.'+className).css('display','none');
    }
  }

  $('#add_new_product_form_data').submit(function(e){
    e.preventDefault();
    $.ajax({
      url: base_url+'welcome/add_new_product_form_data',
      type:'post',
      dataType:'json',
      data: new FormData(this),
      processData: false,
      contentType:false,
      cache:false,
      async:false,
      success: function(data){
        if(data.success != null){
          console.log('check success');
          $('.show-message').removeClass('alert alert-danger');
          $('.show-message').html(data.success);        
        }else{
          $('.show-message').toggleClass('alert alert-danger');
          $('.show-message').html(data.error);        
        }
      },
      complete: function(data){        
        // form.reset();
        $('#add_new_product_form_data')[0].reset();
        $('.table-form-data').load(' .table-form-data > *');        
      }
    })
  })

  function getQntValue(id,qnt){
    var new_qnt_value = $('#qnt_value').val();
    // console.log(id, ' qnt: ', new_qnt_value)
    
    $.ajax({
      url: base_url + 'welcome/qnt_form_data',
      type: 'post',
      data: {id:id, qnt:qnt, new_qnt_value:new_qnt_value},
      dataType:'json',
      success: function(data){
        console.log(data);
        console.log(data.result.length);
        if(data.result !== null){
          var updated_table_show = '';
          var i;
          for(i=0; i<data.result.length; i++){
            updated_table_show += '<tr> <td> <div class="media"> <div class="media-body"> <h4 class="media-heading"><a href="#" id="name">'+data.result[i].name+'</a></h4> <h5 class="media-heading"> by <a href="#" id="brand_name">'+data.result[i].brand_name+'</a></h5> </div></div></td><td> <input type="text" class="form-control" id="qnt_value" onkeyup="getQntValue('+data.result[i].id+','+data.result[i].qnt+'" value="'+data.result[i].qnt+'"> </td><td class="text-center" id="price"><strong>'+data.result[i].price+'</strong></td><td class="text-center" id="total_price"><strong>Rs. '+data.result[i].total_product_qnt_price+'</strong></td><td><button type="button" class="btn btn-danger"> <span class="glyphicon glyphicon-remove"></span> Remove </button></td></tr>';
          }

          var other_html_part = '<tr> <td></td><td></td><td></td><td> <h5>Subtotal</h5> </td><td class="text-right"> <h5 id="sub_total"><strong>Rs. '+data.result[0].total_amount+'</strong></h5> </td></tr><tr> <td></td><td></td><td></td><td> <h5>Fixed shipping</h5> </td><td class="text-right"> <h5 id="fix_shipping"><strong>Rs. 50</strong></h5> </td></tr><tr> <td></td><td></td><td></td><td> <h3>Total</h3> </td><td class="text-right"> <h3 id="grand_total"><strong>Rs. '+data.result[0].total_amount+'</strong></h3> </td></tr>';
          
          $('.table-form-data').html(updated_table_show + other_html_part);
        }
      },
      complete: function(data){
        $('.table-form-data').load(' .table-form-data > *');
      }
    });

  }

  function deleteProduct(id){
    $.ajax({
      url: base_url + 'welcome/delete_product',
      type: 'post',
      data:{id:id},
      dataType: 'json',
      success: function(data){
        console.log(data);
        if(data.success != null){
          $('.show-message').html(data.success);
        }else{
          $('.show-message').html(data.error);
        }
      },
      complete: function(data){
        $('.table-form-data').load(' .table-form-data > *');
      }
    });
  }
</script>