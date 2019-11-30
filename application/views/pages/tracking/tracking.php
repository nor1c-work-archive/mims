<?=js(assets('design/ample/assets/libs/jquery/dist/jquery.min.js'))?>

<input type="text" name="barcode"> <button id="rescan">Scan Ulang</button>
<br>
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">
<input type="text" name="">

<script>
    $(document).ready(function() {
        $('input[name=barcode]').on('change', function(){
        });

        $('#rescan').click(function() {
            $('input[name=barcode]').val('');
            $('input[name=barcode]').focus();
        }); 
    });
</script>