<!-- Modal -->
<div class="modal fade bd-example-modal-xl" id="detailModal" role="dialog" aria-labelledby="detailModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="detailModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div>
                    <div class="card-body">
                        <table class="table th-noColor" style="background:#fff;">
                            <tbody id="detail"></tbody>
                        </table>
                    </div> 
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-dark waves-effect waves-light" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    function setDefaultDetailModalTitle() {
    }

    function initializeDetailInput(res) {
        res = res[0];
        $('#detailModalTitle').html('Detail Katalog <b>' + res.catCode+'-'+res.idAssetMaster +' | '+res.assetMasterName + '</b>');

        // $.ajax({
        //     url: '<?=site_url(uriSegment(1).'/getImage')?>?id=' + res.idPictMain,
        //     type: 'GET',
        // }).then(res => {
        //     console.log(res);
        // });
        var detailHtml = '';
        detailHtml +=   
                        // '<tr>'+
                        //     '<td colspan="2">'+
                        //         '<img src="#" id="twinImg">'+
                        //     '</td>'+
                        // '</tr>'+
                        '<tr>'+
                            '<th>KODE SISTEM</th>'+
                            '<td><?=uriSegment(3)?>-'+res.idAssetMaster+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<th>KODE KATALOG</th>'+
                            '<td>'+res.productCode+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<th>NAMA KATALOG</th>'+
                            '<td>'+res.assetMasterName+'</td>'+
                        '</tr>'+
                        '<tr>'+
                            '<th>MERK</th>'+
                            '<td>'+res.merk+'</td>'+
                        '</tr>'
                        ;

        $('#detail').html(detailHtml);

        // get image
        var xhr = new XMLHttpRequest();
		
        xhr.open('GET', '<?=site_url('master/getImage?id=')?>' + res.idPictMain, true);
		
		xhr.onload = function(){
			var img = new Image();
			var response = xhr.responseText;
            // console.log(response);
			var binary = ""
			
			for(i=0;i<response.length;i++){
				binary += String.fromCharCode(response.charCodeAt(i) & 0xff);
			}
			
			img.src = 'data:image/jpeg;base64,' + btoa(binary);
			var canvas = document.getElementById('showImage');
			var context = canvas.getContext('2d');
				
			context.drawImage(img,0,0);
			var snapshot = canvas.toDataURL("image/png");
			var twinImage = document.getElementById('twinImg');
			twinImage.src = snapshot;
		}
		
		xhr.overrideMimeType('text/plain; charset=x-user-defined');
		xhr.send();
    }

</script>