function add_cart(id) {
  $.ajax({
    url: "manage_cart.php",
    data: "product_id=" + id + "&Add_To_Cart=",
    method: "post",
    success: function (response) {
      //receive json response and check in json status = added
      var data = JSON.parse(response);
      //convert data to array
      var arr = Object.values(data);
      if (arr[0]["status"] == "added") {
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr["success"]("Product added to cart.");
        $(".count").html(arr[0]["cart_count"]);
      } else if (arr[0]["status"] == "already") {
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr["warning"]("Product already in cart.");
      } else if (arr[0]["status"] == "not_login") {
        toastr.options.closeButton = true;
        toastr.options.progressBar = true;
        toastr["warning"]("Please login to continue.");
        setTimeout(function () {
          window.location.href = "login.php";
        }, 5000);
      }
    },
  });
}
