<?= \dominus77\sweetalert2\Alert::widget(['useSessionFlash' => true]);
$vendedor=2;
//var_dump($modelpago);
$idpago=$modelpago->id_pago;
 ?>
 <script src='https://checkout.epayco.co/checkout.js'></script>
<script>
$(document).ready(function(){
  swal({
title: "Importante",
text: "Despues de realizada la transaccion no cierre el navegador, use el boton finalizar o espere el tiempo indicado y aguarde a que el sistema le indique que su pago fue registrado con exito",

})
.then((willDelete) => {
if (willDelete) {
  var handler = ePayco.checkout.configure({
        key: 'ae9dce5fff977ae8057e026e47f28a53',
        test: false
      })
        /*id_cliente=$("#cliente-id_cliente").val()+'**';
        correo=$("#cliente-correo").val()+'**';
        telefono=$("#cliente-telefono").val()+'**';
        celular=$("#cliente-celular").val()+'**';
        evento=$("#cliente-evento").val()+'**';
        programa=$("#cliente-programa").val()+'**';
        carrera1=$("#direccion-carrera1").val()+'**';
        carrera2=$("#direccion-carrera2").val()+'**';
        carrera3=$("#direccion-carrera3").val()+'**';
        municipio=$("#direccion-municipio").val()+'**';
        barrio=$("#direccion-barrio").val()+'**';
        casa1=$("#direccion-casa1").val()+'**';
        casa2=$("#direccion-casa2").val()+'**';
        monto=$("#cliente-monto").val()+'**';
        observaciones=$("#cliente-observaciones").val()+'**';
        tipo_carrera=$("#direccion-tipo_carrera").val()+'**';
        tipo_casa=$("#direccion-tipo_casa").val()+'**';
        vendedor =<?php //echo $vendedor ?>;*/

      var data={
            //Parametros compra (obligatorio)
            name: 'Servicio de Fotografia',
            description: 'Servicio de Fotografia',
            invoice: <?php echo $modelpago->factura ?>,
            currency: "cop",
            amount: <?php echo $modelpago->monto ?>,
            country: "co",
            lang: "es",

            //Onpage="false",
            //Standard="true",
            external: "false",
            //response: '<?php //echo Yii::$app->request->hostInfo ?>'+"/fotoleal/web/index.php/cliente/respuesta?datos="+id_cliente+correo+telefono+celular+evento+programa+carrera1+carrera2+carrera3+casa1+casa2+municipio+barrio+monto+observaciones+tipo_carrera+tipo_casa+vendedor+'**'+paquete+'**',
            response:'<?php echo Yii::$app->request->hostInfo ?>'+"/fotoleal/web/index.php/cliente/respuesta2?id="+'<?php echo $idpago?>&',
            }
            handler.open(data)
            //console.log(d)

} else {

}
})
});


</script>
