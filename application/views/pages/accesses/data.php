<br>
<div class="table-responsive">
    <table id="example" class="table table-striped table-bordered border display" style="width:100%">
        <thead>
            <tr>
                <th></th>
                <!-- <th></th> -->
                <th>No</th>
                <?php
                    foreach ($columns as $columnKey => $columnAliasing) {
                        echo "<th>".$columnAliasing."</th>";
                    }
                ?>
            </tr>
        </thead>
    </table>
</div>