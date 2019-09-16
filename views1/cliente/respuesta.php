<?php 
	echo "<div id='mensaje'><b>Por favor no cierre hasta que el sistema le arroje una respuesta reflejada en la pantalla</b></div>";
?>
<script>
$(document).ready(function() {
var urlapp = "https://secure.epayco.co/validation/v1/reference/<?php echo $ref_payco ?>";
var urlbd = "<?php echo Yii::$app->request->hostInfo?>"+"/fotoleal/web/index.php/cliente/update2?datos=<?php echo $datos ?>";
//alert(urlapp);
$.get(urlapp,function(response) {
    if (response.success) {
    	//alert(response.data.x_cod_response);
    	  if (response.data.x_cod_response == 1) {
            //Codigo personalizado
            console.log('transacción aceptada');
            //alert(urlbd+"?confirmacion=1");
            window.location.replace(urlbd+"?confirmacion=1?factura="+response.data.x_ref_payco);
          }
          //Transaccion Rechazada
          if (response.data.x_cod_response == 2) {
            $("#mensaje").html('<h1>transacción rechazada</h1>');
            console.log('transacción rechazada');
          }
          //Transaccion Pendiente
          if (response.data.x_cod_response == 3) {
            console.log('transacción pendiente');
            //alert(urlbd+"?confirmacion=3");
            window.location.replace(urlbd+"?confirmacion=3?factura="+response.data.x_ref_payco);
          }
          //Transaccion Fallida
          if (response.data.x_cod_response == 4) {
            console.log('transacción fallida');
          }
    }
	})  
});
</script>