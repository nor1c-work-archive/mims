<!-- <select name="searchInsCategory" id="insCategorySelector" class="form-control">
    <option value="">Semua Kategori</option>
</select> -->

<script>

    $(document).ready(function() {
        $.ajax({
            'url': '<?=site_url('ajax/instrumentCategories')?>',
        }).then(res => {
            res = JSON.parse(res);
            
            var Opt = '';
            for (key in res) {
                Opt += '<option value="'+res[key]+'">'+res[key]+'</option>';
            }

            $('select[name="searchInsCategory"]').append(Opt);
        })
    });

</script>
