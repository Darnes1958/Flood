

    <script>

        document.addEventListener('livewire:initialized', () => {
        @this.on('gotoitem', (event) => {
            postid = (event.test);

            if (postid == 'Name1') {
                $("#Name1").focus();
                $("#Name1").select();
            }
            if (postid == 'Name2') {
                $("#Name2").focus();
                $("#Name2").select();
            }

            if (postid == 'Name3') {
                $("#Name3").focus();
                $("#Name3").select();
            }

            if (postid == 'Name4') {
                $("#Name4").focus();
                $("#Name4").select();
            }

            if (postid == 'price1') {

                $("#price1").focus();
                $("#price1").select();

            }
            if (postid == 'price2') {

                $("#price2").focus();
                $("#price2").select();

            }

            if (postid == 'supplier_id') {
                $("#supplier_id").focus();
                $("#supplier_id").select();
            }
            if (postid == 'customer_id') {
                $("#customer_id").focus();
                $("#customer_id").select();
            }

            if (postid == 'place_id') {
                $("#place_id").focus();
                $("#place_id").select();
            }
            if (postid == 'price_type_id') {
                $("#price_type_id").focus();
                $("#price_type_id").select();
            }
            if (postid == 'pay') {
                $("#pay").focus();
                $("#pay").select();
            }
            if (postid == 'barcode_id') {
                $("#barcode_id").focus();
                $("#barcode_id").select();
            }if (postid == 'item_id') {
                $("#item_id").focus();
                $("#item_id").select();

            }if (postid == 'q1') {
                $("#q1").focus();
                $("#q1").select();
            }
            if (postid == 'q2') {
                $("#q2").focus();
                $("#q2").select();
            }
            if (postid == 'price_input') {
                $("#price_input").focus();
                $("#price_input").select();
            }
        });
        });
    </script>


